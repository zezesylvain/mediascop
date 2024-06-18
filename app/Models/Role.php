<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'vz_roles';
    
    public function menus(){
        return $this->hasMany('App\Models\Menu', 'role');
    }
    public function route(){
        return $this->belongsTo('App\Models\Route', 'route');
    }    
    public function profilroles(){
        return $this->hasMany('App\Models\ProfilRole', 'role');
    }
}
