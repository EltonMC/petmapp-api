<?php

namespace App\Transformers;
use App\Reservation;
use League\Fractal;

class ReservationTransformer extends Fractal\TransformerAbstract
{
	public function transform(Reservation $reservation)
	{
	    return [
	        'id'      => (int) $reservation->id,
	        'pet'   => $reservation->pet,
            'petshop'    =>  $reservation->turn->service->petshop,
            'service'    =>  $reservation->turn->service,
            'turn'    =>  $reservation->turn,
            'status'    =>  $reservation->status,
            'reservation_day' => $reservation->reservation_day,
	        'created_at'    =>  $reservation->created_at->format('d-m-Y'),
	        'updated_at'    =>  $reservation->updated_at->format('d-m-Y'),
	    ];
	}
}
