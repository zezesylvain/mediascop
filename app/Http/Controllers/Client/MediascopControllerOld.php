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
    private $requiredata = array('jour1', 'jour2', 'mois1', 'mois2', 'annee1', 'annee2', 'secteur');
    private $listeDataItem = array('nature','media','offretelecom','cible','format','support','annonceur','secteur');
    private $date1;
    private $date2;
    private $condDate;
    private $conditionPrincipale;
    private $condCampagnetitle;
    private $condSupport;
    private $condFormat;
    private $condNature;
    private $listeFormatMedia = [];
    public $lesDonnes;
    public $listeOffretelecom = [];
    public $listeNature = [];
    public $listeFormat = [];
    public $listeCible = [];
    public $listeOperation = [];
    public $listeAnnonceur = [];
    public $listeSecteur = [];
    public $listeSupport = [];
    public $listeMedia = [];
    public $listeDesMedias = [];
    public $CampInfo = [];
    public $detailDesCampagnes = [];
    public $investParAnnonceur = [];
    public $investParAnnonceurEtParMedia = [];
    public $investParMedia = [];
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
            $this->secteur = count($secteur) ? '(' . join(',', $secteur) . ')' : '';
            $this->makeListeItem();
            $this->makeQuery();
            $this->makeData();
            $this->makeQueryAutreSecteur();
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
            $jour1 = $this->data['jour1'];
            $jour2 = $this->data['jour2'];
            $mois1 = $this->data['mois1'];
            $mois2 = $this->data['mois2'];
            $annee1 = $this->data['annee1'];
            $annee2 = $this->data['annee2'];
            $mois1s = intval($mois1) < 10 ? "0" . $mois1 : $mois1;
            $mois2s = intval($mois2) < 10 ? "0" . $mois2 : $mois2;
            $jour1s = intval($jour1) < 10 ? "0" . $jour1 : $jour1;
            $jour2s = intval($jour2) < 10 ? "0" . $jour2 : $jour2;
            $this->date1 = "$annee1-$mois1s-$jour1s";
            $this->date2 = "$annee2-$mois2s-$jour2s";
            $this->is_valide = $this->date1 < $this->date2;
            $this->condDate = " AND p.dateajout BETWEEN '" . $this->date1 . "' AND '" . $this->date2 . "'";
        endif;
    }


    private function makeQuery() {
        $this->makeCondition();
        $laRequete = "SELECT c.*, p.support, (p.tarif + p.investissement) AS tarifs 
            FROM 
            ". DbTablesHelper::dbTable('DBTBL_PUBS','db') . " p, 
            ". DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db') . " c 
            WHERE p.campagne = c.id  " . $this->conditionPrincipale;
        $this->lesDonnes = FunctionController::arraySqlResult($laRequete);
    }

    private function makeCondition() {
        $this->getListeCampagneTitle();
        $this->getCondNature();
        $this->getCondMedia();
        $this->conditionPrincipale = $this->condCampagnetitle . $this->condSupport . $this->condFormat . $this->condNature . $this->condDate;
    }

    private function makeQueryAutreSecteur(){
        $laRequete = " SELECT
                SUM(p.tarif + p.investissement) AS somme
        FROM
                ".DbTablesHelper::dbTable('DBTBL_PUBS','db')." p,
                ".DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db')." c,
                ".DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db')." ct,
                ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')." so,
                ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','db')." o
                
        WHERE

                p.campagne = c.id AND
                c.campagnetitle = ct.id AND
                ct.operation = o.id AND
                o.annonceur = so.id AND
                so.secteur NOT IN (".$this->secteur.")
          
                ".$this->condDate." ";

        $DonnesAutreSecteur = FunctionController::arraySqlResult($laRequete);
        $this->autreSecteur = $DonnesAutreSecteur[0]['somme'];
    }

    private function getListeCampagneTitle() {
        if (array_key_exists('campagne', $this->data)):
            $result = $this->data['campagne'];
        elseif (array_key_exists('annonceur', $this->data)):
            $annonceurlist = $this->data['annonceur'];
            $requete = " SELECT  DISTINCT c.campagnetitle
            FROM  " . DbTablesHelper::dbTable('DBTBL_PUBS','db') . " p, " . DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db') . " c,  " . DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db') . " ct,  " . DbTablesHelper::dbTable('DBTBL_OPERATIONS','db') . " o WHERE p.campagne = c.id AND c.campagnetitle = ct.id AND  ct.operation = o.id AND  o.annonceur IN (" . join(',', $annonceurlist) . ") " . $this->condDate;
            $res = FunctionController::arraySqlResult($requete);
            $result = $this->convertArray2Array ($res);
        else:
            $secteurlist = $this->data['secteur'];
            $requete = " SELECT  DISTINCT c.campagnetitle FROM  
                " . DbTablesHelper::dbTable('DBTBL_PUBS','db') . " p,  
                " . DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db') . " c,  
                " . DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db').
                " ct, 
                " . DbTablesHelper::dbTable('DBTBL_OPERATIONS','db') . " o,
                " . DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db') . " a 
                WHERE p.campagne = c.id AND c.campagnetitle = ct.id AND  ct.operation = o.id AND  o.annonceur = a.id AND a.secteur IN (" . join(',', $secteurlist) . ") " . $this->condDate;
            $res = FunctionController::arraySqlResult($requete);
            $result = $this->convertArray2Array ($res);
        endif;
        $this->ListeCampgnes = $result;
        $this->condCampagnetitle = " AND c.campagnetitle IN (" . join(',', $result) . ")";
    }

    private function convertObject2Array($arrayObj) {
        $array = [];
        foreach ($arrayObj AS $obj):
            $array[] = $obj->campagnetitle;
        endforeach;
        return $array;
    }

    private function convertArray2Array($array) {
        $arr = [];
        foreach ($array AS $obj):
            $arr[] = $obj['campagnetitle'];
        endforeach;
        return $arr;
    }

    private function getCondNature() {
        $cond = "";
        if (array_key_exists('nature', $this->data)):
            $naturelist = $this->data['nature'];
            $cond = " AND c.nature IN (" . join(',', $naturelist) . ") ";
        endif;
        $this->condNature = $cond;
    }

    private function getCondMedia() {
        $condSupport = "";
        $condFormat = "";
        if (array_key_exists('media', $this->data)):
            $medialist = $this->data['media'];
            if (array_key_exists('support', $this->data)):
                $support = $this->data['support'];
                $condSupport = " AND p.support IN (" . join(',', $support) . ")";
            else:
                $condSupport = " AND p.support IN (SELECT id FROM " . DbTablesHelper::dbTable('DBTBL_SUPPORTS','db') . " WHERE media IN (" . join(',', $medialist) . "))";
            endif;
            if (array_key_exists('format', $this->data)):
                $format = $this->data['format'];
                $condFormat = " AND p.support IN (" . join(',', $format) . ")";
            endif;
        endif;
        $this->condSupport = $condSupport;
        $this->condFormat = $condFormat;
    }

    public function makeData() {
        $detailDesCampagnes = [];
        $investParAnnonceurEtParMedia = [];
        $partDeVoixParMedia = [];
        $campagneinvest = [];
        foreach ($this->lesDonnes as $row) :
            $cid = $row['campagnetitle'];
            $this->getCampagneInfo($cid);
            $laCampagne = $this->CampInfo[$cid];
            $this->getListeFormat($row['format']);
            $laSociete = $laCampagne["operation"]["annonceur"];
            $this->getListeAnnonceur($laSociete);
            $couleur = $this->listeAnnonceur[$laSociete]["couleur"];
            $lannonceur = $this->listeAnnonceur[$laSociete]["name"];
            $loffretelecom = $this->listeOffretelecom[$laCampagne["offretelecom"]]['name'];
            $secteur = $laCampagne["operation"]["secteur"];
            $secteurName = $secteur;
            $operationName = $laCampagne["operation"]["name"];
            $campagnetitle = $laCampagne["title"];
            $cleCamp = $cid;
            $mediaId = $this->listeMedia[$this->listeFormat[$row['format']]['media']]['id'];
            $formatName = $this->listeFormat[$row['format']]['name'];
            $mediaName = $this->listeMedia[$mediaId]['name'];
            $supportname = $this->listeSupport[$row['support']]['name'];
            $nature = $this->listeNature[$row['nature']]['name'];
            $cible = $this->listeCible[$row['cible']]['name'];
            $leTarif = $row['tarifs'];
            $this->addUniqueValueMultiArray($this->investParCibleParAnnonceur, $lannonceur, $row['cible'], $leTarif);
            $this->addUniqueValueMultiArray($this->investParOffretelecomParAnnonceur, $lannonceur, $loffretelecom, $leTarif);
            $this->addUniqueValueMultiArray($this->investParMediaParAnnonceur, $mediaName, $lannonceur, $leTarif);
            $this->addUniqueValueMultiArray($this->investParNatureParAnnonceur, $lannonceur, $nature, $leTarif);
            $this->pushInArray($this->listOffretelecom, $loffretelecom);
            $this->pushInArray($this->listDesAnnonceur, $lannonceur);
            $this->pushInArray($this->listNature, $nature);
            $this->pushInArray($this->listCible, $cible);
            $this->pushInArray($this->listMediaId, $mediaId);
            $this->listMediaInvest[$mediaId] = $mediaName;
            $this->incrementValueInArray($this->listMediaIDInvest, $mediaId, $leTarif);
            $this->incrementValueInArray($this->listAnnonceurInvest, $lannonceur, $leTarif);
            $this->pushInArrayKey($this->listDesCouleursDesAnnonceur, $lannonceur, $couleur);
            $this->investParCible[$cible] = array_key_exists($cible, $this->investParCible) ? $this->investParCible[$cible] + $leTarif : $leTarif;
            $this->investParNature[$nature] = array_key_exists($nature, $this->investParNature) ? $this->investParNature[$nature] + $leTarif : $leTarif;
            $this->investParOffretelecom[$loffretelecom] = array_key_exists($loffretelecom, $this->investParOffretelecom) ? $this->investParOffretelecom[$loffretelecom] + $leTarif : $leTarif;
            if (!array_key_exists($mediaName, $partDeVoixParMedia)):
                $partDeVoixParMedia[$mediaName] = [];
            endif;
            if (!array_key_exists($lannonceur, $partDeVoixParMedia[$mediaName])):
                $partDeVoixParMedia[$mediaName][$lannonceur] = 0;
            endif;
            /**
             *
             * La liste des campagnes
             */
            if (!array_key_exists($cleCamp, $campagneinvest)):
                $campagneinvest[$cleCamp] = 0;
            endif;
            $campagneinvest[$cleCamp] += $leTarif;
            $partDeVoixParMedia[$mediaName][$lannonceur] += 1;
            if (!array_key_exists($cleCamp, $detailDesCampagnes)) :
                $detailDesCampagnes[$cleCamp] = array(
                    'tid' => $cleCamp, 'Secteur' => $secteurName,
                    'Annonceur' => $lannonceur, 'Operation' => $operationName, 'Titre' => $campagnetitle,
                    'param' => array(
                        'Format' => array($mediaName => array($formatName)),
                        'Nature' => array($mediaName => array($nature))
                    ),
                    'Format' => array($formatName),
                    'media' => array($mediaName),
                    'invest' => array($mediaName => $leTarif),
                    'investTotal' => $row['tarifs'],
                    'insertionTotal' => 1,
                    'insertion' => array($mediaName => 1),
                    'nature' => array($nature),
                    'offretelecom' => array($loffretelecom),
                    'cible' => array($cible)
                );
                $detailDesCampagnes[$cleCamp]['support'][] = $supportname;
            else :
                $this->addUniqueValueInTab($detailDesCampagnes[$cleCamp]['Format'], $formatName);
                $this->addUniqueValueInTab($detailDesCampagnes[$cleCamp]['media'], $mediaName);
                $this->addUniqueValueInTab($detailDesCampagnes[$cleCamp]['nature'], $nature);
                $this->addUniqueValueInTab($detailDesCampagnes[$cleCamp]['offretelecom'], $loffretelecom);
                $this->addUniqueValueInTab($detailDesCampagnes[$cleCamp]['cible'], $cible);
                $this->addUniqueValueInMultiArray($detailDesCampagnes[$cleCamp]['param']['Format'], $mediaName, $formatName);
                $this->addUniqueValueInMultiArray($detailDesCampagnes[$cleCamp]['param']['Nature'], $mediaName, $nature);
                $detailDesCampagnes[$cleCamp]['Format'] = $this->addUniqueValue($detailDesCampagnes[$cleCamp]['Format'], $formatName);
                $detailDesCampagnes[$cleCamp]['media'] = $this->addUniqueValue($detailDesCampagnes[$cleCamp]['media'], $mediaName);
                $detailDesCampagnes[$cleCamp]['nature'] = $this->addUniqueValue($detailDesCampagnes[$cleCamp]['nature'], $nature);
                $detailDesCampagnes[$cleCamp]['offretelecom'] = $this->addUniqueValue($detailDesCampagnes[$cleCamp]['offretelecom'], $loffretelecom);
                $detailDesCampagnes[$cleCamp]['cible'] = $this->addUniqueValue($detailDesCampagnes[$cleCamp]['cible'], $cible);
                $detailDesCampagnes[$cleCamp]['support'] = $this->addUniqueValue($detailDesCampagnes[$cleCamp]['support'], $supportname);
                if (array_key_exists($mediaName, $detailDesCampagnes[$cleCamp]['invest'])):
                    $detailDesCampagnes[$cleCamp]['invest'][$mediaName] += $leTarif;
                    $detailDesCampagnes[$cleCamp]['insertion'][$mediaName] += 1;
                else:
                    $detailDesCampagnes[$cleCamp]['invest'][$mediaName] = $leTarif;
                    $detailDesCampagnes[$cleCamp]['insertion'][$mediaName] = 1;
                endif;
                $detailDesCampagnes[$cleCamp]['investTotal'] += $leTarif;
                $detailDesCampagnes[$cleCamp]['insertionTotal'] += 1;
            endif;

            if (!array_key_exists($lannonceur, $investParAnnonceurEtParMedia)) :
                $investParAnnonceurEtParMedia[$lannonceur] = [];
            endif;
            $this->incrementValueInArray($investParAnnonceurEtParMedia[$lannonceur], $mediaName, $leTarif);
        endforeach;
        $this->detailDesCampagnes = $detailDesCampagnes;
        $this->orderByMediaId($investParAnnonceurEtParMedia); //
        $this->orderArray($investParAnnonceurEtParMedia);
        $this->partDeVoixParMedia = $partDeVoixParMedia;
        arsort($campagneinvest);
        $this->topNDesCampagnes = array_slice($campagneinvest, 0, 10, true);
        $this->listDesCampagnes = array_keys($detailDesCampagnes);
    }

    private function getCampagneInfo($cid) {
        if (!array_key_exists ($cid,$this->CampInfo)):
            $sqlCampagnes = " SELECT * FROM " . DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db') . "  WHERE id = $cid";
            $rct = DB::select(DB::raw($sqlCampagnes));
            $rr = $this->convertObjectToArray($rct[0]);
            $this->getListeItem($rr["offretelecom"],"offretelecom");
            $operation = $rr["operation"];
            $op = $this->getListeOperation($operation);
            $this->CampInfo[$cid] = $rr;
            $this->CampInfo[$cid]["operation"] = $op;
        endif;
    }

    private function getListeFormat($fid) {
        if (!array_key_exists($fid, $this->listeFormat)):
            $sql = "SELECT name, media FROM " . DbTablesHelper::dbTable('DBTBL_FORMATS','db') . " WHERE id = $fid";
            $r = DB::select(DB::raw($sql));
            $this->listeFormat[$fid] = $r[0]->name;
            if (!array_key_exists($r[0]->media, $this->listeMedia)):
                $this->listeMedia[$r[0]->media] = $this->listeDesMedias[$r[0]->media];
            endif;
            $this->getListeFormatMedia($fid);
        endif;
    }

    private function getListeSupport($id) {
        if (!array_key_exists($id, $this->listeSupport)):
            $sql = "SELECT name, media FROM " . DbTablesHelper::dbTable('DBTBL_SUPPORTS','db') . " WHERE id = $id";
            $r = DB::select(DB::raw($sql));
            $this->listeSupport[$id] = $r[0]->name;
        endif;
    }

    private function getListeAnnonceur($id) {
        if (!array_key_exists($id, $this->listeAnnonceur)):
            $sql = "SELECT * FROM " . DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db') . " WHERE id = $id";
            $r = DB::select(DB::raw($sql));
            $this->listeAnnonceur[$id] = $this->convertObjectToArray($r[0]);
        endif;
    }

    private function getListeFormatMedia($fid) {
        if (!array_key_exists($fid, $this->listeFormatMedia)):
            $sql = "SELECT media FROM " . DbTablesHelper::dbTable("DBTBL_FORMATS",'db') . " WHERE id = $fid";
            $r = DB::select(DB::raw($sql));
            $media = $r[0]->media;
            $sqlm = "SELECT id FROM " . DbTablesHelper::dbTable("DBTBL_FORMATS",'db') . " WHERE media = $media";
            $rm = DB::select(DB::raw($sqlm));
            foreach ($rm AS $row):
                if (!array_key_exists($row->id, $this->listeFormatMedia)):
                    $this->listeFormatMedia[$row->id] = $media;
                endif;
            endforeach;
        endif;
    }

    private function getListeItem($itemId, $item, $colname = "name") {
        $listeItem = "liste" . ucfirst($item);
        $table = $item.'s';
        $dbTable = FunctionController::getTableName($table);
        if (!array_key_exists($itemId, $this->$listeItem)):
            $sql = "SELECT $colname FROM $dbTable WHERE id = $itemId";
            $r = FunctionController::arraySqlResult($sql);
            $this->$listeItem[$itemId] = $r[0][$colname];
        endif;
    }

    private function makeListeItem(){
        foreach ($this->listeDataItem as $item):
            $listeItem = "liste" . ucfirst($item);
            $table = FunctionController::getTableName ($item.'s');
            $sql = "SELECT * FROM {$table} ";
            $r = FunctionController::arraySqlResult($sql);
            $t = [];
            foreach ($r as $value):
                $t[$value['id']] = $value;
            endforeach;
            $this->$listeItem = $t;
        endforeach;
    }

    private function getListeOperation($id) {
        if (!array_key_exists($id, $this->listeOperation)):
            $sql = " SELECT a.secteur, o.annonceur, o.name FROM " . DbTablesHelper::dbTable('DBTBL_OPERATIONS','db') . "  o, " . DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db') . "  a WHERE o.annonceur = a.id AND o.id = $id";
            $r = DB::select(DB::raw($sql));
            $rr = $this->convertObjectToArray($r[0]);
            $this->listeOperation[$id] = $rr;
            $this->getListeAnnonceur($rr["annonceur"]);
            $this->getListeItem($rr["secteur"], "secteur");
        endif;
        return $this->listeOperation[$id];
    }

    private function convertObjectToArray($obj) {
        $tab = [];
        foreach ($obj AS $key => $value):
            $tab[$key] = $value;
        endforeach;
        return $tab;
    }

    public function getListeDesMedias() {
        if (empty($this->listeDesMedias)):
            $sql = "SELECT id, name FROM " . DbTablesHelper::dbTable('DBTBL_MEDIAS');
            $r = DB::select(DB::raw($sql));
            foreach ($r AS $row):
                $this->listeDesMedias[$row->id] = $row->name;
            endforeach;
        endif;
        //dd($this->listeDesMedias);
    }

    public function getListeDesOffreTelecoms() {
        if (empty($this->listeOffretelecom)):
            $sql = "SELECT id, name FROM " . DbTablesHelper::dbTable('DBTBL_OFFRE_TELECOMS','db');
            $r = DB::select(DB::raw($sql));
            foreach ($r AS $row):
                $this->listeOffretelecom[$row->id] = $row->name;
            endforeach;
        endif;
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
        //dd($tab,$key1,$key2,$val);
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

    private function orderByMediaId($tab) {
        ksort($this->listMediaInvest);
        foreach ($this->listMediaInvest as $key => $value) :
            if ($this->listMediaIDInvest[$key] > 0):
                $this->listMedia[] = $value;
                $this->investParMedia[$value] = $this->listMediaIDInvest[$key];
            endif;
        endforeach;
        foreach ($tab as $key => $row) :
            if ($this->listAnnonceurInvest[$key] > 0):
                $data = [];
                foreach ($this->listMedia as $value) :
                    $data[] = array_key_exists($value, $row) ? $row[$value] : 0;
                endforeach;
                $this->investParAnnonceurEtParMediaJSON[] = array(
                    "name" => $key,
                    "stack" => "La selection",
                    "color" => "#" . $this->listDesCouleursDesAnnonceur[$key],
                    "data" => $data
                );
                $this->investParAnnonceur[$key] = $this->listAnnonceurInvest[$key];
            endif;
        endforeach;
    }

    private function orderArray($tab) {
        foreach ($this->listMedia as $value) :
            $this->investParMedia[$value] = 0;
        endforeach;
        foreach ($tab as $key => $row) :
            $this->investParAnnonceur[$key] = array_sum($row);
            foreach ($this->listMedia as $value) :
                $this->investParAnnonceurEtParMedia[$key][$value] = array_key_exists($value, $row) ? $row[$value] : 0;
                $this->investParMedia[$value] += array_key_exists($value, $row) ? $row[$value] : 0;
            endforeach;
        endforeach;
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
