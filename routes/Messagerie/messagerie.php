<?php
/**
 *************************** Messagérie ***********************************************
 */
Route::group(['namespace' => 'Messagerie','prefix' => 'message', 'middleware' => ['auth','logConnexion']],function() {
    Route ::get ('new', 'MessagerieController@newMessage')-> name ( 'message.envoi');
    Route ::get ('{message}/show', 'MessagerieController@showMessage')-> name ( 'message.showMessage');
    Route ::get ('inbox', 'MessagerieController@inbox')-> name ( 'message.inbox');
    Route ::post ('store', 'MessagerieController@storeMessage')-> name ( 'message.store');
    Route ::post ('show-files', 'MessagerieController@showFiles')-> name ( 'ajax.showFiles');
    Route ::get ('download-files/{messageID}/{fichier}', 'MessagerieController@downloadFiles')-> name ( 'message.download');
    Route ::get ('download-files-zip/{messageID}', 'MessagerieController@downloadArchives')-> name ( 'message.downloadZip');
    Route ::get ('messages-envoyes', 'MessagerieController@messagesEnvoyes')-> name ( 'message.messagesEnvoyes');
    Route ::get ('reply/{messageID}', 'MessagerieController@messagesReponse')-> name ( 'message.reply');
    Route ::post ('check-message', 'MessagerieController@checkMessage')-> name ( 'ajax.checkMessage');
    Route ::post ('list-notification', 'MessagerieController@listNotification')-> name ( 'ajax.checkNotification');
    Route ::get ('show-notifications', 'MessagerieController@showNotification')-> name ( 'showNotification');
});
