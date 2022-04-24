<?php

namespace App\Http\Controllers\Billboardmap;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use App\Http\Controllers\core\ModuleController;
use App\Models\Localite;
use App\Models\Secteur;
use App\Models\Annonceur;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class BillBoardMapController extends AdminController
{
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
        $regions = FunctionController::arraySqlResult ("SELECT * FROM $dbLocalite WHERE parent = 0 ORDER BY name ASC");
        $villes = FunctionController::arraySqlResult ("SELECT * FROM $dbLocalite 
            WHERE parent IN (SELECT id FROM $dbLocalite WHERE parent = 0) 
            ORDER BY name ASC");
        $listeDesMap = [];
        $selection = '';
        if (Session::has('BillbordMapSelection')):
            $selection = Session::get('BillbordMapSelection.selection');
            $listeDesMap = Session::get('BillbordMapSelection.listeDesMap');
        endif;
        return view ("bbmap.index", compact ('secteurs','nbrsecteur','regions','villes','listeDesMap','selection'));
    }

    public function accueil(Request $request){
        $this->nettoyerSession ("secteurComm");
        $this->nettoyerSession ("annonceurComm");
        $this->nettoyerSession ("regionLocalite");
        $this->nettoyerSession ("villeLocalite");
        $this->nettoyerSession ("communeLocalite");        
        $dateDebut = date ('Y-m-d', mktime(0,0,0,date("n"), date("j")-30, date('Y')))  ; // 30 jours en arriere
        $dateFin = date ('Y-m-d');
        //$secteurs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SECTEURS','db')." ORDER BY name ASC");
        $secteurs = Secteur::orderBy('name', 'ASC')->get()->toArray();
        $nbrsecteur = count ($secteurs);
        //$villes = FunctionController::arraySqlResult ("SELECT * FROM $dbLocalite WHERE parent = 0 ORDER BY name ASC");
        $villes = Localite::where('parent', 0)->orderBy('name', 'ASC')->get()->toArray();
        $listeDesMap = [];
        $selection = '';
        if (Session::has('BillbordMapSelection')):
            $selection = Session::get('BillbordMapSelection.selection');
            $listeDesMap = Session::get('BillbordMapSelection.listeDesMap');
        else:
            $datas = [
                    'secteur' => [1], 'dateFin' => $dateFin, 'dateDebut' =>$dateDebut,
                    'ville' =>[8]
                ];
            $this->makeData($datas, $request);
            $selection = Session::get('BillbordMapSelection.selection');
            $listeDesMap = Session::get('BillbordMapSelection.listeDesMap');
        endif;
        //return view ("bbmap.index", compact ('secteurs','nbrsecteur','regions','villes','listeDesMap','selection'));
        return view ("bbmap.tplclient", compact ('dateDebut', 'dateFin', 'secteurs','nbrsecteur','villes','listeDesMap','selection'));
    }

    public function gestionLocalite(){
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
        if ($bool == "true"):
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
        $on = $request->on;
        if (array_key_exists ('secteur',$request->all ())){
            $secteur = $request->secteur;
            $tabSecteur = self::checkSessionVar ($on,"secteurComm",$secteur);
            if (count ($tabSecteur)):
                $join = join (',',$tabSecteur);
                $annonceurs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db')." WHERE secteur = $secteur ORDER BY name ASC ");
                return view ("bbmap.panneaux.listeAnnonceurs",compact ('annonceurs'))->render ();
            endif;
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
        $on = $request->on;
        if (array_key_exists ('region',$request->all ())) {
            $region = $request -> region;
            $tabRegion = self ::checkSessionVar ( $on, "regionLocalite", $region );
            if ( count ( $tabRegion ) ):
                $join = join ( ',', $tabRegion );
                $villes = FunctionController ::arraySqlResult ( "SELECT * FROM " . DbTablesHelper ::dbTable ( 'DBTBL_LOCALITES', 'db' ) . " WHERE parent 
                    IN($join)
                 ORDER BY name ASC " );
                return view ( "bbmap.panneaux.listeVilles", compact ( 'villes' ) ) -> render ();
            endif;
        }
        if (array_key_exists ('ville',$request->all ())){
            $ville = $request->ville;
            $tabVille = self::checkSessionVar ($on,"villeLocalite",$ville);
            if (count ($tabVille)):
                $join = join (',',$tabVille);
              //  $cond = $ville == 2 ? " parent IN($join)" : " id = $ville AND parent = ($join)";
                $communes = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_LOCALITES','db')." WHERE parent IN($join)
                 ORDER BY name ASC ");
                return view ("bbmap.panneaux.listeCommunes",compact ('communes'))->render ();
            endif;
        }
    }

    /**
     * @throws \Throwable
     */
    public function trouverPanneaux(Request $request): RedirectResponse
    {
        $request->session()->forget('BillbordMapSelection');
        $datas = $request->all ();
        //dd($datas);
        unset($datas['_token'],$datas['formulairedeselection'],$datas['valider']);
        $this->makeData($datas, $request);
        return back();
    }
    
    public  function makeData(array $datas, Request $request){
        if (!empty($datas)):
            $condition = $this->condition ($datas);
            if ($condition !== ''):
                $pTable = DbTablesHelper::dbTable('DBTBL_REPORTINGS','db');
                $querie = "SELECT * FROM $pTable $condition";
                $donnees = FunctionController::arraySqlResult ($querie);
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
        $cond = "";
        $secteurs = array_key_exists ('secteur',$donnees) ? $donnees['secteur'] : [];
        $annonceurs = array_key_exists ('annonceurs',$donnees) ? $donnees['annonceurs'] : [];
        $villes = array_key_exists ('ville',$donnees) ? $donnees['ville'] : [];
        $communes = array_key_exists ('commune',$donnees) ? $donnees['commune'] : [];
        $dateFin = $donnees['dateFin'];
        $dateDebut = $donnees['dateDebut'];
            if (!empty($secteurs)):
                if ($dateFin >= $dateDebut):
                    $and = " where ";
                    $cond .= " $and date between '$dateDebut' and '$dateFin' ";
                    $this->recherche['Periode'] = [
                        'Debut' => FunctionController::date2Fr($dateDebut),
                        'Fin' => FunctionController::date2Fr($dateFin),
                    ];
                    $and = " and";
                        $dbLocalite = DbTablesHelper::dbTable('DBTBL_LOCALITES','db');
                        if (!empty($villes)):
                            if (!empty($communes)):
                                $and = " and";
                                $listCommune = implode(',',$communes);
                                $cond .= " $and localite in($listCommune)";
                                $this->recherche['Localite'] = $this->selection($communes,DbTablesHelper::dbTable('DBTBL_LOCALITES','db'));
                            else:
                                $and = " and";
                                $listeVille = implode(',',$villes);
                                $lesCommunesDesVilles = "SELECT id FROM $dbLocalite where parent in($listeVille)";
                                $cond .= " $and localite in($lesCommunesDesVilles)";
                                $this->recherche['Localite'] = $this->selection($villes,DbTablesHelper::dbTable('DBTBL_LOCALITES','db'));

                            endif;
                        else:
                            Session::flash('echec','Veuillez choisir au moins une localité!');
                            return back();
                        endif;
                    $and = " and";
                    if (!empty($secteurs)):
                        if (!empty($annonceurs)):
                            $and = " and";
                            $listeAnn = implode(',',$annonceurs);
                            $cond .= " $and annonceur in($listeAnn)";
                            $this->recherche['Annonceur'] = $this->selection($annonceurs,DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db'),'name');
                        // //C'est ce qui empechechait les secteurs dans la condition
                        else:
                            $listeSect = implode(',',$secteurs);
                            $cond .= " $and secteur in($listeSect)";
                            $this->recherche['Secteur'] = $this->selection($secteurs, DbTablesHelper::dbTable('DBTBL_SECTEURS','db'),'name');
                        endif;
                    endif;
                else:
                    Session::flash('echec','La date de fin ne peut être inférieure à la date de début.');
                endif;
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

    /**
     * @param array $result
     * @return string[]
     */
    public function makeMapDatas(array $result): array
    {
        $listeDesPoints = '';
        $listeDesPointsNew = '';
        $virg = '';
        $Bigdata = '';
        $tableauDesMap = [];
        $i = 0;
        $lesAnnonceurs = [];
        $dataRange = [];
        foreach($result as $row) :
            $tb = [];
            $lesAnnonceurs[] = $row['annonceur'];
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
                $dataRange[$row['localite']][$row['annonceur']]['campagne'][] = $row['campagnetitle'];
                $dataRange[$row['localite']][$row['annonceur']]['nb'] += $row['nombre'];
                $dataRange[$row['localite']][$row['annonceur']]['invest'] = $row['investissement'];
            endif;
        endforeach;
        $lesLocalites = Localite::whereIn('id', array_keys($dataRange))->get()->toArray();
        $dataAnnonceurs = Annonceur::whereIn('id', $lesAnnonceurs)->get()->toArray();
        $donneesAnnonceurs = [];
        foreach($dataAnnonceurs AS $t):
            $donneesAnnonceurs[$t['id']] = $t;    
        endforeach;
        //$points = [];
        foreach($lesLocalites AS $local):
            $lat = $local['latitude'];
            $long = $local['longitude'];
            $nomLocalite = $local['name'];
            $donnees = $dataRange[$local['id']];
            $points = self::makeMapPoint($lat, $long, count($donnees));
            $i = 0;
            foreach($donnees AS $annonceur_id => $tab):
                $latitude = $ponts[$i]['lat'];
                $longitude = $ponts[$i]['lon'];
                $nombrePanneaux = $tab['nb'];
                $annonceur = $donneesAnnonceurs[$t['id']]['raisonsociale'];
                $campagneTitle = "";
                $investissement = $tab['invest'];
                $animation = " google.maps.Animation.BOUNCE";
                $icon = 'http://i.stack.imgur.com/KOh5X.png#'.$i++;
                $listeDesPointsNew .=$virg.'new google.maps.Marker({
    			position: new google.maps.LatLng('.$latitude.', '.$longitude.'),
    			map: gm_map,
                title: "'.$campagneTitle.'",
                animation: "'.$animation.'",
              
                optimized: false
			
		})' ;

            $img = '';
            $Bigdata.=$virg.'{lat:"'.$latitude.'",lng:"'.$longitude.'",logo:"'.$img.'",secteur:"'.$secteur.'", annonceur:"'.$annonceur.'", localite:"'.$nomLocalite.'", nombrePanneaux:"'.$nombrePanneaux.'", investissement:"'.$investissement.'"}';
            $virg = ',';

            $tableauDesMap[$i] = [
                'Date' => FunctionController::date2Fr($row['date']),
                'titre' => $campagneTitle,
                'annonceur' => $annonceur,
                'localite' => $nomLocalite,
                'investissement' => $investissement,
                'nombre' => $tab['nb']
            ];		
                //$i++;
            endforeach;
        endforeach;
        /*        return [
            'listeDesPoints' => $listeDesPoints,
            'Bigdata' => $Bigdata,
            'TableauDesMap' => $tableauDesMap,
        ];//*/
        //dd($result, $dataRange, $lesLocalites, $points);
        foreach($result as $row) :
            $latitude = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_LOCALITES','db'),$row['localite'],'latitude');
            $longitude = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_LOCALITES','db'),$row['localite'],'longitude');
            $nomLocalite = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_LOCALITES','db'),$row['localite']);
            $nombrePanneaux = $row['nombre'];
            $annonceur = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db'),$row['annonceur']);
            $couleur = str_replace ('#','',FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db'),$row['annonceur'],'couleur'));

            $secteur = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_SECTEURS','db'),$row['secteur']);
            $campagneTitle = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLES','db'),$row['campagnetitle'],'title');
            $investissement = $row['investissement'];
            $animation = " google.maps.Animation.BOUNCE";
            $icon = 'http://i.stack.imgur.com/KOh5X.png#'.$i++;
            $listeDesPoints.=$virg.'new google.maps.Marker({
			position: new google.maps.LatLng('.$latitude.', '.$longitude.'),
			map: gm_map,
            title: "'.$campagneTitle.'",
            animation: "'.$animation.'",
          
            optimized: false
			
		})' ;

            $img = '';
            $Bigdata.=$virg.'{lat:"'.$latitude.'",lng:"'.$longitude.'",logo:"'.$img.'",secteur:"'.$secteur.'", annonceur:"'.$annonceur.'", localite:"'.$nomLocalite.'", nombrePanneaux:"'.$nombrePanneaux.'", investissement:"'.$investissement.'"}';
            $virg = ',';

            $tableauDesMap[$i] = [
                'Date' => FunctionController::date2Fr($row['date']),
                'titre' => $campagneTitle,
                'annonceur' => $annonceur,
                'localite' => $nomLocalite,
                'investissement' => $row['investissement'],
                'nombre' => $row['nombre']
            ];
        endforeach;

        return [
            'listeDesPoints' => $listeDesPoints,
            'Bigdata' => $Bigdata,
            'TableauDesMap' => $tableauDesMap,
        ];
    }
    public static function makeMapPoint($lat, $lon, int $nb): array{
        $pair = $nb % 2 == 0;
        $k = $pair ? $nb : $nb - 1;
        $coef = 100000;
        $inc = 10;
        $m = $inc * $k;
        $points = [];
        if(!$pair):
            $points[] = ['lat' => $lat, 'lon' => $lon];
        endif;
        for($i = 0 ; $i < $k/2; $i++):
            $dlon = 2 * $i * $inc;
            $dlat = $dlon - $m ;
            $points[] = ['lat' => $lat + $dlat / $coef , 'lon' => $lon + $dlon / $coef];
            $points[] = ['lat' => $lat - $dlat / $coef , 'lon' => $lon - $dlon / $coef];
        endfor;
        return $points;
    }
    public function tableauDesPanneaux()
    {

    }

}
