<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeService extends Model
{
    //
    protected $table = 'typeservices';
    public function operations(){
        return $this->hasMany('App\Models\Operation', 'typeservice');
    }
    public function reportings(){
        return $this->hasMany('App\Models\Reporting');
    }
}
