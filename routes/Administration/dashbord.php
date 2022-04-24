<?php
/**
 *************************** Tableau de bord *****************************************
 */
Route::group(['namespace' => 'Administration','prefix' => 'dashbord', 'middleware' => ['auth','logConnexion']],function(){
    Route::get('campagnes/liste', 'TableauDeBordController@listeCampagnes')->name('dashbord.campagnes');
    Route::get('operations/liste', 'TableauDeBordController@listeOperations')->name('dashbord.operations');
    Route::post('campagne/filter-campagne/{action}', 'TableauDeBordController@filterCampagne')->name('dashbord.filterCampagne');
    Route::get('television/liste', 'TableauDeBordController@listePubsTelevision')->name('dashbord.television');
    Route::get('radio/liste', 'TableauDeBordController@listePubsRadio')->name('dashbord.radio');
    Route::get('presse/liste', 'TableauDeBordController@listePubsPresse')->name('dashbord.presse');
    Route::get('affichage/liste', 'TableauDeBordController@listePubsAffichage')->name('dashbord.affichage');
    Route::get('internet/liste', 'TableauDeBordController@listePubsInternet')->name('dashbord.internet');
    Route::get('mobile/liste', 'TableauDeBordController@listePubsMobile')->name('dashbord.mobile');
    Route::get('hors-media/liste', 'TableauDeBordController@listePubsHorsMedia')->name('dashbord.hors-media');
    Route::post('form-valider-pubs', 'TableauDeBordController@FormvaliderPubs')->name('dashbord.validerPubs');
    Route::post('valider-pubs', 'TableauDeBordController@validerPubs')->name('ajax.validerPubs');
    Route::get('choix-pubs-valide/{action}/{valide}', 'TableauDeBordController@choisirPubs')->name('dashbord.choisirPubs');
    Route::get('choix-campagnes-valide/{valide}', 'TableauDeBordController@choisirCampagnes')->name('dashbord.choisirCampagnes');
    Route::post('detail-medias', 'TableauDeBordController@detailMedias')->name('ajax.getMediaDetail');
    Route::post('form-valider-titre-campagne', 'CampagnesController@validerCampTitleForm')->name('dashbord.validerCampTitleForm');
    Route::get('save-table-export', 'TableauDeBordController@saveTableExport')->name('dashbord.saveTableExport');
    Route::get('export', 'TableauDeBordController@export')->name('dashbord.export');
    Route::get('export-table/{tableID}', 'TableauDeBordController@exportTable')->name('dashbord.exportTable');
    Route::post('ajouter-table-export', 'TableauDeBordController@ajouterTableExport')->name('ajax.ajouterTableExport');
    Route::post('form-export-query', 'TableauDeBordController@formExportQuery')->name('dashbord.formExportQuery');
    Route::post('get-operateur-champs', 'TableauDeBordController@getOperateurChamps')->name('ajax.getOperateurChamps');
    Route::post('get-donnees-table', 'TableauDeBordController@getDonneesDeTable')->name('ajax.getDonneesDeTable');
    Route::post('get-donnees-pubs', 'TableauDeBordController@getDonneesPubs')->name('ajax.getDonneesPubs');
    Route::get('get-csv-pubs', 'TableauDeBordController@getCsvPubs')->name('dashbord.getCSV');
});
