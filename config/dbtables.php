<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 26/08/2017
 * Time: 07:53
 */

$prefixe = env('DB_PREFIX');

return [
    /**
     * 'DBTBL_NOMTABLE'     =>      'table'
     */

    'DBTBL_FORMATS'                      => ''.$prefixe.'formats',
    'DBTBL_HISTORIQUES'                  => ''.$prefixe.'historiques',
    'DBTBL_AFFICHAGE_DIMENSIONS'         => ''.$prefixe.'affichage_dimensions',
    'DBTBL_AFFICHAGE_DENOMINATIONS'      => ''.$prefixe.'affichage_denominations',
    'DBTBL_AFFICHAGES_NATURES'           => ''.$prefixe.'affichage_natures',
    'DBTBL_AFFICHAGES_PANNEAUS'          => ''.$prefixe.'affichage_panneaus',
    'DBTBL_REGIES'                       => ''.$prefixe.'regies',
    'DBTBL_ALERTES'                      => ''.$prefixe.'alertes',
    'DBTBL_ALERTS_CONTACTS'              => ''.$prefixe.'alerts_contacts',
    'DBTBL_ALERTS_ENREGISTRES'           => ''.$prefixe.'alerts_enregistres',
    'DBTBL_ANNONCEURS'                   => ''.$prefixe.'annonceurs',
    'DBTBL_CAMPAGNES'                    => ''.$prefixe.'campagnes',
    'DBTBL_CAMPAGNETITLES'               => ''.$prefixe.'campagnetitles',
    'DBTBL_CIBLES'                       => ''.$prefixe.'cibles',
    'DBTBL_COEFS'                        => ''.$prefixe.'coefs',
    'DBTBL_CONTACT_DIFFUSIONS'           => ''.$prefixe.'contact_diffusions',
    'DBTBL_COUVERTURES'                  => ''.$prefixe.'couvertures',
    'DBTBL_DOCAMPAGNES'                  => ''.$prefixe.'docampagnes',
    'DBTBL_INTERNET_DIMENSIONS'          => ''.$prefixe.'internet_dimensions',
    'DBTBL_INTERNET_EMPLACEMENTS'        => ''.$prefixe.'internet_emplacements',
    'DBTBL_INTERNET_PAGES'               => ''.$prefixe.'internet_pages',
    'DBTBL_INTERNET_TYPES'               => ''.$prefixe.'internet_types',
    'DBTBL_LISTE_DE_DIFFUSIONS'          => ''.$prefixe.'liste_de_diffusions',
    'DBTBL_LOCALITES'                    => ''.$prefixe.'localites',
    'DBTBL_MEDIAS'                       => ''.$prefixe.'medias',
    'DBTBL_NATURES'                      => ''.$prefixe.'natures',
    'DBTBL_OFFRE_TELECOMS'               => ''.$prefixe.'offretelecoms',
    'DBTBL_OFFRETELECOMS'               => ''.$prefixe.'offretelecoms',
    'DBTBL_OPERATIONS'                   => ''.$prefixe.'operations',
    'DBTBL_PERIODES'                     => ''.$prefixe.'periodes',
    'DBTBL_PERIODICITES'                 => ''.$prefixe.'periodicites',
    'DBTBL_PRESSE_CALIBRES'              => ''.$prefixe.'presse_calibres',
    'DBTBL_PRESSE_COULEURS'              => ''.$prefixe.'presse_couleurs',
    'DBTBL_PRESSE_PAGECOULS'             => ''.$prefixe.'presse_pagecouls',
    'DBTBL_PRESSE_PAGES'                 => ''.$prefixe.'presse_pages',
    'DBTBL_PROFIL_MOBILES'               => ''.$prefixe.'profil_mobiles',
    'DBTBL_MOBILES'                      => ''.$prefixe.'mobiles',
    'DBTBL_PUBS'                         => ''.$prefixe.'pubs',
    'DBTBL_RAPPORTS'                     => ''.$prefixe.'rapports',
    'DBTBL_SECTEURS'                     => ''.$prefixe.'secteurs',
    'DBTBL_SLIDERS'                      => ''.$prefixe.'sliders',
    'DBTBL_SPEEDNEWS'                    => ''.$prefixe.'speednews',
    'DBTBL_SPONSOR_CAMPAGNES'            => ''.$prefixe.'sponsor_campagnes',
    'DBTBL_STATUS'                       => ''.$prefixe.'status',
    'DBTBL_TARIFS'                       => ''.$prefixe.'tarifs',
    'DBTBL_TICKETS'                      => ''.$prefixe.'tickets',
    'DBTBL_USER_SECTEURS'                => ''.$prefixe.'user_secteurs',
    'DBTBL_MODULE_BD'                    => ''.$prefixe.'modules',
    'DBTBL_SUPPORTS'                     => ''.$prefixe.'supports',
    'DBTBL_INJECTIONS'                   => ''.$prefixe.'injections',
    'DBTBL_PUB_NON_VALIDES'              => ''.$prefixe.'pub_non_valides',
    'DBTBL_CAMPAGNETITLE_NON_VALIDES'    => ''.$prefixe.'campagnetitle_non_valides',
    'DBTBL_MESSAGES'                     => ''.$prefixe.'messages',
    'DBTBL_USER_MESSAGES'                => ''.$prefixe.'user_messages',
    'DBTBL_PIECES_JOINTES'               => ''.$prefixe.'pieces_jointes',
    'DBTBL_TYPE_DE_SERVICES'             => ''.$prefixe.'type_services',
    'DBTBL_TYPE_SERVICES'                => ''.$prefixe.'type_services',
    'DBTBL_TYPE_DE_PROMOS'               => ''.$prefixe.'type_promos',
    'DBTBL_TYPE_PROMOS'                  => ''.$prefixe.'type_promos',
    'DBTBL_COORDONNEES_HORS_MEDIAS'      => ''.$prefixe.'coordonnees_hors_medias',
    'DBTBL_PHOTO_PANNEAUS'               => ''.$prefixe.'photo_panneaus',
    'DBTBL_INDICATEURS'                  => ''.$prefixe.'indicateurs',
    'DBTBL_PROFILS_INDICATEURS'          => ''.$prefixe.'profils_indicateurs',
    'DBTBL_TABLE_EXPORTS'                => ''.$prefixe.'table_exports',
    'DBTBL_REPORTINGS'                   => ''.$prefixe.'reportings',
    'DBTBL_FUSION_TABLES'                => ''.$prefixe.'fusion_tables',
    'DBTBL_FUSION_TABLES_DEPENDANCES'    => ''.$prefixe.'fusion_tables_dependances',
    'DBTBL_USERS_ANNONCEURS'             => ''.$prefixe.'users_annonceurs',
    'DBTBL_NOTIFICATIONS'                => ''.$prefixe.'notifications',
    'DBTBL_TYPECOMS'                    => ''.$prefixe.'typecoms',
    'DBTBL_TYPESERVICES'                => ''.$prefixe.'typeservices',

];
