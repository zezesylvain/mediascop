<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuTarget extends Model
{
    protected $table = 'vz_menu_targets';
    
    public function menus(){
        return $this->hasMany('App\Models\Menu', 'menu_target');
    }
}
