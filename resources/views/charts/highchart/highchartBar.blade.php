           <script type="text/javascript">
                $(function () {
                    $('#{{$identifiant}}').highcharts({
                        chart: {
                            type: 'column',
                           marginTop: 80,
                            marginRight: 40
                        },
                        title: {
                            text: '{{$titre}}'
                        },
                        xAxis: {
                             categories: [{!!$lesAbscisses!!}]
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
                        series: [{!!$chartData!!}]
                    });
                });
            </script>