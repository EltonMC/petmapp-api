<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Petshop extends Model
{
    protected $guarded = [];

    public function service(){
        return $this->hasOne('App\Service');
    }

    public function image(){
        return $this->hasMany('App\PetshopImage');
    }
}
