<?php

namespace App\Http\Controllers;

use App\Turn;
use App\Address;
use App\Phone;

use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Hash;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\TurnTransformer;

class TurnController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $fractal;

    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function show($id){
        $paginator = Turn::where('service_id', '=', $id)->paginate();
        $turn = $paginator->getCollection();
        $resource = new Collection($turn, new TurnTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($resource)->toArray();
    }

    public function store(Request $request){
        //validate request parameters
        $this->validate($request, [
            'service_id' => 'required',
            'day' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'max_reservation' => 'required',
            'percent_discount' => 'required',
            'status' => 'required',
        ]);

        $turn = Turn::create($request->only([
            'service_id',
            'day',
            'time_start',
            'time_end',
            'max_reservation',
            'percent_discount',
            'status',
        ]));

        $resource = new Item($turn, new TurnTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function update($id, Request $request){

        //validate request parameters
        $this->validate($request, [
            'day' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'max_reservation' => 'required',
            'percent_discount' => 'required',
            'status' => 'required',
        ]);

        $turn = Turn::find($id);

        //Return error 404 response if turn was not found
        if(!$turn) return $this->customResponse('turn not found!', 404);

        $turn->update($request->except(['service_id']));

        if($turn){
            //return updated data
            $resource = new Item($turn, new TurnTransformer);
            return $this->fractal->createData($resource)->toArray();
        }

        //Return error 400 response if updated was not successful
        return $this->customResponse('Failed to update turn!', 400);
    }

    public function customResponse($message = 'success', $status = 200)
    {
        return response(['status' =>  $status, 'message' => $message], $status);
    }
}
