<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'vz_menus';
    
    public function groupemenu(){
        return $this->belongsTo('App\Models\GroupeMenu', 'groupemenu');
    }
    public function icone(){
        return $this->belongsTo('App\Models\Icone', 'icone');
    }
    public function role(){
        return $this->belongsTo('App\Models\Role', 'role');
    }
    public function menu_target(){
        return $this->belongsTo('App\Models\MenuTarget', 'menu_target');
    }

}
