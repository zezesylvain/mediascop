@extends("layouts.client")
@section("content")
{!! $campagnecontent !!}
       <script type="text/javascript">
                $(function() {
                    $('#repartitionCampagneSurMedia').highcharts({
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null, //1,
                            plotShadow: false
                        },
                        title: {
                            text: 'Investissement par média sur $partdelaCampagne'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: false,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                                type: 'pie',
                                name: 'Part de la campagne',
                                data: [{$transformArray($leTabInvest)}]
                            }]
                    });
                });
                $(function() {
                    $('#partDeLaCampagneSurSelection').highcharts({
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null, //1,
                            plotShadow: false
                        },
                        title: {
                            text: 'Part investissement sur $investtotaldelaselection'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: false,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                                type: 'pie',
                                name: 'Part invest',
                                data: [
                                            ['La campagne', $partdelaCampagne],
                                            ['Autres campagnes', $autrescampagnesdelaselection]                        ]
                            }]
                    });
                });

            /**
         * Grid-light theme for Highcharts JS
         * @author Torstein Honsi
         */
        // Load the fonts
        Highcharts.createElement('link', {
           href: 'http://fonts.googleapis.com/css?family=Dosis:400,600',
           rel: 'stylesheet',
           type: 'text/css'
        }, null, document.getElementsByTagName('head')[0]);
        Highcharts.theme = {
           colors: ["#7cb5ec", "#f7a35c", "#90ee7e", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
              "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
           chart: {
              backgroundColor: null,
              style: {
                 fontFamily: "Dosis, sans-serif"
              }
           },
           title: {
              style: {
                 fontSize: '12px',
                 fontWeight: 'bold',
                 textTransform: 'uppercase'
              }
           },
           tooltip: {
              borderWidth: 0,
              backgroundColor: 'rgba(219,219,216,0.8)',
              shadow: false
           },
           legend: {
              itemStyle: {
                 fontWeight: 'bold',
                 fontSize: '12px'
              }
           },
           xAxis: {
              gridLineWidth: 1,
              labels: {
                 style: {
                    fontSize: '12px'
                 }
              }
           },
           yAxis: {
              minorTickInterval: 'auto',
              title: {
                 style: {
                    textTransform: 'uppercase'
                 }
              },
              labels: {
                 style: {
                    fontSize: '12px'
                 }
              }
           },
           plotOptions: {
              candlestick: {
                 lineColor: '#404048'
              }
           },
           // General
           background2: '#F0F0EA'
        };
        // Apply the theme
        Highcharts.setOptions(Highcharts.theme);
    </script>
@stop