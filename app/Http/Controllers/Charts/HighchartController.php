<?php

namespace App\Http\Controllers\Charts;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Client\ReportingController;

class HighchartController extends Controller
{
    public  function __construct(){
        $this->middleware('auth');
    }
    public static function makeHighchartJS($identifiant, $titre, $donnees) {
            $chartData = "" ;
            $virg = "" ;
            $data = [] ;
            //dd($donnees, $titre) ;
            foreach ($donnees as $k => $v):
                $sum = is_array($v) ? array_sum($v) : $v ;
                $ab = addslashes($k) ;
                if ($sum != 0) :
                    $data[$k] = $sum ;
                endif;
            endforeach;
        return view('charts.highchart.highchartPie', compact('data', 'identifiant', 'titre'))->render();
    }
    public static function transfrmDataForChart(array $data){
        $key = [] ;
        foreach($data AS $k => $r):
            if(array_sum($r)>0):
                foreach($r AS $n => $t):
                    if(!in_array($n, $key)):
                        $key[] = $n ;
                    endif;
                endforeach;
            endif;
        endforeach;
        return $key ;
    }
    public static function BarChartDataForPieChartData($data){
        $tab = [] ;
        $key = ReportingController::get2ndKeyInTab($data) ;
        foreach($key AS $k):
            $tab[$k] = 0 ;
            foreach($data AS $t):
                $tab[$k] += $t[$k] ?? 0 ;
            endforeach;
        endforeach ;
        return self::trimTab($tab) ;
    }

    public static function transformTripleDataForChart($data, $tabKey = ["invest", "insertion"]){
        $tab = [] ;
        $lesKeys = [];
        $donnees = [];
        $lesCles = [];
        if(!empty($data)):
            foreach($tabKey AS $tk):
                $lesKeys[$tk] = [];
            endforeach;
            foreach($data AS $k => $r):
                foreach($r AS $n => $t):
                    foreach($tabKey AS $tk):
                        $tab[$tk][$k][$n] = $t[$tk] ;
                        if(!array_key_exists($n, $lesKeys[$tk])):
                            $lesKeys[$tk][$n] = $t[$tk];
                        else:
                            $lesKeys[$tk][$n] += $t[$tk];
                        endif;
                    endforeach;
                endforeach;
            endforeach ;
            foreach($lesKeys AS $k => $t):
                $lesCles[$k] = [];
                foreach($t AS $n => $v):
                    if($v):
                        $lesCles[$k][] = $n;
                    endif;
                endforeach;
            endforeach;
            foreach($tabKey AS $tk):
                $donnees[$tk] = self::makeBarChartData($tab[$tk], $lesCles[$tk]);
            endforeach;
        endif;
        return compact('donnees', 'lesCles') ;
    }
    public static function makeBarChartData($data, $abscisses){
        $donnees = [];
        foreach($data AS $k => $r):
            $donnees[$k] = [];
            foreach($abscisses AS $x):
                $donnees[$k][$x] = $r[$x] ?? 0 ;
            endforeach;
        endforeach;
        return $donnees;
    }
    public static function trimTab(array $tab): array{
        $data = [];
        foreach($tab AS $k => $v):
            $s = is_array($v) ? array_sum($v) : $v ;
            if($s):
                $data[$k] = $v ;
            endif;
        endforeach;
        return $data;
    }
    public static function makeHighchartJSBar($identifiant, $titre, $donnees, $colorArray = array()) {
        $chartData = "";
        $virg = "";
        $lesAbscisses = "";
        //$key = ReportingController::get2ndKeyInTab($donnees) ;
        $key = array_keys(self::BarChartDataForPieChartData($donnees));
            $virgule = "";
        foreach ($key as $m) :
            $ab = addslashes($m) ;
            $lesAbscisses .= "$virgule'$ab'";
            $virgule = ", ";
        endforeach;
        foreach ($donnees as $k => $r):
            $virgule = "";
            $couleur = "";
            foreach ($key as $m) :
                if(!array_key_exists($m, $r)):
                    $r[$m] = 0 ;
                endif;
            endforeach;
            if (array_key_exists($k, $colorArray)):
                $couleur = "
                    color: '$colorArray[$k]',";
            endif;
            $chartData .= "$virg {
                    name: '$k',
                    data: [" . join(',', $r) . "],
                    $couleur
                    stack: 'Selection'
                }";
            $virg = ",
                 ";
        endforeach;
        return view('charts.highchart.highchartBar', compact('chartData', 'identifiant', 'lesAbscisses', 'titre'))->render();
    }
    public static function makeHighchartJSColor($identifiant, $titre, $donnees, $couleur) {
        //dd($donnees);
        $chartData = [];
        $virg = "";
        $colors = [] ;
        foreach ($donnees as $k => $v):
            if ($v != 0) :
                $colors[$k] = array_key_exists($k, $couleur) ? $couleur[$k] : "" ;
                $chartData[$k] = $v ;
            endif;
        endforeach;
        return view('charts.highchart.highchartJscolor', compact('chartData', 'identifiant', 'titre', 'colors'))->render();
    }
    public static function makeHighchartJSon($identifiant, $titre, $file, $mesDonnees = "mesDonnees") {
        return view('charts.highchart.highchartJson', compact('mesDonnees', 'identifiant', 'titre', 'file'))->render();
    }
    public static function transformTripleDataForBarChart($data, $type = "invest"){
        $tab = [] ;
        foreach($data AS $k => $r):
            foreach($r AS $n => $t):
                $tab[$k][$n] = $t[$type] ;
            endforeach;
        endforeach ;
        return $tab ;
    }
    
    public static function makeHighchartJSOLD($identifiant, $titre, $donnees) {
        $chartData = "";
        $virg = "";
        foreach ($donnees as $k => $v):
            $sum = is_array($v) ? array_sum($v) : $v ;
            $ab = addslashes($k) ;
            if ($v != 0) :
                $chartData .= $virg . "{
                    name:'$ab',
                    y:$sum
                }";
                $virg = ",
                     ";
            endif;
        endforeach;
        $data = "" ;
        foreach ($donnees as $k => $v):
            $sum = is_array($v) ? array_sum($v) : $v ;
            $ab = addslashes($k) ;
            if ($v != 0) :
                $data .= $virg . "[\"$ab\", $sum]";
                $virg = ",
                     ";
            endif;
        endforeach;
        $return = <<<EOT
            <script type="text/javascript">
                $(function () {
                    $('#$identifiant').highcharts({
                        chart: {
                            type: 'pie',
                            options3d: {
                                enabled: true,
                                alpha: 45
                            }
                        },
                        title: {
                            text: "$titre"
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        subtitle: {
                            text: ""
                        },
                        plotOptions: {
                            pie: {
                                innerSize: 100,
                                depth: 45
                            }
                        },
                        series: [{
                "name": "Valeur",
                "data": [$data]
                            }]});
                });
            </script>
EOT;
        return $return;
    }

}