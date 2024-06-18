        <div class="col-xs-12 bordered">
            <div class="row">
                <div class="col-xs-12 col-md-6 p-b-25">
                    <div class="content-panel">
                        <h3 style="text-transform:uppercase;">
                            <i class="fa fa-angle-right"></i>
                            Investissement par Annonceur
                        </h3>
                        <section id="sinvestParAnnonceur">
                            <div class="table-responsive">
                                
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div id="investParAnnonceur" style="min-width: 600px; height: {{$hauteur ?? 600 }}px; margin: 0 auto;">
                                            
                                        </div>
                                    </div>
                                </div>
                    
                            </div>
                        </section>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 p-b-25">
                    <div class="content-panel">
                        <h3 style="text-transform:uppercase;">
                            <i class="fa fa-angle-right"></i>
                            Insertion par Annonceur
                        </h3>
                        <section id="sinsertParAnnonceur">
                            <div class="table-responsive">
                                
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <div id="insertParAnnonceur" style="min-width: 600px; height: {{$hauteur ?? 600 }}px; margin: 0 auto;">
                                            
                                        </div>
                                    </div>
                                </div>
                    
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div> 

        <div class="col-xs-12 bordered">
            <div class="row">
                <div class="col-xs-12 col-md-6 p-b-25">
                    <div class="content-panel">
                        <h3 style="text-transform:uppercase;">
                            <i class="fa fa-angle-right"></i>
                            Investissement par Localit&eacute;
                        </h3>
                        <section id="sinvestParLocalite">
                            <div class="table-responsive">
                                
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <div id="investParLocalite" style="min-width: 600px; height: {{$hauteur ?? 600 }}px; margin: 0 auto;">
                                            
                                        </div>
                                    </div>
                                </div>
                    
                            </div>
                        </section>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 p-b-25">
                    <div class="content-panel">
                        <h3 style="text-transform:uppercase;">
                            <i class="fa fa-angle-right"></i>
                            Insertion par  Localit&eacute;
                        </h3>
                        <section id="sinsertParLocalite">
                            <div class="table-responsive">
                                
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <div id="insertParLocalite" style="min-width: 600px; height: {{$hauteur ?? 600 }}px; margin: 0 auto;">
                                            
                                        </div>
                                    </div>
                                </div>
                    
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div> 
      
        
    @inject("highChart", "\App\Http\Controllers\Charts\HighchartController")
        @if(is_array($listeDesMap))
            {!! $highChart::makeHighchartJS("insertParLocalite", "Nombre de panneaux par Localite", $listeDesMap['insertParLocalite']) !!}
            {!! $highChart::makeHighchartJS("investParLocalite", "Investissement par Localite", $listeDesMap['investParLocalite']) !!}
            {!! $highChart::makeHighchartJSColor("insertParAnnonceur", "Nombre de panneaux par Annonceur", $listeDesMap['insertParAnnonceur'], $listeDesMap['lesCouleurs']) !!}
            {!! $highChart::makeHighchartJSColor("investParAnnonceur", "Investissement par par Annonceur", $listeDesMap['investParAnnonceur'], $listeDesMap['lesCouleurs']) !!}
        @endif      

    <script type="text/javascript">
    // Radialize the colors
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