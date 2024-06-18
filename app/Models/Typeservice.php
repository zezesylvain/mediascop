<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typeservice extends Model
{
    //
    public function operations(){
        return $this->hasMany('App\Models\Operation', 'typeservice');
    }
    public function reportings(){
        return $this->hasMany('App\Models\Reporting');
    }
}
