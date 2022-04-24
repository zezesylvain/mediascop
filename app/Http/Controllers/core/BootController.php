<?php

namespace App\Http\Controllers\core;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\core\FunctionController;
use App\Http\Controllers\Controller;

class BootController extends Controller {

    //
    public function getDefaultFieldParam() {
        $result = DB::select(DB::raw("SELECT * FROM zd_woo_fiwlsdefaults"));
    }

}
