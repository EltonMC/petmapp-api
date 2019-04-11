<?php

namespace App\Transformers;
use App\Pet;
use League\Fractal;

class PetTransformer extends Fractal\TransformerAbstract
{
	public function transform(Pet $pet)
	{
	    return [
            'id'      => (int) $pet->id,
            'user_id' => (int) $pet->user_id,
            'name' => $pet->name,
            'birthday' => $pet->birthday,
            'type' => $pet->type,
            'breed' => $pet->breed,
            'gender' => $pet->gender,
            'temper' => $pet->temper,
            'castrated' => $pet->castrated,
            'coat' => $pet->coat,
            'observation' => $pet->observation
	    ];
	}
}
