<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilRole extends Model
{
    protected $table = 'vz_profilroles';
    
    public function profil(){
        return $this->belongsTo('App\Models\Profil', 'profil');
    }
    public function role(){
        return $this->belongsTo('App\Models\Role', 'role');
    }
}
