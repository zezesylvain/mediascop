<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icone extends Model
{
    protected $table = 'vz_icones';
    
    public function menus(){
        return $this->hasMany('App\Models\Menu', 'icone');
    }    
    public function groupemenus(){
        return $this->hasMany('App\Models\GroupeMenu', 'groupemenu');
    }
}
