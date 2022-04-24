<?php

namespace App\Http\Controllers\core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FunctionHighchartController extends Controller
{
    public  function __construct(){
        $this->middleware('auth');
    }

    public static function makeHighchartJS($identifiant, $titre, $donnees) {
        $data = "";
        $virg = "";
        foreach ($donnees as $k => $v):
            if ($v != 0) :
                $data .= $virg . "[\"$k\", $v]";
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

    public static function makeHighchartJSBar($identifiant, $titre, $donnees, $colorArray = array()) {
        $data = "";
        $virg = "";
        $lesAbscisses = "";
        $inc = 0;
        foreach ($donnees as $k => $r):
            $virgule = "";
            $couleur = "";
            if (array_key_exists($k, $colorArray)):
                $couleur = '
                    "color": "#' . $colorArray[$k] . '",';
            endif;
            $data .= $virg . '{
                    "name": "' . $k . '",
                    "data": [' . join(',', $r) . '],
                    ' . $couleur . '
                    "stack": "Selection"
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
        $return = <<<EOT
            <script type="text/javascript">
                $(function () {
                    $('#$identifiant').highcharts({
                        chart: {
                            type: 'column',
                           marginTop: 80,
                            marginRight: 40
                        },
                        title: {
                            text: '$titre'
                        },
                        xAxis: {
                             categories: [$lesAbscisses]
                        },
           yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -30,
                verticalAlign: 'top',
                y: 25,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                shared: true
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 1px black'
                        }
                    }
                }
            },
                        series: [$data ]
                    });
                });
            </script>
EOT;
        return $return;
    }

    public static function makeHighchartJSColor($identifiant, $titre, $donnees, $couleur = array()) {
        $data = "";
        $virg = "";
        foreach ($donnees as $k => $v):
            $color = array_key_exists($k, $couleur) ? ",color:'#$couleur[$k]'" : "";
            if ($v != 0) :
                $data .= $virg . "{
                                name: '$k',
                                y: $v
                                $color
                            }";
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


    public static function makeHighchartJSon($identifiant, $titre, $file, $mesDonnees = "mesDonnees") {
        $return = <<<EOT
            <script type="text/javascript">
                $.getJSON( "$file", function($mesDonnees) {
                    $(public static function () {
                        $('#$identifiant').highcharts({
                            chart: {
                                type: 'column',
                               marginTop: 80,
                                marginRight: 40
                            },
                            title: {
                                text: '$titre'
                            },
                            xAxis: {
                                 categories: $mesDonnees.abscisses
                            },
               yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    },
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                        }
                    }
                },
                legend: {
                    align: 'right',
                    x: -30,
                    verticalAlign: 'top',
                    y: 25,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false
                },
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                    shared: true
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                            style: {
                                textShadow: '0 0 1px black'
                            }
                        }
                    }
                },
                            series: $mesDonnees.donnees
                        });
                    });
                });
            </script>
EOT;
        return $return;
    }

}
