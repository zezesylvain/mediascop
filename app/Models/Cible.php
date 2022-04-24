<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cible extends Model
{

    public function pubs(){
        return $this->hasMany('App\Models\Pub', 'cible');
    }
    public function reportings(){
        return $this->hasMany('App\Models\Reporting', 'cible');
    }
}
