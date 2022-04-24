<?php

$imageext = array('png', 'jpg', 'jpeg', 'gif') ;
$audioext = array('mp3'  => 'audio/mpeg', 'ogg' => 'audio/ogg', 'wav' => 'audio/wav') ;
$videoext = array('mp4' => 'video/mp4', 'webm' => 'video/webm') ;
$docext = array('csv','xls','xlsx','pdf','docx','doc','ppt','pptx') ;
$premierDuMois = date('Y-m-d', mktime(0, 0, 0, date("m"), 1, date("Y")));
$inputTypeDate = array('date','datetime','timestamp','time','year');
$inputTypeEntier = array('int','tinyint','smallint','mediumint','bigint');
$ChampsInterdits = ["id", "created_at", "updated_at", "lastmodif"];

$jourFr = ["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"];
$moisFr = ["Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Decembre"];

$medias = [
    6 => 'affichage',4 => 'internet',5 => 'mobile',2 => 'radio',
    1 => 'television',3 => 'presse',7 => 'hors-media'];

$champmedia =[
    1   =>  array('duree'           => array('select'   =>  'c.duree',
        'from'     =>  '',
        'where'    =>  ''
    )
    ),
    2   =>  array('duree'           => array('select'   =>  'c.duree',
        'from'     =>  '',
        'where'    =>  ''
    )
    ),
    3   =>  array('presse_couleur'         => array('select'   =>  'cou.name as couleur',
        'from'     =>  'zdsb_presse_couleurs' . ' cou ',
        'where'    =>  ' AND c.presse_couleur = cou.id'
    ),
        'presse_calibre'          => array('select'   =>  'ca.name as calibre',
            'from'     =>  'zdsb_presse_calibres' . ' ca ',
            'where'    =>  ' AND c.presse_calibre = ca.id'
        )
    ),
    4   =>  array('internet_dimension' => array('select'   =>  'd.name as internet_dimension',
        'from'     =>  'zdsb_internet_dimensions' . ' d ',
        'where'    =>  ' AND c.internet_dimension = d.id'
    )
    ),
    5   =>  array('profil_mobile' => array('select'   =>  'mp.name as profil_mobile',
        'from'     =>  "zdsb_profil_mobiles" . ' mp ',
        'where'    =>  ' AND c.profil_mobile = mp.id'
    )
    ),
    6   =>  array('affichage_dimension' => array('select' => 'di.name as affichage_dimension',
        'from' => "zdsb_affichage_dimensions" . ' di ',
        'where' => ' AND c.affichage_dimension = di.id'
    )
    )
];

$defaultvalue = array(
    'offretelecom' => 9,
    'mobileprofil' => 1,
    'mobilemessage' => '',
    'dimension' => 19,
    'duree' => 0,
    'couleur' => 3,
    'calibre' => 31,
    'dimensionpaneau' => 12
);




$dependanceBDTable = [
    //'zdsb_campagnes' => ['zdsb_pubs'],
    'zdsb_supports' => ['zdsb_pubs','zdsb_speednews','zdsb_tarifs','zdsb_coefs'],
    'zdsb_campagnetitles' => ['zdsb_campagnes', 'zdsb_docampagnes', 'zdsb_speednews'],
    'zdsb_natures' => ['zdsb_campagnes'],
    'zdsb_formats' => ['zdsb_campagnes'],
    'zdsb_cibles' => ['zdsb_campagnes'],
    'zdsb_operations' => ['zdsb_campagnetitles'],
    'zdsb_offretelecoms' => ['zdsb_campagnetitles'],
    'zdsb_mobiles' => ['zdsb_campagnes'],
    'zdsb_internet_dimensions' => ['zdsb_campagnes'],
    'zdsb_affichage_dimensions' => ['zdsb_campagnes'],
    'zdsb_presse_calibres' => ['zdsb_campagnes'],
    'zdsb_presse_couleurs' => ['zdsb_campagnes'],
    'zdsb_annonceurs'   =>  ['zdsb_operations,zdsb_pubs,zdsb_mobiles']
];

$notificationsType = [
    'zdsb_campagnetitle_non_valides' => [
        'message' => 'Vous avez %s titre(s) de campagne en attente de validation.',
        'font' => 'fa fa-check',
        'url' => 'valider.campagnes',
        ]
];

return [
    'prefixe'           => env('DB_PREFIXE'),
    'db'                => env('DB_DATABASE'),
    'session_delay'     => env('SESSION_DELAY'),
    'prefixe_appli'     => env('DB_PREFIX_APPLI'),

    'db_server_file'    => env('DB_SERVER_FILE'),
    'app_url'           => env('APP_URL'),
    //'appli_baseUrl'     => "http://".$_SERVER['HTTP_HOST'],
    'imageext'          => $imageext,
    'audioext'          => $audioext,
    'videoext'          => $videoext,
    'docext'            => $docext,
    'date_debut'        => $premierDuMois,
    'typedate'          => $inputTypeDate,
    'ChampsInterdits'   => $ChampsInterdits,
    'typeentier'        => $inputTypeEntier,

    'jourFr'            => $jourFr,
    'moisFr'            => $moisFr,

    'champmedia'        => $champmedia,
    'defaultvalue'      => $defaultvalue,
    'medias'            => $medias,
    'notificationsType' => $notificationsType,

];
