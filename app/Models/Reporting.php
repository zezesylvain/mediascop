<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporting extends Model
{
        public function secteur(){
        return $this->belongsTo('App\Models\Secteur', 'secteur');
    }
        public function cible(){
        return $this->belongsTo('App\Models\Cible', 'cible');
    }

    public function annonceur(){
        return $this->belongsTo('App\Models\Annonceur', 'annonceur');
    }
    public function operation(){
        return $this->belongsTo('App\Models\Operation', 'operation');
    }
    public function campagnetitle(){
        return $this->belongsTo('App\Models\Campagnetitle', 'campagnetitle');
    }
    public function format(){
        return $this->belongsTo('App\Models\Format', 'format');
    }
    public function media(){
        return $this->belongsTo('App\Models\Media', 'media');
    }
    public function support(){
        return $this->belongsTo('App\Models\Support', 'support');
    }
    public function typecom(){
        return $this->belongsTo('App\Models\Typecom');
    }
    public function typeservice(){
        return $this->belongsTo('App\Models\Typeservice');
    }
    public function localite(){
        return $this->belongsTo('App\Models\Localite', 'localite');
    }
    public function point(){
        return $this->belongsTo('App\Models\Point');
    }
}
