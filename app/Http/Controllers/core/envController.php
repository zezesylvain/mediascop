<?php

namespace App\Http\Controllers\core;

use App\User;
use Dotenv\Dotenv;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

class envController implements envInterface
{

    public function getSchool()
    {
        $url = $_SERVER['REQUEST_URI'];
        $sch = 'xerya';
        $t = explode('/',$url);
        //dd($url,$t);
        if (isset($t[2]) && in_array($t[2],array_keys(Config::get('db')))):
            $sch = $t[2];
        endif;

        return $sch;
    }
    public function makeEnv()
    {
        // TODO: Implement makeEnv() method.
    }

    public  function selectedDatabase(){
        $r = $this->getSchool();
        $conn = $this->getSchool();
        $newDb = Config::get("db.$conn.database");
        $env = new Dotenv(app_path('env'),Config::get("db.$conn.file"));
        $env->overload();
        DB::setDefaultConnection(Config::get("db.$conn.connection"));
        $con = new User();
        $con->setConnection($newDb);
        dump(DB::getDefaultConnection());
        $env = new Dotenv(app_path('env'),Config::get("db.$r.file"));
        return $env->overload();
    }

}
