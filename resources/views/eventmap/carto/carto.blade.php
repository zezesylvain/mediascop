
<script>
    var markerCluster = null;
    var gm_map;
    var markerArray = [];
    var infoWindow = new google.maps.InfoWindow();
    
    
    function addCSSRule(sheet, selector, rules, index) {
        if ("insertRule" in sheet) {
            sheet.insertRule(selector + "{" + rules + "}", index);
        } else if ("addRule" in sheet) {
            sheet.addRule(selector, rules, index);
        }
    }
    var markers = [<?php echo $Bigdata; ?>];
    
    function initMap() {
        var mapOptions = {
            zoom: 8,
            center: {lat: 5.3096600,lng: -4.0126600},
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            fullscreenControl: true
        };
        
    
        map = new google.maps.Map(document.getElementById('mapCanvas'),
            mapOptions);
        
        
        var options_markerclusterer = {
            gridSize: 20,
            maxZoom: 18,
            zoomOnClick: false,
            imagePath:  'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
    
        };
        
        
        
        markerCluster = new MarkerClusterer(map, clusterMarkers, options_markerclusterer);
        
        google.maps.event.addListener(markerCluster, 'clusterclick');
        
        
        for (var i = 0; i < markers.length; ++i) {
            var marker = clusterMarkers[i];
            
            addCSSRule(document.styleSheets[0],
                'img[src="' + 'http://i.stack.imgur.com/KOh5X.png#' + i + '"]',
                'background-color:#'+markers[i].couleur);
            
            google.maps.event.addListener(marker, 'click', (function (marker) {
                var content = "<div class='panel panel-primary'><div class='panel-heading'> <h5 class='panel-title'> Infos Panneaux</h5></div>" +
                    "<div class='panel-body'>" +
                        "<div class='row'>" +
                            "<div class='col-sm-4'>" +
                                "<img src='"+markers[i].docampagne+"' class='img-thumbnail' style='width: 100%;height: auto;'> " +
                            "</div>" +
                            "<div class='col-sm-8'>" +
                                "<strong>Campagne:</strong>" + markers[i].titre + "<br>" +
                                "<strong>Annonceur:</strong>" + markers[i].annonceur + "<br>" +
                                "<strong>Localité:</strong>" + markers[i].localite + "<br>" +
                                "<strong> Code:</strong>" + markers[i].code + "<br>" +
                                "<strong> Regie:</strong>" + markers[i].regie + "<br>" +
                                "<strong> Nature:</strong>" + markers[i].nature + "<br>" +
                                "<strong> Format:</strong>" + markers[i].format + "<br>" +
                            "</div>" +
                            "</div>" +
                        "</div>" +
                    "<div class='panel-footer'><a class='btn btn-primary btn-xs' title='voir plus !' href='"+markers[i].url+"'><i class ='fa fa-search'></i></a></div>";
                
                return function () {
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);
                }
            })(marker));
        }
        
        
    }
    google.maps.event.addDomListener(window, 'load', initMap);
    
    
    var clusterMarkers = [<?php echo $listeDesPoints; ?>];
    
</script>
