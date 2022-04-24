<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offretelecom extends Model
{
    //
    
    public function campagnetitles(){
        return $this->hasMany('App\Models\Campagnetitle', 'campagnetitle');
    }
}
