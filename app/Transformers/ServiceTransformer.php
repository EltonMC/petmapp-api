<?php

namespace App\Transformers;
use App\Service;
use League\Fractal;

class ServiceTransformer extends Fractal\TransformerAbstract
{
	public function transform(Service $service)
	{
	    return [
	        'id'      => (int) $service->id,
	        'petshop_id'   => $service->petshop_id,
			'type'    =>  $service->type,
			'price' =>  $service->price,
            'max_discount' =>  $service->max_discount,
            'status'    =>  $service->status,
            'created_at'    =>  $service->created_at->format('d-m-Y'),
	        'updated_at'    =>  $service->updated_at->format('d-m-Y'),
	    ];
    }
}
