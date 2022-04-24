@php
$listeDesGraphiquesAll = [
            'investParAnnonceur', 'investParMedia',
            'investParMediaEtParAnnonceur', 'investParAnnonceurEtParCible',
            'partInvestParNature', 'investParAnnonceurEtParFormat',
            'investParAnnonceurEtParNature','investParAnnonceurEtParMedia'
        ] ;
$listeDesGraphiques = [
            'investParAnnonceurEtParMedia',
            'investParAnnonceurEtParNature',
            'investParAnnonceurEtParCible' ,
            'investParAnnonceurEtParFormat'
        ] ;
        
@endphp
    @foreach($listeDesGraphiques AS $item)
        <div class="col-xs-12 bordered">
        @php($title = str_replace('invest', 'investissement', implode(" ", preg_split('/(?=[A-Z])/', $item, -1, PREG_SPLIT_NO_EMPTY))))
            <h3 style="text-transform:uppercase;">{{$title}}</h3>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="pie{{$item}}" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto;">
                        
                    </div>
                </div>
                <div class="col-xs-12 col-md-7">
                    <div id="{{$item}}" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto;">
                        
                    </div>
                </div>
            </div>
                    
        </div>
        <div class="col-xs-12 p-b-25">&nbsp;</div>
        <hr>
    @endforeach
    @inject("highChart", "\App\Http\Controllers\Charts\HighchartController")
    @foreach($listeDesGraphiques AS $item)
        @php($title = str_replace('invest', 'investissement', implode(" ", preg_split('/(?=[A-Z])/', $item, -1, PREG_SPLIT_NO_EMPTY))))
        @php($it2 = str_replace('ParAnnonceurEt', '', $item))
        @php($titre2 = str_replace('Par Annonceur Et', '', $title))
        @php($var = $$item)
        @php($var2 = $$it2)
        {!! $highChart::makeHighchartJSBar($item, $title, $var, $listDesCouleursDesAnnonceur) !!}
        {!! $highChart::makeHighchartJS("pie$item", $titre2, $var2) !!}
    @endforeach
    <script type="text/javascript">
    / Radialize the colors
        Highcharts.setOptions({
            colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
                return {
                    radialGradient: {
                        cx: 0.5,
                        cy: 0.3,
                        r: 0.7
                    },
                    stops: [
                        [0, color],
                        [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
                    ]
                };
            })
        });
    </script>