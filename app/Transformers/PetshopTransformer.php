<?php

namespace App\Transformers;
use App\Petshop;
use League\Fractal;

class PetshopTransformer extends Fractal\TransformerAbstract
{
	public function transform(Petshop $petshop)
	{
	    return [
	        'id'      => (int) $petshop->id,
	        'user_id'   => $petshop->user_id,
            'name'    =>  $petshop->name,
            'description'    =>  $petshop->description,
            'phone'    =>  $petshop->user->phone,
            'address'    =>  $petshop->user->address,
            'images' => $petshop->images,
            'created_at'    =>  $petshop->created_at->format('d-m-Y'),
	        'updated_at'    =>  $petshop->updated_at->format('d-m-Y'),
	    ];
    }
}
