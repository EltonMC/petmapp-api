<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class PetshopImage extends Model
{
    protected $guarded = [];

    public function petshop(){
        return $this->belongsTo('App\Petshop');
    }
}
