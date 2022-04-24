<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 14/11/2017
 * Time: 02:30
 */

$prefixe_appli = env('APPLI_PREFIX');

return
    [
        /**
         * 'DBTBL_NOMTABLE'     =>      'table'
         */

        'DBTBL_USERS'                        => ''.$prefixe_appli.'users',
        'DBTBL_MODULES'                      => ''.$prefixe_appli.'modules',
        'DBTBL_CONFIGS'                      => ''.$prefixe_appli.'configs',
        'DBTBL_BLACKLISTS'                   => ''.$prefixe_appli.'blacklists',
        'DBTBL_LOG_CONNEXIONS'               => ''.$prefixe_appli.'log_connexions',
        'DBTBL_PASSWORD_RESETS'              => ''.$prefixe_appli.'password_resets',
        'DBTBL_PASSWORD_FORGET'              => ''.$prefixe_appli.'password_forgets',
        'DBTBL_BDCS'                         => ''.$prefixe_appli.'bdcs',
        'DBTBL_BDC_CATEGORIES'               => ''.$prefixe_appli.'bdc_categories',
        'DBTBL_BDC_TITLES'                   => ''.$prefixe_appli.'bdc_titles',
        'DBTBL_PROFILS'                      => ''.$prefixe_appli.'profils',
        'DBTBL_PROFILROLES'                  => ''.$prefixe_appli.'profilroles',
        'DBTBL_ROLES'                        => ''.$prefixe_appli.'roles',
        'DBTBL_MENUS'                        => ''.$prefixe_appli.'menus',
        'DBTBL_ICONES'                       => ''.$prefixe_appli.'icones',
        'DBTBL_GROUPEROLES'                  => ''.$prefixe_appli.'grouperoles',
        'DBTBL_LEVELS'                       => ''.$prefixe_appli.'levels',
        'DBTBL_PARAMS'                       => ''.$prefixe_appli.'params',
        'DBTBL_ROUTES'                       => ''.$prefixe_appli.'routes',
        'DBTBL_ROUTE_PARAMS'                 => ''.$prefixe_appli.'routeparams',
        'DBTBL_AUTORISATIONS'                => ''.$prefixe_appli.'autorisations',
        'DBTBL_FIELDDEFAULTS'                => ''.$prefixe_appli.'fielddefaults',
        'DBTBL_FIELDPARAMS'                  => ''.$prefixe_appli.'fieldparams',
        'DBTBL_GROUPEMENUS'                  => ''.$prefixe_appli.'groupemenus',
        'DBTBL_DOCUMENTATIONS'               => ''.$prefixe_appli.'documentations',
        'DBTBL_GROUPEMENU_ROUTES'            => ''.$prefixe_appli.'groupemenu_routes',
        'DBTBL_SQL_ERRORS_ARCHIVES'          => ''.$prefixe_appli.'sql_errors_archives',
        'DBTBL_DEFAULT_LABELS'               => ''.$prefixe_appli.'default_labels',
        'DTBL_MENU_TARGETS'                 => ''.$prefixe_appli.'menu_targets',
    ];


