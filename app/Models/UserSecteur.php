<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSecteur extends Model
{
    protected $fillable = ['user','secteur','actif'];


    public function user()
    {
        return $this->belongsTo(User::class,'user');
    }
}
