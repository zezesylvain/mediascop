<?php

namespace App\Http\Controllers\core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session ;
use Illuminate\Support\Facades\DB ;

class MenuController extends Controller
{
    //
    public function index(){
            $sql = "SELECT * FROM zd_menus WHERE parent = %d ORDER BY rang ASC";
            $result = DB::select(DB::raw(sprintf($sql, 0))) ;
            $lesMenus = [] ;
            foreach ($result AS $item) :
                    $tab = [] ;
                    foreach ($item AS $k => $v) :
                        $tab[$k] = $v ;
                    endforeach;
                    $lesMenus[] = $tab ;
            endforeach;
            dd($lesMenus) ;
    }
}
