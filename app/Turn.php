<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Turn extends Model
{
    protected $guarded = [];

    public function service(){
        return $this->belongsTo('App\Service');
    }

}
