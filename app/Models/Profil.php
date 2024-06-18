<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $table = 'vz_profils';
    
    public function profilroles(){
        return $this->hasMany('App\Models\ProfilRole', 'profil');
    }
}
