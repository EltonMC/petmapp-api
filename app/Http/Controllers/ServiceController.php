<?php

namespace App\Http\Controllers;

use App\Service;
use App\Address;
use App\Phone;

use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Hash;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\ServiceTransformer;

class ServiceController extends Controller
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
        $paginator = Service::where('petshop_id', '=', $id)->paginate();
        $service = $paginator->getCollection();
        $resource = new Collection($service, new ServiceTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($resource)->toArray();

    }

    public function store(Request $request){
        //validate request parameters
        $this->validate($request, [
            'petshop_id' => 'required',
            'type' => 'required',
        ]);

        $service = Service::create($request->only([
            'petshop_id',
            'type',
        ]));

        $resource = new Item($service, new ServiceTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function update($id, Request $request){

        //validate request parameters
        $this->validate($request, [
            'status' => 'required',
        ]);

        $service = Service::find($id);

        //Return error 404 response if service was not found
        if(!$service) return $this->customResponse('service not found!', 404);

        $service->update($request->only('status'));

        if($service){
            //return updated data
            $resource = new Item($service, new ServiceTransformer);
            return $this->fractal->createData($resource)->toArray();
        }

        //Return error 400 response if updated was not successful
        return $this->customResponse('Failed to update service!', 400);
    }

    public function customResponse($message = 'success', $status = 200)
    {
        return response(['status' =>  $status, 'message' => $message], $status);
    }
}
