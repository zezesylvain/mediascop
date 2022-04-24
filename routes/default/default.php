<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 21/11/2017
 * Time: 00:03
 */

Route::group(['namespace' => 'core','prefix' => '', 'middleware' => ['auth','logConnexion']],function() {
    Route::get('gestion/{databaseTable}', 'CoreController@gestionTable')->name('gestionTable');
    Route::post('storeData', 'ModuleController@traiterForm')->name('traiterDefaultForm');
    Route::post('storeDataMenu', 'ModuleController@traiterFormMenu')->name('traiterFormMenu');
    Route::get('gestion-utilisateurs', 'UtilisateursController@create')->name('CreerUtilisateur');
    Route::post('storeUser', 'UtilisateursController@storeUser')->name('storeUser');
    Route::post('makeFormRang', 'FunctionController@makeFormRang')->name('makeFormRang');
    Route::get('download/{fichier}', 'ModuleController@getFile')->name('download');
    Route::get('field-default', 'TmaController@fieldDefault')->name('fieldDefault');
    Route::get('field-default-param', 'TmaController@fieldDefaultParam')->name('fieldDefaultParam');
    Route::post('getChampsTable', 'TmaController@getChampsTable')->name('ajax.getChampsTable');
    Route::post('traitementChampsParam', 'TmaController@traitementChampsParam')->name('ajax.traitementChampsParam');
    Route::post('storeFieldDefault', 'TmaController@storeFieldDefault')->name('storeFieldDefault');

   // Route::get('update/{table}/{id}', 'ModuleController@update')->name('updateData');
    Route::get('update/{table}/{id}', 'CoreController@update')->name('updateData');
    
    //Route::get('makeProfilRole/{id}', 'TmaController@makeProfilRole')->name('makeProfilRole');
    Route::get('attribuer-roles/{id}', 'TmaController@makeProfilRole')->name('makeProfilRole');
    Route::get('gestionDesRoles', 'TmaController@gestionDesRoles')->name('gestionDesRoles');
    Route::get('gestionDesRoutes', 'TmaController@gestionDesRoutes')->name('gestionDesRoutes');
    Route::post('makeFormRole', 'TmaController@makeFormRole')->name('ajax.makeFormRole');
    Route::post('traiterFormRole', 'TmaController@traiterFormRole')->name('traiterFormRole');
    Route::post('traiterFormProfil', 'TmaController@traiterFormProfil')->name('traiterFormProfil');
    Route::post('estRole', 'TmaController@estRole')->name('ajax.estRole');
    Route::post('storeRoleItem', 'TmaController@storeRoleItem')->name('ajax.storeRoleItem');
    Route::post('profilRole', 'TmaController@profilRole')->name('ajax.profilRole');
    Route::get('monCompte', 'UtilisateursController@monCompte')->name('user.monCompte');
    Route::post('renitMyPassword', 'UtilisateursController@renitMyPassword')->name('user.renitMyPassword');
    Route::get('deleteData/{table}/{id}', 'ModuleController@deleteData')->name('deleteData');
    Route::get('gestionDuMenus', 'TmaController@gestionDuMenus')->name('gestionDuMenus');
    Route::post('ordonnerMenu', 'TmaController@ordonnerMenu')->name('ajax.ordonnermenu');
    Route::post('storeMenu', 'TmaController@storeMenu')->name('storeMenu');
    Route::post('storeMenuItem', 'TmaController@storeMenuItem')->name('ajax.storeMenuItem');
    Route::post('delMenus', 'TmaController@delMenus')->name('ajax.delMenus');
    Route::post('getFormMenu', 'TmaController@getFormMenu')->name('ajax.getFormMenu');
    Route::post('modifierMenu', 'TmaController@modifierMenu')->name('ajax.modifierMenu');
    Route::get('modifierMenu/{menu}', 'TmaController@modifierMenu')->name('modifierMenu');
    Route::get('gestionGroupeDemenu', 'TmaController@gestionGroupeDeMenu')->name('gestionGroupeDeMenu');
    Route::post('storeGrpMenu', 'TmaController@storeGrpMenu')->name('storeGrpMenu');
    Route::post('delGrpMenus', 'TmaController@delGrpMenus')->name('ajax.delGrpMenus');
    Route::post('ordonnerGrpMenu', 'TmaController@ordonnerGrpMenu')->name('ajax.ordonnergrpmenu');
    Route::get('modifierGrpMenu/{grpMenu}', 'TmaController@modifierGrpMenu')->name('modifierGrpMenu');
    Route::get('backups/{token}', 'BackupDB@cron')->name('backupsDatabase');
    Route::post('bdcForm', 'BdcController@bdcForm')->name('ajax.bdcForm');
    Route::post('storeDoc', 'BdcController@storeDoc')->name('storeDoc');
    Route::get('consulter-documentation', 'BdcController@consulterDoc')->name('consulterDoc');
    Route::get('groupe-menus-routes', 'TmaController@groupeMenusRoutes')->name('groupeMenusRoutes');
    Route::post('ajouterRoutesDsGroupeMenus', 'TmaController@ajouterRoutesDsGroupeMenus')->name('ajax.ajouterRoutesDsGroupeMenus');
    Route::post('deleteGrpMenuRoute', 'TmaController@deleteGrpMenuRoute')->name('ajax.deleteGrpMenuRoute');
    Route::post('getRoutesDeGroupeMenus', 'TmaController@getRoutesDeGroupeMenus')->name('ajax.getRoutesDeGroupeMenus');
    Route::get('ajx', 'TmaController@ajx')->name('ajx');
    Route::get('creer-module', 'TmaController@gestionModules')->name('gestionModules');
    Route::post('store-module', 'TmaController@storeModule')->name('storeModule');
    Route::get('update-users/{table}/{id}', 'UtilisateursController@updateUsers')->name('updateUsers');
    Route::post('reinitPassword', 'UtilisateursController@reinitPassword')->name('ajax.reinitPassword');
    Route::post('updateUser', 'UtilisateursController@updateUser')->name('updateUser');
    Route::post('genererUserEmail', 'UtilisateursController@genererUserEmail')->name('ajax.genererUserEmail');
    Route::post('x-editable-modif', 'XeditableController@updatexEditable')->name('updatexEditable');
    Route::post('x-editable-update', 'XeditableController@xEditableUpdate')->name('xEditableUpdate');

    Route::get('template-function', 'TemplateController@templateFunction')->name('templateFunction');
    Route::post('update-datas', 'XeditableController@updateDatas')->name('ajax.updateDatas');
    Route::get('icones', 'TmaController@createIcones')->name('CreerIcones');
    Route::get('gestion-profils', 'TmaController@createProfils')->name('CreerProfils');

   // Route::get('/tables', 'HomeController@tables')->name('tables');
   // Route::get('/chartjs', 'HomeController@chartjs')->name('chartjs');
    Route::get('/menus', 'MenuController@index')->name('menus');
    Route::post('/menus', 'MenuController@modif')->name('menusModif');
    Route::post('/menuUpdate', 'MenuController@update')->name('menusUpdate');
    Route::get('/listeTables', 'ModuleController@index')->name('listeTables');
    Route::post('/detailTable', 'ModuleController@detailTable')->name('detailTable');

    Route::post('/inlineInput', 'ModuleController@inlineInput')->name('inlineInput');
    Route::post('/inlineSelect', 'ModuleController@inlineSelect')->name('inlineSelect');
    Route::post('/inlineUpdate', 'ModuleController@inlineUpdate')->name('inlineUpdate');
    Route::post('/inlineTexte', 'ModuleController@inlineTexte')->name('inlineTexte');
    Route::post('changerCoord/{action}', ['as' => 'ajax.changerCoord', 'uses' =>'ModuleController@changerCoord']);
    Route::post('changeSessionValue', 'ModuleController@changeSessionValue')->name('ajax.changeSessionValue');
   // Route::get('/exportToCsv', 'HomeController@excelToCsv')->name('excelToCsv');
    Route::post('getSubmitBtn', 'ModuleController@getSubmitBtn')->name('ajax.getSubmitBtn');
    Route::get('groupemenus', 'TmaController@moduleGestion')->name('groupemenus');
    //Route::get('user-module/{moduleID}', 'HomeController@moduleHome')->name('moduleHome');
    //Route::get('choisir-module', 'HomeController@choisirModule')->name('choisirModule');
    Route::post('getFilsLocalite', 'ModuleController@getFilsLocalite')->name('ajax.getFilsLocalite');
    Route::post('store-localites', 'ModuleController@storeLocalites')->name('storeLocalites');
    Route::post('delete-modal-data', 'ModuleController@deleteModalDatas')->name('ajax.deleteModalDatas');
    Route::get('delete-data/{table}/{id}', 'ModuleController@deleteDatas')->name('deleteDatas');

    Route::get('mon-profil', 'UtilisateursController@monProfil')->name('user.monProfil');
    Route::get('changer-photo-profil', 'UtilisateursController@changerPhotoProfil')->name('user.changerPhotoProfil');
    Route::post('charger-photo-utilisateur', 'UtilisateursController@chargerUserPhoto')->name('user.maPhoto');
    Route::post('valider-photo-utilisateur', 'UtilisateursController@validerPhoto')->name('user.validerPhoto');
    Route::get('droits-utilisateurs/{profilID}', 'UtilisateursController@droitsUtilisateurs')->name('user.droitsUtilisateurs');
    Route::get('table-field-default', 'TmaController@tableFieldDefault')->name('tableFieldDefault');
    Route::get('get-list-field', 'TmaController@getListTableField')->name('ajax.getListTableField');
    Route::post('add-default-field', 'TmaController@addTableField')->name('ajax.addTableField');
});




