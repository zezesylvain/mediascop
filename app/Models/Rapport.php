<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    //
    public function secteur(){
        return $this->belongsTo('App\Models\Secteur', 'secteur');
    }
    public function periodicite(){
        return $this->belongsTo('App\Models\Periodicite', 'periodicite');
    }
}
