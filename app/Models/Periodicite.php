<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodicite extends Model
{
    //
    public function rapports(){
        return $this->hasMany('App\Models\Rapport', 'periodicite');
    }
}
