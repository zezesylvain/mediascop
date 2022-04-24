<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\DbTablesHelper;

class Media extends Model
{
    //
    protected $table = 'medias';
    
    public function speednews(){
        return $this->hasMany('App\Models\Speednew', 'media');
    }
    public function supports(){
        return $this->hasMany('App\Models\Support', 'media');
    }
    public function formats(){
        return $this->hasMany('App\Models\Format', 'media');
    }
    public function reportings(){
        return $this->hasMany('App\Models\Reporting', 'media');
    }
}
