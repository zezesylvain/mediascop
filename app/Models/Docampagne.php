<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docampagne extends Model
{
    //
    public function campagnetitle(){
        return $this->belongsTo('App\Models\Campagnetitle', 'campagnetitle');
    }
    public function media(){
        return $this->belongsTo('App\Models\Media', 'media');
    }
}
