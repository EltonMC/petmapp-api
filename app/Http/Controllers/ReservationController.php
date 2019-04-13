<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\Turn;
use App\Pet;

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

    public function index(Request $request){
        $paginator = Reservation::join('pets', 'reservations.pet_id', '=', 'pets.id')->select('reservations.*')->where('pets.user_id', $request->auth->id)->paginate();
        $reservations = $paginator->getCollection();
        $resource = new Collection($reservations, new ReservationTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($resource)->toArray();
    }

    public function store(Request $request){
        //validate request parameters
        $this->validate($request, [
            'pet_id' => 'required|max:255',
            'turn_id' => 'required',
            'reservation_day' => 'required'
        ]);

        $turn = Turn::find($request->get('turn_id'));

        if(!$turn) return $this->customResponse('Failed to create reservation, turn not exit!', 400);

        if (!Pet::find($request->get('pet_id'))) return $this->customResponse('Failed to create reservation, pet not exit!', 400);

        if(count(Reservation::where([['turn_id', '=', $turn->id], ['status', '=', 'approved']])->get()) >= $turn->max_reservation){
            return $this->customResponse('Failed to create reservation, limit exceeded', 400);
        }

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
            'status' => 'required',
        ]);

        $reservation = Reservation::find($id);
        $reservation->update($request->only(['status']));

        if($reservation){
            //return updated data
            $resource = new Item($reservation, new ReservationTransformer);
            return $this->fractal->createData($resource)->toArray();
        }

        //Return error 400 response if updated was not successful
        return $this->customResponse('Failed to update!', 400);
    }

    public function customResponse($message = 'success', $status = 200)
    {
        return response(['status' =>  $status, 'message' => $message], $status);
    }
}
