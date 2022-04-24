<?php

namespace App\Http\Controllers\core;

use GeneaLabs\LaravelMaps\Facades\Map;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class GoogleMapsController extends Controller
{

    public $latDefault;
    public $lngDefault;
    public $center = "";
    public $zoom ;
    public $draggableCursor ;

    public function __construct (float $lat = 5.356149786699246, float $lng = -4.007166835937483,string $zoom = "auto",string $draggableCursor = "default")
    {
       $this->latDefault = $lat;
       $this->lngDefault = $lng;
       $this->center = "{$this->latDefault}, {$this->lngDefault}";
       $this->zoom = $zoom;
       $this->draggableCursor = $draggableCursor;
    }

    public function index($map)
    {
        app()
            ->make('\App\Http\Controllers\core\GoogleMapsController')
            ->callAction($map, $parameters = array());

        $map = app ('map')->create_map();
        return view('bbmap.view')->with('map', $map);
    }

    public function markers_single()
    {
        $config = array();
        $config['center'] = $this->center;
        $config['draggableCursor'] = 'default';
        Map::initialize($config);

        $marker = array();
        $marker['position'] = '37.4419, -122.1419';
        Map::add_marker($marker);
    }

    public function markers_multiple()
    {
        $config = array();
        $config['center'] = '37.4419, -122.1419';
        $config['zoom'] = 'auto';
        $config['draggableCursor'] = 'default';
        Map::initialize($config);

        $marker = array();
        $marker['position'] = '37.429, -122.1519';
        $marker['infowindow_content'] = '1 - Hello World!';
        $marker['icon'] = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|9999FF|000000';
        Map::add_marker($marker);

        $marker = array();
        $marker['position'] = '37.409, -122.1319';
        $marker['draggable'] = TRUE;
        $marker['animation'] = 'DROP';
        Map::add_marker($marker);

        $marker = array();
        $marker['position'] = '37.449, -122.1419';
        $marker['onclick'] = 'alert("You just clicked me!!")';
        Map::add_marker($marker);
    }

    public function polyline()
    {
        $config = array();
        $config['center'] = '37.4419, -122.1419';
        $config['zoom'] = 'auto';
        Map::initialize($config);

        $polyline = array();
        $polyline['points'] = array('37.429, -122.1319',
            '37.429, -122.1419',
            '37.4419, -122.1219');
        Map::add_polyline($polyline);
    }

    public function polygon()
    {
        $config = array();
        $config['center'] = '37.4419, -122.1419';
        $config['zoom'] = 'auto';
        Map::initialize($config);

        $polygon = array();
        $polygon['points'] = array('37.425, -122.1321',
            '37.4422, -122.1622',
            '37.4412, -122.1322',
            '37.425, -122.1021');
        $polygon['strokeColor'] = '#000099';
        $polygon['fillColor'] = '#000099';
        Map::add_polygon($polygon);
    }

    public function drawing()
    {
        $config = array();
        $config['drawing'] = true;
        $config['drawingDefaultMode'] = 'circle';
        $config['drawingModes'] = array('circle','rectangle','polygon');
        Map::initialize($config);
    }

    public function directions()
    {
        $config = array();
        $config['center'] = '37.4419, -122.1419';
        $config['zoom'] = 'auto';
        $config['directions'] = TRUE;
        $config['directionsStart'] = 'empire state building';
        $config['directionsEnd'] = 'statue of liberty';
        $config['directionsDivID'] = 'directionsDiv';
        Map::initialize($config);
    }

    public function streetview()
    {
        $config = array();
        $config['center'] = '37.4419, -122.1419';
        $config['map_type'] = 'STREET';
        $config['streetViewPovHeading'] = 90;
        Map::initialize($config);
    }

    public function clustering(array $datas)
    {
        $config = array();
        $config['center'] = $this->center;
        $config['zoom'] = $this->zoom;
        $config['cluster'] = TRUE;
        $config['clusterStyles'] = array(
            array(
                "url"=>"https://raw.githubusercontent.com/googlemaps/js-marker-clusterer/gh-pages/images/m1.png",
                "width"=>"53",
                "height"=>"53"
            ));
        app ('map')->initialize($config);

        if (count ($datas)):
            foreach ($datas as $data):
                $marker = array ();
                $marker['position'] = $data['position'];
                $marker['title'] = $data['title'];
                $infowindow = $data['infowindows'];
                $infowindowContent = "<div class=\"panel panel-primary\">";
                $infowindowContent .= "<div class=\"panel-body\">";
                $infowindowContent .= "<div class=\"row\">";
                $infowindowContent .= "<div class=\"col-sm-4\">";
                $infowindowContent .= "<img src=\"".$infowindow['docampagne']."\" class=\"img-thumbnail\" style=\"width:100%;height:auto\">";
                $infowindowContent .= "</div>";
                $infowindowContent .= "<div class=\"col-sm-8\">";
                $infowindowContent .= "<p>";
                $infowindowContent .= "<strong>Campagne:</strong> ".$infowindow['titre']." <br>";
                $infowindowContent .= "<strong>Annonceur:</strong> ".$infowindow['annonceur']." <br>";
                $infowindowContent .= "<strong>Localité:</strong> ".$infowindow['localite']." <br>";
                $infowindowContent .= "<strong>Code:</strong> ".$infowindow['code']." <br>";
                $infowindowContent .= "<strong>Regie:</strong> ".$infowindow['regie']." <br>";
                $infowindowContent .= "<strong>Nature:</strong> ".$infowindow['nature']."<br> ";
                $infowindowContent .= "<strong>Format:</strong> ".$infowindow['format']."<br> ";
                $infowindowContent .= "</p>";
                $infowindowContent .="</div>";
                $infowindowContent .="</div>";
                $infowindowContent .="<div class=\"panel-footer\">";
                $infowindowContent .= "<a href=\"#info-pills\" class='btn btn-primary btn-xs' title='Plus de detail!' data-toggle=\"tab\"><i class='fa fa-search'></i></a>";
                $infowindowContent .="</div>";
                $infowindowContent .="</div>";
                $marker['infowindow_content'] = $infowindowContent;

                app ('map')->add_marker($marker);
            endforeach;
        endif;
        $map = app ('map')->create_map();
        return $map;
    }

    public function kml_layer()
    {
        $config = array();
        $config['zoom'] = 'auto';
        $config['kmlLayerURL'] = 'https://www.google.com/maps/d/kml?mid=zQsfa8t0PJbc.kXZmQVidOFfE';
        Map::initialize($config);
    }

}
