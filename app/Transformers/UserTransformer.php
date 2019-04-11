<?php

namespace App\Transformers;
use App\User;
use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
{
	public function transform(User $user)
	{
	    return [
	        'id'      => (int) $user->id,
	        'email'   => $user->email,
            'name'    =>  $user->name,
            'gender'    =>  $user->gender,
            'type'    =>  $user->type,
            'photo'    =>  $user->photo,
	        'created_at'    =>  $user->created_at->format('d-m-Y'),
	        'updated_at'    =>  $user->updated_at->format('d-m-Y'),
            'phone' => $user->phone->phone,
            'address' => $user->address
	    ];
	}
}
