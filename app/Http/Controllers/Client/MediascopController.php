<?php

namespace App\Http\Controllers\Client;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MediascopController extends Controller
{
    private $data;
    public $is_valide = false;
    private $secteur;
    private $requiredata = array('date_debut', 'date_fin', 'secteur');
    private $listeDataItem = array('secteur', 'annonceur','campagne','nature','media', 'format','support');
    private $listeChart = array('annonceur','offretelecom','nature','media', 'format');
    private $date_debut;
    private $date_fin;
    private $condDate;
    private $condition = "" ;
    private $listeDesItems = ['media', 'nature', 'format', 'offretelecom', 'cible'] ;
    public $listeDesMedias = [];
    public $listeDesNatures = [];
    public $listeDesCibles = [];
    public $listeDesFormats = [];
    public $listeDesSecteurs = [];
    public $listeDesOffretelecoms = [];
    
    
    public $zeroMedias = [];
    public $zeroNatures = [];
    public $zeroCibles = [];
    public $zeroFormats = [];
    public $zeroSecteurs = [];
    public $zeroOffretelecoms = [];
    public $zeroAnnonceur = [] ;


    public $listeDesAnnonceurs = [] ;
    
    public $listeAnnonceurs = [] ;
    
    public $detailDesCampagnes = [];
    /*
     * 
     * Les donnees pour les graphiques
     */
    
    public $investParAnnonceur = [] ;
    public $investParSecteur = [] ;
    public $investParMedia = [] ;
    
    private $listeFormatMedia = [];
    public $lesDonnes = [];
    public $listeNature = [];
    public $listeFormat = [];
    public $listeCible = [];
    public $listeOperation = [];
    public $listeAnnonceur = [];
    public $listeSecteur = [];
    public $listeSupport = [];
    public $listeMedia = [];
    public $CampInfo = [];
    //public $investParAnnonceur = [];
    public $investParAnnonceurEtParMedia = [];
    public $investParNature = [];
    public $investParOffretelecom = [];
    public $investParCible = [];
    public $listDesCampagnes = [];
    public $topNDesCampagnes = [];
    public $partDeVoixParMedia = [];
    public $investParCibleParAnnonceur = [];
    public $investParOffretelecomParAnnonceur = [];
    public $investParNatureParAnnonceur = [];
    public $autreSecteur = 0;
    public $listMediaIDInvest = [];
    public $listMediaIDPartDeVoix = [];
    public $listAnnonceurInvest = [];
    public $investParAnnonceurEtParMediaJSON = [];
    public $investParMediaParAnnonceur = [];
    public $listMediaId = [];
    public $listDesCouleursDesAnnonceur = [];
    public $listCible = [];
    public $listNature = [];
    public $listOffretelecom = [];
    public $listMedia = [];
    public $listMediaInvest = [];
    public $listDesAnnonceur = [];
    public $lesspeednews = [];

    /**
     *
     * @param type $date_debut
     * @param type $date_fin
     * @param type $secteur
     */
    public function __construct($pos) {
        $this->data = $pos;
        $this->validatee();
        if ($this->is_valide):
            $secteur = $this->data['secteur'];
            $this->listeDesSecteurs = $secteur ;
            $this->secteur = count($secteur) ? '(' . join(',', $secteur) . ')' : '';
            $this->makeCondition();
            $this->makeListeItems();
            $this->makeQuery();
            $this->makeData();
            //$this->makeQueryAutreSecteur();
        endif;
    }

    private function validatee() {
        $validatevalue = true;
        $inc = 0;
        $nbq = count($this->requiredata);
        while ($validatevalue and $inc < $nbq) :
            $value = $this->requiredata[$inc];
            $validatevalue = array_key_exists($value, $this->data);
            $inc++;
        endwhile;
        $this->is_valide = $validatevalue;
        if ($this->is_valide) :
            $this->date_debut = $this->data['date_debut'];
            $this->date_fin = $this->data['date_fin'];
            $this->is_valide = $this->date_debut < $this->date_fin;
            $this->condDate = " date BETWEEN '" . $this->date_debut . "' AND '" . $this->date_fin . "'";
        endif;
    }


    private function makeQuery() {
        if(empty($this->lesDonnes)):
            $this->makeCondition();
            $laRequete = "SELECT * 
                FROM 
                ". DbTablesHelper::dbTable('DBTBL_REPORTINGS','db') . "
                WHERE " . $this->condition . " ORDER BY date ASC";
            $this->lesDonnes = FunctionController::arraySqlResult($laRequete);            
        endif;
    }

    private function makeCondition() {
        if($this->condition == "" ):
            $and = "" ;
            foreach ($this->listeDataItem AS $item):
                if(array_key_exists($item, $this->data)):
                    $var = "liste".ucfirst($item) ;
                    $this->$var = $this->data[$item];
                    $this->condition .= " $and $item IN (" . join(',', $this->$var) . ") ";
                    $and = " AND " ;
                endif;
            endforeach;
            $this->condition .= " $and  date BETWEEN '" . $this->date_debut . "' AND '" . $this->date_fin . "'" ;
        endif;       
    }
        
    private function getListItems($item, $dbTable){   
        $var = "listeDes".ucfirst($item)."s"  ;
        $zero = "zero" . ucfirst($item)."s"  ;
        $tab = [] ;
        if (empty($this->$var)):
            $sql = "SELECT id, name FROM $dbTable WHERE id IN (SELECT DISTINCT $item FROM 
                ". DbTablesHelper::dbTable('DBTBL_REPORTINGS','db') . "
                WHERE " . $this->condition . ") ORDER BY id ASC";
            $r = DB::select(DB::raw($sql));
            foreach ($r AS $row):
                $this->$var[$row->id] = $row->name;
                $tab[$row->name] = 0  ;
            endforeach;
            $this->$zero = $tab ;
        endif;
    }
        
    private function getListAnnonceurs(){   
        if (empty($this->liteDesAnnonceurs)):
            $sql = "SELECT * FROM ". DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db') . " WHERE id IN (SELECT DISTINCT annonceur FROM 
                ". DbTablesHelper::dbTable('DBTBL_REPORTINGS','db') . "
                WHERE " . $this->condition . ") ORDER BY id ASC";
            $r = DB::select(DB::raw($sql));
            $i = 0 ;
            foreach ($r AS $row):
                //dump($row) ;
                $this->listeDesAnnonceurs[$row->id] = $this->convertObjectToArray($row);
                $this->listeDesAnnonceurs[$row->id]["inc"] = $i++;
                $this->zeroAnnonceur[$row->raisonsociale] = 0;
                $this->listeAnnonceurs[$row->raisonsociale] = $this->listeDesAnnonceurs[$row->id] ;
            endforeach;
        endif;
    }
    private function makeListeItems(){
        foreach ($this->listeDesItems AS $item):
            $dbTable = DbTablesHelper::dbTable('DBTBL_'.strtoupper($item).'S','db') ;
            $this->getListItems($item, $dbTable) ;
        endforeach;
    }
    public function makeData() {
        $this->getListAnnonceurs() ;
        //$nbJours = FunctionController::diff_date($this->date_debut, $this->date_fin, "%a") ;
        $this->makeListeItems() ;
        //dd($this->listeDesMedias, $this->listeDesFormats,  $this->listeDesCibles,  $this->listeDesNatures, $this->listeDesOffretelecoms, $this->liteDesAnnonceurs, $nbJours) ;
        $detailDesCampagnes = [];
        $investParAnnonceurEtParMedia = [];
        $partDeVoixParMedia = [];
        $campagneinvest = [];
        foreach ($this->lesDonnes as $row) :
            $cid = $row['campagnetitle'];
            foreach($row AS $it => $v):
                $$it = $v ;
            endforeach;
            $annonceur = $this->listeDesAnnonceurs[$row["annonceur"]]["raisonsociale"];
            $cout = $tarif + $investissement ;
            if(!array_key_exists($secteur, $this->investParSecteur)):
                $this->investParSecteur[$secteur] = $cout;
            else:
                $this->investParSecteur[$secteur] += $cout;
            endif;
            if(!array_key_exists($annonceur, $this->investParAnnonceur)):
                $this->investParAnnonceur[$annonceur] = [
                    "media"         => $this->zeroMedias,
                    "nature"        => $this->zeroNatures,
                    "offretelecom"  => $this->zeroOffretelecoms
                ];
            endif;
            $this->addUniqueValueMultiArray($this->investParAnnonceur[$annonceur], "media", $this->listeDesMedias[$media], $cout) ;
            $this->addUniqueValueMultiArray($this->investParAnnonceur[$annonceur], "nature", $this->listeDesNatures[$nature], $cout) ;
            $this->addUniqueValueMultiArray($this->investParAnnonceur[$annonceur], "offretelecom", $this->listeDesOffretelecoms[$offretelecom], $cout) ;
            $this->addUniqueValueMultiArray($this->investParMedia,  $this->listeDesMedias[$media], $this->listeDesFormats[$format], $cout) ;
            
            if (!array_key_exists($this->listeDesMedias[$media], $partDeVoixParMedia)):
                $partDeVoixParMedia[$this->listeDesMedias[$media]] = $this->zeroAnnonceur ;
            endif;
            if (!array_key_exists($annonceur, $partDeVoixParMedia[$this->listeDesMedias[$media]])):
                $partDeVoixParMedia[$this->listeDesMedias[$media]][$annonceur] = 0;
            endif;
            /** 
             *
             * La liste des campagnes
             */
            if (!array_key_exists($cid, $campagneinvest)):
                $campagneinvest[$cid] = 0;
            endif;
            $campagneinvest[$cid] += $cout;
            $partDeVoixParMedia[$this->listeDesMedias[$media]][$annonceur] += 1;
            if (!array_key_exists($cid, $detailDesCampagnes)) :
                $detailDesCampagnes[$cid] = [
                    'tid' => $cid, 'Secteur' => $secteur,
                    'Annonceur' => $annonceur, 'Operation' => $row["operation"], 'Titre' => $cid,
                    'param' => [
                        'Format' => [$this->listeDesMedias[$media] => [$this->listeDesFormats[$format]]],
                        'Nature' => [$this->listeDesMedias[$media] => [$this->listeDesNatures[$nature]]]
                    ],
                    'Format' => [$this->listeDesFormats[$format]],
                    'media' => [$this->listeDesMedias[$media]],
                    'support' => [$row['support']],
                    'invest' => [$this->listeDesMedias[$media] => $cout],
                    'investTotal' => $cout,
                    'insertionTotal' => 1,
                    'insertion' => [$this->listeDesMedias[$media] => 1],
                    'nature' => [$this->listeDesNatures[$nature]],
                    'offretelecom' => [$this->listeDesOffretelecoms[$offretelecom]],
                    'cible' => [$cible]
                ];
            else :
                $this->addUniqueValueInTab($detailDesCampagnes[$cid]['Format'], $this->listeDesFormats[$format]);
                $this->addUniqueValueInTab($detailDesCampagnes[$cid]['media'], $this->listeDesMedias[$media]);
                $this->addUniqueValueInTab($detailDesCampagnes[$cid]['nature'], $this->listeDesNatures[$nature]);
                $this->addUniqueValueInTab($detailDesCampagnes[$cid]['offretelecom'], $this->listeDesOffretelecoms[$offretelecom]);
                $this->addUniqueValueInTab($detailDesCampagnes[$cid]['cible'], $cible);
                $this->addUniqueValueInMultiArray($detailDesCampagnes[$cid]['param']['Format'], $this->listeDesMedias[$media], $format);
                $this->addUniqueValueInMultiArray($detailDesCampagnes[$cid]['param']['Nature'], $this->listeDesMedias[$media], $nature);
                $detailDesCampagnes[$cid]['Format'] = $this->addUniqueValue($detailDesCampagnes[$cid]['Format'], $this->listeDesFormats[$format]);
                $detailDesCampagnes[$cid]['media'] = $this->addUniqueValue($detailDesCampagnes[$cid]['media'], $this->listeDesMedias[$media]);
                $detailDesCampagnes[$cid]['nature'] = $this->addUniqueValue($detailDesCampagnes[$cid]['nature'], $this->listeDesNatures[$nature]);
                $detailDesCampagnes[$cid]['offretelecom'] = $this->addUniqueValue($detailDesCampagnes[$cid]['offretelecom'], $this->listeDesOffretelecoms[$offretelecom]);
                $detailDesCampagnes[$cid]['cible'] = $this->addUniqueValue($detailDesCampagnes[$cid]['cible'], $cible);
                $detailDesCampagnes[$cid]['support'] = $this->addUniqueValue($detailDesCampagnes[$cid]['support'], $support);
                if (array_key_exists($this->listeDesMedias[$media], $detailDesCampagnes[$cid]['invest'])):
                    $detailDesCampagnes[$cid]['invest'][$this->listeDesMedias[$media]] += $cout;
                    $detailDesCampagnes[$cid]['insertion'][$this->listeDesMedias[$media]] += 1;
                else:
                    $detailDesCampagnes[$cid]['invest'][$this->listeDesMedias[$media]] = $cout;
                    $detailDesCampagnes[$cid]['insertion'][$this->listeDesMedias[$media]] = 1;
                endif;
                $detailDesCampagnes[$cid]['investTotal'] += $cout;
                $detailDesCampagnes[$cid]['insertionTotal'] += 1;
            endif;

            if (!array_key_exists($annonceur, $investParAnnonceurEtParMedia)) :
                $investParAnnonceurEtParMedia[$annonceur] = [];
            endif;
            $this->incrementValueInArray($investParAnnonceurEtParMedia[$annonceur], $this->listeDesMedias[$media], $cout);
        endforeach;
        $this->detailDesCampagnes = $detailDesCampagnes ;
        $tab = $this->transformData();
       // dd($this->listeDesMedias, $tab, $this->listeDesAnnonceurs, $partDeVoixParMedia, $this->investParAnnonceur, $detailDesCampagnes);
        /*
        $this->detailDesCampagnes = $detailDesCampagnes;
        $this->orderByMediaId($investParAnnonceurEtParMedia); //
        $this->orderArray($investParAnnonceurEtParMedia);
        $this->partDeVoixParMedia = $partDeVoixParMedia;
        arsort($campagneinvest);
        $this->topNDesCampagnes = array_slice($campagneinvest, 0, 10, true);
        $this->listDesCampagnes = array_keys($detailDesCampagnes);
        //*/
    }
    
    public function transformData(){
        $invAnnMedia = [] ;
        foreach($this->investParAnnonceur AS $k => $r):
            $data = [];
            foreach($this->listeDesMedias AS $media):
                $data[] = $r["media"][$media] ;
            endforeach;
            $color = str_replace("#", "", $this->listeAnnonceurs[$k]["couleur"]) ;
            $invAnnMedia[] = [
                        "name"      => $k ,
                        "data"      => $data,
                        "color"     => "#".$color
                    ] ;
        endforeach;
        return $invAnnMedia ;
    }
    public function listMediaRange() {
        $tab = [];
        foreach ($this->listeDesMedias AS  $value):
            $tab[] = $value;
        endforeach;
        return $tab;
    }
    private function convertObjectToArray($obj) {
        $tab = [];
        foreach ($obj AS $key => $value):
            $tab[$key] = $value;
        endforeach;
        return $tab;
    }

    
    private function pushInArrayKey(&$tab, $key, $val) {
        if (!array_key_exists($key, $tab)) :
            $tab[$key] = $val;
        endif;
    }

    private function pushInArray(&$tab, $val, $unique = true) {
        if ($unique) :
            if (!in_array($val, $tab)):
                $tab[] = $val;
            endif;
        else:
            $tab[] = $val;
        endif;
    }

    private function addUniqueValue($tab, $val) {
        if (!in_array($val, $tab)):
            $tab[] = $val;
        endif;
        return $tab;
    }

    private function addUniqueValueInTab(&$tab, $val) {
        if (!in_array($val, $tab)):
            $tab[] = $val;
        endif;
    }

    private function addUniqueValueInMultiArray(&$tab, $key, $val) {
        if (!array_key_exists($key, $tab)):
            $tab[$key][] = $val;
        else:
            if (!in_array($val, $tab[$key])):
                $tab[$key][] = $val;
            endif;
        endif;
    }

    private function addUniqueValueMultiArray(&$tab, $key1, $key2, $val) {
        if (!array_key_exists($key1, $tab)):
            $tab[$key1][$key2] = $val;
        else :
            if (!array_key_exists($key2, $tab[$key1])):
                $tab[$key1][$key2] = $val;
            else :
                $tab[$key1][$key2] += $val;
            endif;
        endif;
    }

    private function incrementValueInArray(&$tab, $key, $val) {
        if (!array_key_exists($key, $tab)):
            $tab[$key] = intval($val);
        else :
            $tab[$key] += intval($val);
        endif;
    }



    public function getSpeednews()
    {
        $tab = [];
        if($this->is_valide):
            $sql = "SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db')." WHERE campagnetitle IN (".join(',', $this->listDesCampagnes).")";
            $result = FunctionController::arraySqlResult($sql);
            foreach ($result as $value):
                $tab[$value['id']] = $value;
            endforeach;
            $this->lesspeednews = $tab;
        endif;
        return $tab;
    }


    public function makeSpeednewsTable(){
        $inc = 0;
        $speednewTbody = "";
        $lesspeednews = $this->getSpeednews();
        foreach ($lesspeednews as $lesspeednew):
            $inc++;
            if($lesspeednew['media'] != 6):
                $lesupport = $this->detailDesCampagnes[$lesspeednew['campagnetitle']]['support'][0];
            else:
                $lesupport = FunctionController::getChampTable(DbTablesHelper::dbTable ("DBTBL_AFFICHAGE_DIMENSIONS",'db'),$lesspeednew['support'],'code');
            endif;
            $illustrator = ReportingController::illustrationCampagne($lesspeednew['campagnetitle'],$lesspeednew['media']);
            $sqlfilecampsp = "SELECT fichier FROM 
                ".DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','db')."
                 WHERE campagnetitle = {$lesspeednew['campagnetitle']} 
                 AND media = {$lesspeednew['media']} 
                 AND type IN ('png', 'jpg', 'gif', 'GIF', 'PNG', 'JPG') LIMIT 0, 1";
            $resufilesp = FunctionController::arraySqlResult($sqlfilecampsp);
            $limagespmedia = "" ;
            if(count($resufilesp) >= 1) :
                $imageDir = "";
                $limagespmedia .= '<img style="width: 99%;" src="' . $imageDir . 'upload'.DIRECTORY_SEPARATOR.'campagnes'.DIRECTORY_SEPARATOR. $lesspeednew['campagnetitle'].DIRECTORY_SEPARATOR. $resufilesp[0]['fichier'].'" >' ;
                $image =  $imageDir.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'campagnes' .DIRECTORY_SEPARATOR.$lesspeednew['campagnetitle'] .DIRECTORY_SEPARATOR. $resufilesp[0]['fichier'] ;
            endif;

            $title = $this->detailDesCampagnes[$lesspeednew['campagnetitle']]['Titre'];
            $medianame = $this->detailDesCampagnes[$lesspeednew['campagnetitle']]['media'][0];
            $secteurname =  $this->listeSecteur[$this->detailDesCampagnes[$lesspeednew['campagnetitle']]['Secteur']]['name'];
            $raisonsociale = $this->detailDesCampagnes[$lesspeednew['campagnetitle']]['Annonceur'];
            $route = route('client.detail', $lesspeednew['campagnetitle']);

            $speednewTbody .= view ("clients.speednews.speedNewTbody",compact ('lesspeednew','route','title','medianame','raisonsociale','secteurname','lesupport','illustrator'))->render ();
        endforeach;
        return view ("clients.speednews.tableauSpeednews",compact ('speednewTbody'))->render ();
    }

}
