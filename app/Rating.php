<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Rating extends Model
{
    protected $guarded = [];

    public function reservation(){
        return $this->belongsTo('App\Reservation');
    }
}
