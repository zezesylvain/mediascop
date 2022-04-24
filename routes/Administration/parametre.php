<?php

Route::group(['namespace' => 'Administration','prefix' => 'parametre', 'middleware' => ['auth','logConnexion']],function(){
    Route::get('new/{table}', 'ParametreController@create')->name('parametre.nouveau');
    Route::get('medias/new', 'ParametreController@newMedias')->name('parametre.medias');
    Route::get('formats/new', 'ParametreController@newFormats')->name('parametre.formats');
    Route::get('cibles/new', 'ParametreController@newCibles')->name('parametre.cibles');
    Route::get('natures/new', 'ParametreController@newNatures')->name('parametre.natures');
    Route::get('supports/new', 'ParametreController@newSupports')->name('parametre.supports');
    Route::get('secteurs/new', 'ParametreController@newSecteur')->name('parametre.secteurs');
    Route::get('couvertures/new', 'ParametreController@newCouvertures')->name('parametre.couvertures');
    Route::get('annonceurs/new', 'ParametreController@newAnnonceurs')->name('parametre.annonceurs');
    Route::post('annonceurs/store', 'ParametreController@storeAnnonceurs')->name('parametre.storeAnnonceurs');
    Route::get('dimension-internet/new', 'ParametreController@newDimensionInternet')->name('parametre.dimensionInternet');
    Route::get('dimension-affichage/new', 'ParametreController@newDimensionAffichage')->name('parametre.dimensionAffichage');
    Route::get('profil-mobile/new', 'ParametreController@newProfilMobile')->name('parametre.profilMobile');
    Route::get('fusion', 'ParametreController@fusion')->name('parametre.fusion');
    Route::get('annonceur/update/{id}', 'ParametreController@updateAnnonceur')->name('parametre.updateAnnonceur');
    Route::post('color-update', 'ParametreController@updateColor')->name('parametre.updateColor');
    Route::post('logo-changer', 'ParametreController@changerLogo')->name('ajax.changerLogo');
    Route::get('tables-de-fusion', 'ParametreController@gestionTablesFusion')->name('parametre.gestionTablesFusion');
    Route::get('dependance-de-fusion', 'ParametreController@dependanceTablesFusion')->name('parametre.dependanceTablesFusion');
    Route::get('fusion-table/{tableID}', 'ParametreController@fusionTable')->name('parametre.fusionTable');
    Route::post('store-table-fusion', 'ParametreController@ajouterTableFusion')->name('ajax.ajouterTableFusion');
    Route::post('store-table-fusion-dependance', 'ParametreController@ajouterTableFusionDependant')->name('ajax.ajouterTableFusionDependant');
    Route::get('faire-fusion-table', 'ParametreController@faireFusion')->name('parametre.faireFusion');
    Route::get('fusion-table-donnees/{tableID}', 'ParametreController@getDonneesFusion')->name('parametre.getDonneesFusion');
    Route::post('ajouter-id-fusion', 'ParametreController@ajouterIdFusion')->name('ajax.ajouterIdFusion');
    Route::post('traiter-form-fusion', 'ParametreController@traiterFormFusion')->name('ajax.traiterFormFusion');
});
