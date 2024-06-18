<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = 'vz_routes';
    
    public function role(){
        return $this->hasMany('App\Models\Role', 'route');
    }
}
