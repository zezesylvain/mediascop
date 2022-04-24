<?php
/**
 *************************** Saisie et Campagne
 */
Route::group(['namespace' => 'Administration','prefix' => 'saisie', 'middleware' => ['auth','logConnexion']],function(){
    Route::get('operations/new', 'CampagnesController@newOperations')->name('saisie.operation');
    Route::get('campagnes/new', 'CampagnesController@newCampagnes')->name('saisie.campagne');
    Route::get('campagnes/{cptitleID}/new', 'CampagnesController@creerCampagne')->name('saisie.creerCampagne');
    Route::post('campagne/filter-campagne/{action}', 'CampagnesController@filterCampagne')->name('saisie.filterCampagne');
    Route::post('listSelectAnnonceur', 'CampagnesController@listSelectAnnonceur')->name('ajax.listSelectAnnonceur');
    Route::post('listSelectTypeservice', 'CampagnesController@getTypeserviceBySecteur')->name('ajax.listSelectTypeservice');
    Route::post('listSelectOperation', 'CampagnesController@listSelectOperation')->name('ajax.listSelectOperation');
    Route::get('diminuer/{date}/{action}', 'CampagnesController@diminuer')->name('saisie.diminuer');
    Route::post('formCampagne', 'CampagnesController@formCampagne')->name('ajax.campagne');
    Route::post('store-cptitle', 'CampagnesController@storeCampTitle')->name('saisie.storeCampTitle');
    Route::post('ajouter-sponsors', 'CampagnesController@ajouterSponsors')->name('ajax.ajouterSponsors');
    Route::post('input-campagne', 'CampagnesController@formInputCampagne')->name('ajax.formInputCampagne');
    Route::post('campagnes/store', 'CampagnesController@storeCampagne')->name('saisie.storeCampagne');
    Route::post('upload-files', 'CampagnesController@uploadFiles')->name('saisie.uploadFiles');
    Route::post('bdselect', 'CampagnesController@bdselect')->name('ajax.bdselect');
    Route::post('bdupdate', 'CampagnesController@bdupdate')->name('ajax.bdupdate');
    Route::post('putMediaInBd', 'CampagnesController@putMediaInBd')->name('ajax.putMediaInBd');
    Route::post('delcampfile', 'CampagnesController@delcampfile')->name('ajax.delcampfile');

    Route::get('television/new', 'SaisieController@newTelevision')->name('saisie.television');
    Route::get('radio/new', 'SaisieController@newRadio')->name('saisie.radio');
    Route::get('affichage/new', 'SaisieController@newAffichage')->name('saisie.affichage');
    Route::get('presse/new', 'SaisieController@newPresse')->name('saisie.presse');
    Route::get('internet/new', 'SaisieController@newInternet')->name('saisie.internet');
    Route::get('mobile/new', 'SaisieController@newMobile')->name('saisie.mobile');
    Route::get('hors-media/new', 'SaisieController@newHorsMedia')->name('saisie.hors-media');
    Route::post('chercher-campagnes', 'SaisieController@chercherCampagne')->name('saisie.chercherCampagne');

    Route::post('chercher-donnees', 'SaisieController@getDataInSession')->name('ajax.getDataInSession');
    Route::post('addInputMedia', 'SaisieController@addInputMedia')->name('ajax.addInputMedia');
    Route::post('changeDate', 'SaisieController@changeDate')->name('ajax.changeDate');
    Route::post('store-pub', 'SaisieController@storePub')->name('saisie.storePub');
    Route::get('form-saisie/{campagne}/{media}', 'SaisieController@frameFormSaisie')->name('saisie.frameFormSaisie');
    Route::post('form-saisie-frame', 'SaisieController@getFormSaisieFrame')->name('ajax.getFormSaisieFrame');
    Route::post('filtrer-annonceurs', 'SaisieController@filterAnnonceurs')->name('ajax.filterAnnonceurs');
    Route::get('importer-donnees', 'SaisieController@importation')->name('saisie.importation');
    Route::post('store-donnees-importees', 'SaisieController@storeDataImporter')->name('saisie.storeDonneesImportees');
    Route::post('store-operations', 'CampagnesController@storeOperations')->name('saisie.storeOperations');
    Route::get('tarifs/new', 'SaisieController@newTarif')->name('saisie.tarif');
    Route::post('chercher-saisie', 'SaisieController@getUserSaisie')->name('ajax.getUserSaisie');
    Route::post('form-tarif', 'SaisieController@getFormTarif')->name('ajax.getFormTarif');
    Route::post('coupure-tarif', 'SaisieController@coupureTarif')->name('ajax.coupureTarif');
    Route::post('insert-tarif', 'SaisieController@insertTarif')->name('saisie.insertTarif');
    Route::get('tarif-form/{media}', 'SaisieController@formTarifFrame')->name('saisie.formTarifFrame');
    Route::get('campagnes/liste', 'SaisieController@listeCampagnesNonValider')->name('saisie.listeCampagnesNonValider');
    Route::get('saisies/liste', 'SaisieController@listeSaisiesNonValider')->name('saisie.listeSaisiesNonValider');
    Route::post('saisies/update', 'SaisieController@updateSaisie')->name('saisie.updateSaisie');
    Route::post('saisies/form-update', 'SaisieController@formUpdateSaisie')->name('ajax.formUpdateSaisie');
    Route::get('saisies/form-hors-media', 'SaisieController@frameFormSaisieHorsMedia')->name('saisie.frameFormSaisieHorsMedia');
    Route::post('choisirCoordHorsMedia', 'SaisieController@choisirCoordHorsMedia')->name('ajax.choisirCoordHorsMedia');
    Route::get('type-de-communication/new', 'SaisieController@newTypeDeComm')->name('saisie.newTypeDeComm');
    Route::get('type-de-service/new', 'SaisieController@newTypeDeService')->name('saisie.newTypeDeService');
    Route::post('ajouter-point-hors-media', 'SaisieController@ajouterPointHorsMedia')->name('ajax.ajouterPointHorsMedia');
    Route::post('delete-point-hors-media', 'SaisieController@deletePointsHorsMedia')->name('ajax.deletePointsHorsMedia');
});
