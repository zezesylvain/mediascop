<?php

Route::group(['namespace' => 'Administration', 'prefix' => 'reporting', 'middleware' => ['auth','logConnexion']],function (){
    Route::get('rapport', 'RapportController@showReport')->name('reporting.rapport');
    Route::get('get-file/{rapport}', 'RapportController@getFile')->name('reporting.getFile');
    Route::get('nouveau-rapport', 'RapportController@nouveauRapport')->name('reporting.nouveauRapport');
    Route::post('valider-rapport', 'RapportController@validerRapport')->name('rapport.validerRapport');
    Route::post('form-rapport', 'RapportController@getFormRapport')->name('ajax.getFormRapport');
    Route::post('supprimer-file-rapport', 'RapportController@delFormRapport')->name('ajax.delfile');
    Route::get('download-file/{cid}', 'RapportController@downloadFile')->name('reporting.downloadFile');
});

