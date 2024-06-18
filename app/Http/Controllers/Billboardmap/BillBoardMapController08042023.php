<?php

namespace App\Http\Controllers\Billboardmap;

use Illuminate\Support\Str;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use App\Http\Controllers\core\ModuleController;
use App\Models\Campagnetitle;
use App\Models\Format;
use App\Models\Localite;
use App\Models\Secteur;
use App\Models\Reporting;
use App\Models\Annonceur;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class BillBoardMapController extends AdminController
{
    public $media = 6;
    public $recherche = [];
    public $listeDesPoints = "";

    public function home(){
        $this->nettoyerSession ("secteurComm");
        $this->nettoyerSession ("annonceurComm");
        $this->nettoyerSession ("regionLocalite");
        $this->nettoyerSession ("villeLocalite");
        $this->nettoyerSession ("communeLocalite");
        $dbLocalite = DbTablesHelper::dbTable ('DBTBL_LOCALITES','db');
        $secteurs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SECTEURS','db')." ORDER BY name ASC");
        $nbrsecteur = count ($secteurs);
        $villes = FunctionController::arraySqlResult ("SELECT * FROM $dbLocalite 
            WHERE parent IN (SELECT id FROM $dbLocalite WHERE parent = 0) 
            ORDER BY name ASC");
        $listeDesMap = [];
        $regions = [];
        $selection = '';
        if (Session::has('BillbordMapSelection')):
            $selection = Session::get('BillbordMapSelection.selection');
            $listeDesMap = Session::get('BillbordMapSelection.listeDesMap');
        endif;
        return view ("bbmap.index", compact ('secteurs','nbrsecteur','regions','villes','listeDesMap','selection'));
    }

    public function accueil(Request $request){
        self::getLocalitesDeAbidjan();
        //$this->nettoyerSession ("BillbordMapSelection");
        $this->nettoyerSession ("secteurComm");
        $this->nettoyerSession ("annonceurComm");
        $this->nettoyerSession ("villeLocalite");
        $this->nettoyerSession ("communeLocalite");
        $dateDebut = date ('Y-m-d', mktime(0,0,0,date("n"), date("j")-30, date('Y')))  ; // 30 jours en arriere
        $dateFin = date ('Y-m-d');
        $secteurs = Secteur::orderBy('name', 'ASC')->get()->toArray();
        $nbrsecteur = count ($secteurs);
        $formats = Format::where('media',$this->media)->orderBy('name', 'ASC')->get()->toArray();
        $nbrformat = count($formats);
        $villes = Localite::whereIn('parent', [0, 8])->orderBy('name', 'ASC')->get()->toArray();
        if(Session::has('BillbordMapSelection')):
            $selection = Session::get('BillbordMapSelection.selection');
            $listeDesMap = Session::get('BillbordMapSelection.listeDesMap');
        else:
            $datas = [
                    'secteur' => [1],
                    'dateFin' => $dateFin, 'dateDebut' =>$dateDebut,
                    'ville' => getToutesLocalites()
                ];
            //$this->makeDataEloq($datas, $request);
            $this->makeData($datas, $request);
            $selection = Session::get('BillbordMapSelection.selection');
            $listeDesMap = Session::get('BillbordMapSelection.listeDesMap');
        endif;
        return view ("bbmap.tplclient", compact ('dateDebut', 'dateFin', 'secteurs','nbrsecteur','villes','listeDesMap','selection','formats','nbrformat'));
    }

    /**
     * @param array $datas
     * @param Request $request
     */
    public  function makeData(array $datas, Request $request){
        if (!empty($datas)):
            $condition = $this->condition($datas);
            if ($condition !== ''):
                $pTable = DbTablesHelper::dbTable('DBTBL_REPORTINGS','db');
                $querie = "SELECT * FROM $pTable $condition";
                $donnees = FunctionController::arraySqlResult ($querie);
                //dd($querie);
                if (!empty($donnees)):
                    $listeDesMap = $this->makeMapDatas($donnees);
                    $this->listeDesPanneaux($listeDesMap);
                else:
                    $request->session()->flash('echec','Votre Sélection a retourné un resultat vide.');
                endif;
            else:
                $request->session()->flash('echec','La date de fin ne peut être inférieure à la date de début.</br>La sélection de la Localisation et de la communication est obligatoire. ');
            endif;
        else:
            $request->session()->flash('echec','Votre Sélection est Invalide.');
        endif;
    }


    public  function makeDataEloq(array $datas, Request $request){
        $donnees = [];
        if (!empty($datas)):
            $conditionElo = $this->conditionEloquent($datas);
            extract($conditionElo);
            $requete = Reporting::with('secteur:id,name', 'annonceur:id,raisonsociale,couleur,logo', 
                                    'media:id,name', 'support:id,name', 'format:id,name', 'cible:id,name', 
                                    'campagnetitle:id,title', 'operation:id,name', 'typecom:id,name','typeservice:id,name')
                            ->whereBetween('date', $condDates);
            $tab = ['format', 'secteur', 'localite', 'annonceur'];
            foreach($tab AS $md):
                $model = ucfirst($md);
                $v = "cond$model"."s";
                $lesD = $$v;
                //$model = str_replace('', 'cond', $v);
                $leModel = "App\\Models\\$model";
                $pv = "lesP$model";
                if(!empty($lesD)):
                    $requete = $requete->whereIn($md, $lesD);
                    //$$pv = $leModel::whereIn('id', $lesD)->get()->toArray();
                else:
                    $$pv = [];
                endif;               
            endforeach;
            $requete = $requete->whereMedia($this->media);
            $donnees = $requete->orderBy('date', 'ASC')->get()->toArray();
            if (!empty($donnees)):
                $listeDesMap = $this->makeMapDatasEloq($donnees);
                $this->listeDesPanneaux($listeDesMap);
            else:
                $request->session()->flash('echec','Votre Sélection a retourné un resultat vide.');
            endif;
        else:
            $request->session()->flash('echec','Votre Sélection est Invalide.');
        endif;
    }

    public function conditionEloquent(array $donnees): array
    {
        //dd($donnees);
        $cond = "";
        $secteurs = array_key_exists ('secteur',$donnees) ? $donnees['secteur'] : [];
        $formats = array_key_exists ('format',$donnees) ? $donnees['format'] : [];
        $annonceurs = array_key_exists ('annonceurs',$donnees) ? $donnees['annonceurs'] : [];
        $villes = array_key_exists ('ville',$donnees) ? $donnees['ville'] : [];
        $communes = array_key_exists ('commune',$donnees) ? $donnees['commune'] : [];
        $dateFin = $donnees['dateFin'];
        $dateDebut = $donnees['dateDebut'];
        $condDates = [];
        $condLocalites = [];
        $condSecteurs = [];
        $condAnnonceurs = [];
        $condFormats = [];
            if (!empty($secteurs)):
                if ($dateFin >= $dateDebut):
                    $condDates = [$dateDebut, $dateFin];
                    $this->recherche['Periode'] = [
                        'Debut' => FunctionController::date2Fr($dateDebut),
                        'Fin' => FunctionController::date2Fr($dateFin),
                    ];
                    if (!empty($villes)):
                        $villeEnSession = Session::get('villeLocalite');
                        $and = " and";
                        if (!empty($communes)):
                            $nbDeVille = count($villeEnSession);
                            foreach ($communes as $commune):
                                if (array_key_exists((int)$commune,self::getLocalitesDeAbidjan())):
                                    $villeEnSession[$commune] = (int)$commune;
                                endif;
                            endforeach;
                            if (count($villeEnSession) > $nbDeVille):
                                unset($villeEnSession[8]);
                            endif;
                            $condLocalites = $villeEnSession;
                            $this->recherche['Localite'] = $this->selection($villeEnSession,DbTablesHelper::dbTable('DBTBL_LOCALITES','db'));
                        else:
                            $villeEnSession = $villeEnSession !== null ? $villeEnSession : $villes;
                            $this->recherche['Localite'] = $this->selection($villeEnSession,DbTablesHelper::dbTable('DBTBL_LOCALITES','db'));
                        endif;
                        $condLocalites = $villeEnSession;
                    else:
                        Session::flash('echec','Veuillez choisir au moins une localité!');
                    endif;
                    $and = " and";
                    if (!empty($secteurs)):
                        if (!empty($annonceurs)):
                            $condAnnonceurs = $annonceurs;
                            $this->recherche['Annonceur'] = $this->selection($annonceurs,DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db'),'name');
                        else:
                            $condSecteurs = $secteurs;
                            $this->recherche['Secteur'] = $this->selection($secteurs, DbTablesHelper::dbTable('DBTBL_SECTEURS','db'),'name');
                        endif;
                        if (!empty($formats)):
                            $condFormats = $formats;
                            $this->recherche['Format'] = $this->selection($formats, DbTablesHelper::dbTable('DBTBL_FORMATS','db'),'name');
                        endif;
                    endif;
                else:
                    Session::flash('echec','La date de fin ne peut être inférieure à la date de début.');
                endif;
            else :
                Session::flash('echec','Veuillez choisir obligatoirement un secteur!');
                //return back();
            endif;
        return compact('condFormats', 'condSecteurs', 'condLocalites', 'condDates', 'condAnnonceurs');
    }
    public function makeMapDatasEloq(array $result): array
    {
        $investParLocalite = [];
        $investParAnnonceur = [];
        $insertParLocalite = [];
        $insertParAnnonceur = [];
        $listeDesPoints = '';
        $listeDesPointsNew = '';
        $virg = '';
        $Bigdata = '';
        $tableauDesMapNew = [];
        $lesCampagnes = [];
        $lesAnnonceurs = [];
        $dataRange = [];
        //dd($result);
        foreach($result as $row) :
            $lesAnnonceurs[] = $row['annonceur']['id'];
            if(!array_key_exists($row['localite'], $investParLocalite)):
                $investParLocalite[$row['localite']] = $row['investissement'];
                $insertParLocalite[$row['localite']] = $row['nombre'];
            else:
                $investParLocalite[$row['localite']] += $row['investissement'];
                $insertParLocalite[$row['localite']] += $row['nombre'];
            endif;
            if(!array_key_exists($row['annonceur']['id'], $investParAnnonceur)):
                $investParAnnonceur[$row['annonceur']['id']] = $row['investissement'];
                $insertParAnnonceur[$row['annonceur']['id']] = $row['nombre'];
            else:
                $investParAnnonceur[$row['annonceur']['id']] += $row['investissement'];
                $insertParAnnonceur[$row['annonceur']['id']] += $row['nombre'];
            endif;
            if(!array_key_exists($row['localite'], $dataRange)):
                $dataRange[$row['localite']][$row['annonceur']['id']] = [
                    'campagne'  => [$row['campagnetitle']['id']],
                    'nb'        => $row['nombre'],
                    'invest'    => $row['investissement']
                ];
            elseif(!array_key_exists($row['annonceur']['id'], $dataRange[$row['localite']])):
                $dataRange[$row['localite']][$row['annonceur']['id']] = [
                    'campagne'  => [$row['campagnetitle']['id']],
                    'nb'        => $row['nombre'],
                    'invest'    => $row['investissement']
                ];
            else:
                if(!in_array($row['campagnetitle']['id'], $dataRange[$row['localite']][$row['annonceur']['id']]['campagne'])):
                    $dataRange[$row['localite']][$row['annonceur']['id']]['campagne'][] = $row['campagnetitle']['id'];
                endif;
                $dataRange[$row['localite']][$row['annonceur']['id']]['nb'] += $row['nombre'];
                $dataRange[$row['localite']][$row['annonceur']['id']]['invest'] = $row['investissement'];
            endif;
            if(!array_key_exists($row['annonceur']['id'], $lesCampagnes)):
                $lesCampagnes[$row['annonceur']['id']]['campagnes'][] = $row['campagnetitle'];
            else:
                if(!in_array($row['campagnetitle']['id'], $lesCampagnes[$row['annonceur']['id']]['campagnes'])):
                    $lesCampagnes[$row['annonceur']['id']]['campagnes'][] = $row['campagnetitle']['id'];
                endif;
            endif;
            $dataRange[$row['localite']][$row['annonceur']['id']]['logo'] = str_replace('\\','/',FunctionController::getIconAnnonceur($row['annonceur']['id'],'Map'));
            $dataRange[$row['localite']][$row['annonceur']['id']]['couleur'] = $row['annonceur']['couleur'];
            $dataRange[$row['localite']][$row['annonceur']['id']]['date'] = $row['date'];
        endforeach;
            $lesLocalitesK = array_keys($insertParLocalite);
            $lesAnnonceursK = array_keys($insertParAnnonceur);
            $lesLocalitesUt = Localite::whereIn('id', $lesLocalitesK)->get()->toArray();
            $lesAnnonceursUt = Annonceur::whereIn('id', $lesAnnonceursK)->get()->toArray();
            $lesLocalitesB = [];
            $lesAnnonceursB = [];
            $lesCouleurs = [];
            foreach($lesAnnonceursUt AS $loc):
                $lesAnnonceursB[$loc['id']] = $loc['name'] ; 
                $lesCouleurs[$loc['name']] = Str::startsWith($loc['couleur'], "#") ? $loc['couleur'] : "#".$loc['couleur'] ; 
                if(array_key_exists($loc['id'], $lesCampagnes)):
                    $lesCampagnes[$loc['id']]['name'] = $loc['name'];
                    $lesCampagnes[$loc['id']]['slug'] = $loc['slug'] ?? str_split($loc['name'], 2);
                endif;
            endforeach;
            $lesTabCamp = self::makeAbbreviationCampagne($lesCampagnes);
            //dd($result, $dataRange, $lesCampagnes);
            foreach($lesLocalitesUt AS $loc):
                $lesLocalitesB[$loc['id']] = $loc['name'] ; 
            endforeach;
            $investParLocaliteBB = [];
            $insertParLocaliteBB = [];
            $investParAnnonceurBB = [];
            $insertParAnnonceurBB = [];
            foreach($investParLocalite AS $k => $v):
                $investParLocaliteBB[$lesLocalitesB[$k]] = intval($v) ;
                $insertParLocaliteBB[$lesLocalitesB[$k]] = intval($insertParLocalite[$k]) ;
            endforeach;
            //dd($lesAnnonceursB, $investParAnnonceur, $insertParAnnonceur);
            foreach($insertParAnnonceur AS $k => $v):
                $investParAnnonceurBB[$lesAnnonceursB[$k]] = intval($investParAnnonceur[$k]) ;
                $insertParAnnonceurBB[$lesAnnonceursB[$k]] = intval($v) ;
            endforeach;
        $lesLocalites = Localite::whereIn('id', array_keys($dataRange))->get()->toArray();
        $dataAnnonceurs = Annonceur::whereIn('id', $lesAnnonceurs)->get()->toArray();
        $donneesAnnonceurs = [];
        foreach($dataAnnonceurs AS $t):
            $donneesAnnonceurs[$t['id']] = $t;
        endforeach;
        //$points = [];
        $j = 0;
        $k = $j;
        $points = [];
        foreach($lesLocalites AS $local):
            $lat = $local['latitude'];
            $long = $local['longitude'];
            $nomLocalite = $local['name'];
            $donnees = $dataRange[$local['id']];
            if (!array_key_exists($local['id'],$points)):
                $points = self::makeMapPoint($lat, $long, count($donnees),$local['id']);
            endif;
            $i = 0;
            $anTb = [];
            foreach($donnees AS $annonceur_id => $tab):
                $img = $tab['logo'];
                $couleur = $tab['couleur'];

                $latitude = $points[$local['id']][$i]['lat'];
                $longitude = $points[$local['id']][$i]['lon'];
                $nombrePanneaux = $tab['nb'];
                $annonceur = $donneesAnnonceurs[$annonceur_id]['raisonsociale'];
                $campagneTitle = "";
                $investissement = $tab['invest'];
                $animation = " google.maps.Animation.BOUNCE";
                $icon = 'https://i.stack.imgur.com/KOh5X.png#'.$j;
                $listeDesPointsNew .= $virg.'new google.maps.Marker({
    			position: new google.maps.LatLng('.$latitude.', '.$longitude.'),
    			map: gm_map,
                title: "'.$campagneTitle.'",
                animation: "'.$animation.'",
                icon: "'.$icon.'",
                optimized: false
		        })' ;

                $campagneTitles = self::makeCampagneTitleMapLink($lesTabCamp, $tab['campagne']);
                //$anTb[$annonceur_id]['inc'] +=  $campagneTitles['inc'];
                //$k = $campagneTitles['inc'];
                $campagneTitlesMap = $campagneTitles['Map'];
                $campagneTitlesListe = $campagneTitles['List'];

                $Bigdata .= $virg.'{lat:"'.$latitude.'",lng:"'.$longitude.'",logo:"'.$img.'", annonceur:"'.$annonceur.'", localite:"'.$nomLocalite.'", nombrePanneaux:"'.$nombrePanneaux.'", investissement:"'.$investissement.'",couleur:"'.$couleur.'", campagne:"'.$campagneTitlesMap.'"}';
                $virg = ',';

                $tableauDesMapNew[$local['id']][$annonceur_id] = [
                    'Date' => FunctionController::date2Fr($tab['date']),
                    'titre' => $campagneTitlesListe,
                    'annonceur' => $annonceur,
                    'localite' => $nomLocalite,
                    'investissement' => $investissement,
                    'nombre' => $tab['nb']
                ];
                $i++;
                if (count($points) > 1):
                    $j = $j+$i;
                else:
                    $j++;
                endif;
                //$k = $j;
            endforeach;
        endforeach;
        //dd($listeDesPointsNew,$Bigdata,$pt);
        //*
        return [
            'listeDesPoints' => $listeDesPointsNew,
            'Bigdata' => $Bigdata,
            'TableauDesMap' => $tableauDesMapNew,
            'investParAnnonceur' => $investParAnnonceurBB,
            'investParLocalite' => $investParLocaliteBB,
            'insertParAnnonceur' => $insertParAnnonceurBB,
            'insertParLocalite' => $insertParLocaliteBB,
            "lesCouleurs" => $lesCouleurs
        ];
    }
    public function accueil3(Request $request){
        self::getLocalitesDeAbidjan();
        $this->nettoyerSession ("secteurComm");
        $this->nettoyerSession ("annonceurComm");
        $this->nettoyerSession ("villeLocalite");
        $this->nettoyerSession ("communeLocalite");
        $dateDebut = date ('Y-m-d', mktime(0,0,0,date("n"), date("j")-30, date('Y')))  ; // 30 jours en arriere
        $dateFin = date ('Y-m-d');
        $secteurs = Secteur::orderBy('name', 'ASC')->get()->toArray();
        $nbrsecteur = count ($secteurs);
        $formats = Format::where('media',6)->orderBy('name', 'ASC')->get()->toArray();
        $nbrformat = count($formats);
        $villes = Localite::where('parent', 0)->orderBy('name', 'ASC')->get()->toArray();
        //dd(Session::has('BillbordMapSelection'));
        if(Session::has('BillbordMapSelection')):
            $selection = Session::get('BillbordMapSelection.selection');
            $listeDesMap = Session::get('BillbordMapSelection.listeDesMap');
        else:
            $datas = [
                    'secteur' => [1],
                    'dateFin' => $dateFin, 'dateDebut' =>$dateDebut,
                    'ville' => self::getLocalitesDeAbidjan()
                ];
            $this->makeDataEloq($datas, $request);
            $this->makeData($datas, $request);
            $selection = Session::get('BillbordMapSelection.selection');
            $listeDesMap = Session::get('BillbordMapSelection.listeDesMap');
        endif;
        return view ("bbmap.tplclient3", compact ('dateDebut', 'dateFin', 'secteurs','nbrsecteur','villes','listeDesMap','selection','formats','nbrformat'));
    }

    public static function getLocalitesDeAbidjan()
    {
        if (!Session::has('localitesDeAbidjan')):
            $localite = Localite::where('parent',8)->get(['id'])->toArray();
            $k = [];
            foreach ($localite as $r):
                $k[$r['id']] = $r['id'];
            endforeach;
            Session::put('localitesDeAbidjan',$k);
        endif;
        return Session::get('localitesDeAbidjan');
    }

	public static function getToutesLocalites()
    {
        if (!Session::has('toutesLocalites')):
            $localite = Localite::all()->get(['id'])->toArray();
            $k = [];
            foreach ($localite as $r):
                $k[$r['id']] = $r['id'];
            endforeach;
            Session::put('toutesLocalites',$k);
        endif;
        return Session::get('toutesLocalites');
    }

    public function gestionLocalite()
    {
        $localites = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_LOCALITES','db')." WHERE parent = 0 ORDER BY name ASC");
        $tableau = ModuleController::makeTable (DbTablesHelper::dbTable ('DBTBL_LOCALITES','db'),"",['routeUpdate' => 'bbmap.updateLocalite']);
        return view ("bbmap.localites.form", compact ('localites','tableau'));
    }
    public function updateLocalite(string $table, int $id)
    {
        $localite = Localite::find($id);
        $cond = " WHERE parent = 0";
        $grandPere = 0;
        if ($localite->parent !== 0):
            $pere = Localite::where('id',$localite->parent)->get();
            $grandPere = $pere[0]->parent;
            $cond = " WHERE parent < {$localite->parent}";
        endif;
        $localites = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_LOCALITES','db')." $cond ORDER BY name ASC");
        $tableau = ModuleController::makeTable (DbTablesHelper::dbTable ('DBTBL_LOCALITES','db'),"",['routeUpdate' => 'bbmap.updateLocalite']);
        return view ("bbmap.localites.formUpdate", compact ('localites','tableau','localite','grandPere'));

    }
    public function getCommune(Request $request){
        $communes = [];
        if (!is_null ($request->localite)):
           $tLocalite = DbTablesHelper::dbTable ('DBTBL_LOCALITES','db');
            $nomLocalite = FunctionController::getChampTable ($tLocalite,$request->localite);
            if (trim ($nomLocalite) === 'ABIDJAN'):
                $communes = FunctionController::arraySqlResult ("SELECT * FROM $tLocalite WHERE parent = {$request->localite} ORDER BY name ASC");
            else:
                $communes[] = ['id' => $request->localite,'name' => $nomLocalite];
            endif;
          //  return view ("bbmap.panneaux.inputCommune",compact ('communes','send'))->render ();
        endif;
        return response()->json($communes);
    }

    public static function codeLocalite(int $localiteID):string {
        $codeVille = "A";
        $codeCommune = "";
        $localite = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_LOCALITES','db'),$localiteID);
        $parentID = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_LOCALITES','db'),$localiteID,'parent');
        if ($parentID != 0 or $parentID != 1):
           $parent = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_LOCALITES','db'),$parentID);
            $codeVille = $parent != "ABIDJAN" ? "I" : $codeVille;
            $nc = explode ('-',$localite);
            if (count ($nc) != 1):
                $codeCommune .= substr ($nc[0],0,1).substr ($nc[1],0,1);
            else:
                $codeCommune .= substr ($localite,0,2);
            endif;
        endif;

        return $codeVille.'.'.$codeCommune;
    }


    public static function checkSessionVar(string $bool,string $session,int $var){
        if ($bool === "true"):
            if (!Session::has ("$session")):
                Session::put ("$session", []);
            endif;
            if (!Session::has ("$session.$var")):
                Session::put ("$session.$var", $var);
            endif;
            Session::save ();
        endif;
        if ($bool == "false"):
            if (Session::has ("$session.$var")):
                Session::forget ("$session.$var");
            endif;
            Session::save ();
        endif;
        return Session::get ("$session");
    }

    public function bbordmapSupport(Request $request){
        $on = $request->on;
        $regie = $request->regie;
        $tabRegie = self::checkSessionVar ($on,"regieSupport",$regie);
        if (count ($tabRegie)):
            $join = join (',',$tabRegie);
            $localiteCond = "";
            if ($request->session ()->has ('villeLocalite') && count ($request->session ()->get ('villeLocalite'))):
                if ($request->session ()->has ('communeLocalite') && count ($request->session ()->get ('communeLocalite'))):
                    $joinLoc = join (',',$request->session ()->get ('communeLocalite'));
                    $localiteCond = " AND localite IN($joinLoc)";
                 else:
                     $joinLoc = join (',',$request->session ()->get ('villeLocalite'));
                     $localiteCond = " AND localite IN(
                     SELECT id FROM ".DbTablesHelper::dbTable ('DBTBL_LOCALITES','db')." WHERE parent IN($joinLoc)
                     )";
                endif;
            endif;
            $querie = "SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_AFFICHAGES_PANNEAUS','db')." WHERE regie IN ($join) $localiteCond ORDER BY code ASC" ;
            $panneaux = FunctionController::arraySqlResult ($querie);
            return view ("bbmap.panneaux.listePanneaux",compact ('panneaux'))->render ();
        endif;
    }

    public function bbordmapComm(Request $request){
        $on = $request->input('on');
        if (array_key_exists ('secteur',$request->all())){
            $secteur = $request->input('secteur');
            $annonceurs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db')." WHERE secteur = $secteur ORDER BY name ASC ");
                return view ("bbmap.panneaux.listeAnnonceurs",compact ('annonceurs'))->render ();
            //endif;
        }
        if (array_key_exists ('annonceur',$request->all ())){
            $annonceurs = $request->annonceur;
            $tabAnnonceur = self::checkSessionVar ($on,"annonceurComm",$annonceurs);
            if (count ($tabAnnonceur)):
                $join = join (',',$tabAnnonceur);
                $campagnes = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLES','db')." WHERE operation 
                    IN(
                        SELECT id FROM ".DbTablesHelper::dbTable ('DBTBL_OPERATIONS','db')." WHERE annonceur IN ($join) 
                    )
                 ORDER BY created_at DESC ");
                return view ("bbmap.panneaux.listeCampagnes",compact ('campagnes'))->render ();
            endif;
        }


    }

    public function bbordmapLocalite(Request $request){
        $on = $request->input('on');
        if (array_key_exists ('ville',$request->all ())){
            $ville = $request->input('ville');
            $tabVille = self::checkSessionVar ($on,"villeLocalite",$ville);
            if (count ($tabVille)):
                $result = Session::get('villeLocalite');
                if (((int)$ville === 8 and $on === 'true') or in_array('8',$tabVille)):
                    $v = [];
                    foreach ($tabVille as $item):
                        $v[$item] = $item;
                    endforeach;
                    foreach (self::getLocalitesDeAbidjan() as $item):
                        $v[$item] = $item;
                    endforeach;
                    Session::forget('villeLocalite');
                    Session::put('villeLocalite', $v);
                    $request->session()->save();
                    $result = Session::get('villeLocalite');
                endif;
                if((int)$ville === 8 and $on === 'false'):
                    $result = array_diff(Session::get('villeLocalite'),self::getLocalitesDeAbidjan());
                endif;
                unset($result[8]);
                //dump($result);
                $communes = Localite::whereIn('id',$result)
                    ->orderBy('name')->get()->toArray();
                return view ("bbmap.panneaux.listeCommunes",compact ('communes'))->render ();
            endif;
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function trouverPanneaux(Request $request): RedirectResponse
    {

        $validate = $this->validate($request,[
            'dateDebut' => 'required',
            'dateFin' => 'required',
            'secteur' => 'required',
            'ville' => 'required',
        ]);
        //dd(!$validate);
        if (!$validate):
            $request->session()->flash('infos',"La date (debut et fin), le secteur d'activité et la localisation sont obligatoire!");
        else:
            $request->session()->forget('BillbordMapSelection');
            $datas = $request->all ();
            unset($datas['_token'],$datas['formulairedeselection'],$datas['valider']);
            $this->makeData($datas, $request);
        endif;
        return back();
    }

    /**
     * @param array $listeDesMap
     * @return Factory|Application|View
     * @throws \Throwable
     */
    public function listeDesPanneaux(array $listeDesMap)
    {
        $selection = self::afficherLaSelection($this->recherche);
        Session::put('BillbordMapSelection', [
            'listeDesMap' => $listeDesMap,
            'selection' => $selection,
        ]);
    }

    /**
     * @param array $donnees
     * @return string
     */
    public function condition(array $donnees): string
    {
        //dd($donnees);
        $cond = "";
        $secteurs = array_key_exists ('secteur',$donnees) ? $donnees['secteur'] : [];
        $formats = array_key_exists ('format',$donnees) ? $donnees['format'] : [];
        $annonceurs = array_key_exists ('annonceurs',$donnees) ? $donnees['annonceurs'] : [];
        $villes = array_key_exists ('ville',$donnees) ? $donnees['ville'] : [];
        $communes = array_key_exists ('commune',$donnees) ? $donnees['commune'] : [];
        $dateFin = $donnees['dateFin'];
        $dateDebut = $donnees['dateDebut'];
        $condDates = [];
        $condLocalites = [];
        $condSecteurs = [];
        $condAnnonceurs = [];
        $condFormats = [];
            if (!empty($secteurs)):
                if ($dateFin >= $dateDebut):
                    $and = " where ";
                    $cond .= " $and date between '$dateDebut' and '$dateFin' ";
                    $lesDates = [$dateDebut, $dateFin];
                    $this->recherche['Periode'] = [
                        'Debut' => FunctionController::date2Fr($dateDebut),
                        'Fin' => FunctionController::date2Fr($dateFin),
                    ];
                    if (!empty($villes)):
                        $villeEnSession = Session::get('villeLocalite');
                        $and = " and";
                        if (!empty($communes)):
                            $nbDeVille = count($villeEnSession);
                            foreach ($communes as $commune):
                                if (array_key_exists((int)$commune,self::getLocalitesDeAbidjan())):
                                    $villeEnSession[$commune] = (int)$commune;
                                endif;
                            endforeach;
                            if (count($villeEnSession) > $nbDeVille):
                                unset($villeEnSession[8]);
                            endif;
                            $listCommune = implode(',',$villeEnSession);
                            $cond .= " $and localite in($listCommune)";
                            $condLocalites = $villeEnSession;
                            $this->recherche['Localite'] = $this->selection($villeEnSession,DbTablesHelper::dbTable('DBTBL_LOCALITES','db'));
                        else:
                            $villeEnSession = $villeEnSession !== null ? $villeEnSession : $villes;
                            $listVille = implode(',',$villeEnSession);
                            $cond .= " $and localite in($listVille)";
                            $this->recherche['Localite'] = $this->selection($villeEnSession,DbTablesHelper::dbTable('DBTBL_LOCALITES','db'));
                        endif;
                        $condLocalites = $villeEnSession;
                    else:
                        Session::flash('echec','Veuillez choisir au moins une localité!');
                        //return back();
                    endif;
                    $and = " and";
                    if (!empty($secteurs)):
                        if (!empty($annonceurs)):
                            $condAnnonceurs = $annonceurs;
                            $and = " and";
                            $listeAnn = implode(',',$annonceurs);
                            $cond .= " $and annonceur in($listeAnn)";
                            $this->recherche['Annonceur'] = $this->selection($annonceurs,DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db'),'name');
                        // //C'est ce qui empechechait les secteurs dans la condition
                        else:
                            $listeSect = implode(',',$secteurs);
                            $cond .= " $and secteur in($listeSect)";
                            $condSecteurs = $secteurs;
                            $this->recherche['Secteur'] = $this->selection($secteurs, DbTablesHelper::dbTable('DBTBL_SECTEURS','db'),'name');
                        endif;
                        if (!empty($formats)):
                            $condFormats = $formats;
                            $and = " and";
                            $listForm = implode(',', $formats);
                            $cond .= " $and format in ($listForm)";
                            $this->recherche['Format'] = $this->selection($formats, DbTablesHelper::dbTable('DBTBL_FORMATS','db'),'name');
                        else:
                            $and = " and";
                            $cond .= " $and media = ".$this->media." ";
                        endif;
                    endif;
                else:
                    Session::flash('echec','La date de fin ne peut être inférieure à la date de début.');
                endif;
            else :
                Session::flash('echec','Veuillez choisir obligatoirement un secteur!');
                //return back();
            endif;
            //dd($cond);
        return $cond;
    }
    /**
     * @param int $campagneID
     * @param int $mediaID
     * @return string
     */
    public function getDocampagne(int $campagneID,int $mediaID=0): string
    {
        $cond = $mediaID != 0 ? " AND media = $mediaID " : "" ;
        $campagneTitleID = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_CAMPAGNES','db'),$campagneID,'campagnetitle');
        $doc = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_DOCAMPAGNES','db')." WHERE campagnetitle = $campagneTitleID $cond");
        //dd ($doc);
        if (count ($doc)):
           $affiche = "upload/campagnes/".$campagneTitleID."/".$doc[0]['fichier'];
           return asset ($affiche);
        else:
            return "";
        endif;
    }

    public function nettoyerSession(string $sessionName):void {
        if (Session::has ($sessionName)):
            Session::forget ($sessionName);
        endif;
    }

    public function selection(array $donnees,string $table,string $name='name'):array {
        $table = FunctionController::getTableName ($table);
        $liste = [];
        foreach ($donnees as $donnee):
            $liste[] = FunctionController::getChampTable ($table,$donnee,$name);
        endforeach;
        return $liste;
    }

    public static function transformDataToPieChart($data, $tabKey = ['nb', 'invest']){
        $rp = [];
        foreach($tabKey AS $key):
            $rp[$key] = [];
        endforeach;
        foreach($data AS $k => $row):
            foreach($tabKey AS $key):
                $rp[$key][$k] = $row[$key];
            endforeach;
        endforeach;
        return $rp;
    }
    /**
     * @param array $result
     * @return string[]
     */
    public function makeMapDatas(array $result): array
    {
        $investParLocalite = [];
        $investParAnnonceur = [];
        $insertParLocalite = [];
        $insertParAnnonceur = [];
        $listeDesPoints = '';
        $listeDesPointsNew = '';
        $virg = '';
        $Bigdata = '';
        $tableauDesMapNew = [];
        $lesCampagnes = [];
        $lesAnnonceurs = [];
        $dataRange = [];
        //dd($result);
        foreach($result as $row) :
            $lesAnnonceurs[] = $row['annonceur'];
            if(!array_key_exists($row['localite'], $investParLocalite)):
                $investParLocalite[$row['localite']] = $row['investissement'];
                $insertParLocalite[$row['localite']] = $row['nombre'];
            else:
                $investParLocalite[$row['localite']] += $row['investissement'];
                $insertParLocalite[$row['localite']] += $row['nombre'];
            endif;
            if(!array_key_exists($row['annonceur'], $investParAnnonceur)):
                $investParAnnonceur[$row['annonceur']] = $row['investissement'];
                $insertParAnnonceur[$row['annonceur']] = $row['nombre'];
            else:
                $investParAnnonceur[$row['annonceur']] += $row['investissement'];
                $insertParAnnonceur[$row['annonceur']] += $row['nombre'];
            endif;
            if(!array_key_exists($row['localite'], $dataRange)):
                $dataRange[$row['localite']][$row['annonceur']] = [
                    'campagne'  => [$row['campagnetitle']],
                    'nb'        => $row['nombre'],
                    'invest'    => $row['investissement']
                ];
            elseif(!array_key_exists($row['annonceur'], $dataRange[$row['localite']])):
                $dataRange[$row['localite']][$row['annonceur']] = [
                    'campagne'  => [$row['campagnetitle']],
                    'nb'        => $row['nombre'],
                    'invest'    => $row['investissement']
                ];
            else:
                if(!in_array($row['campagnetitle'], $dataRange[$row['localite']][$row['annonceur']]['campagne'])):
                    $dataRange[$row['localite']][$row['annonceur']]['campagne'][] = $row['campagnetitle'];
                endif;
                $dataRange[$row['localite']][$row['annonceur']]['nb'] += $row['nombre'];
                $dataRange[$row['localite']][$row['annonceur']]['invest'] = $row['investissement'];
            endif;
            if(!array_key_exists($row['annonceur'], $lesCampagnes)):
                $lesCampagnes[$row['annonceur']]['campagnes'][] = $row['campagnetitle'];
            else:
                if(!in_array($row['campagnetitle'], $lesCampagnes[$row['annonceur']]['campagnes'])):
                    $lesCampagnes[$row['annonceur']]['campagnes'][] = $row['campagnetitle'];
                endif;
            endif;
            $dataRange[$row['localite']][$row['annonceur']]['logo'] = str_replace('\\','/',FunctionController::getIconAnnonceur($row['annonceur'],'Map'));
            $dataRange[$row['localite']][$row['annonceur']]['couleur'] = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db'),$row['annonceur'],'couleur');
            $dataRange[$row['localite']][$row['annonceur']]['date'] = $row['date'];
        endforeach;
            $lesLocalitesK = array_keys($insertParLocalite);
            $lesAnnonceursK = array_keys($insertParAnnonceur);
            $lesLocalitesUt = Localite::whereIn('id', $lesLocalitesK)->get()->toArray();
            $lesAnnonceursUt = Annonceur::whereIn('id', $lesAnnonceursK)->get()->toArray();
            $lesLocalitesB = [];
            $lesAnnonceursB = [];
            $lesCouleurs = [];
            foreach($lesAnnonceursUt AS $loc):
                $lesAnnonceursB[$loc['id']] = $loc['name'] ; 
                $lesCouleurs[$loc['name']] = Str::startsWith($loc['couleur'], "#") ? $loc['couleur'] : "#".$loc['couleur'] ; 
                if(array_key_exists($loc['id'], $lesCampagnes)):
                    $lesCampagnes[$loc['id']]['name'] = $loc['name'];
                    $lesCampagnes[$loc['id']]['slug'] = $loc['slug'] ?? str_split($loc['name'], 2);
                endif;
            endforeach;
            $lesTabCamp = self::makeAbbreviationCampagne($lesCampagnes);
            //dd($result, $dataRange, $lesCampagnes);
            foreach($lesLocalitesUt AS $loc):
                $lesLocalitesB[$loc['id']] = $loc['name'] ; 
            endforeach;
            $investParLocaliteBB = [];
            $insertParLocaliteBB = [];
            $investParAnnonceurBB = [];
            $insertParAnnonceurBB = [];
            foreach($investParLocalite AS $k => $v):
                $investParLocaliteBB[$lesLocalitesB[$k]] = intval($v) ;
                $insertParLocaliteBB[$lesLocalitesB[$k]] = intval($insertParLocalite[$k]) ;
            endforeach;
            //dd($lesAnnonceursB, $investParAnnonceur, $insertParAnnonceur);
            foreach($insertParAnnonceur AS $k => $v):
                $investParAnnonceurBB[$lesAnnonceursB[$k]] = intval($investParAnnonceur[$k]) ;
                $insertParAnnonceurBB[$lesAnnonceursB[$k]] = intval($v) ;
            endforeach;
        $lesLocalites = Localite::whereIn('id', array_keys($dataRange))->get()->toArray();
        $dataAnnonceurs = Annonceur::whereIn('id', $lesAnnonceurs)->get()->toArray();
        $donneesAnnonceurs = [];
        foreach($dataAnnonceurs AS $t):
            $donneesAnnonceurs[$t['id']] = $t;
        endforeach;
        //$points = [];
        $j = 0;
        $k = $j;
        $points = [];
        foreach($lesLocalites AS $local):
            $lat = $local['latitude'];
            $long = $local['longitude'];
            $nomLocalite = $local['name'];
            $donnees = $dataRange[$local['id']];
            if (!array_key_exists($local['id'],$points)):
                $points = self::makeMapPoint($lat, $long, count($donnees),$local['id']);
            endif;
            $i = 0;
            $anTb = [];
            foreach($donnees AS $annonceur_id => $tab):
                $img = $tab['logo'];
                $couleur = $tab['couleur'];

                $latitude = $points[$local['id']][$i]['lat'];
                $longitude = $points[$local['id']][$i]['lon'];
                $nombrePanneaux = $tab['nb'];
                $annonceur = $donneesAnnonceurs[$annonceur_id]['raisonsociale'];
                $campagneTitle = "";
                $investissement = $tab['invest'];
                $animation = " google.maps.Animation.BOUNCE";
                $icon = 'https://i.stack.imgur.com/KOh5X.png#'.$j;
                $listeDesPointsNew .= $virg.'new google.maps.Marker({
    			position: new google.maps.LatLng('.$latitude.', '.$longitude.'),
    			map: gm_map,
                title: "'.$campagneTitle.'",
                animation: "'.$animation.'",
                icon: "'.$icon.'",
                optimized: false
		        })' ;

                $campagneTitles = self::makeCampagneTitleMapLink($lesTabCamp, $tab['campagne']);
                //$anTb[$annonceur_id]['inc'] +=  $campagneTitles['inc'];
                //$k = $campagneTitles['inc'];
                $campagneTitlesMap = $campagneTitles['Map'];
                $campagneTitlesListe = $campagneTitles['List'];

                $Bigdata .= $virg.'{lat:"'.$latitude.'",lng:"'.$longitude.'",logo:"'.$img.'", annonceur:"'.$annonceur.'", localite:"'.$nomLocalite.'", nombrePanneaux:"'.$nombrePanneaux.'", investissement:"'.$investissement.'",couleur:"'.$couleur.'", campagne:"'.$campagneTitlesMap.'"}';
                $virg = ',';

                $tableauDesMapNew[$local['id']][$annonceur_id] = [
                    'Date' => FunctionController::date2Fr($tab['date']),
                    'titre' => $campagneTitlesListe,
                    'annonceur' => $annonceur,
                    'localite' => $nomLocalite,
                    'investissement' => $investissement,
                    'nombre' => $tab['nb']
                ];
                $i++;
                if (count($points) > 1):
                    $j = $j+$i;
                else:
                    $j++;
                endif;
                //$k = $j;
            endforeach;
        endforeach;
        //dd($listeDesPointsNew,$Bigdata,$pt);
        //*
        return [
            'listeDesPoints' => $listeDesPointsNew,
            'Bigdata' => $Bigdata,
            'TableauDesMap' => $tableauDesMapNew,
            'investParAnnonceur' => $investParAnnonceurBB,
            'investParLocalite' => $investParLocaliteBB,
            'insertParAnnonceur' => $insertParAnnonceurBB,
            'insertParLocalite' => $insertParLocaliteBB,
            "lesCouleurs" => $lesCouleurs
        ];
    }
    public static function makeAbbreviationCampagne(array $arr): array{
        $data = [];
        foreach($arr AS $k => $v):
            $an = $v['slug'];
            $i = 1 ;
            foreach($v['campagnes'] AS $cid):
                $campagneTitle = Campagnetitle::find($cid);
                $data[$cid]['map'] = "<a href='".route('reporting.detailCampagne',$cid)."' style='font-size: 13pt;font-weight: 700;' target='_blank'>$an[0]$i</a> " ;
                $data[$cid]['list'] = "<a href='".route('reporting.detailCampagne',$cid)."' target='_blank'>{$campagneTitle->title}</a><b>($an[0]$i)</b> " ;
                $i++;
            endforeach;
        endforeach;
        return $data;
    }

    public static function makeMapPoint($lat, $lon, int $nb,int $localiteID): array{
        $pair = $nb % 2 == 0;
        $k = $pair ? $nb : $nb - 1;
        $coef = 100000;
        $inc = 50;
        $m = $inc * $k;
        $points = [];
        if(!$pair):
            $points[$localiteID][] = ['lat' => $lat, 'lon' => $lon];
        endif;
        for($i = 0 ; $i < $k/2; $i++):
            $dlon = 2 * $i * $inc;
            $dlat = $dlon - $m ;
            $points[$localiteID][] = ['lat' => $lat + $dlat / $coef , 'lon' => $lon + $dlon / $coef];
            $points[$localiteID][] = ['lat' => $lat - $dlat / $coef , 'lon' => $lon - $dlon / $coef];
        endfor;
        return $points;
    }

    /**
     * @param array $campagnes
     * @param int $inc
     * @param string $type
     * @return array
     */
    public static function makeCampagneTitleMapLink(array $arr, array $campagnes): array
    {
        $strMap = '';
        $strList = '';
        $camp = array_unique($campagnes);
        foreach ($camp AS $k => $cid):
            $strMap .= $arr[$cid]['map'] ;
            $strList .= $arr[$cid]['list'] ;
        endforeach;
        return [
            'Map' => $strMap,
            'List' => $strList,
        ];
    }


    /**
     * @param array $campagnes
     * @param int $inc
     * @param string $type
     * @return array
     */
    public static function makeCampagneTitleMap(array $campagnes,int $inc, string $abr): array
    {
        $strMap = '';
        $strList = '';
        $i = $inc;
        foreach ($campagnes as $campagne):
            $i++;
            $campagneTitle = Campagnetitle::find($campagne);
            $strMap .= "<a href='".route('reporting.detailCampagne',$campagne)."' style='font-size: 13pt;font-weight: 700;' target='_blank'>$i</a>, " ;
            $strList .= "<a href='".route('reporting.detailCampagne',$campagne)."' target='_blank'>{$campagneTitle->title}</a><b>($abr$i)</b>, ";
        endforeach;
        return [
            'Map' => $strMap,
            'List' => $strList,
            'inc' => $i+count($campagnes),
        ];
    }


    public function bbmapSecteur(Request $request)
    {
        $check = $request->input('on');
        $secteur = $request->input('secteur');
        $annonceur = Annonceur::where('secteur',$secteur)->get()->toArray();
        $format = Format::where('secteur',$secteur)->get()->toArray();

    }
}
