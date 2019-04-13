<?php

namespace App\Transformers;
use App\Rating;
use League\Fractal;

class RatingTransformer extends Fractal\TransformerAbstract
{
	public function transform(Rating $rating)
	{
	    return [
	        'id'      => (int) $rating->id,
	        'user'   => $rating->reservation->pet->user,
            'rate'    =>  $rating->rate,
            'description'    =>  $rating->description,
	        'created_at'    =>  $rating->created_at->format('d-m-Y'),
	        'updated_at'    =>  $rating->updated_at->format('d-m-Y'),
	    ];
	}
}
