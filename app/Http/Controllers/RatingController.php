<?php

namespace App\Http\Controllers;

use App\Rating;
use App\Reservation;
use App\Bill;

use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\RatingTransformer;

class RatingController extends Controller
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
        $paginator = Rating::
            join('reservations', 'ratings.reservation_id', '=', 'reservations.id')
            ->join('pets', 'reservations.pet_id', '=', 'pets.id')
            ->select('ratings.*')
            ->where('pets.user_id', $request->auth->id)
            ->paginate();
        $reservations = $paginator->getCollection();
        $resource = new Collection($reservations, new RatingTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($resource)->toArray();
    }

    public function store(Request $request){
        //validate request parameters
        $this->validate($request, [
            'reservation_id' => 'required|max:255',
            'rate' => 'required',
            'description' => 'required'
        ]);

        if (!$reservation = Reservation::find($request->get('reservation_id'))) return $this->customResponse('Failed to create rating, pet not exit!', 400);
        else $reservation->update(['status' => 'concluded']);

        $rating = Rating::create($request->only([
            'reservation_id',
            'rate',
            'description'
        ]));

        if($rating) Bill::create($request->only('reservation_id'));

        $resource = new Item($rating, new RatingTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function customResponse($message = 'success', $status = 200)
    {
        return response(['status' =>  $status, 'message' => $message], $status);
    }
}
