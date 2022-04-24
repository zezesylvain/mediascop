<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    //
    public function media(){
        return $this->belongsTo('App\Models\Media', 'media');
    }
    
    public function reportings(){
        return $this->hasMany('App\Models\Reporting', 'format');
    }
}
