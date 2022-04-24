@php
    $chartData = "" ;
    $virg = "" ;
    foreach ($donnees as $k => $v):
        if ($v != 0) :
            $chartData .= $virg . "[\"$k\", $v]";
            $virg = ",
                 ";
        endif;
    endforeach;
@endphp
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
                            text: "{{$titre}}"
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
                "data": [{{$chartData}}]
                            }]});
                });
            </script>