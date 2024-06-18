<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localite extends Model
{

    public function reportings(){
        return $this->hasMany('App\Models\Reporting', 'localite');
    }
    public function points(){
        return $this->hasMany('App\Models\Point');
    }
}
