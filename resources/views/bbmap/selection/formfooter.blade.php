<div class="row">
    <div class="col-sm-4 col-md-4">
        <div class="btn-group btn-group-justified">
            <div class="btn-group">
                <button class="btn btn-primary" type="submit" name="valider" id="valider"><i class="fa fa-check"></i> Valider</button>
            </div>
        </div>
    </div> <!-- END .col -->
    <div class="col-sm-4 col-md-4">
        <div class="btn-group btn-group-justified">
            <div class="btn-group">
                <button type="reset" class="btn btn-default btn-danger"><i class="fa fa-trash"></i>
                    Annuler
                </button>
            </div>
        </div>
    </div> <!-- END .col -->
</div>

<script type="text/javascript">
    /**
    $(document).ready(function () {
        $('#formulairedeselection').on('submit', function (event) {
            event.preventDefault();
            $.ajax(
                {
                    url: '',
                    method: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        //$('#answer').html(data.result);
                       // window.onload = loadScript();
                        //$('#mapCanvas').html(data.result);
                    }
                }
            );
        });
        
    })
    

    function loadScript() {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyD6cjLW8vcDWHitlFzWnWKb0QoFaLcu8xI&callback=initialize';
        document.body.appendChild(script);
    }

    /**
    $(document).ready( function () {
        $('#formulairedeselection').on('submit', function (event) {
            event.preventDefault();
            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });
    
            // Param??trage des marqueurs
            var pinColor = "29aec3";// couleur des ??pingles google MAP
            var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
                new google.maps.Size(21, 34),
                new google.maps.Point(0,0),
                new google.maps.Point(10, 34));
            var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
                new google.maps.Size(40, 37),
                new google.maps.Point(0, 0),
                new google.maps.Point(12, 35));
    
            function initialiser() {
                // R??cup??ration de la latitude et longitude pour centrer notre carte
                var formBBmap = $('#formulairedeselection');
                var latlng = new google.maps.LatLng(5.3096600,-4.0126600);
        
                //Objet contenant des les propri??t??s d'affichage de la carte Google MAP
                var options = {
                    center    : latlng,
                    zoom      : 12,
                    mapTypeId : google.maps.MapTypeId.ROADMAP
                };
        
                //Constructeur de la carte
                var carte = new google.maps.Map(document.getElementById("mapCanvas"), options);
                
    
                // R??cup??ration en AJAX des donn??es des lieux ?? ??pingler sur la carte Google map
                $.ajax({
                    url   : '{{route ('ajax.trouverPanneaux')}}',
                    method: 'POST',
                    data: formBBmap.serialize(),
                    dataType: 'JSON',
                    error : function(request, error) { // Info Debuggage si erreur
                        alert("Erreur sous genre - responseText: "+request.responseText);
                    },
                    success  : function(data){
                        $("#mapCanvas").fadeIn('slow');
                        var infowindow = new google.maps.InfoWindow();
                        //var marker, i;
                        var markers = data.items;
                        console.log(markers);
                        var options_markerclusterer = {
                            gridSize: 20,
                            maxZoom: 18,
                            zoomOnClick: false,
                            imagePath:  'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                        };
    
                        var markerCluster = new MarkerClusterer(carte, clusterMarkers, options_markerclusterer);
                        var clusterMarkers = [data.listeDesPoints];
    
                        google.maps.event.addListener(markerCluster, 'clusterclick');
    
    
                        for (var i = 0; i < markers.length; ++i) {
                            var marker = clusterMarkers[i];
                            

                            google.maps.event.addListener(marker, 'click', (function (marker) {
                                var content = "<div class='panel1 panel-primary'><div class='panel-heading'> <h5 class='panel-title'> Infos Panneaux:</h5></div><div class='panel-body1'> <strong>Localit??:</strong>" + markers[i].localite + "<br><strong> Code:</strong>" + markers[i].code + "<br><a class='btn btn-primary btn-xs' title='voir plus !' href='"+markers[i].url+"'><i class ='fa fa-search'></i></a></div>";
                                return function () {
                                    infoWindow.setContent(content);
                                    infoWindow.open(carte, this);
                                }
                            })(marker));
                        }
    
    /*
                        //var markerCluster = new MarkerClusterer(carte, clusterMarkers, options_markerclusterer);
    
                        //google.maps.event.addListener(markerCluster, 'clusterclick');
    
    
                        // Parcours des donn??es re??us depuis le fichier index-map-ajax.php
    
                        // R??cup??ration de LatLng, Hint et Legende de chaque lieu et cr??ation d'un marqueur
                        $.each(data.items, function(i,item){
                            if (item) {
                                if (item.lat!='' && item.lng!=''){
                                    marker = new google.maps.Marker({
                                        position : new google.maps.LatLng(item.lat, item.lng),
                                        map      : carte,
                                        icon     : pinImage,
                                        shadow   : pinShadow,
                                        title    : item.code
                                    });
                                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                        return function() {
                                            // Affichage de la l??gende de chaque lieu
                                            infowindow.setContent('<a target="_blank" href="'+item.url+'"><br/>'+item.code+' </a> ');
                                            infowindow.open(carte, marker);
                                        }
                                    })(marker, i));                              }
                                //alert('V??rification donn??es re??ues '+item.Titre_lieu+' -- '+item.Url_lieu+ ' -- '+item.LatLng_lieu);
                            }
                        });
                    }
                });
            }initialiser();
            window.onload = loadScript();
    
        });
    }) //*/

</script>
