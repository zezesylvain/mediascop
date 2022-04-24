<?php
/**
 * ************************* Indicateurs *********************************************
 */

Route::group(['namespace' => 'Administration', 'prefix' => 'indicateur', 'middleware' => ['auth','logConnexion']],function (){
    Route::get('new', 'AdminController@newIndicateur')->name('indicateur.new');
    Route::get('attribuer', 'AdminController@attribuerIndicateurs')->name('indicateur.attribuer');
    Route::post('listeIndicateur', 'AdminController@listeIndicateur')->name('ajax.listeIndicateur');
    Route::post('attribuerIndicateur', 'AdminController@attribuerIndicateur')->name('ajax.attribuerIndicateur');

    Route::get('historique-de-connexion', 'UserConnexionController@historiqueDeConnexion')->name('historiqueDeConnexion');
    Route::get('utilisateurs-connectes', 'UserConnexionController@UserConnected')->name('UserConnected');
    Route::post('getUsersByProfil', 'UserConnexionController@getUsersByProfil')->name('ajax.getUsersByProfil');
    Route::post('get-historique-de-connexion', 'UserConnexionController@getHistoriqueDeConnexion')->name('getHistoriqueDeConnexion');
});
