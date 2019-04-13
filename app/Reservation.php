<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Reservation extends Model
{
    protected $guarded = [];

    public function pet(){
        return $this->belongsTo('App\Pet');
    }

    public function turn(){
        return $this->belongsTo('App\Turn');
    }
}
