<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonceur extends Model
{
    //
    public function secteur(){
        return $this->belongsTo('App\Models\Secteur', 'secteur');
    }
    public function operations(){
        return $this->hasMany('App\Models\Operation', 'annonceur');
    }
}
