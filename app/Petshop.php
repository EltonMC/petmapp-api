<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Petshop extends Model
{
    protected $guarded = [];


    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function services(){
        return $this->hasMany('App\Service', 'petshop_id', 'id');
    }

    public function images(){
        return $this->hasMany('App\PetshopImage', 'petshop_id', 'id');
    }
}

