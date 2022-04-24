<?php

Route::group(['namespace' => 'Billboardmap','prefix' => 'bbordmap', 'middleware' => ['auth','logConnexion']],function() {
    Route ::get ( '/v2', 'BillBoardMapController@home' ) -> name ( 'bbmap.home');
    Route ::get ( '/', 'BillBoardMapController@accueil' ) -> name ( 'bbmap.accueil');
    Route ::get ( 'gestion-localites', 'BillBoardMapController@gestionLocalite' ) -> name ( 'bbmap.localite');
    Route ::get ( 'update-localites/{table}/{id}', 'BillBoardMapController@updateLocalite' ) -> name ( 'bbmap.updateLocalite');
    Route ::get ( 'panneaux/new', 'BillBoardMapController@newPanneau' ) -> name ( 'bbmap.panneau');
    Route ::post ( 'panneaux/store', 'BillBoardMapController@storePanneaux' ) -> name ( 'storePanneaux');
    Route ::post ( 'make-code-panneaux', 'BillBoardMapController@makeCodePanneau' ) -> name ( 'ajax.makeCodePanneau');
    Route ::post ( 'get-commune', 'BillBoardMapController@getCommune' ) -> name ( 'ajax.getCommune');
    Route ::get ( 'update/{table}/{id}', 'BillBoardMapController@updatePanneau' ) -> name ( 'bbmap.updatePanneau');
    Route ::post ( 'liste-des-panneaux', 'BillBoardMapController@getListeDesPanneaux' ) -> name ( 'ajax.getListeDesPanneaux');
    Route ::post ( 'billbord-support', 'BillBoardMapController@bbordmapSupport' ) -> name ( 'ajax.bbordmapSupport');
    Route ::post ( 'billbord-comm', 'BillBoardMapController@bbordmapComm' ) -> name ( 'ajax.bbordmapComm');
    Route ::post ( 'billbord-localisation', 'BillBoardMapController@bbordmapLocalite' ) -> name ( 'ajax.bbordmapLocalite');
    Route ::post ( 'trouver-panneaux', 'BillBoardMapController@trouverPanneaux' ) -> name ( 'ajax.trouverPanneaux');
});

?>
