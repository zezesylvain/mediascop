<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pub extends Model
{
    //
    public function cible(){
        return $this->belongsTo('App\Models\Cible', 'cible');
    }
}
