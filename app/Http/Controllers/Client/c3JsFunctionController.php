<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class c3JsFunctionController extends Controller
{
    public function __construct ()
    {
        $this->middleware('auth');
    }

    public static function makeC3JS(string $chartID,string $title,string $description,int $nb = 0){
        return view ("clients.charts.c3ChartArea",compact ('chartID','title','description','nb'))->render ();
    }

    public static function makeC3Data(string $title,string $description,array $datas,string $type = 'bar',bool $groupe = null,array $label = []) {
        $d = [];
        if (!empty($label)):
            $d['x'] = $label;
            $d['axis'] = "category";
        endif;
        if (!is_null ($groupe)):
            $d['groupe'] = $groupe;
        endif;
        $labels = !empty ($label) ? true : false;
        $c3Data = [
           "title"=>"{$title}",
           "description"=>"{$description}",
           "datas" => $datas,
           "type"=> $type,
           "labels"=> $labels,
       ] ;
       $c3Data = array_merge ($c3Data,$d);
        return $c3Data;
    }


}
