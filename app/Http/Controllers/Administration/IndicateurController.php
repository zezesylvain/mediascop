<?php

namespace App\Http\Controllers\Administration;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use App\WoodyModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IndicateurController extends Controller
{
    protected $userID;
    protected $model;
    protected $mesIndicateurs = [];

    public function __construct (int $userID)
    {
        $this->userID = $userID;
        $this->model = new WoodyModel();
        $this->mesIndicateurs = $this->mesIndicateurs ();
    }


    /**
     * @return string
     */
    public function dateHeure(){
        return FunctionController::date2Fr ("d-m-Y H:mm");
    }

    /**
     * @param string $date
     */
    public function nouvelleCampagneDuJour(string $date,int $valider = 1):array {
        $tcampagne = $valider == 1 ? DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNES','db') : DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNETITLE_NON_VALIDES','db');
        $campagne = $this->model;
        $campagne->setTable ($tcampagne);
        $d = explode ('-',$date);
        $campagnesDuJours = $campagne->whereDay('created_at',$d[2])->whereMonth('created_at',$d[1])->whereYear('created_at',$d[0])->get();
        return $campagnesDuJours->toArray();
    }


    /**
     * @param string $date
     * @return int
     */
    public function nbreNouvelleCampagneDuJour(string $date):int{
       $campagnes = $this->nouvelleCampagneDuJour ($date);
       return count($campagnes);
    }
    
    public function mesCampagneDuJour(string $date):array {
        $tcampagne = DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNES','db');
        $campagne = $this->model;
        $campagne->setTable ($tcampagne);
        $d = explode ('-',$date);
        $campagnesDuJours = $campagne->where('user',$this->userID)
            ->whereDay('created_at',$d[2])
            ->whereMonth('created_at',$d[1])
            ->whereYear('created_at',$d[0])
            ->get();
       return $campagnesDuJours->toArray();
    }

    public function mesCampagnes(int $valider = 1,int $userID = 0,string $date):array {
        $d = explode ('-',$date);
        $tcampagne = $valider == 1 ? DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNES','db') : DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNETITLE_NON_VALIDES','db');
        $campagne = $this->model;
        $campagne->setTable ($tcampagne);
        $campagnesDuJours = $userID == 0 ? $campagne ->whereDay('created_at',$d[2])
            ->whereMonth('created_at',$d[1])
            ->whereYear('created_at',$d[0])
            ->where('user',$this->userID)
            ->get() : $campagne ->whereDay('created_at',$d[2])
            ->whereMonth('created_at',$d[1])
            ->whereYear('created_at',$d[0])
            ->get();
       return $campagnesDuJours->toArray();
    }

    public function nbreMesCampagnesNonValidees(){
        $nbreCampagnes = count ($this->mesCampagnes (0,0,date ('Y-m-d')));
        return view ("administration.Indicateurs.nbreMesCampagnesNonValidees",compact ('nbreCampagnes'))->render ();
    }

    public function nbreCampagnesNonValidees(){
        $nbreCampagnes = count ($this->mesCampagnes (0,1,date ('Y-m-d')));
        return view ("administration.Indicateurs.nbreCampagnesNonValidees",compact ('nbreCampagnes'))->render ();
    }
    /**
     * Les nouvelles campagnes saisies par l'utilisateur
     * @param string $date
     */
    public function mesNouvelleCampagneDuJour(string $date):string {
        $nbreCampagnes = count ($this->mesCampagneDuJour ($date));
        return view ("administration.Indicateurs.nbreMesNouvelleCampagne",compact ('nbreCampagnes'))->render ();
    }

    /**
     * Le nombre des nouvelles campagnes saisies par l'utilisateur
     * @param string $date
     * @return int
     */
    public function lesNouvelleCampagneDuJour(string $date):string {
       $nbreCampagnes = $this->nbreNouvelleCampagneDuJour ($date);
       return view ("administration.Indicateurs.nbreNouvelleCampagneDuJour",compact ('nbreCampagnes'))->render ();
    }

    /**
     * @param int $valide
     * @return mixed
     */
    public function mesSaisieDuJour(int $valide = 1){
        $tPub = $valide == 1 ? DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUBS','db'): DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUB_NON_VALIDES','db');
        $saisie = $this->model;
        $saisie->setTable ($tPub);
        $date = date ("Y-m-d");
        $d = explode ('-',$date);
        $mesSaisies = $saisie->where('user',$this->userID)
            ->whereDay('created_at',$d[2])
            ->whereMonth('created_at',$d[1])
            ->whereYear('created_at',$d[0])
            ->get();
        return $mesSaisies->toArray();
    }

    public function mesSaisies(int $valide = 1,string $date){
        $d = explode ('-',$date);
        $tPub = $valide == 1 ? DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUBS','db'): DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUB_NON_VALIDES','db');
        $saisie = $this->model;
        $saisie->setTable ($tPub);
        $mesSaisies = $saisie->whereDay('created_at',$d[2])
            ->whereMonth('created_at',$d[1])
            ->whereYear('created_at',$d[0])
            ->where('user',$this->userID)->get();
        return $mesSaisies->toArray();
    }


    public function listeMesSaisiesDuJour(){
        
    }
    /**
     *
     */
    public function mesSaisiesNonValidees(){
       return $this->mesSaisieDuJour (0);
    }

    public function nbreMesSaisiesDuJour(){
        $saisie = $this->mesSaisieDuJour (1);
        return count($saisie);
    }

        public function nbreMesSaisiesNonValidees(string $date){
        $saisie = $this->mesSaisies (0,$date);
        $nbreMesSaisies = count($saisie);
        return view ("administration.Indicateurs.nbreMesSaisiesNonValidees",compact ('nbreMesSaisies'))->render ();
    }

    public function nbreMesSaisiesValidees(string $date){
        $saisie = $this->mesSaisies (1,$date);
        $nbreMesSaisies = count($saisie);
        return view ("administration.Indicateurs.nbreMesSaisiesValidees",compact ('nbreMesSaisies'))->render ();
    }


    public function nouvellesCampagnesDuJour(){
        $date = date ("Y-m-d");
        $tcampagne = DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNES','db');
        $campagne = $this->model;
        $campagne->setTable ($tcampagne);
        $d = explode ('-',$date);
        $campagnesDuJours = $campagne->whereDay('created_at',$d[2])
            ->whereMonth('created_at',$d[1])
            ->whereYear('created_at',$d[0])
            ->get();
        $lesCampagnesDuJour = $campagnesDuJours->toArray();
        //dd($lesCampagnesDuJour);
        //return $lesCampagnesDuJour;
    }


    public function tableauRecapDesNouvellesCampagnesDuJour(){
        $lesCampagnesDuJour = $this->nouvellesCampagnesDuJour ();
        //return view ("administration.Indicateurs.tableauRecapNouvelleCampagne",compact ('lesCampagnesDuJour'))->render ();
    }

    /**
     * @param int $mediaID
     * @param string $date
     * @return array
     */
    public function saisiesParMedia(int $mediaID,string $date = ""): array
    {
        $cond = "";
        $and = $cond !== "" ? "WHERE" : "AND";
        $cond .= $mediaID !== null ? " $and media = $mediaID " : "";
        $and = $cond !== "" ? "WHERE" : "AND";
        $cond .= $date === "" ? "" : " $and r.date = '$date' ";
        $query = "select media,user, count(*) as nb from 
               ".DbTablesHelper::dbTable('DBTBL_REPORTINGS','db')." r
               $cond group by media  ";
        $saisies = FunctionController::arraySqlResult ($query);
        return $saisies;
    }

    /**
     * @param int $mediaID
     * @return int
     */
    public function nbreSaisiesParMedia(int $mediaID,string $date){
        return count ($this->saisiesParMedia ($mediaID,$date));
    }

    /**
     * @param int $mediaID
     * @return array
     */
    public function saisiesParMediaNonValidees(int $mediaID,string $date = ""){
        $cond = $date == "" ? "" : " AND p.date ='$date'";
        $saisies = FunctionController::arraySqlResult ("SELECT p.* FROM 
            ".DbTablesHelper::dbTable ('DBTBL_PUB_NON_VALIDES','db')." p,
            ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." s 
            WHERE p.support = s.id AND s.media = $mediaID $cond ORDER BY p.created_at DESC");
        return $saisies;
    }

    public function nbreSaisiesParMediaNonValidees(int $mediaID,string $date){
        $saisies = $this->saisiesParMediaNonValidees ($mediaID,$date);
        return count ($saisies);
    }

    /**
     * @return mixed
     */
    public function saisieNonValider(string $date){
        $tPub = DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUB_NON_VALIDES','db');
        $saisie = $this->model;
        $saisie->setTable ($tPub);
        $d = explode ('-',$date);
        $mesSaisies = $saisie->whereDay('created_at',$d[2])
            ->whereMonth('created_at',$d[1])
            ->whereYear('created_at',$d[0])->count();
        return $mesSaisies;
    }

    public function nbreSaisieNonValider(string $date){
       $saisie = $this->saisieNonValider ($date);
       return $saisie;
    }

    public function nbreSaisiesNonValidees(){
       $saisies = $this->nbreSaisieNonValider (date ('Y-m-d'));
       return view ("administration.Indicateurs.nbreSaisieNonvalider",compact ('saisies'))->render ();
    }

    public function nouveauSupports(){

    }

    public function topAnnonceur(int $nombre = 5){

    }

    public function topCampagne(int $nombre = 5){
       return $nombre;
    }

    public function derniersRapports(){
        
    }

    public function saisieUserParMedia(int $mediaID, int $valider = 1,string $date){
        $cond = $date == "" ? "" : " AND p.date ='$date'";
        $tPub = $valider == 1 ? DbTablesHelper::dbTable ('DBTBL_PUBS','db') : DbTablesHelper::dbTable ('DBTBL_PUB_NON_VALIDES','db');
        $saisies = FunctionController::arraySqlResult ("SELECT p.* FROM 
            $tPub p,".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." s 
            WHERE p.support = s.id AND s.media = $mediaID $cond AND p.user = {$this->userID}");
        return $saisies;

    }

    public static function tableauRecapDesSaisies(){
        $date = date ('Y-m-d');
        $indic = new IndicateurController(Auth::id());
        $saisiesValidees = $indic->saisiePMediaPUser(1);
        $saisiesNonValidees = $indic->saisiePMediaPUser(0);
        $tabSV = $indic->transformSaisie($saisiesValidees);
        $tabNV = $indic->transformSaisie($saisiesNonValidees);
        $tab = [
            'saisieValidees' => $tabSV,
            'saisieNonValidees' => $tabNV,
        ];
        $tUser = DbTablesHelper::dbTable('DBTBL_USERS');
        return view ("administration.Indicateurs.tableauRecapDesSaisies",compact ('tab','tUser'))->render ();
    }

    /**
     * @param int $valide
     * @return array
     */
    public function saisiePMediaPUser(int $valide = 1): array
    {
        $args = '';
        $from = '';
        $groupBy = '';
        $where = '';
        if ($valide === 1):
            $args = 'media as medias, user, count(*) AS nb';
            $from = DbTablesHelper::dbTable ('DBTBL_REPORTINGS','db');
            $groupBy = 'medias, user';
        endif;
        if ($valide === 0):
            $args = 'm.id as medias,p.user, count(*) as nb';
            $from = ''.DbTablesHelper::dbTable ('DBTBL_PUB_NON_VALIDES','db').' p,
                    '.DbTablesHelper::dbTable ('DBTBL_MEDIAS','db').' m,
                    '.DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db').' s';
            $where = 'WHERE p.support = s.id and s.media = m.id';
            $groupBy = 'medias,p.`user`';
        endif;
        $query = "SELECT $args FROM $from $where GROUP BY $groupBy";
        return FunctionController::arraySqlResult($query);
    }
    public function transformSaisie(array $saisies):array{
        $tab = [];
        foreach ($saisies as $tb):
            $tab[$tb['medias']][$tb['user']] = $tb['nb'];
        endforeach;
        return $tab;
    }

    public function nombreAgentsConnectes(){

    }

    public function nombreClientsConnectes(){
        
    }
    
    public function mesIndicateurs(){
        Session::forget ("mesIndicateurs");
        if (!Session::has ("mesIndicateurs")):
            $userProfil = FunctionController::getChampTable (FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_USERS')),$this->userID,"profil");

            $indic = FunctionController::arraySqlResult ("SELECT i.* FROM 
                ".DbTablesHelper::dbTable ('DBTBL_INDICATEURS','db')." i,
                ".DbTablesHelper::dbTable ('DBTBL_PROFILS_INDICATEURS','db')." pi
                WHERE i.id = pi.indicateur AND pi.profil = $userProfil
                 ");
            $tab = [];
            foreach($indic as $r):
                $tab[$r['id']] = $r['methode'];
            endforeach;
            Session::put ("mesIndicateurs", $tab);
        endif;
        return Session::get("mesIndicateurs");
    }

    public static function nbreTotalDeLigneDeSaisieParMedia(){
        $medias = AdminController::listeDesMedias ();
        $userID = Auth::id ();
        $saisies = self::getNbreSaisiesParMedia($userID);
        return view ("administration.Indicateurs.tableauRecapDesSaisiesParMedia",compact ('medias','saisies'))->render ();
    }

    public static function nbreTotalDeLigneDeSaisieParMediaNonValider(){
        $medias = AdminController::listeDesMedias ();
        $userID = Auth::id ();
        $saisie = new IndicateurController($userID);
        return view ("administration.Indicateurs.tableauRecapDesSaisiesParMediaNonValider",compact ('medias','saisie'))->render ();
    }

    /**
     * @param int $userID
     * @param string $date
     * @return array
     */
    public static function getNbreSaisiesParMedia(int $userID = null,string $date = ""): array
    {
        $cond = "";
        $and = $cond === "" ? "WHERE" : "AND";
        $cond .= $userID !== null ? " $and r.user = $userID " : "";
        $and = $cond !== "" ? "AND" : "";
        $cond .= $date === "" ? "" : " $and r.date = '$date' ";
        $query = "select r.media, count(*) as nb from 
               ".DbTablesHelper::dbTable('DBTBL_REPORTINGS','db')." r
               $cond group by r.media ";
        $v = FunctionController::arraySqlResult ($query);
        $saisies = [];
        if(count($v)):
            foreach ($v as $r):
                $saisies[$r['media']] = $r['nb'];
            endforeach;
        endif;
        return $saisies;
    }

}
