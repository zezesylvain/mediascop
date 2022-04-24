<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSpeednews extends Model
{
    protected $table = 'user_speednews';

    protected $fillable = ['user','actif'];

    public function user()
    {
        return $this->belongsTo(User::class,'user');
    }

}
