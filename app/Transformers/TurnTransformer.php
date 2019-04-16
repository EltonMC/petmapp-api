<?php

namespace App\Transformers;
use App\Turn;
use League\Fractal;

class TurnTransformer extends Fractal\TransformerAbstract
{
	public function transform(Turn $turn)
	{
	    return [
	        'id'      => (int) $turn->id,
	        'service_id'   => $turn->service_id,
            'day'    =>  $turn->day,
            'time_start'    =>  $turn->time_start,
            'time_end'    =>  $turn->time_end,
            'max_reservation'    =>  $turn->max_reservation,
            'percent_discount'    =>  $turn->percent_discount,
            'status'    =>  $turn->status,
            'created_at'    =>  $turn->created_at->format('d-m-Y'),
	        'updated_at'    =>  $turn->updated_at->format('d-m-Y'),
	    ];
    }
}
