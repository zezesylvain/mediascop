@php
    $latDefault =  isset($localite['latitude']) ? $localite['latitude'] : 5.356149786699246;
    $lngDefault = isset($localite['longitude']) ? $localite['longitude'] :-4.007166835937483;

    $Bigdata = $listeDesMap['Bigdata'];
    $listeDesPoints = $listeDesMap['listeDesPoints'];
@endphp

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false&key=AIzaSyD6cjLW8vcDWHitlFzWnWKb0QoFaLcu8xI
"></script>

<script>
    let gm_map;
    let markerArray = [];
    let infoWindow = new google.maps.InfoWindow();
    function addCSSRule(sheet, selector, rules, index) {
        if ("insertRule" in sheet) {
            sheet.insertRule(selector + "{" + rules + "}", index);
        } else if ("addRule" in sheet) {
            sheet.addRule(selector, rules, index);
        }
    }
    let markers = [{!! $Bigdata !!}];
    function initMap() {
        let mapOptions = {
            zoom: 6.5,
            center: new google.maps.LatLng({!! $latDefault !!},{!! $lngDefault !!}),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            fullscreenControl: true
        };
         const map = new google.maps.Map(document.getElementById('google-maps'),
            mapOptions);

         const options_markerclusterer = {
            gridSize: 20,
            maxZoom: 8,
            zoomOnClick: false,
            imagePath:  'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
        };


        const markerCluster = new MarkerClusterer(map, clusterMarkers, options_markerclusterer);

        google.maps.event.addListener(markerCluster, 'clusterclick');


        for (let i = 0; i < markers.length; ++i) {
            const marker = clusterMarkers[i];

            if (markers[i].logo !== ""){
                addCSSRule(document.styleSheets[0],
                    'img[src="' + 'https://i.stack.imgur.com/KOh5X.png#' + i + '"]',
                    'background:url('+markers[i].logo +') no-repeat 4px 4px');
                addCSSRule(document.styleSheets[0],
                    'img[src="' + 'https://i.stack.imgur.com/KOh5X.png#' + i + '"]',
                    'background-size:35px 35px');
            }else {
                addCSSRule(document.styleSheets[0],
                    'img[src="' + 'https://i.stack.imgur.com/KOh5X.png#' + i + '"]',
                    'background-color:#'+markers[i].couleur);
            }


            marker.addListener('click', () => {
                const content = "<div class='panel panel-primary' style='text-align: center;'><div class='panel-heading'> <h5 class='panel-title'> Infos Campagne:</h5></div><div class='panel-body'><strong>Campagne(s):</strong>" + markers[i].campagne + "<br><strong>Localité:</strong>" + markers[i].localite + "<br><strong> Annonceur:</strong>" + markers[i].annonceur + "<br><strong> Nombre:</strong><span style='font-weight: 700;'>" + markers[i].nombrePanneaux + "</span></br><strong> Investissement:</strong>" + markers[i].investissement + "</br></div></div>";

                infoWindow.setContent(content);
                infoWindow.open(map, marker);
            });
        }
    }
    google.maps.event.addDomListener(window, 'load', initMap);
    const clusterMarkers = [{!! $listeDesPoints !!}];
</script>

<style>
    #google-maps {
        width: 100%;
        height: 400px;
        background-color: grey;
    }
</style>
<div id="google-maps"></div>
