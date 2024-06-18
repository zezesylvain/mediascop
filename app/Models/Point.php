<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $fillable = ['localite_id', 'latitude', 'longitude', 'name', 'description'];
    
    public function reportings(){
        return $this->hasMany('App\Models\Reporting');
    }
    public function localite(){
        return $this->belongsTo('App\Models\Localite');
    }
}
