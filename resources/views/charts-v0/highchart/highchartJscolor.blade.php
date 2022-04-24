
            <script type="text/javascript">
                $(function () {
                    $('#{{$identifiant}}').highcharts({
                        chart: {
                            type: 'pie',
                            options3d: {
                                enabled: true,
                                alpha: 45
                            }
                        },
                        title: {
                            text: '{{$titre}}'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        plotOptions: {
                            pie: {
                                innerSize: 100,
                                depth: 45
                            }
                        },
                        series: [{
                name: 'Valeur',
                data: [
                            @php($virg = "")
                            @foreach($chartData AS $x => $y)
                                    {{$virg}}{
                                        name:'{{addslashes($x)}}',
                                        y:{{$y}}
                                        @if($colors[$x])
                                            ,
                                            color: '{{$colors[$x]}}'
                                        @endif
                                    }
                                    @php($virg = ",
                                    ")
                            @endforeach
                ]
                            }]});
                });
            </script>