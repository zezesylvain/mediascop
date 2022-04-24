<?php

namespace App\Http\Controllers\Client;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use App\Http\Controllers\core\UtilisateursController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ReportingController extends AdminController
{
    //public static $param = ['format', 'nature', 'annonceur', 'media', 'support', 'cible', 'secteur', 'type_service']
    //public static $param = ['format', 'type_service', 'annonceur', 'media', 'support', 'cible', 'secteur'] ;
    public static $param = ['format', 'annonceur', 'media', 'support', 'cible', 'secteur'] ;

    public function home(){
        if(Session::has('getParamName')):
            Session::forget('getParamName') ;
        endif;    
        $date_debut = date ('Y-m-d', mktime(0,0,0,date("n"), date("j")-30, date('Y')))  ; // 30 jours en arriere
        $date_fin = date ('Y-m-d');
        return self::built(compact('date_debut', 'date_fin')) ;
    }
    public static function built($donnees){
        $hauteur = 400;
        $hauteur2 = 500;
        $tblSecteurs = DbTablesHelper::dbTable("DBTBL_SECTEURS",'db') ;
        $tblType_services = DbTablesHelper::dbTable("DBTBL_TYPE_SERVICES",'db') ;
        $tblMedias = DbTablesHelper::dbTable("DBTBL_MEDIAS",'db') ;
        $tblReportings = DbTablesHelper::dbTable("DBTBL_REPORTINGS",'db') ;
        Session::put('formvar.annonceur', []);
        Session::put('formvar.secteur', []);
        Session::put('formvar.media', []);
        Session::put('formvar.support', []);
        Session::put('formvar.format', []);
        extract($donnees) ;
        Session::put('formvar.date.date_debut', $date_debut);
        Session::put('formvar.date.date_fin', $date_fin);
        $condition = self::makeConditions($donnees) ;
        //$q = self::planMediaQuery($condition) ;
        $lesDonnees = self::makeReportingData(FunctionController
                        ::arraySqlResult("SELECT * FROM $tblReportings WHERE $condition ORDER BY date ASC"));
        //dd($lesDonnees);
        $route = route('client.formvalider');
        $userSecteur = UtilisateursController::UserSecteur();

        $condSecteur = !$userSecteur ? '' : " WHERE id = $userSecteur ";
        $secteurs = FunctionController::arraySqlResult("SELECT * FROM $tblSecteurs $condSecteur ORDER BY name ASC");

        $condTypeService = !$userSecteur ? '' : " WHERE secteur = $userSecteur ";
        $type_services = FunctionController::arraySqlResult("SELECT * FROM $tblType_services $condTypeService ORDER BY name ASC");
        $medias = FunctionController::arraySqlResult("SELECT * FROM $tblMedias ORDER BY name ASC");
        $nbrsecteur = count($secteurs);
        $nbrtype_service = count($type_services);
        $nbrmedia = count($medias);
        Session::put('Reporting.Data', $lesDonnees) ;
        $data = compact('lesDonnees', 'secteurs','type_services','route','medias', 'donnees','nbrsecteur',
                    'nbrtype_service','nbrmedia','date_fin','date_debut', 'hauteur', 'hauteur2') ;
        return view('clients.index', $data) ;
    }
    public function formReport(Request $request){
        $route = route('client.formvalider');
        $data = $request->all() ;
        unset($data['_token']) ;
        return self::built($data) ;
    }
    public static function makeConditions(array $data){
        $date_debut = $data['date_debut'] ;
        $date_fin = $data['date_fin'] ;
        $condItem = ['secteur', 'annonceur', 'campagne', 'type_service', 'media', 'support', 'format'] ;
        $cond = " `date` BETWEEN '$date_debut' AND '$date_fin'" ;
        $userSecteur = UtilisateursController::UserSecteur();
        foreach($condItem AS $item):
            $and = " AND " ;
            if(array_key_exists($item, $data)):
                $tab = $item === 'secteur' && $userSecteur ? $userSecteur : join(', ', $data[$item]);
                $cond .= " $and $item IN ($tab)" ;
            endif;
        endforeach ;
        return $cond ;
    }
    public static function makeReportingData(array $donnees):array{
        $param = self::$param ;
        $tableaux = [] ;
        $paramName = self::getParamName() ;
        foreach($param AS $v):
            $var = "les".ucfirst($v) ;
            $$var = [] ;
            $tableaux[$v] = [] ;
        endforeach;  
        $listeTableaux = [
                'lesDate' , 'dateMois' , 'detailDesCampagnes', 'investParAnnonceurEtParMedia',
                'campagneinvest', 'planMedia', 'listCampagne', 'investParAnnonceurEtParFormat',
                'investParAnnonceurEtParNature', 'investParAnnonceur','investParMediaEtParSupport', 
                'investParMedia', 'partDeVoixParAnnonceur', 'investParMediaEtParAnnonceur',
                'investParType_service',
                'investParCible', 'investParFormat', 'investParNature', 'parAnnonceurEtParMedia',
                'investParAnnonceurEtParCible', 'parAnnonceur',  'investParAnnonceurEtParType_service',
                'investParAnnonceurEtParCible', 'listDesCouleursDesAnnonceur', 'parItem', 'partDeVoixParMedia',
                'partDeVoixParMediaEtParAnnonceur', 'investParSecteurEtParAnnonceur', 'speedNewsElt', 'lesSpeedNews'
            ] ;
        foreach($listeTableaux AS $it):
            $$it = [] ;
        endforeach;
        //$listeSpeciale = ['media', 'format', 'cible', 'nature', 'type_service'] ;
        //$listeSpeciale = ['media', 'format', 'cible', 'type_service'] ;
        $listeSpeciale = ['media', 'format', 'cible'] ;
        foreach($listeSpeciale AS $it):
            $parAnnonceur[$it] = [] ;
        endforeach;
        $annonceursID = [] ;
        foreach($donnees AS $row):
            $tarif = $row['media'] == 61 ? $row['investissement'] : $row['tarif'] ;
            $insertion = $row['media'] == 61 ? $row['nombre'] : 1 ;
            foreach($param AS $v):
                $var = "les".ucfirst($v) ;
                self::pushInArray($$var, $row[$v]) ;
                $$v = $paramName[$v][$row[$v]] ;
                self::incrementValueInTripleArray($tableaux, $v, $$v, $tarif, $insertion) ;
            endforeach ;
            self::pushValueInArray($speedNewsElt, 'media', $row['media']) ;
            self::pushValueInArray($speedNewsElt, 'support', $row['support']) ;
            self::pushInArray($annonceursID, $row['annonceur']) ;
            self::pushInArray($lesDate, $row['date']) ;
            self::pushInArray($lesDate, $row['date']) ;
            self::makeDateTab($dateMois, $row['date']) ;
            
            foreach($listeSpeciale AS $it):
                self::incrementValueInTripleArray($parAnnonceur[$it], $annonceur, $$it, $tarif, $insertion) ;
            endforeach;
            self::incrementValueInTripleArray($parAnnonceurEtParMedia, $annonceur, $media, $tarif, $insertion) ;
            self::incrementValueInMultiArray($investParSecteurEtParAnnonceur, $secteur, $annonceur, $tarif) ;
            self::incrementValueInMultiArray($investParAnnonceurEtParFormat, $annonceur, $format, $tarif) ;
            self::incrementValueInMultiArray($investParAnnonceurEtParType_service, $annonceur, $type_service, $tarif) ;
            self::incrementValueInMultiArray($investParAnnonceurEtParCible, $annonceur, $cible, $tarif) ;
            self::incrementValueInMultiArray($investParAnnonceurEtParMedia, $annonceur, $media, $tarif) ;
            self::incrementValueInMultiArray($investParMediaEtParAnnonceur, $media, $annonceur, $tarif) ;
            self::incrementValueInMultiArray($partDeVoixParMediaEtParAnnonceur, $media, $annonceur, $insertion) ;
            self::incrementValueInMultiArray($investParMediaEtParSupport, $annonceur, $support, $tarif) ;
            self::incrementValueInMultiArray($investParMediaEtParSupport, $annonceur, $support, $tarif) ;
            self::incrementValueInArray($investParAnnonceur, $annonceur, $tarif) ;
            self::incrementValueInArray($investParType_service, $type_service, $tarif) ;
            self::incrementValueInArray($investParCible, $cible, $tarif) ;
            self::incrementValueInArray($investParFormat, $format, $tarif) ;
            self::incrementValueInArray($partDeVoixParAnnonceur, $annonceur, $insertion) ;
            self::incrementValueInArray($partDeVoixParMedia, $media, $insertion) ;
            self::incrementValueInArray($investParMedia, $media, $tarif) ;
            self::makePlanMedia($planMedia, $annonceur, $media, $row['date'], $insertion, $tarif) ;
            self::detailCampagne($detailDesCampagnes, $row) ;
        endforeach;
        foreach($param AS $v):
            $var = "les".ucfirst($v) ;
            $parItem[$v] = $$var ;
        endforeach ;
        $speedNewsElt['campagnetitle'] = array_keys($detailDesCampagnes) ;
        $speednewTbody = self::makeSpeednewsTable($speedNewsElt, $detailDesCampagnes) ;
        $lesDocCampagnes = self::getAllDocCampagne($speedNewsElt) ;
        //$lesDocCampagnes = self::getDocCampagnePourLaDemo($detailDesCampagnes) ;
        Session::put('docCampagnes', $lesDocCampagnes) ;
        Session::put('detailDesCampagnes', $detailDesCampagnes) ;
        $listDesCouleursDesAnnonceur = self::getAnnonceurInfo($annonceursID);
        $parItem['date'] = $lesDate ;
        $parItem['dateMois'] = $dateMois ;
        return compact('lesDate' , 'dateMois' , 'detailDesCampagnes', 'investParAnnonceurEtParMedia',
                'campagneinvest', 'planMedia', 'listCampagne', 'investParAnnonceurEtParFormat',
                'parItem', 'investParAnnonceurEtParType_service', 'investParAnnonceur', 'partDeVoixParAnnonceur',
                'investParMedia', 'investParMediaEtParSupport', 'investParMediaEtParAnnonceur', 'parAnnonceur',
                'investParCible', 'investParFormat', 'investParType_service', 'tableaux', 'parAnnonceurEtParMedia',
                'investParAnnonceurEtParCible', 'listDesCouleursDesAnnonceur', 'partDeVoixParMedia',
                'partDeVoixParMediaEtParAnnonceur', 'investParSecteurEtParAnnonceur', 'speednewTbody'
                ) ;
    }
    public static function makePlanMedia(array &$array, $annonceur, $media, $date, $ins, $invest){
        if(!array_key_exists($annonceur, $array)):
            $array[$annonceur][$media]['invest'] = $invest ;
            $array[$annonceur][$media]['ins'] = $ins ;
            $array[$annonceur][$media][$date] = ['insertion' => $ins, 'budget' => $invest ] ;
        elseif(!array_key_exists($media, $array[$annonceur])):
            $array[$annonceur][$media]['invest'] = $invest ;
            $array[$annonceur][$media]['ins'] = $ins ;
            $array[$annonceur][$media][$date] = ['insertion' => $ins, 'budget' => $invest ] ;
        elseif(!array_key_exists($date, $array[$annonceur][$media])):
            $array[$annonceur][$media][$date] = ['insertion' => $ins, 'budget' => $invest ] ;
            $array[$annonceur][$media]['invest'] += $invest ;
            $array[$annonceur][$media]['ins'] += $ins ;
        else:
            $array[$annonceur][$media][$date]['insertion'] += $ins ;
            $array[$annonceur][$media][$date]['budget'] += $invest ;
            $array[$annonceur][$media]['invest'] += $invest ;
            $array[$annonceur][$media]['ins'] += $ins ;
        endif;
    }
    public static function getParamName():array{
        $param = self::$param ;
        if(!Session::has('getParamName')):
            $ret = [] ;
            foreach($param AS $it):
                $dbtbl = DbTablesHelper::dbTable("DBTBL_".strtoupper($it)."S",'db') ;
                $name = $it == "annonceur" ? " raisonsociale AS name " : " name" ;
                $d = FunctionController::arraySqlResult("SELECT id, $name FROM $dbtbl") ;
                foreach($d AS $r):
                    $ret[$it][$r['id']] = $r['name'] ;
                endforeach ;
            endforeach ;
            Session::put('getParamName', $ret) ;
        endif ;
        return Session::get('getParamName') ;
    }
    public static function getAnnonceurInfo(array $annonceurs):array{
        $ret = [] ;
        $join = join(', ', $annonceurs) ;
        $cond = !empty($annonceurs) ? " WHERE id IN($join)" : "";
        $dbtbl = DbTablesHelper::dbTable("DBTBL_ANNONCEURS",'db') ;
        $d = FunctionController::arraySqlResult("SELECT * FROM $dbtbl $cond") ;
        foreach($d AS $r):
            $ret[$r['raisonsociale']] = $r['couleur'] ;
        endforeach ;
        return $ret ;
    }
    public static function makeDateTab(&$tab, $date){
        $t = explode('-', $date) ;
        $mois = date("M-Y", mktime(0,0,0,$t[1],$t[2], $t[0])) ;
        if(!array_key_exists($mois, $tab)):
            $tab[$mois][] = $t[2] ;
        elseif(!in_array($t[2], $tab[$mois])):
            $tab[$mois][] = $t[2] ;
        endif;
    }
    public static function pushInArray(&$tab, $val, $unique = true) {
        if ($unique) :
            if (!in_array($val, $tab)):
                $tab[] = $val;
            endif;
        else:
            $tab[] = $val;
        endif;
    }
    public static function incrementValueInArray(&$array, $key, $value){
        if(!array_key_exists($key, $array)):
            $array[$key] = $value ;
        else:
            $array[$key] += $value ; 
        endif;
    }
    public static function pushValueInArray(&$array, $key, $value){
        if(!array_key_exists($key, $array)):
            $array[$key][] = $value ;
        elseif(!in_array($value, $array[$key])):
            $array[$key][] = $value ; 
        endif;
    }
    public static function incrementValueInMultiArray(&$tab, $key1, $key2, $val) {
        if (!array_key_exists($key1, $tab)):
            $tab[$key1][$key2] = $val;
        else :
            self::incrementValueInArray($tab[$key1], $key2, $val) ;
        endif;
    }
      
    public static function incrementValueInTripleArray(&$tab, $key1, $key2, $tarif, $insertion = 1) {
        if (!array_key_exists($key1, $tab)):
            $tab[$key1][$key2] = ['invest' => $tarif, 'insertion' => $insertion];
        else :
            self::incrementValueInMultiArray($tab[$key1], $key2, 'invest', $tarif) ;
            self::incrementValueInMultiArray($tab[$key1], $key2, 'insertion', $insertion) ;
        endif;
    }
    public static function pushValueInMultiArray(&$tab, $key1, $key2, $val) {
        if (!array_key_exists($key1, $tab)):
            $tab[$key1][$key2][] = $val;
        else :
            self::pushValueInArray($tab[$key1], $key2, $val) ;
        endif;
    }
    public static function transformTripleDataForBarChart($data, $type = "invest"){
        $tab = [] ;
        foreach($data AS $k => $r):
            foreach($r AS $n => $t):
                $tab[$k][$n] = $t[$type] ;
            endforeach;
        endforeach ;
        return $tab ;
    }

    public static function detailCampagne(&$detailDesCampagnes, $row){
        $paramName = self::getParamName() ;
        $leTarif = $row['tarif'] ;
        $insertion = $row['nombre'] == 0 ? 1 : $row['nombre'] ; //A ajuster pour le media Affichage
        $cleCamp = $row['campagnetitle'] ;
        $media = $paramName['media'][$row['media']] ;
        $mid    = $row['media'] ;
        $annonceur = $paramName['annonceur'][$row['annonceur']] ;
        $format = $paramName['format'][$row['format']] ;
        $type_service = $paramName['type_service'][$row['type_service']] ;
        $cible = $paramName['cible'][$row['cible']] ;
        $support = $paramName['support'][$row['support']] ;
        $secteur = $paramName['secteur'][$row['secteur']] ;
        
        if (!array_key_exists($cleCamp, $detailDesCampagnes)) :
            $detailDesCampagnes[$cleCamp] = [
                'cid' => $cleCamp, 'tid' => $cleCamp, 'Secteur' => $secteur,'AnnonceurID' => $row['annonceur'], 
                'Annonceur' => $annonceur, 'Operation' => self::getOperation($row['operation']), 
                'Titre' => self::getCampagneTitle($cleCamp),
                'param' => [
                    'Format' => [$media => [$format]],
                    'Type_service' => [$media => [$type_service]]
                    ],
                'Date' => [$row['date']],
                'Premiere Diffusion'    => ['date' => $row['date'], 'media' => $media, 'support' => $support],
                'Derniere Diffusion'    => ['date' => $row['date'], 'media' => $media, 'support' => $support],
                'Format' => [$format],
                'media' => [$media],
                'lesMediasDeLaCampagne' => [$mid => $media] ,
                'support' => [$row['media'] => [$support]],
                'supportID' => [$row['media'] => [$row['support']]],
                'invest' => [$media => $leTarif],
                'investTotal' => $leTarif,
                'insertionTotal' => $insertion,
                'ins' => [$media => $insertion],
                'infoPM' => [$support =>['ins' => $insertion, 'invest' => $leTarif]],
                'type_service' => [$type_service],
                'cible' => [$cible],
                'dateMois' => [],
                'planMedia' => [
                                $media =>[
                                    $support =>[
                                        $row['date'] => [
                                                'ins' => $insertion, 
                                                'invest' => $leTarif
                                            ]
                                        ]
                                    ]
                            ]
            ];
        else :
            self::pushInArray($detailDesCampagnes[$cleCamp]['Date'], $row['date']);
            self::pushInArray($detailDesCampagnes[$cleCamp]['Format'], $format);
            self::pushInArray($detailDesCampagnes[$cleCamp]['media'], $media);
            self::pushInArray($detailDesCampagnes[$cleCamp]['type_service'], $type_service);
            self::pushInArray($detailDesCampagnes[$cleCamp]['cible'], $cible); //
            self::pushValueInArray($detailDesCampagnes[$cleCamp]['support'], $row['media'], $support);
            self::pushValueInArray($detailDesCampagnes[$cleCamp]['supportID'], $row['media'], $row['support']);
            self::pushValueInArray($detailDesCampagnes[$cleCamp]['param']['Format'], $media, $format);
            self::pushValueInArray($detailDesCampagnes[$cleCamp]['param']['Type_service'], $media, $type_service);
            self::incrementValueInMultiArray($detailDesCampagnes[$cleCamp]['infoPM'], $support, 'ins', $insertion) ;
            self::incrementValueInMultiArray($detailDesCampagnes[$cleCamp]['infoPM'], $support, 'invest', $leTarif) ;
            self::incrementValueInMultiArray($detailDesCampagnes[$cleCamp], 'invest', $media, $leTarif) ;
            self::incrementValueInMultiArray($detailDesCampagnes[$cleCamp], 'ins', $media, $insertion) ;
            $detailDesCampagnes[$cleCamp]['investTotal'] += $leTarif;
            $detailDesCampagnes[$cleCamp]['insertionTotal'] += $insertion;
            $detailDesCampagnes[$cleCamp]['Derniere Diffusion'] = ['date' => $row['date'], 'media' => $media, 'support' => $support];
            $planMedia = $detailDesCampagnes[$cleCamp]['planMedia'] ;
            if(!array_key_exists($mid, $detailDesCampagnes[$cleCamp]['lesMediasDeLaCampagne'])):
                $detailDesCampagnes[$cleCamp]['lesMediasDeLaCampagne'][$mid] = $media ;
            endif ;
            if(!array_key_exists($media, $planMedia)):
                $planMedia[$media] = [
                                    $support =>[
                                        $row['date'] => [
                                                'ins' => $insertion, 
                                                'invest' => $leTarif
                                            ]
                                        ]
                                    ]  ;
            elseif(!array_key_exists($support, $planMedia[$media])):
                $planMedia[$media][$support] = [
                                        $row['date'] => [
                                                    'ins'    => $insertion, 
                                                    'invest' => $leTarif
                                                ]
                                            ] ; 
            else:
                self::incrementValueInMultiArray($planMedia[$media][$support], $row['date'], 'invest',  $leTarif) ;
                self::incrementValueInMultiArray($planMedia[$media][$support], $row['date'], 'ins',  $insertion) ;
            endif ;
            $detailDesCampagnes[$cleCamp]['planMedia'] = $planMedia ;
        endif;
        self::makeDateTab($detailDesCampagnes[$cleCamp]['dateMois'], $row['date']) ;
    }
    public static function getCampagneTitle(int $id):string{
        if(!Session::has("Reporting.getCampagneTitle.$id")):
            $table = DbTablesHelper::dbTable("DBTBL_CAMPAGNETITLES",'db') ;
            $d = FunctionController::arraySqlResult("SELECT title FROM $table WHERE id = $id") ;
            $title = $d[0]['title'] ?? "ERREUR" ;
            Session::put("Reporting.getCampagneTitle.$id", $title) ;
        endif;    
        return Session::get("Reporting.getCampagneTitle.$id") ;
    }    
    public static function getOperation(int $id):string{
        if(!Session::has("Reporting.getOperation.$id")):
            $table = DbTablesHelper::dbTable("DBTBL_OPERATIONS",'db') ;
            $d = FunctionController::arraySqlResult("SELECT name FROM $table WHERE id = $id") ;
            $title = $d[0]['name'] ?? "ERREUR" ;
            Session::put("Reporting.getOperation.$id", $title) ;
        endif;    
        return Session::get("Reporting.getOperation.$id") ;
    }

    public static function getReportingData($donnees){
        $tblReportings = DbTablesHelper::dbTable("DBTBL_REPORTINGS",'db') ;
        $cond = self::makeConditions($donnees) ;
        $query =  "SELECT  * FROM  `$tblReportings`  WHERE $cond" ;
        return FunctionController::arraySqlResult($query) ;                
    }
    public static function getSpeednews($elt) {
        $tab = [];
        $sptab = [];
        if (array_key_exists('campagnetitle',$elt) && !empty($elt['campagnetitle'])):             
            $cc = " campagnetitle IN (".join(',', $elt['campagnetitle']).")" ;
            $cm = "";
            $cs = "";
            if (array_key_exists('media',$elt) && !empty($elt['media'])):
                $cm = " AND media IN (".join(',', $elt['media']).")" ;
            endif;
            if (array_key_exists('support',$elt) && !empty($elt['support'])):
                $cs = " AND support IN (".join(',', $elt['support']).")" ;
            endif;
            $table = DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db') ;
            $sql = "SELECT * FROM $table WHERE $cc  $cm $cs";
            $result = FunctionController::arraySqlResult($sql);
            foreach ($result as $value):
                $tab[$value['id']] = $value;
                $sptab[$value['campagnetitle']][] = $value;
            endforeach;
        endif;
        Session::put('lesSppeedNews', $sptab) ;
        return $tab;
    }
    public static function getDocCampagne($elt) {
        $tab = [];
        if (array_key_exists('campagnetitle',$elt) && !empty($elt['campagnetitle'])):
            $cc = " campagnetitle IN (".join(',', $elt['campagnetitle']).")" ;
            $cm = "";
            if(array_key_exists('media',$elt) && !empty($elt['media'])):
                $cm = " AND media IN (".join(',', $elt['media']).")" ;
            endif;
            $table = DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','db') ;
            $sql = "SELECT * FROM $table WHERE $cc $cm";
            $result = FunctionController::arraySqlResult($sql);
            foreach ($result as $value):
                $tab[$value['campagnetitle']][$value['media']] = $value;
            endforeach;
        endif;
        return $tab;
    }
    public static function getDocCampagnePourLaDemo($detailDesCampagnes) {
        $tab = [];
        $lesDocs = self::getAllDocCampagnePourLaDemo() ;
        foreach($detailDesCampagnes AS $cid => $camp):
            $aid = $camp['AnnonceurID'] ;
            if(in_array($aid, [1,2,3])):
                $lesMedias = $camp['media'] ;
                foreach($lesMedias AS $mName):
                    $docCamp = array_key_exists($aid,$lesDocs) ? $lesDocs[$aid][$mName] : [] ;
                    foreach($docCamp AS $d):
                        $tab[$cid][$mName][] = $d ;
                    endforeach;
                endforeach;
            endif;
        endforeach;
        return $tab;
    }


    public static function getAllDocCampagne($elt) {
        $params = self::getParamName() ;
        //dd($params) ;
        $tab = [];
        if (array_key_exists('campagnetitle',$elt) && !empty($elt['campagnetitle'])):
            $cc = " campagnetitle IN (".join(',', $elt['campagnetitle']).")" ;
            $cm = "";
            if(array_key_exists('media',$elt) && !empty($elt['media'])):
                $cm = " AND media IN (".join(',', $elt['media']).")" ;
            endif;
            $table = DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','db') ;
            $sql = "SELECT * FROM $table WHERE $cc $cm";
            $result = FunctionController::arraySqlResult($sql);
            foreach ($result as $value):
                $tab[$value['campagnetitle']][$params['media'][$value['media']]][] = $value;
            endforeach;
        endif;
        //dd($tab);
        return $tab;
    }
    /***
     * 
     * Pour la demo
     * */
    public static function getAllDocCampagnePourLaDemo(){
        $dir = 'upload'.DIRECTORY_SEPARATOR.'campagnes'.DIRECTORY_SEPARATOR.'visuels'.DIRECTORY_SEPARATOR ;
        $d = glob("$dir*".DIRECTORY_SEPARATOR."*".DIRECTORY_SEPARATOR."*") ;
        $tab = [] ;
        foreach($d AS $img):
            $t = explode(DIRECTORY_SEPARATOR, str_replace($dir, "", $img));
            $infoFichier = pathinfo($t[2]) ;
            $tab[$t[0]][$t[1]][] = [
                                    'fichier'   => $t[2],
                                    'type'      => $infoFichier['extension'],
                                    'name'      => $infoFichier['filename']
                                    ];
        endforeach;    
        //dd($tab) ;
        return $tab ;  
    }
    public static function makeSpeednewsTableForDemo($elt, $detailDesCampagnes){
        $inc = 0;
        $speednewTbody = "";
        $paramName = self::getParamName() ;
        $lesSpeednews = self::getSpeednews($elt);
        $lesDocs = self::getAllDocCampagnePourLaDemo() ;
        foreach ($lesSpeednews as $leSpeednews):
            $inc++;
            $cid = $leSpeednews['campagnetitle'] ;
            $mediaID = $leSpeednews['media'] ;
            $leSupport = $detailDesCampagnes[$cid]['support'][$mediaID][0] ?? '';
            $illustrator = "" ;
            $fichiers = [] ;
            $annonceurID = $detailDesCampagnes[$cid]['AnnonceurID'] ;
            if(array_key_exists($annonceurID, $lesDocs)):
                $media = $paramName['media'][$mediaID] ;
                if(array_key_exists($media, $lesDocs[$annonceurID])):
                    $fichiers = $lesDocs[$annonceurID][$media] ;
                endif;
            endif;
            $dir = 'upload'.DIRECTORY_SEPARATOR.'campagnes'.DIRECTORY_SEPARATOR.'visuels'.DIRECTORY_SEPARATOR ;
            if(count($fichiers)) :
                $leFichier = $fichiers[0] ;
                $fichier = $leFichier['fichier'] ;
                $type = $leFichier['type'] ;
                //dd($type) ;
                $file = $dir.$annonceurID.DIRECTORY_SEPARATOR.$media.DIRECTORY_SEPARATOR.$fichier ;
                $ret = '';
                if(in_array($type, Config::get ("constantes.imageext"))):
                    if (file_exists(public_path($file))):
                        $ret = '<img style="width: 90%; " class="img-responsive" src="'.asset($file).'">' ;
                    else:
                        $ret = '<i class=\"fa fa-image fa-3x\"></i>';
                    endif;
                elseif(array_key_exists($type, Config::get ("constantes.videoext"))) :
                    $ret = "<i class=\"fa fa-film fa-3x\"></i>" ;
                elseif(array_key_exists($type, Config::get ("constantes.audioext"))) :
                    $ret = "<i class=\"fa fa-microphone fa-3x\"></i>" ;
                endif ;
               // $illustrator = "<a target='_blank' href='".route('reporting.detailCampagne',encrypt($cid))."' >$ret</a>" ;
                $illustrator = "<a href='".route('reporting.downloadFile',encrypt($file))."' >$ret</a>";
            endif;

            $title = $detailDesCampagnes[$cid]['Titre'];
            $medianame = $paramName['media'][$mediaID];
            $secteurname =  $detailDesCampagnes[$cid]['Secteur'] ;
            $raisonsociale = $detailDesCampagnes[$cid]['Annonceur'] ;
            $route = route('reporting.detailCampagne',encrypt($cid));
            $speednewTbody .= view ("clients.speednews.speedNewTbody",compact ('leSpeednews','route','title','medianame','raisonsociale','secteurname','leSupport','illustrator'))->render ();
        endforeach;
        return $speednewTbody ;
        //return view ("clients.speednews.tableauSpeednews",compact ('speednewTbody'))->render ();
    }
    public static function makeSpeednewsTable($elt, $detailDesCampagnes){
        $inc = 0;
        $speednewTbody = "";
        $paramName = self::getParamName() ;
        $lesSpeednews = self::getSpeednews($elt);
        $lesDocs = self::getDocCampagne($elt) ;
        foreach ($lesSpeednews as $leSpeednews):
            $inc++;
            $cid = $leSpeednews['campagnetitle'] ;
            $mediaID = $leSpeednews['media'] ;
            $leSupport = $detailDesCampagnes[$cid]['support'][$mediaID][0] ?? '';
            $illustrator = "" ;
            $fichiers = $lesDocs[$cid][$mediaID] ?? [] ;
            $annonceurID = $detailDesCampagnes[$cid]['AnnonceurID'] ;
            if(count($fichiers)) :
                $fichier = $fichiers['fichier'] ;
                $type = $fichiers['type'] ;
                $file = 'upload'.DIRECTORY_SEPARATOR.'campagnes'.DIRECTORY_SEPARATOR.$cid.DIRECTORY_SEPARATOR.$fichier ;
                $ret = '';
                if(in_array($type, Config::get ("constantes.imageext"))):
                    if (file_exists(public_path($file))):
                        $ret = '<img style="width: 90%; " class="img-responsive" src="'.asset($file).'">' ;
                    else:
                        $ret = '<i class=\"fa fa-image fa-3x\"></i>';
                    endif;
                elseif(array_key_exists($type, Config::get ("constantes.videoext"))) :
                    $ret = "<i class=\"fa fa-film fa-3x\"></i>" ;
                elseif(array_key_exists($type, Config::get ("constantes.audioext"))) :
                    $ret = "<i class=\"fa fa-microphone fa-3x\"></i>" ;
                endif ;
                $illustrator = "<a data-toggle=\"modal\" data-target=\"#listeSpeednewModal\" onclick=\"getCampagneDetail('{$cid}')\" href='#' >$ret</a>" ;
            else :
                if($mediaID === 5):
                    $file = "medias".DIRECTORY_SEPARATOR."sms".DIRECTORY_SEPARATOR."SMS-$annonceurID.jpg" ;
                    $file1 = public_path($file) ;
                    if(file_exists($file1)):
                        $illustrator = '<a href="'.$file.'" target="_blank">
                            <img style="width: 90%; " class="img-responsive" src="' . asset($file) . '">
                        </a>'  ;
                    endif ;
                endif;
            endif ;
            $limagespmedia = '' ;
            if(array_key_exists($cid, $lesDocs)) :
                if(array_key_exists($mediaID, $lesDocs[$cid])) :
                    if(in_array($lesDocs[$cid][$mediaID]['type'], ['png', 'jpg', 'gif', 'GIF', 'PNG', 'JPG'])):
                        $imageDir = "";
                        $limagespmedia .= '<img style="width: 99%;" src="' . $imageDir . 'upload'.DIRECTORY_SEPARATOR.'campagnes'.DIRECTORY_SEPARATOR. $cid.DIRECTORY_SEPARATOR. $fichier .'" >' ;
                        $image =  $imageDir.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'campagnes' .DIRECTORY_SEPARATOR.$cid .DIRECTORY_SEPARATOR. $fichier ;
                    endif;
                endif;
            endif;

            $title = $detailDesCampagnes[$cid]['Titre'];
            $medianame = $paramName['media'][$mediaID];
            $secteurname =  $detailDesCampagnes[$cid]['Secteur'] ;
            $raisonsociale = $detailDesCampagnes[$cid]['Annonceur'] ;
            $route = route('reporting.detailCampagne',encrypt($cid)); //route('client.detail', $cid);
            $speednewTbody .= view ("clients.speednews.speedNewTbody",compact ('leSpeednews','route','title','medianame','raisonsociale','secteurname','leSupport','illustrator'))->render ();
        endforeach;
        return $speednewTbody ;
        //return view ("clients.speednews.tableauSpeednews",compact ('speednewTbody'))->render ();
    }

    public static function get2ndKeyInTab(array $data){
        $key = [] ;
        foreach($data AS $k => $r):
            if(array_sum($r)):
                foreach($r AS $n => $t):
                    if(!in_array($n, $key)):
                        $key[] = $n ;
                    endif;
                endforeach;
            endif;
        endforeach;
        return $key ;
    }
    
    public function detailSpeednews(Request $request){
        $cid = $request->input('cid');
        //dd($cid) ;
        $res = Session::has("detailDesCampagnes.$cid") ? Session::get("detailDesCampagnes.$cid") : [];
        $view = "";
        if(!empty($res)):
            $resfirst = Session::has("lesSppeedNews.$cid") ? Session::get("lesSppeedNews.$cid")[0] : [];
            $docCamp = '';
            if ($request->session()->has('docCampagnes') && array_key_exists($cid,$request->session()->get('docCampagnes'))):
                $docCamp = ClientFunctionController::showDocCampagne($cid);
            endif;
            //$view = view('clients.campagnes.detail', compact('resfirst','res','docCamp'))->render();
            $view = view('clients.campagnes.detail', compact('resfirst','res','docCamp', 'cid'))->render();
        endif;
        return response()->json([
            'detail' => $view
        ]);
        
    }
    
    public function detailSpeednewsNew(Request $request){
        $cid = $request->input('cid');
        $res = Session::get('detailDesCampagnes') ; ;
        $view = "";
        if(!empty($res)):
            $re = $res[0];
            $sqlfirst = "SELECT 
                            sp.dateajout, sp.support, sp.media, m.name as mname, sp.dateajout, sp.datefin 
                        FROM 
                            ".DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db')." sp, 
                            ".DbTablesHelper::dbTable('DBTBL_MEDIAS','db')." m
                        WHERE sp.campagnetitle = $cid  AND sp.media = m.id";
            $resfirsts = FunctionController::arraySqlResult($sqlfirst);
            $resfirst = [];
            if(count($resfirsts) === 1):
                $resfirst = $resfirsts[0];
            endif;
            $docCamp = '';
            if ($request->session()->has('docCampagnes') && array_key_exists($cid,$request->session()->get('docCampagnes'))):
                $docCamp = ClientFunctionController::showDocCampagne($cid);
            endif;
            $view = view('clients.campagnes.detail', compact('resfirst','re','docCamp'))->render();
        endif;
        return response()->json([
            'detail' => $view
        ]);
        
    }
    
    
    public function getReportDatas(Request $request){
        $action = $request->action;
        $data = $request->all ();
        if (!$request->session ()->has ("formvar")):
            $request->session ()->put ("formvar",[]);
        endif;
        if (!$request->session ()->has ("formvar.date")):
            $request->session ()->put ("formvar.date",[]);
        endif;

        if ($action == "date"):
            $param = $data['param'];
            $valeur = $data['valeur'];
            $request->session()->put('formvar.date.'.$param.'', $valeur);
        endif;
        $request->session ()->save ();
    }
    public static function getListeCampagnes(){
        $campagneArray = serialize($lescampagneDetail);
        if (count($lescampagneDetail)):
            Session::put('selection.lescampagneDetail', $campagneArray);
            return view ("clients.campagnes.listeDesCampagnes", compact ('lescampagneDetail'))->render ();
        endif;
    }
    public function chercherDonnees(Request $request){
        $obj = $request->donnee;
        $var = $request->var;
        if ($obj == "secteurs"):
            $sessionName = "secteur";
            $table = DbTablesHelper::dbTable ('DBTBL_SECTEURS','db');
            $chp = "secteur";
            $tableFille = DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db');
            $vue = "listeAnnonceurs";
            $suivant = "annonceur";
        endif;
        if ($obj == "medias"):
            $sessionName = "media";
            $table = DbTablesHelper::dbTable ('DBTBL_MEDIAS','db');
            $chp = "media";
            $tableFille = DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db');
            $vue = "listeSupports";
            $suivant = "support";
        endif;
        if ($obj == "supports"):
            $sessionName = "support";
            $table = DbTablesHelper::dbTable ('DBTBL_MEDIAS','db');
            $chp = "media";
            $tableFille = DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db');
            $vue = "listeSupports";
            $suivant = "support";
        endif;

        if ($obj == "formats"):
            $sessionName = "format";
            $table = DbTablesHelper::dbTable ('DBTBL_MEDIAS','db');
            $chp = "media";
            $tableFille = DbTablesHelper::dbTable ('DBTBL_FORMATS','db');
            $vue = "listeFormats";
            $suivant = "format";
            //dd ($obj,$sessionName,$var);
        endif;
        if (!$request->session ()->has ("formvar")):
            $request->session ()->put ("formvar",[]);
        endif;

        if ($request->val == "true"):
            if (!$request->session ()->has ("formvar.$sessionName")):
                $request->session ()->put ("formvar.$sessionName",[]);
            endif;
            if ($request->var != "all"):
                if (!$request->session ()->has ("formvar.$sessionName.$var")):
                    $request->session ()->put ("formvar.$sessionName.$var",$var);
                    $request->session ()->save ();
                endif;
            else:
                $condition = "";
                if ($obj == "supports" or $obj == "formats"):
                    $table = $tableFille;
                    $listeDonnees =  $request->session ()->get ("formvar.media");
                    $condition = " WHERE $chp IN (".join (',',$listeDonnees).") ";
                endif;

                $donnees = FunctionController::arraySqlResult ("SELECT id FROM $table  $condition ORDER BY name ASC");
                $tab = [];
                foreach ($donnees as $r):
                    $tab[$r['id']] = $r['id'];
                endforeach;
                $request->session ()->put ("formvar.$sessionName", $tab);
                $request->session ()->save ();
            endif;
        else:
            if ($request->var != "all"):
                if ($obj == "secteurs"):
                    $lie = 'annonceur';
                    $condLie = !empty($request->session ()->get ("formvar.$lie")) ? " AND id IN (".join (',',$request->session ()->get ("formvar.$lie")).") " : "";
                    $dLies = FunctionController::arraySqlResult ("SELECT * FROM $tableFille WHERE $chp = $var $condLie ORDER BY name ASC");
                    foreach ($dLies as $r):
                        $request->session ()->forget ("formvar.$lie.{$r['id']}");
                    endforeach;
                endif;
                $request->session ()->forget ("formvar.$sessionName.$var");
            else:
                $request->session ()->forget ("formvar.$sessionName");
            endif;
        endif;
        $request->session ()->save ();
        $listeSuivant = [];
        $listeDonnees = [];
        $datas = [];
        if ($request->session ()->has ("formvar.$sessionName")):
            if ($obj == "supports" or $obj == "formats"):
                $sessionName = "media";
            endif;
            $listeDonnees =  $request->session ()->get ("formvar.$sessionName");
            $listeSuivant = $request->session ()->get ("formvar.$suivant");
            $condition = " WHERE $chp IN (".join (',',$listeDonnees).") ";
            $sql = "SELECT * FROM $tableFille $condition  ORDER BY name ASC";
            $datas = FunctionController::arraySqlResult ($sql);
        else:
            if ($obj == "supports" or $obj == "formats"):
                $sessionName = "media";
                $listeDonnees =  $request->session ()->get ("formvar.$sessionName");
                $condition = " WHERE $chp IN (".join (',',$listeDonnees).") ";
                $sql = "SELECT * FROM $tableFille $condition  ORDER BY name ASC";
                $datas = FunctionController::arraySqlResult ($sql);
            endif;
        endif;
        $nbrDatas = count ($datas);
        return view ("clients.formReporting.$vue", compact ('datas','nbrDatas','listeDonnees','listeSuivant'))->render ();
    }

    public function chercherListeCampagnes(Request $request){
        $val = $request->val;
        $annonceur = $request->annonceur;
        if($val == 'true'):
            if(!$request->session()->has('formvar.annonceur.'.$annonceur.'')):
                $request->session ()->put('formvar.annonceur.'.$annonceur.'', $annonceur);
            endif;
        else:
            $request->session ()->forget('formvar.annonceur.'.$annonceur.'');
        endif;
        $request->session ()->save ();
        if (count ($request->session ()->get ("formvar.annonceur"))):
            $donnees = $request->session ()->get ("formvar");
            return self::secteurCampagne($donnees);
        endif;
    }

    public static function secteurCampagne(array $donnees):string {

        $datedebuts = $donnees['date']['date_debut'];

        $datefins = $donnees['date']['date_fin'];

        $annonceur = $donnees['annonceur'];
        // dd ($annonceur,$datedebuts,$datefins);
        $annonceurlist = count($annonceur) >= 1 ? '(' . join(',', $annonceur) . ')' : '';
        $conditionannonceur = $annonceurlist != '' ? " AND annonceur IN " . $annonceurlist : '' ;
        $listedescampagnes = [];

        $sqlconddate = " SELECT DISTINCT campagnetitle FROM
            ". DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db') . " 
        WHERE '$datedebuts' BETWEEN dateajout AND datefin XOR dateajout BETWEEN '$datedebuts' AND '$datefins'";
        $larequete = "SELECT DISTINCT  ct.id  , ct.title  FROM 
                 " .DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db')  . " c, 
                 " .DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db')  . " ct,
                 " .DbTablesHelper::dbTable('DBTBL_OPERATIONS','db')  . " o, 
                 " .DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db') . " so 
            WHERE 
            ct.id IN ($sqlconddate) AND  
            c.campagnetitle = ct.id AND
            ct.operation = o.id AND 
            o.annonceur = so.id  $conditionannonceur";

        $resultatrequete = FunctionController::arraySqlResult($larequete);

        foreach ($resultatrequete as $field) :
            $listedescampagnes[] = $field ;
        endforeach;
        $nbrDatas = count ($listedescampagnes);
        return view ("clients.formReporting.listeCampagnes", compact ('listedescampagnes','nbrDatas'))->render ();
    }

    public function chercherDonneesMediaSupport(Request $request){
        $var = $request->var;
        $table = DbTablesHelper::dbTable ('DBTBL_MEDIAS','db');
        $chp = "media";
        $tableFille1 = DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db');
        $tableFille2 = DbTablesHelper::dbTable ('DBTBL_FORMATS','db');
        if (!$request->session ()->has ("formvar")):
            $request->session ()->put ("formvar",[]);
        endif;

        if ($request->val == "true"):
            if (!$request->session ()->has ("formvar.media")):
                $request->session ()->put ("formvar.media",[]);
            endif;
            if ($request->var != "all"):
                if (!$request->session ()->has ("formvar.media.$var")):
                    $request->session ()->put ("formvar.media.$var",$var);
                    $request->session ()->save ();
                endif;
            else:
                $donnees = FunctionController::arraySqlResult ("SELECT id FROM $table  ORDER BY name ASC");
                $tab = [];
                foreach ($donnees as $r):
                    $tab[$r['id']] = $r['id'];
                endforeach;
                $request->session ()->put ("formvar.media", $tab);
                $request->session ()->save ();
            endif;
        else:
            if ($request->var != "all"):
                $condSuppLie = !empty($request->session ()->get ("formvar.support")) ? " AND id IN (".join (',',$request->session ()->get ("formvar.support")).") " : "";
                $condFormLie = !empty($request->session ()->get ("formvar.format")) ? " AND id IN (".join (',',$request->session ()->get ("formvar.format")).") " : "";
                $supportLies = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." WHERE $chp = $var $condSuppLie ORDER BY name ASC");
                $formatLies = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_FORMATS','db')." WHERE $chp = $var $condFormLie ORDER BY name ASC");
                $request->session ()->forget ("formvar.media.$var");
                foreach ($supportLies as $r):
                    $request->session ()->forget ("formvar.support.{$r['id']}");
                endforeach;
                foreach ($formatLies as $r):
                    $request->session ()->forget ("formvar.format.{$r['id']}");
                endforeach;
            else:
                $request->session ()->forget ("formvar.media");
                $request->session ()->forget ("formvar.support");
                $request->session ()->forget ("formvar.format");
            endif;
        endif;
        $request->session ()->save ();
        $supports = [];
        $formats = [];
        $supportsSession = [];
        $formatsSession = [];
        $listeDonnees = [];
        if ($request->session ()->has ("formvar.media")):
            if (count ($request->session ()->get ("formvar.media"))):
                $listeDonnees =  $request->session ()->get ("formvar.media");
                $formatsSession = !is_null ($request->session ()->get ("formvar.format")) ? $request->session ()->get ("formvar.format") : $formatsSession;
                $supportsSession = !is_null ($request->session ()->get ("formvar.support")) ? $request->session ()->get ("formvar.support") : $supportsSession;
                $condition = " WHERE $chp IN (".join (',',$listeDonnees).") ";
                $sql1 = "SELECT * FROM $tableFille1 $condition  ORDER BY name ASC";
                $sql2 = "SELECT * FROM $tableFille2 $condition  ORDER BY name ASC";
                $supports = FunctionController::arraySqlResult ($sql1);
                $formats = FunctionController::arraySqlResult ($sql2);
            endif;
        endif;
        $nbrSupports = count ($supports);
        $nbrFormats = count ($formats);
        return view ("clients.formReporting.listeSupportsFormats", compact ('supports','formats','listeDonnees','formatsSession','supportsSession','nbrFormats','nbrSupports'))->render ();
    }

    public function selection(){
        $pos = $this->data;
        $dateDebut = $pos['date_debut'];
        $dateFin = $pos['date_fin'];
        $laselection['Periode'] = "Du " .FunctionController::date2Fr ($dateDebut). "  
          au  " . FunctionController::date2Fr ($dateFin);
        /**
         *
         * Construction des variables sélectionnées
         */
        $tabselection = [
            'secteur' => ['dbtblname' => DbTablesHelper::dbTable('DBTBL_SECTEURS','db'), 'dbtblcolname' => 'name'],
            'annonceur' => ['dbtblname' =>DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db'), 'dbtblcolname' => 'name'],
            'campagne' => ['dbtblname' => DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db'), 'dbtblcolname' => 'title'],
            'nature' => ['dbtblname' =>DbTablesHelper::dbTable('DBTBL_NATURES','db'), 'dbtblcolname' => 'name'],
            'media' => ['dbtblname' => DbTablesHelper::dbTable('DBTBL_MEDIAS','db'), 'dbtblcolname' => 'name'],
            'support' => ['dbtblname' => DbTablesHelper::dbTable('DBTBL_SUPPORTS','db'), 'dbtblcolname' => 'name'],
            'format' => ['dbtblname' => DbTablesHelper::dbTable('DBTBL_FORMATS','db'), 'dbtblcolname' => 'name'],
            'couverture' => ['dbtblname' => DbTablesHelper::dbTable('DBTBL_COUVERTURES','db'), 'dbtblcolname' => 'name'],
            'localite' => ['dbtblname' => DbTablesHelper::dbTable('DBTBL_LOCALITES','db'), 'dbtblcolname' => 'name']
        ] ;
        foreach ($tabselection as $key => $rowselect) :
            if(array_key_exists($key, $pos) && count($pos[$key]) >= 1):
                $laselection[$key] = $this->makeselectdata($key, $rowselect['dbtblname'], $rowselect['dbtblcolname']) ;
            endif;
        endforeach;
        return $laselection;
    }


    public  function makeselectdata(string $inputname, string $dbtblname, string $dbtblcolname = 'name', string $separate = ', '):string {
        $lesdonnees = count($this->data[$inputname]) >= 1 ? join(',', $this->data[$inputname]) : '';
        $sql = "SELECT $dbtblcolname FROM $dbtblname WHERE id IN ($lesdonnees) ORDER BY id";
        $redonnees = FunctionController::arraySqlResult($sql);
        $donneeselectionnee = array();
        $donnee = '';
        foreach ($redonnees as $rowdonnee) :
            $donneeselectionnee[] = $rowdonnee[$dbtblcolname];
        endforeach;
        $separate = $inputname == 'campagne' ? '<br>' : $separate;
        if (count($donneeselectionnee) >= 1):
            $donnee = join($separate, $donneeselectionnee);
        endif;
        return $donnee;
    }


    private function makeListeCampagne($lescampagneDetail){
        $campagneArray = serialize($lescampagneDetail);
        if (count($lescampagneDetail)):
            Session::put('selection.lescampagneDetail', $campagneArray);
            return view ("clients.campagnes.listeDesCampagnes", compact ('lescampagneDetail'))->render ();
        endif;
    }

    public static function makeRapports(){
        $sqlrapp = "SELECT r.*, se.name as secteurname, pe.name as periodicitename 
           FROM 
                ".DbTablesHelper::dbTable('DBTBL_RAPPORTS','db')." r, 
                ".DbTablesHelper::dbTable('DBTBL_SECTEURS','db'). " se, 
                ".DbTablesHelper::dbTable('DBTBL_PERIODICITES','db')." pe 
           WHERE r.secteur = se.id AND r.periodicite = pe.id
           ORDER BY r.dateajout ASC" ;
        $lesrapports = FunctionController::arraySqlResult($sqlrapp);
        return view ("clients.rapports.listeDesRapports",compact ('lesrapports'))->render ();
    }

    private function makeTableaux($dataTableaux)
    {
        if(!empty($dataTableaux['topNDesCampagnes'])):
            $tableau = "";
            $tableau .= $this->makeTableauInsertInvestMed($dataTableaux);
            $tableau .= $this->makeTableauOffreTel($dataTableaux);
            $tableau .= $this->makeTableauNature($dataTableaux);
            $tableau .= $this->makeTableauCampagne($dataTableaux['topNDesCampagnes'],$dataTableaux['lescampagneDetail']);
        else:
            $tableau = <<<TBL
            <div class="col-xs-12 bordered">
               <h4>Votre sélection n'a retourné aucune donnée</h4>
            </div>
TBL;
        endif;
        return $tableau;
    }

    public static function icone(string $ext):string {
        switch ($ext):
            case 'docx' :
            case 'doc' :
                $n = '-word' ;
                break ;
            case 'xlsx' :
            case 'xls' :
                $n = '-excel' ;
                break ;
            case 'pptx' :
            case 'ppt' :
                $n = '-powerpoint' ;
                break ;
            case 'zip' :
            case 'rar' :
                $n = '-archive' ;
                break ;
            case 'gif' :
            case 'jpg' :
            case 'jpeg' :
            case 'png' :
                $n = '-image' ;
                break ;
            case 'mp3' :
            case 'wav' :
                $n = '-audio' ;
                break ;
            case 'pdf' :
                $n = '-pdf' ;
                break ;
            default :
                $n = "";
        endswitch;
        return $n ;
    }

    public function makeTableauInsertInvestMed($dataTableaux){
        $laListeDesMedias = [];
        foreach ($dataTableaux['partDeVoixParMedia'] as $key => $row) :
            $laListeDesMedias[] = $key;
        endforeach;
        return view ("clients.tableaux.tableauInsertInvestMed",compact ('laListeDesMedias','dataTableaux'))->render ();
    }

    public function makeTableauNature($dataTableaux){
        $titreNature = "Investissement des annonceurs par nature";
        $llaaaN = new TableauController($titreNature, $dataTableaux['listNature'], $dataTableaux['investParNatureParAnnonceur'], $dataTableaux['investParNature']);
        $idapn = $llaaaN->gabarit;
        $tableau = <<<TBL
                <div class="col-xs-12 bordered">
                        $idapn
                </div>
TBL;
        return $tableau;
    }

    public function makeTableauOffreTel($dataTableaux){
        $titre = "Investissement des annonceurs par offre telecom";
        $llaaaC = new TableauController($titre,$dataTableaux['listOffretelecom'], $dataTableaux['investParOffretelecomParAnnonceur'], $dataTableaux['investParOffretelecom']);
        $idapot = $llaaaC->gabarit;
        $tableau = <<<TBL
                <div class="col-xs-12 bordered">
                        $idapot
                </div>
TBL;
        return $tableau;
    }

    public function makeTableauCampagne($topNDesCampagnes,$lescampagneDetail)
    {
        $incTop = 0;
        $textHtmlTR = view ("clients.tableaux.textHtmlTR",compact ('topNDesCampagnes','lescampagneDetail','incTop'))->render ();
        return view ("clients.tableaux.tableauTopCampagne", compact ('textHtmlTR'))->render ();
    }

    public static function numberDisplayer(int $number) {
        $ret = $number == 0 ? "-" : number_format($number, 0, ',', ' ');
        return $ret;
    }

    public function detailSpeednewsold(Request $request){
        $cid = $request->input('cid');
        $sql = "SELECT 
            ct.title, o.name as opname, so.raisonsociale as aname, se.name as secname
        FROM 
            ".DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db')." ct, 
            ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','db')." o,
            ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')." so, 
            ".DbTablesHelper::dbTable('DBTBL_SECTEURS','db')." se 
        WHERE 
            ct.id = $cid AND ct.operation = o.id AND o.annonceur = so.id AND so.secteur = se.id ";
        $res = FunctionController::arraySqlResult($sql) ;
        $slider = "" ;
        $view = "";
        if(!empty($res)):
            $re = $res[0];
            $docsql = "SELECT 
                d.*, m.name as medianame 
              FROM " .DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','db'). " d,  " .DbTablesHelper::dbTable('DBTBL_MEDIAS','db'). " m  
              WHERE d.campagnetitle = $cid AND d.media = m.id";
            $resdoc = FunctionController::arraySqlResult($docsql);
            //dd($resdoc);
            $sqlfirst = "SELECT 
                            sp.dateajout, sp.support, sp.media, m.name as mname, sp.dateajout, sp.datefin 
                        FROM 
                            ".DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db')." sp, 
                            ".DbTablesHelper::dbTable('DBTBL_MEDIAS','db')." m
                        WHERE sp.campagnetitle = $cid  AND sp.media = m.id";
            $resfirsts = FunctionController::arraySqlResult($sqlfirst);
            if(count($resfirsts) === 1):
                $resfirst = $resfirsts[0];
                $view = view('clients.campagnes.detail', compact('resfirst','re','slider'))->render();
            endif;
        endif;
        return response()->json([
            'detail' => $view
        ]);
    }

    public function getReportFormDatas(Request $request){
        $date = $request->donnee;
        $key = $request->key;
        if (!is_null ($date)):
            $request->session ()->put ("formvar.date.$key",$date);
        endif;
       $request->session ()->save ();
    }
}
