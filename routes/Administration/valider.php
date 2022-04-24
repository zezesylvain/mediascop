<?php
/**
 *************************** Valider Donnée *******************************************
 */
Route::group(['namespace' => 'Administration','prefix' => 'valider', 'middleware' => ['auth','logConnexion']],function() {
    Route ::get ('campagne/liste', 'ValidationDonneesController@getListeCampagneTitleNonValider')-> name ( 'valider.getListeCampagneTitleNonValider');
    Route ::get ('campagne/liste', 'ValidationDonneesController@getListeCampagneTitleNonValider')-> name ( 'valider.campagnes');
    Route ::get ('liste-pubs/{mediaID}', 'ValidationDonneesController@getListePubNonValider')-> name ( 'valider.getListePubNonValider');
    Route::get('operations', 'ValidationDonneesController@listeOperationNonValider')->name('valider.operation');
    Route::get('television/liste', 'ValidationDonneesController@listeTelevisionNonValider')->name('valider.television');
    Route::get('radio/liste', 'ValidationDonneesController@listeRadioNonValider')->name('valider.radio');
    Route::get('presse/liste', 'ValidationDonneesController@listePresseNonValider')->name('valider.presse');
    Route::get('affichage/liste', 'ValidationDonneesController@listeAffichageNonValider')->name('valider.affichage');
    Route::get('internet/liste', 'ValidationDonneesController@listeInternetNonValider')->name('valider.internet');
    Route::get('mobile/liste', 'ValidationDonneesController@listeMobileNonValider')->name('valider.mobile');
    Route::get('hors-media/liste', 'ValidationDonneesController@listeHorsMediaNonValider')->name('valider.hors-media');
    Route::get('signaler-erreur-pub', 'ValidationDonneesController@signalerErreurPub')->name('ajax.signalerErreurPub');
    Route::get('signaler-erreur-pub', 'ValidationDonneesController@signalerErreurPub')->name('ajax.signalerErreurPub');
    Route::get('valider-operation', 'ValidationDonneesController@validerOperations')->name('ajax.validerOperation');
    Route::post('valider-operation-masse', 'ValidationDonneesController@validerOperationsMasse')->name('store.operation');
});
