<?php

Route::group(['namespace' => 'Eventmap', 'prefix' => 'eventmap', 'middleware' => ['auth','logConnexion']], function ()
{
    Route::get('/', 'EventMapController@accueil')->name('event.home');

    Route::post('trouver-evenement', 'EventMapController@trouverEvenements' )->name('trouverEvenements');

    Route::get('add-lieu-localite','EventMapController@addLieuLocalite')->name('addLieuLocalite');
    Route::post('store-lieu-localite','EventMapController@storeLieuLocalites')->name('storeLieuLocalites');
    Route::post('get-lieu-localite','EventMapController@getLieuLocalite')->name('ajax.getLieuLocalite');

});