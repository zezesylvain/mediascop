<?php

namespace App\Http\Controllers\core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChartJsController extends Controller
{
            public static $option = [
                  "color" =>"rgba(0, 0, 0, .125)" , 
                  "backgroundColor" =>"rgba(2,117,216,0.2)" , 
                  "borderColor" =>"rgba(2,117,216,1)" ,
                  "pointBorderColor" =>"rgba(0, 235, 0, .15)" , 
                  "pointHoverBackgroundColor" =>"rgba(0, 235, 255, .12)" , 
                  "pointBackgroundColor" =>"rgba(2,117,216,1)" , 
                  "lineTension" => 0.3, 
                  "pointRadius" => 5, 
                  "pointHitRadius" => 20,
                  "pointBorderWidth" => 2, 
                  "pointHoverRadius" => 5
        ] ;
    /**
           * 
           * @param string $id
           * @param array $data : Le tableau de donnees pour le camembert sous la forme suivante
           * 
           * $data = [
           *                  "labels"                                   =>     ["Blue", "Red", "Yellow", "Green"],
           *                  "data"                                      =>    [12.21, 15.58, 11.25, 8.32],
           *                  "backgroundColor"                  =>    ['#007bff', '#dc3545', '#ffc107', '#28a745']
           *        ]
           * @param string $typeChart Le type de graphique, dans ce cas, il ya 2 ca possible: pie ou daughnut 
           */
          public static function pie(string $id, array $data, string $typeChart = "pie"): string{
                    $labels = "" ;
                    $bg = "" ;
                    $virg = "" ;
                    foreach ($data["labels"] AS $k => $lab) :
                              $labels .= "$virg\"$lab\"" ;
                              $bg .= "$virg'" . $data["backgroundColor"][$k]  . "'" ;
                              $virg = ", " ;
                    endforeach;          
                    $donnees = join(", ", $data["data"]) ;
                    return <<<PIECHART
                                        <script type="text/javascript">
                                                  var ctx = document.getElementById("{$id}");
                                                  var {$id} = new Chart(ctx, {
                                                    type: '{$typeChart}',
                                                    data: {
                                                      labels: [{$labels}],
                                                      datasets: [{
                                                        data: [{$donnees}],
                                                        backgroundColor: [$bg],
                                                      }],
                                                    },
                                                  });
                                            </script>

PIECHART;
          }


          /**
           * 
           * @param string $id
           * @param array $data : Le tableau de donnees pour le camembert sous la forme suivante
           * 
           * $data = [
           *                  "labels"                                   =>     ["Blue", "Red", "Yellow", "Green"],
           *                  "data"                                      =>    [12.21, 15.58, 11.25, 8.32]
           *        ]
           * @param array $param
           * @return string
           */
          public static function area(string $id, array $data, string $type = "line", array $param = []) : string{                    
                    $labels = "" ;
                    $virg = "" ;
                    foreach ($data["labels"] AS $lab) :
                              $labels .= "$virg\"$lab\"" ;
                              $virg = ", " ;
                    endforeach;  
                    $donnees = join(", ", $data["data"]) ;
                    $maxData =1.1 *  max($data["data"]) ;
                    $color                      = $param["color"]                         ??        "rgba(0, 0, 0, .125)" ;
                    $label                      = $param["label"]                         ??        "Sessions" ;
                    $unit                      = $param["unit"]                         ??        "date" ;
                    $lineTension           = $param["lineTension"]               ??        0.3 ;
                    $backgroundColor = $param["backgroundColor"]      ??        "rgba(2,117,216,0.2)" ;
                    $borderColor         = $param["borderColor"]              ??         "rgba(2,117,216,1)" ;
                    $pointRadius         = $param["pointRadius"]               ??         5 ;
                    $pointHitRadius     = $param["pointHitRadius"]          ??        20 ;
                    $pointBorderWidth = $param["pointBorderWidth"]    ??        2 ;
                    $pointHoverRadius = $param["pointHoverRadius"]    ??        5 ;
                    $pointBorderColor = $param["pointBorderColor"]      ??      "rgba(255,255,255,0.8)" ;
                    $pointHoverBackgroundColor = $param["pointHoverBackgroundColor"] ??  "rgba(2,117,216,1)" ;
                    $pointBackgroundColor = $param["pointBackgroundColor"] ?? "rgba(2,117,216,1)" ;
                    return <<<AREACHART
                        <script type="text/javascript">
                              // -- Area Chart Example 
                              var ctx = document.getElementById("{$id}");
                              var {$id} = new Chart(ctx, {
                                type: '{$type}',
                                data: {
                                  labels: [{$labels}],
                                  datasets: [{
                                    label: "{$label}",
                                    lineTension: {$lineTension},
                                    backgroundColor: "{$backgroundColor}",
                                    borderColor: "{$borderColor}",
                                    pointRadius: {$pointRadius},
                                    pointBackgroundColor: "{$pointBackgroundColor}",
                                    pointBorderColor: "{$pointBackgroundColor}",
                                    pointHoverRadius: {$pointHoverRadius},
                                    pointHoverBackgroundColor: "{$pointHoverBackgroundColor}",
                                    pointHitRadius: {$pointHitRadius},
                                    pointBorderWidth: {$pointBorderWidth},
                                    data: [{$donnees}],
                                  }],
                                },
                                options: {
                                  scales: {
                                    xAxes: [{
                                      time: {
                                        unit: '{$unit}'
                                      },
                                      gridLines: {
                                        display: false
                                      },
                                      ticks: {
                                        maxTicksLimit: 7
                                      }
                                    }],
                                    yAxes: [{
                                      ticks: {
                                        min: 0,
                                        max: {$maxData},
                                        maxTicksLimit: 5
                                      },
                                      gridLines: {
                                        color: "{$color}",
                                      }
                                    }],
                                  },
                                  legend: {
                                    display: false
                                  }
                                }
                              });
                         </script>
AREACHART;
         }


          /**
           * 
           * @param string $id
           * @param array $data : Le tableau de donnees pour le camembert sous la forme suivante
           * 
           * $data = [
           *                  "labels"                                   =>     ["Blue", "Red", "Yellow", "Green"],
           *                  "data"                                      =>    [12.21, 15.58, 11.25, 8.32]
           *        ]
           * @param array $param
           * @return string
           */
          public static function plotter(string $id, array $data, string $type = "line", array $param = []) : string{                    
                    $labels = "" ;
                    $virg = "" ;
                    foreach ($data["labels"] AS $lab) :
                              $labels .= "$virg\"$lab\"" ;
                              $virg = ", " ;
                    endforeach;  
                    $options = "" ;
                    $vir = "," ;
                    foreach ($param AS $k => $v):
                        $val = is_numeric($v) ? "$k: $v" : " $k: \"$v\"" ;
                        $options .= "$vir $val" ;
                        $vir = ",
                                  " ;
                    endforeach;
                    $donnees = join(", ", $data["data"]) ;
                    $maxData =1.1 *  max($data["data"]) ;
                    return <<<AREACHART
                        <script type="text/javascript">
                              // -- Area Chart Example 
                              var ctx = document.getElementById("{$id}");
                              var {$id} = new Chart(ctx, {
                                type: '{$type}',
                                data: {
                                  labels: [{$labels}],
                                  datasets: [{
                                    $options
                                    data: [{$donnees}],
                                  }],
                                },
                                options: {
                                  scales: {
                                    xAxes: [{
                                      time: {
                                        unit: '{$unit}'
                                      },
                                      gridLines: {
                                        display: false
                                      },
                                      ticks: {
                                        maxTicksLimit: 7
                                      }
                                    }],
                                    yAxes: [{
                                      ticks: {
                                        min: 0,
                                        max: {$maxData},
                                        maxTicksLimit: 5
                                      },
                                      gridLines: {
                                        color: "{$color}",
                                      }
                                    }],
                                  },
                                  legend: {
                                    display: false
                                  }
                                }
                              });
                         </script>
AREACHART;
         }

         
          /**
           * 
           * @param string $id
           * @param array $data : Le tableau de donnees pour le camembert sous la forme suivante
           * 
           * $data = [
           *                  "labels"                                   =>     ["Blue", "Red", "Yellow", "Green"],
           *                  "data"                                      =>    [12.21, 15.58, 11.25, 8.32]
           *        ]
           * @param array $param
           * @return string
           */
          public static function bar(string $id, array $data, string $type = "bar", array $param = []) : string{                    
                    $labels = "" ;
                    $virg = "" ;
                    foreach ($data["labels"] AS $lab) :
                              $labels .= "$virg\"$lab\"" ;
                              $virg = ", " ;
                    endforeach;    
                    $donnees = join(", ", $data["data"]) ;
                    $maxData =1.1 *  max($data["data"]) ;
                    $label                      = $param["label"]                         ??        "Sessions" ;
                    $unit           = $param["unit"]               ??        "month";
                    $backgroundColor = $param["backgroundColor"]      ??        "rgba(2,117,216,0.2)" ;
                    $borderColor         = $param["borderColor"]              ??         "rgba(2,117,216,1)" ;
                    return <<<AREACHART
                        <script type="text/javascript">
                              // -- Area Chart Example 
                              var ctx = document.getElementById("{$id}");
                              var {$id} = new Chart(ctx, {
                                type: '{$type}',
                                data: {
                                  labels: [{$labels}],
                                  datasets: [{
                                    label: "{$label}",
                                      backgroundColor: "{$backgroundColor}",
                                      borderColor: "{$borderColor}",
                                    data: [{$donnees}],
                                  }],
                                },
                                options: {
                                  scales: {
                                    xAxes: [{
                                      time: {
                                        unit: '{$unit}'
                                      },
                                      gridLines: {
                                        display: false
                                      },
                                      ticks: {
                                        maxTicksLimit: 6
                                      }
                                    }],
                                    yAxes: [{
                                      ticks: {
                                        min: 0,
                                        max: {$maxData},
                                        maxTicksLimit: 5
                                      },
                                      gridLines: {
                                        display: true
                                      }
                                    }],
                                  },
                                  legend: {
                                    display: false
                                  }
                                }
                              });
                         </script>
AREACHART;
         }
         /**
          * 
          * @param array $data Tableau de donnees a trsnformer en donnees pour chartjs sous la forme
          * 
          * @return array
          */
         public static function makeDatasets(array $data): array{
             $tab = [] ;
             foreach ($data AS $r) :
                            $donnees = join(", ", $r["data"]) ; 
                            unset($r["data"]) ;
                            $str = "" ;
                            foreach ($r AS $k => $v):
                                    $str .= "$k: '$v',
                                        " ;
                            endforeach;
                            $tab[] = <<<DATASET
                                        {
                                            {$str}
                                            data:[
                                                        {$donnees}
                                                    ]
                                        }
DATASET;
             endforeach;
             return $tab ;
         }

         public static function mixedChart(string $id, array $data, string $title = "", string $type = "bar", array $param = []) : string{                    
                    $labels = "" ;
                    $virg = "" ;
                    foreach ($data["labels"] AS $lab) :
                              $labels .= "$virg\"$lab\"" ;
                              $virg = ", " ;
                    endforeach;    
                    $datasets = "[" . join(", ", self::makeDatasets($data["data"])) . "]" ; 
                    return <<<AREACHART
                        <script type="text/javascript">
                              var chartData = {
                                        labels: [{$labels}],
                                        datasets: {$datasets}
                                  } ;
                              var ctx = document.getElementById("{$id}");
                              var {$id} = new Chart(ctx, {
                                type: '{$type}',
                                data: chartData,
                                options: {
                                        responsive: true,
                                        title: {
                                            display: true,
                                            text: '{$title}'
                                        },
                                        tooltips: {
                                            mode: 'index',
                                            intersect: true
                                        }
                                    }
                              });
                         </script>
AREACHART;
         }

}
