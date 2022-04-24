<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    //
    public function media(){
        return $this->belongsTo('App\Models\Media', 'media');
    }
    public function speednews(){
        return $this->hasMany('App\Models\Speednew', 'support');
    }
    
    public function reportings(){
        return $this->hasMany('App\Models\Reporting', 'support');
    }
}
