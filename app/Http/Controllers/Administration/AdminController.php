<?php

namespace App\Http\Controllers\Administration;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\CoreController;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends CoreController
{
    protected $core ;

    public function __construct ()
    {
        //   $this = new CoreController();
    }


    public function isPalindrome(string $str): bool
    {
        $strcasse = strtolower ($str) ;
        $inverse = strrev ($strcasse);
        return $strcasse === $inverse;
    }

    public function create(string $table) {
        $table = FunctionController::getTableName ($table);
        return $this->gestionTable ($table);
    }

    public function makeForm(string $table):string {
        $table = FunctionController::getTableName ($table);

        return $this->gestionTableForm ($table);
    }

    public function makeTableDatas(string $table):string {
        $table = FunctionController::getTableName ($table);
        return $this->gestionTableDatas ($table);
    }

    public static function getSponsors(int $campagnetitle, bool $sp = true){
        $in = $sp ? " " : " NOT " ;
        $listDesSponsors = FunctionController::arraySqlResult("SELECT a.id,a.raisonsociale
            FROM ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','dbtable')." a
            WHERE a.id $in
            IN (SELECT annonceur
                FROM
                    ".DbTablesHelper::dbTable('DBTBL_SPONSOR_CAMPAGNES','dbtable')."
                WHERE campagnetitle = $campagnetitle) ORDER BY a.raisonsociale ASC");
        return $listDesSponsors;
    }

    public static function verifierSiSaisieSurCamp(int $cid):bool {
        $ok = false;
        $sql = "SELECT * FROM
                ".DbTablesHelper::dbTable('DBTBL_PUBS','db')."
                WHERE  campagne = $cid";
        $req = FunctionController::arraySqlResult($sql);
        if(!empty($req)):
            $ok = true;
        endif;
        return $ok;
    }

    public static function getVisuelCampagne(array $docampagne, int $idCampagneTitle){
        $slidercamp = "";
        $documentsCampagnes = self::trierDocumentsCampagnes($docampagne);
        $slidercamp .= self::makeImageSlider ($documentsCampagnes['images']);
        $slidercamp .= self::makeAudioSlider ($documentsCampagnes['audios']);
        $slidercamp .= self::makeVideoSlider ($documentsCampagnes['videos']);
        return view ("administration.Campagnes.afficherVisuelsCampagne", compact ('slidercamp','idCampagneTitle'))->render ();
    }

    public static function createInputTvRadio(int $media):string {
        $r = route('ajax.getDataInSession');
        $r1 = route('ajax.addInputMedia');
        $r2 = route('ajax.addInputMedia');
        $option = array("onchange" => "sendData('var=support&val='+this.value+'&action=cid', '$r', 'cid')");
        $support = self::createInputSupport($media, $option);
        $TarifCoefBox = self::createinputtarif();
        $inputAutre = self::createInputPubs($media);
        return view ("administration.Form.inputTvRadio", compact ('r','r1','r2','support','TarifCoefBox','inputAutre'))->render ();
    }

    public static function createInputSupport(int $media, array $option = []):string {
        $optiontext = "";
        foreach ($option as $key => $v):
            $optiontext .= " $key=\"$v\"";
        endforeach;
        $cond = " WHERE media = ".$media." ";
        $support = isset($support) ? $support : 0;
        return view ("administration.Form.inputSupport",compact ('optiontext','support','cond'))->render ();
    }

    public static function createInputSupportUpdate(int $media, int $support, array $option = []):string {
        $optiontext = "";
        foreach ($option as $key => $v):
            $optiontext .= " $key=\"$v\"";
        endforeach;
        $cond = " WHERE media = ".$media." ";
        $support = isset($support) ? $support : 0;
        return view ("administration.Form.inputSupportUpdate",compact ('optiontext','support','cond'))->render ();
    }

    public static function createinputtarif() {
        return view ("administration.Form.inputTarif")->render ();
    }

    public static function createinputtarifUpdate(int $tarif,$coef) {
        return view ("administration.Form.inputTarifUpdate",compact ('tarif','coef'))->render ();
    }

    public static function createInputPubs($media){
        $defaultvalue = [
            'internet_emplacement' => 1, 'presse_page' => 0, 'affichage_panneau' => 1, 'investissement' => 0, 'nombre' =>  0, 'profil_mobile' => 1, 'support' => 1, 'coeff' => 1, 'tarif' => 1, 'duree' => 0,'heure' => ''
        ];
        switch ($media) {
            case '1':
                return view('administration.Form.champInputMediaTvR',compact('defaultvalue'))->render ();
                break;

            case '2':
                return view('administration.Form.champInputMediaTvR',compact('defaultvalue'))->render ();
                break;
            case '3':
                return view('administration.Form.champInputMediaPresse',compact('defaultvalue'))->render ();
                break;
            case '4':
                return view('administration.Form.champInputMediaInternet',compact('defaultvalue'))->render ();
                break;
            case '5':
                return view('administration.Form.champInputMediaMobile',compact('defaultvalue'))->render ();
                break;
            case '6':
                return view('administration.Form.champInputMediaAffichage',compact('defaultvalue'))->render ();
                break;

            default:
                return '';
                break;
        }
    }

    public static function createInputPresse(int $media):string {
        $route = route('ajax.addInputMedia');
        $k = "";
        $inputSupport = self::createInputSupport ($media);
        $inputTarif = self::createinputtarif ();
        $inputPub = self::createInputPubs ($media);
        $action = 'pageinterneitem';
        return view ("administration.Form.inputPresse", compact ('route','k','inputPub','inputSupport','inputTarif','action'))->render ();
    }

    public static function createInputAffichage(int $media):string {
        $inputPubs = self::createInputPubs ($media);
        $villes = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_LOCALITES','db')." WHERE parent = 0 ORDER BY name ASC");
        return view ("administration.Form.inputAffichage", compact ('media','inputPubs','villes'))->render ();
    }

    public static function createInputInternet(int $media):string {
        $inputSupport = self::createInputSupport ($media);
        $inputTarif = self::createinputtarif ();
        $inputPubs = self::createInputPubs ($media);
        return view ('administration.Form.inputInternet', compact ('inputPubs','inputTarif','inputSupport'))->render ();
    }

    public static function createInputMobile(int $media):string {
        $heure = date('H:i:s');
        $route = route('ajax.addInputMedia');
        $support = self::createInputSupport($media,array("onchange" => "sendData('operateur='+this.value+'&action=mobile_profil', '$route ', 'mobileprofilitem')"));
        $inputPubs =  self::createinputtarif().self::createInputPubs($media);
        return view ("administration.Form.inputMobile", compact ('heure','support','inputPubs'))->render ();

    }

    public static function createInputHorsMedia(int $media, int $campagneID):string {
        if (Session::has ('pointsHorsMedia')):
            Session::forget ('pointsHorsMedia');
        endif;
        $heure = date('H:i:s');
        if (Session::has ("coordonneesHorsMedia")):
            Session::forget ("coordonneesHorsMedia");
        endif;
        $campagneTitleId = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_CAMPAGNES','db'),$campagneID,"campagnetitle");
        $operation = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLES','db'),$campagneTitleId,"operation");
        $annonceur = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_OPERATIONS','db'),$operation,"annonceur");
        $secteurID = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db'),$annonceur,"secteur");
        $natureID = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_CAMPAGNES','db'),$campagneID,"nature");
        $formatID = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_CAMPAGNES','db'),$campagneID,"format");
        $supports = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." WHERE media = $media  ORDER BY name ASC");
        $typeDeService = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_TYPE_DE_SERVICES','db')." WHERE secteur = $secteurID ORDER BY name ASC");
        $typeDePromos = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_TYPE_DE_PROMOS','db')." WHERE 
        secteur = $secteurID 
        ORDER BY name ASC");
        $formats = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_FORMATS','db')." WHERE media = $media ORDER BY name ASC");
        $latDefault = 5.3096600;
        $lngDefault = -4.0126600;
        return view ("administration.Form.inputHorsMedias", compact ('heure','supports','secteurID','typeDeService','typeDePromos','latDefault','lngDefault','formats','formatID'))->render ();
    }

    public static function trierDocumentsCampagnes(array $docampagnes):array {
        $imageext = Config::get("constantes.imageext");
        $audioext = Config::get("constantes.audioext");
        $videoext = Config::get("constantes.videoext");
        $doc = [
            'images' => [],
            'audios' => [],
            'videos' => [],
        ];
        foreach ($docampagnes as $docampagne):
              if (in_array ($docampagne['type'],$imageext)):
                  $doc['images'][] = $docampagne;
              endif;
            if (array_key_exists ($docampagne['type'],$audioext)):
                $doc['audios'][] = $docampagne;
            endif;
            if (array_key_exists ($docampagne['type'],$videoext)):
                $doc['videos'][] = $docampagne;
            endif;
        endforeach;
        return $doc;
    }

    public static function makeImageSlider(array $images):string {
        return view ("template.Html.boxImages",compact ('images'))->render ();
    }

    public static function makeAudioSlider(array $audios):string {
        return view ("template.Html.boxAudios",compact ('audios'))->render ();
    }

    public static function makeVideoSlider(array $videos):string {
        return view ("template.Html.boxVideos",compact ('videos'))->render ();
    }

    public static function formTarifTv(int $media){
        $supports = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." WHERE media = $media ORDER BY name ASC");
        return view ("administration.Tarifs.formTarifTv", compact ('supports'));
    }

    public static function formTarifRadio(int $media){
        $supports = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." WHERE media = $media ORDER BY name ASC");
        return view ("administration.Tarifs.formTarifRadio", compact ('supports'));
    }

    public static function formTarifPresse(int $media){
        $supports = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." WHERE media = $media ORDER BY name ASC");
        $calibres = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_PRESSE_CALIBRES','db')." ORDER BY id ASC");
        $pressePages = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_PRESSE_PAGES','db')." ORDER BY name ASC");
        return view ("administration.Tarifs.formTarifPresse", compact ('supports','calibres','pressePages'));
    }

    public static function formTarifInternet(int $media){
        $supports = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." WHERE media = $media ORDER BY name ASC");
        $formats = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_FORMATS','db')." WHERE media = $media ORDER BY name ASC");
        $emplacements = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_INTERNET_EMPLACEMENTS','db')." ORDER BY name ASC");
        return view ("administration.Tarifs.formTarifInternet", compact ('supports','formats','emplacements'));
    }

    public static function formTarifMobile(int $media){
        $supports = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." WHERE media = $media ORDER BY name ASC");
        return view ("administration.Tarifs.formTarifMobile", compact ('supports'));
    }

    public static function listeDesMedias(){
        if (!Session::has("listeDesMedias")):
            $medias = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')." ORDER BY name ASC");
            $tab = [];
            foreach ($medias as $r):
                $tab[$r['id']] = $r;
            endforeach;
            Session::put ("listeDesMedias", $tab);
        endif;
        return Session::get ("listeDesMedias");
    }

    public static function formUpdateInputTv(int $media, int $pubID, string $table){
        $pub = FunctionController::arraySqlResult ("SELECT * FROM $table WHERE id = $pubID");
        $r = route('ajax.getDataInSession');
        $r1 = route('ajax.addInputMedia');
        $r2 = route('ajax.addInputMedia');
        $option = array("onchange" => "sendData('var=support&val='+this.value+'&action=cid', '$r', 'cid')");
        $support = self::createInputSupportUpdate($media,$pub[0]['support'], $option);
        $TarifCoefBox = self::createinputtarifUpdate($pub[0]['tarif'],$pub[0]['coeff']);
        $heure = $pub[0]['heure'];
        return view ("administration.Form.inputTvRadioUpdate", compact ('r','r1','r2','support','TarifCoefBox','heure'))->render ();
    }


    public static function formUpdateInputPresse(int $media,int $pubID,string $table):string {
        $pub = FunctionController::arraySqlResult ("SELECT * FROM $table WHERE id = $pubID");
        $route = route('ajax.addInputMedia');
        $k = "";
        $inputSupport = self::createInputSupportUpdate ($media,$pub[0]['support']);
        $inputTarif = self::createinputtarifUpdate ($pub[0]['tarif'],$pub[0]['coef']);
        $action = 'pageinterneitem';
        return view ("administration.Form.inputPresseUpdate", compact ('route','k','inputSupport','inputTarif','action','pub'))->render ();
    }

    public static function formUpdateInputAffichage(int $media,int $pubID,string $table):string {
        $pub = FunctionController::arraySqlResult ("SELECT * FROM $table WHERE id = $pubID");
        //dd($pub);
        return view ("administration.Form.inputAffichageUpdate", compact ('media','pub'))->render ();
    }

    public static function formUpdateInputInternet(int $media,int $pubID,string $table):string {
        $pub = FunctionController::arraySqlResult ("SELECT * FROM $table WHERE id = $pubID");
        $inputSupport = self::createInputSupportUpdate ($media,$pub[0]['support']);
        $inputTarif = self::createinputtarifUpdate ($pub[0]['tarif'],$pub[0]['coeff']);
        return view ('administration.Form.inputInternetUpdate', compact ('inputTarif','inputSupport','pub'))->render ();
    }

    public static function formUpdateInputMobile(int $media,int $pubID,string $table):string {
        $pub = FunctionController::arraySqlResult ("SELECT * FROM $table WHERE id = $pubID");
        $heure = $pub[0]['heure'];
        $route = route('ajax.addInputMedia');
        $support = self::createInputSupportUpdate($media,$pub[0]['support'],array("onchange" => "sendData('operateur='+this.value+'&action=mobile_profil', '$route ', 'mobileprofilitem')"));
        $inputPubs =  self::createinputtarifUpdate($pub[0]['tarif'],$pub[0]['coeff']);
        return view ("administration.Form.inputMobileUpdate", compact ('heure','support','inputPubs'))->render ();

    }

    public function newIndicateur(){
        $table = DbTablesHelper::dbTable ('DBTBL_INDICATEURS','db');
        return $this->create ($table);
    }

    public function attribuerIndicateurs(){
        $profils = FunctionController::arraySqlResult ("SELECT * FROM ".FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_PROFILS'))." ORDER BY name ASC");

        return view ("administration.Indicateurs.formIndicateur",compact ('profils'));
    }

    public function listeIndicateur(Request $request){
        $profil = $request->profil;
        if(!is_null($profil)):
            $mesIndicateurs = FunctionController::arraySqlResult ("SELECT i.* FROM
                ".DbTablesHelper::dbTable ('DBTBL_INDICATEURS','db')." i,
                ".DbTablesHelper::dbTable ('DBTBL_PROFILS_INDICATEURS','db')." pi
                WHERE i.id = pi.indicateur AND pi.profil = $profil
                ");
                    $indicateurs = FunctionController::arraySqlResult ("SELECT * FROM
                ".DbTablesHelper::dbTable ('DBTBL_INDICATEURS','db')."
               ORDER BY name ASC
                ");
            $mesIndicateursList = [];
            foreach ($mesIndicateurs as $r):
                $mesIndicateursList[] = $r['id'];
            endforeach;
            $profilID = $profil;
            return view ("administration.Indicateurs.listeIndicateurs",compact ('mesIndicateursList','indicateurs','profilID'))->render ();
        endif;
    }

    public function attribuerIndicateur(Request $request){
        $etat = $request->etat;
        $indicateur = $request->indicateur;
        $profil = $request->profil;
        if ($etat == "true"):
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_PROFILS_INDICATEURS','db'))
                ->insert ([
                    'profil' => $profil,
                    'indicateur' => $indicateur
                ]);
        endif;
        if ($etat == "false"):
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_PROFILS_INDICATEURS','db'))
                ->where ([
                    'profil' => $profil,
                    'indicateur' => $indicateur
                ])
                ->delete ();
        endif;
    }

    public static function generateSpeednews() {
        $tableRep = DbTablesHelper::dbTable('DBTBL_REPORTINGS','db');
        $tableSpeed = DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db');
        $sqlSpdN = "SELECT DISTINCT campagnetitle AS cid, media
                FROM $tableRep ORDER BY cid ASC ";
        $lesCamp = FunctionController::arraySqlResult($sqlSpdN);
        $data = [];
       // dd($lesCamp);
        $select1 = "date, heure,  support";
        $select2 = "date";
        $as = " ASC ";
        $ds = " DESC ";

        foreach ($lesCamp as $r) :
            $cid = $r['cid'];
            $media = $r['media'];
            $tdata = array('campagnetitle' => $cid, 'media' => $media);
            $sqlPub = "
                    SELECT %s
                    FROM $tableRep 
                    WHERE campagnetitle = $cid AND media = $media
                    ORDER BY  date %s LIMIT 1
                 ";
            $sql1 = sprintf($sqlPub, $select1, $as);
            $res1 = FunctionController::arraySqlResult($sql1);
            $sql2 = sprintf($sqlPub, $select2, $ds);
            $res2 = FunctionController::arraySqlResult($sql2);
            if(count($res1)):
                $tdata['dateajout'] = $res1[0]['date'];
                if($res1[0]['heure'] === null or $res1[0]['heure'] === ""):
                    $tdata['heure'] = "00:00";
                else:
                    $tdata['heure'] = $res1[0]['heure'];
                endif;
            endif;
            $tdata['datefin'] = $res2[0]['date'];
            $tdata['support'] = $res1[0]['support'];
            $data[] = $tdata;
        endforeach;
        if (count($data) >= 1) :
            $query = "TRUNCATE TABLE $tableSpeed ";
            DB::select($query);
            $insertdata = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_SPEEDNEWS','db'))->insert($data);
            if($insertdata):
                Session::flash("success", "<h2>lignes inserrees dans speedNews</h2>");
            else :
                Session::flash("echec", "<h2>Aucune donn&eacute;e inserr&eacute;e</h2>");
            endif;
        endif;
    }

    /**
     * @param string $typeNotif
     * @param int $id
     */
    public static function addNotifications(string $typeNotif, int $id)
    {
        DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_NOTIFICATIONS','db'))
                ->insert(
                    [
                        'libelle_table' => $typeNotif,
                        'id_table' => $id,
                    ]
                );
    }

    public static function flashNotifications(): string
    {
        $user = Auth::user();
        $notifs = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_NOTIFICATIONS','db'))
            ->where('user_profil','=',$user->profil)
            ->where('traiter','=',0)
            ->get()->count();
        $notifications = '';
        if ($notifs):
            $notifications = view('administration.Notifications.flashNotif',compact('notifs'))->render();
        endif;
        return $notifications;
    }

    public static function nbreNotification()
    {

    }

}
