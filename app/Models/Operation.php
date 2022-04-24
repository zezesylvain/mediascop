<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    //
    public function annonceur(){
        return $this->belongsTo('App\Models\Annonceur', 'annonceur');
    }
    public function couverture(){
        return $this->belongsTo('App\Models\Couverture', 'couverture');
    }
    public function typecom(){
        return $this->belongsTo('App\Models\Typecom');
    }
    public function typeservice(){
        return $this->belongsTo('App\Models\Typeservice');
    }
    public function campagnetitles(){
        return $this->hasMany('App\Models\Campagnetitle', 'operation');
    }
    public function reportings(){
        return $this->hasMany('App\Models\Reporting', 'operation');
    }
}
