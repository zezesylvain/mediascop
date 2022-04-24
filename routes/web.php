<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('home');
});  */

/**  */

include_once("default/default.php");

Route::group(['namespace' => 'Template','prefix' => 'template', 'middleware' => ['auth']],function(){
    Route::get('pdf-viewer', 'TemplateAdminController@showPdfViewer')->name('showPdfViewer');
    Route::get('x-editable', 'TemplateAdminController@showxEditable')->name('showxEditable');
    Route::get('file-manager', 'TemplateAdminController@showFileManager')->name('fileManager');

});

Auth::routes();


Route::get('/z', 'TestController@index')->name('testz');

Route::get('/welcome', function (){
    return view ("welcome");
});

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

include_once("Administration/parametre.php");
include_once("Administration/saisie.php");
include_once("Administration/dashbord.php");
include_once("Administration/reporting.php");
include_once("Administration/valider.php");
include_once("Administration/indicateur.php");
include_once("Messagerie/messagerie.php");
include_once("Client/reporting.php");

/**
 * Ajout BillBord Gninatin
 */
include_once("Billboardmap/bbordmap.php");

/**
 *************************** Client et Report ***********************************
 */
 /*
Route::group(['namespace' => 'Client','prefix' => 'report', 'middleware' => ['auth','logConnexion']],function() {
    Route ::get ( '/', 'ReportController@home' ) -> name ( 'client.home');
    Route ::post ( 'report', 'ReportController@formReport' ) -> name ( 'client.formvalider');
    Route ::post ( 'getReportDatas', 'ReportController@getReportDatas' ) -> name ( 'ajax.reporting');
    Route ::post ( 'chercher-liste-campagnes', 'ReportController@chercherListeCampagnes' ) -> name ( 'ajax.chercherListeCampagnes');
    Route ::post ( 'chercher-donnees', 'ReportController@chercherDonnees' ) -> name ( 'ajax.chercherDonnees');
    Route ::post ( 'chercher-annonceur-by-secteur', 'ReportController@chercherAnnonceursBySecteur' ) -> name ( 'ajax.chercherAnnonceursBySecteur');
    Route ::post ( 'chercher-supports-by-media', 'ReportController@chercherSupportsByMedia' ) -> name ( 'ajax.chercherSupportsByMedia');
    //Route ::get ( 'detail/{cid}', 'ReportController@detailSpeednews' ) -> name ( 'client.detail');
    Route ::post ('detail', 'ReportController@detailSpeednews') -> name ( 'client.detail');
    Route ::get ('detail-campagne/{cid}', 'ClientFunctionController@detailCampagne') -> name ('reporting.detailCampagne');

    Route ::get ( 'chercher-detail/{cid}', 'ReportController@getDetail' ) -> name ( 'client.getDetail');
    Route ::post ( 'chercher-donnees-medias', 'ReportController@chercherDonneesMediaSupport' ) -> name ( 'ajax.chercherDonneesMediaSupport');
    Route ::post ( 'get-reportform-datas', 'ReportController@getReportFormDatas' )->name ( 'ajax.getReportFormDatas');
    Route ::get ( 'gestion-des-abonnements', 'AdminController@gestionDesAbonnements' )->name ( 'gestionDesAbonnements');
    Route ::post ( 'forms-abonnements', 'AdminController@chercherFormAbonnement' )->name ( 'ajax.chercherFormAbonnement');
    Route ::post ( 'abonnement-secteur', 'AdminController@abonnementSecteur' )->name ( 'ajax.abonnementSecteur');
    Route ::post ( 'abonnement-societe', 'AdminController@abonnementSociete' )->name ( 'ajax.abonnementSociete');
    Route ::get ( 'lister-speednews', 'AdminController@listerSpeednews' )->name ( 'listerSpeednews');
    Route ::post ( 'filterCampagneSecteur', 'AdminController@filterCampagneSecteur' )->name ( 'filterCampagneSecteur');
    Route ::get ( 'listeSpeednews', 'AdminController@listeSpeednews' )->name ( 'ajax.listeSpeednews');
});
//*/
Route::get('/geomap', function(){
    $config = array();
    $config['center'] = 'auto';
    $config['oncenterchanged'] = 'if (!centreGot) {
            var mapCentre = map.getCenter();
            marker_0.setOptions({
                position: new google.maps.LatLng(mapCentre.lat(), mapCentre.lng())
            });
        }
        centreGot = true;';

    app('map')->initialize($config);

    // set up the marker ready for positioning
    // once we know the users location
    $marker = array();
    app('map')->add_marker($marker);

    $map = app('map')->create_map();
    echo "<html><head><script type=\"text/javascript\">var centreGot = false;</script>".$map['js']."</head><body>".$map['html']."</body></html>";
});

Route::get('/maps/{map}', 'core\GoogleMapsController@index')->name('maps.index');
Route::get('/frontend/{map}', 'core\GoogleMapsController@index')->name('frontend.maps.show');
Route::get('/updateOperation', 'Administration\CampagnesController@updateOperation')->name('updateOperationZDS');

