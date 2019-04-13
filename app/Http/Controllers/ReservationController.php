<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\Turn;

use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\ReservationTransformer;

class ReservationController extends Controller
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

    // public function show($id){
    //     $reservation = Reservation::find($id);
    //     $resource = new Item($reservation, new ReservationTransformer);
    //     return $this->fractal->createData($resource)->toArray();
    // }

    public function store(Request $request){
        //validate request parameters
        $this->validate($request, [
            'pet_id' => 'required|max:255',
            'turn_id' => 'required',
            'reservation_day' => 'required'
        ]);
        $turn = Turn::find($request->get('turn_id'));
        if(count(Reservation::where([['turn_id', '=', $turn->id], ['status', '=', 'approved']])->get()) >= $turn->max_reservation){
            return $this->errorResponse('Failed to create reservation, limit exceeded', 400);
        };
        $reservation = Reservation::create($request->only([
            'pet_id',
            'turn_id',
            'reservation_day'
        ]));

        $resource = new Item($reservation, new ReservationTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function update($id, Request $request){

        //validate request parameters
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'type' => 'required',
        ]);

        $reservation = Reservation::find($id);

        if($request->has('phone')){
            $reservation->phone()->update($request->only(['phone']));
        }
        if($request->has('address')){
            $address = $request->get('address');
            unset($address['user_id']);
            $reservation->address()->update($address);
        }

        //Return error 404 response if product was not found
        if(!$reservation) return $this->errorResponse('product not found!', 404);

        $reservation->update($request->except(['email', 'phone', 'address', 'id']));

        if($reservation){
            //return updated data
            $resource = new Item($reservation, new ReservationTransformer);
            return $this->fractal->createData($resource)->toArray();
        }

        //Return error 400 response if updated was not successful
        return $this->errorResponse('Failed to update product!', 400);
    }

    public function customResponse($message = 'success', $status = 200)
    {
        return response(['status' =>  $status, 'message' => $message], $status);
    }
}
