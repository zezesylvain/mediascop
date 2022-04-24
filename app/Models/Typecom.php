<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typecom extends Model
{
    //
    //
    public function operations(){
        return $this->hasMany('App\Models\Operation');
    }
    public function reportings(){
        return $this->hasMany('App\Models\Reporting');
    }
}
