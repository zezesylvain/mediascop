<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campagnetitle extends Model
{
    //
    public function docampagnes(){
        return $this->hasMany('App\Models\Docampagne', 'campagnetitle');
    }
    public function campagnes(){
        return $this->hasMany('App\Models\Campagne', 'campagnetitle');
    }
    public function speednews(){
        return $this->hasMany('App\Models\Speednew', 'campagnetitle');
    }
    public function offretelecom(){
        return $this->belongsTo('App\Models\Offretelecom', 'offretelecom');
    }
    public function operation(){
        return $this->belongsTo('App\Models\Operation', 'operation');
    }
    public function reportings(){
        return $this->hasMany('App\Models\Reporting', 'campagnetitle');
    }
}
