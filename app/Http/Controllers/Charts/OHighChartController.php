<?php

namespace App\Http\Controllers\Charts;

use App\Http\Controllers\Controller;

class HighchartController extends Controller
{
    public  function __construct(){
        $this->middleware('auth');
    }
    public static function makeHighchartJS($identifiant, $titre, $donnees) {
        return view('charts.highchart.highchartPie', compact('donnees', 'identifiant', 'titre'))->render();
    }
    public static function makeHighchartJSBar($identifiant, $titre, $donnees, $colorArray = array()) {
        $chartData = "";
        $virg = "";
        $lesAbscisses = "ZEZE";
        dd($lesAbscisses);
        $inc = 0;
        foreach ($donnees as $k => $r):
            $virgule = "";
            $couleur = "";
            if (array_key_exists($k, $colorArray)):
                $couleur = '
                    "color": "#' . $colorArray[$k] . '",';
            endif;
            $chartData .= $virg . '{
                    name: "' . $k . '",
                    data: [' . join(',', $r) . '],
                    ' . $couleur . '
                    stack: "Selection"
                }';
            if ($inc == 1):
                foreach ($r as $m => $v) :

                    $lesAbscisses .= "$virgule'$m'";
                    $virgule = ", ";

                endforeach;
            endif;
            $inc++;
            $virg = ",
                 ";
        endforeach;
        dd($lesAbscisses);
        return view('charts.highchart.highchartBar', compact('chartData', 'identifiant', 'lesAbscisses', 'titre'))->render();
    }
    public static function makeHighchartJSColor($identifiant, $titre, $donnees, $couleur = array()) {
        return view('charts.highchart.highchartJscolor', compact('donnees', 'identifiant', 'titre', 'couleur'))->render();
    }
    public static function makeHighchartJSon($identifiant, $titre, $file, $mesDonnees = "mesDonnees") {
        return view('charts.highchart.highchartJson', compact('mesDonnees', 'identifiant', 'titre', 'file'))->render();
    }
}