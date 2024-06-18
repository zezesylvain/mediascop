<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campagne extends Model
{
    protected $table = 'campagnes';
    public function campagnetitle(){
        return $this->belongsTo(Campagnetitle::class,'campagnetitle');
    }
    public function cible(){
        return $this->belongsTo(Cible::class,'cible');
    }
}
