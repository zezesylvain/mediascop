<?php
/**
 *************************** Client et Reporting ***********************************
 */
Route::group(['namespace' => 'Client','prefix' => 'reporting', 'middleware' => ['auth','logConnexion']],function() {
    Route ::get ( '/', 'DataController@home' ) -> name ( 'client.home');
    Route ::post ( 'report', 'DataController@formReport' ) -> name ( 'client.formvalider');//
    Route ::post ( 'chercherAnnonceursBySecteur', 'ReportingController@chercherAnnonceursBySecteur' ) -> name ( 'ajax.chercherAnnonceursBySecteur');//
    Route ::post ( 'getReportDatas', 'ReportingController@getReportDatas' ) -> name ( 'ajax.reporting');
    Route ::post ( 'chercher-liste-campagnes', 'ReportingController@chercherListeCampagnes' ) -> name ( 'ajax.chercherListeCampagnes');
    Route ::post ( 'chercher-donnees', 'ReportingController@chercherDonnees' ) -> name ( 'ajax.chercherDonnees');
    //Route ::post ( 'chercher-annonceur-by-secteur', 'DataController@chercherAnnonceursBySecteur' ) -> name ( 'ajax.chercherAnnonceursBySecteur');
    Route ::post ( 'chercher-supports-by-media', 'DataController@chercherSupportsByMedia' ) -> name ( 'ajax.chercherSupportsByMedia');
    //Route ::get ( 'detail/{cid}', 'ReportingController@detailSpeednews' ) -> name ( 'client.detail');
    Route ::post ('detail', 'ReportingController@detailSpeednews') -> name ( 'client.detail');
    Route ::get ('detail-campagne/{cid}', 'ClientFunctionController@detailCampagne') -> name ('reporting.detailCampagne');

    Route ::get ( 'chercher-detail/{cid}', 'ReportingController@getDetail' ) -> name ( 'client.getDetail');
    Route ::post ( 'chercher-donnees-medias', 'ReportingController@chercherDonneesMediaSupport' ) -> name ( 'ajax.chercherDonneesMediaSupport');
    Route ::post ( 'get-reportform-datas', 'ReportingController@getReportFormDatas' )->name ( 'ajax.getReportFormDatas');
    Route ::get ( 'gestion-des-abonnements', 'AdminController@gestionDesAbonnements' )->name ( 'gestionDesAbonnements');
    Route ::post ( 'forms-abonnements', 'AdminController@chercherFormAbonnement' )->name ( 'ajax.chercherFormAbonnement');
    Route ::post ( 'abonnement-secteur', 'AdminController@abonnementSecteur' )->name ( 'ajax.abonnementSecteur');
    Route ::post ( 'abonnement-societe', 'AdminController@abonnementSociete' )->name ( 'ajax.abonnementSociete');
    Route ::get ( 'lister-speednews', 'AdminController@listerSpeednews' )->name ( 'listerSpeednews');
    Route ::post ( 'filterCampagneSecteur', 'AdminController@filterCampagneSecteur' )->name ( 'filterCampagneSecteur');
    Route ::get ( 'listeSpeednews', 'AdminController@listeSpeednews' )->name ( 'ajax.listeSpeednews');
});
