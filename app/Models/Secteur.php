<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    //
    public function annonceurs(){
        return $this->hasMany('App\Models\Secteur', 'secteur');
    }//
    public function rapports(){
        return $this->hasMany('App\Models\Rapport', 'secteur');
    }
    public function pubs(){
        return $this->hasMany('App\Models\Pub', 'secteur');
    }
    public function reportings(){
        return $this->hasMany('App\Models\Reporting', 'secteur');
    }
}
