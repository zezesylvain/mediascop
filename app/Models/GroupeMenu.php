<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupeMenu extends Model
{
    protected $table = 'vz_groupemenus';
    
    public function menus(){
        return $this->hasMany('App\Models\Menu', 'groupemenu');
    }
    public function icone(){
        return $this->belongsTo('App\Models\Icone', 'icone');
    }
}
