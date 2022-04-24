<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 07/04/2018
 * Time: 13:53
 */

namespace App\Http\Controllers\core;


interface envInterface
{
    public function getSchool();

    public function makeEnv();

    public  function selectedDatabase();

}