            <script type="text/javascript">
                $(function () {
                    $('#{{$identifiant}}').highcharts({
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: '{{$titre}}'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    connectorColor: 'silver'
                                }
                            }
                        },
                        series: [{
                        name: 'Valeur',
                        data: [
                            @php($virg = "")
                            @foreach($data AS $x => $y)
                                    {{$virg}}{
                                        name:'{{addslashes($x)}}',
                                        y:{{$y}}
                                    }
                                    @php($virg = ",
                                    ")
                            @endforeach
                                ]
                            }]
                    });
                });
            </script>