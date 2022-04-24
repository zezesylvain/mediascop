<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Reporting;
use App\Models\Rapport;
use App\Models\Secteur;
use App\Models\Media;
use App\Models\Speednew;
use App\Models\Campagnetitle;

class DataController extends Controller
{
    public static $param = [
                            'format' => ['k' => 'name', 'chp' => 'format', 'libelle' => 'Format'], 
                            'typecom' => ['k' => 'name', 'chp' => 'typecom_id', 'libelle' => 'Type de Comm'], 
                            'typeservice' => ['k' => 'name', 'chp' => 'typeservice_id', 'libelle' => 'Type de service'], 
                            'annonceur' => ['k' => 'raisonsociale', 'chp' => 'annonceur', 'libelle' => 'Raison sociale'], 
                            'media' => ['k' => 'name', 'chp' => 'media', 'libelle' => 'Media'], 
                            'support' => ['k' => 'name', 'chp' => 'support', 'libelle' => 'Support'], 
                            'cible' => ['k' => 'name', 'chp' => 'cible', 'libelle' => 'Cible'], 
                            'secteur' => ['k' => 'name', 'chp' => 'secteur', 'libelle' => 'Secteur']
                            ] ;
    public static $secteur = [1]; //Secteur par defaut = 1 = TELECOM
    public static $annonceur = []; //Annonceurs par defaut Vide donc tous les annonceurs de TELECOM
    public static $media = []; //Medias par defaut = Vide donc TOUS
    public static $support = []; //Supports par defaut = VIDE donc TOUS
    
    public function home(){
        
        $date_debut = date ('Y-m-d', mktime(0,0,0,date("n"), date("j")-30, date('Y')))  ; // 30 jours en arriere
        $date_fin = date ('Y-m-d');
        $secteur = self::$secteur;
        $annonceur = self::$annonceur;
        $media = self::$media;
        $support = self::$support;
        return self::built(compact('date_debut', 'date_fin', 'secteur', 'annonceur', 'annonceur', 'support')) ;
    }
    
    public static function built($donnees){
        $hauteur = 400;
        $hauteur2 = 500;
        extract($donnees);
        $requete = Reporting::with('secteur:id,name', 'annonceur:id,raisonsociale,couleur', 
                                    'media:id,name', 'support:id,name', 'format:id,name', 'cible:id,name', 
                                    'campagnetitle:id,title', 'operation:id,name', 'typecom:id,name','typeservice:id,name')
                            ->whereBetween('date', [$date_debut, $date_fin]);
        if(!empty($secteur)):
            $requete = $requete->whereIn('secteur', $secteur);
        endif;
        if(!empty($annonceur)):
            $requete = $requete->whereIn('annonceur', $annonceur);
        endif;
        if(!empty($media)):
            $requete = $requete->whereIn('media', $media);
        endif;
        if(!empty($support)):
            $requete = $requete->whereIn('support', $support);
        endif;
        $data = $requete->orderBy('date', 'ASC')->get()->toArray();
        $lesSpeednews = self::getSpeednews();
        $lesDonnees = self::makeReportingData($data);
        $route = route('client.formvalider');
        $secteurs = Secteur::orderBy('id')->get()->toArray();
        $medias = Media::orderBy('id')->get()->toArray();
        $data = compact('lesDonnees','route','date_fin','date_debut', 'hauteur', 
                        'hauteur2', 'lesSpeednews', 'secteurs', 'medias') ;
        return view('clients-v2.index', $data) ;
    }
    public static function formReport(Request $request){
        $data = $request->all() ;
        return self::built($data) ;
    }


    public static function makeReportingData(array $donnees):array{
        $param = self::$param ;
        $tableaux = [] ;
        foreach($param AS $k => $v):
            $var = "les".ucfirst($k)."s" ;
            $investVar = "investPar".ucfirst($k);
            $insertVar = "partDeVoixPar".ucfirst($k);
            $$var = [] ;
            $$investVar = [] ;
            $$insertVar = [] ;
            $tableaux[$k] = [] ;
        endforeach;  
        $listeTableaux = [
                'lesDate' , 'dateMois' , 'detailDesCampagnes', 'planMedia', 'lesInvestParAnnonceurs',
                'investParMediaEtParSupport', 'investParMediaEtParAnnonceur','partDeVoixParAnnonceurEtParMedia', 
                'listDesCouleursDesAnnonceur', 'partDeVoixParMediaEtParAnnonceur', 'investParSecteurEtParAnnonceur'
            ] ;
        foreach($listeTableaux AS $it):
            $$it = [] ;
        endforeach;
        $listeSpeciale = ['typeservice', 'typecom', 'media', 'format', 'cible'] ;
        foreach($listeSpeciale AS $it):
            $lesInvestParAnnonceurs[$it] = [] ;
            $parAnnonceur[$it] = [] ;
        endforeach;
        foreach($donnees AS $row):
            extract($row);
            $tarif =  $investissement + $tarif ;
            $insertion = $media['id'] == 61 ? $nombre : 1 ;
            $raisonsociale = $annonceur['raisonsociale'] ;
            foreach($param AS $k => $v):
                if(is_array($$k)):
                    $var = "les".ucfirst($k)."s" ;
                    $investVar = "investPar".ucfirst($k);
                    $insertVar = "partDeVoixPar".ucfirst($k);
                    self::pushInArray($$var, $$k[$v['k']]) ;
                    self::incrementValueInTripleArray($tableaux, $k, $$k[$v['k']], $tarif, $insertion) ;
                    self::incrementValueInArray($$investVar,  $$k[$v['k']], $tarif) ;
                    self::incrementValueInArray($$insertVar,  $$k[$v['k']], $insertion) ;
                    if(in_array($k, $listeSpeciale)):
                        self::incrementValueInTripleArray($parAnnonceur[$k], $raisonsociale, $$k[$v['k']], $tarif, $insertion) ;
                        self::incrementValueInMultiArray($lesInvestParAnnonceurs[$k], $raisonsociale, $$k[$v['k']], $tarif, $insertion) ;
                    endif;
                endif;
            endforeach; 
            self::pushInArray($lesDate, $date) ;
            self::makeDateTab($dateMois, $date) ;
            self::pushKeyValueInArray($listDesCouleursDesAnnonceur, $raisonsociale, $annonceur['couleur']);
            self::incrementValueInMultiArray($investParMediaEtParAnnonceur, $media['name'], $raisonsociale, $tarif) ;
            self::incrementValueInMultiArray($partDeVoixParMediaEtParAnnonceur, $media['name'], $raisonsociale, $insertion) ;
            self::incrementValueInMultiArray($investParMediaEtParSupport, $media['name'], $support['name'], $tarif) ;
            self::incrementValueInArray($investParAnnonceur, $raisonsociale, $tarif) ;
            self::incrementValueInArray($partDeVoixParAnnonceur, $raisonsociale, $insertion) ;
            self::makePlanMedia($planMedia, $raisonsociale, $media['name'], $date, $insertion, $tarif) ;
            self::detailCampagne($detailDesCampagnes, $row);
        endforeach;
        $data = [];
        foreach($param AS $k => $v):
            $var = "les".ucfirst($k)."s" ;
            $investVar = "investPar".ucfirst($k);
            $insertVar = "partDeVoixPar".ucfirst($k);
            $data[$investVar] = $$investVar;
            $data[$insertVar] = $$insertVar;
            $data[$var] = $$var;
            $data[$k] = $tableaux[$k];
        endforeach; 
        $data['parametres'] = self::$param ;
        $data['parAnnonceur'] = $parAnnonceur;
        $data['investParAnnonceurs'] = $lesInvestParAnnonceurs;
        $data['lesDate'] = $lesDate;
        $data['dateMois'] = $dateMois;
        $data['planMedia'] = $planMedia;
        $data['listDesCouleursDesAnnonceur'] = $listDesCouleursDesAnnonceur;
        
        $data['investParMediaEtParAnnonceur'] = $investParMediaEtParAnnonceur;
        $data['partDeVoixParMediaEtParAnnonceur'] = $partDeVoixParMediaEtParAnnonceur;
        $data['investParMediaEtParSupport'] = $investParMediaEtParSupport;
        $data['detailDesCampagnes'] = $detailDesCampagnes;
        //dd($investParMedia, $investParFormat, $investParTypeservice, $investParMediaEtParAnnonceur, $planMedia);
        /*
        foreach($param AS $v):
            $var = "les".ucfirst($v) ;
            $parItem[$v] = $$var ;
        endforeach ;
        $speedNewsElt['campagnetitle'] = array_keys($detailDesCampagnes) ;
        $speednewTbody = self::makeSpeednewsTable($speedNewsElt, $detailDesCampagnes) ;
        $lesDocCampagnes = self::getAllDocCampagne($speedNewsElt) ;
        Session::put('docCampagnes', $lesDocCampagnes) ;
        Session::put('detailDesCampagnes', $detailDesCampagnes) ;
        $parItem['date'] = $lesDate ;
        $parItem['dateMois'] = $dateMois ;
        //*/
        return $data ;
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
    public static function pushKeyValueInArray(&$tab, $key, $val, $unique = true) {
        if ($unique) :
            if (!array_key_exists($key, $tab)):
                $tab[$key] = $val;
            endif;
        else:
            $tab[$key] = $val;
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
    public static function makeDateTab(&$tab, $date){
        $t = explode('-', $date) ;
        $mois = date("M-Y", mktime(0,0,0,$t[1],$t[2], $t[0])) ;
        if(!array_key_exists($mois, $tab)):
            $tab[$mois][] = $t[2] ;
        elseif(!in_array($t[2], $tab[$mois])):
            $tab[$mois][] = $t[2] ;
        endif;
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
    
    
    public static function detailCampagne(&$detailDesCampagnes, $row){
        $cid = $row['campagnetitle']['id'] ;
        if (!array_key_exists($cid, $detailDesCampagnes)) :
            $detailDesCampagnes[$cid] = [
                'tid' => $cid, 'Secteur' => $row['secteur']['name'],'Annonceur' => $row['annonceur']['raisonsociale'], 
                'Titre' => $row['campagnetitle']['title'] ,
                'Date' => [$row['date']],
                'media' => [$row['media']['name']]
            ];
        else :
            self::pushInArray($detailDesCampagnes[$cid]['Date'], $row['date']);
            self::pushInArray($detailDesCampagnes[$cid]['media'], $row['media']['name']);
        endif;
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

    public static function getSpeednews(){
        $dat = Speednew::with(['media:id,name,code', 'support:id,name', 'campagnetitle.docampagnes'])
                        ->with('campagnetitle.operation.annonceur:id,raisonsociale')
                        ->orderBy('dateajout', 'DESC')->get()->toArray();
        $data = [];
        foreach($dat AS $r):
            $media_id = $r['media']['id'];
            $campagnetitle_id = $r['campagnetitle']['id'];
            $docs = $r['campagnetitle']['docampagnes'];
            $t = [
                    'date' => $r['dateajout'],
                    'campagnetitle_id' => $campagnetitle_id,
                    'media_id' => $media_id,
                    'media' => $r['media']['name'],
                    'support' => $r['support']['name'],
                    'campagne' => $r['campagnetitle']['title'],
                    'annonceur' => $r['campagnetitle']['operation']['annonceur']['raisonsociale'] ?? "-",
                    'url' => $r['campagnetitle']['id'],
                ];
            $dc = [];
            foreach($docs AS $d):
                $dc[$d['media']] = $d['fichier'];
            endforeach;
            $t['fichier'] = $dc[$media_id] ?? '';
            $data[] = $t;
        endforeach;
        return $data;
    }
    
    public function getReportFormDatas(Request $request){
        $date = $request->donnee;
        $key = $request->key;
        if (!is_null ($date)):
            $request->session ()->put ("formvar.date.$key",$date);
        endif;
       $request->session ()->save ();
    }

    public function chercherDonneesMediaSupport(Request $request){
        $var = $request->var;
        $table = DbTablesHelper::dbTable ('DBTBL_MEDIAS','db');
        $chp = "media";
        $tableFille1 = DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db');
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
                $supportLies = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." WHERE $chp = $var $condSuppLie ORDER BY name ASC");
                $request->session ()->forget ("formvar.media.$var");
                foreach ($supportLies as $r):
                    $request->session ()->forget ("formvar.support.{$r['id']}");
                endforeach;
            else:
                $request->session ()->forget ("formvar.media");
                $request->session ()->forget ("formvar.support");
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
                $supportsSession = !is_null ($request->session ()->get ("formvar.support")) ? $request->session ()->get ("formvar.support") : $supportsSession;
                $condition = " WHERE $chp IN (".join (',',$listeDonnees).") ";
                $sql1 = "SELECT * FROM $tableFille1 $condition  ORDER BY name ASC";
                $supports = FunctionController::arraySqlResult ($sql1);
            endif;
        endif;
        $nbrSupports = count ($supports);
        return view ("clients-v2.formReporting.listeSupportsFormats", compact ('supports','listeDonnees','supportsSession','nbrSupports'))->render ();
    }

    public function chercherAnnonceursBySecteur(Request $request){
        $var = $request->var;
            $sessionName = "secteur";
            $table = DbTablesHelper::dbTable ('DBTBL_SECTEURS','db');
            $chp = "secteur";
            $tableFille = DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db');

        if (!$request->session ()->has ("formvar")):
            $request->session ()->put ("formvar",[]);
        elseif (!$request->session ()->has ("formvar.secteur")):
                $request->session ()->put ("formvar.secteur",[]);
        endif;
        $datas = [];
        if ($request->val == "true"):
            if ($request->var != "all"):
                $request->session ()->put ("formvar.secteur.$var",$var);
            else:
                $lesSect = FunctionController::arraySqlResult ("SELECT id FROM $table");
                foreach ($lesSect AS $r):
                    $request->session ()->put ("formvar.secteur.$r[id]",$r["id"]);
                endforeach;
            endif;
        else:
            if ($request->var != "all"):
                $request->session ()->forget ("formvar.secteur.$var");
            else:
                $request->session ()->put ("formvar.secteur",[]);
                $nbrDatas = 0;
            endif;
        endif;
        $request->session ()->save ();
        if(!empty($request->session ()->get ("formvar.section"))):
            $query = "SELECT * FROM $tableFille WHERE   secteur IN (".join (',',$request->session ()->get ("formvar.secteur")).") " ;
            $datas = FunctionController::arraySqlResult ($query);
        endif;
        return view ("clients-v2.formReporting.listeAnnonceurs", compact ('datas'))->render ();
    }
    
    public function chercherSupportsByMedia(Request $request){
        $var = $request->var;
        $sessionName = "secteur";
        $table = DbTablesHelper::dbTable ('DBTBL_SECTEURS','db');
        $chp = "secteur";
        $tableFille = DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db');

        if (!$request->session ()->has ("formvar")):
            $request->session ()->put ("formvar",[]);
        elseif (!$request->session ()->has ("formvar.secteur")):
                $request->session ()->put ("formvar.secteur",[]);
        endif;
        $datas = [];
        if ($request->val == "true"):
            if ($request->var != "all"):
                $request->session ()->put ("formvar.secteur.$var",$var);
            else:
                $lesSect = FunctionController::arraySqlResult ("SELECT id FROM $table");
                foreach ($lesSect AS $r):
                    $request->session ()->put ("formvar.secteur.$r[id]",$r["id"]);
                endforeach;
            endif;
        else:
            if ($request->var != "all"):
                $request->session ()->forget ("formvar.secteur.$var");
            else:
                $request->session ()->put ("formvar.secteur",[]);
                $nbrDatas = 0;
            endif;
        endif;
        $request->session ()->save ();
        if(!empty($request->session ()->get ("formvar.section"))):
            $query = "SELECT * FROM $tableFille WHERE   secteur IN (".join (',',$request->session ()->get ("formvar.secteur")).") " ;
            $datas = FunctionController::arraySqlResult ($query);
        endif;
        return view ("clients-v2.formReporting.listeAnnonceurs", compact ('datas'))->render ();
    }
    
}
