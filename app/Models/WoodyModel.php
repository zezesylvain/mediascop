<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WoodyModel extends Model
{
    protected $guarded = [];
    protected $date = ['created_at','updated_at','date'];
}
