<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public $nbreNotif = 0;
    public $userProfil;
    public $userId;

    public function __construct()
    {

    }

}
