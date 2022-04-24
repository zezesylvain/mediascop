<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    //
    public function medias()
    {
        return $this->belongsTo('App\Media');
    }
}
