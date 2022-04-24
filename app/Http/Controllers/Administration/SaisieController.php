<?php

namespace App\Http\Controllers\Administration;


use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FormController;
use App\Http\Controllers\core\FunctionController;
use App\Http\Controllers\core\ModuleController;
use App\Imports\DonneesImport;
use App\Imports\DonneesImportees;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class SaisieController extends AdminController
{


    public function newSaisie(int $mediaID){
        $media = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')." WHERE id = $mediaID");
        $lesMedias = Config::get ("constantes.medias");
        $action = strtoupper ($lesMedias[$media[0]['id']]);
        return self::collecte ($media,$action);
    }

    public function newTelevision(){
        return $this->newSaisie (1);
    }

    public function newRadio(){
        return $this->newSaisie (2);
    }

    public function newPresse(){
        return $this->newSaisie (3);
    }

    public function newInternet(){
        return $this->newSaisie (4);
    }

    public function newAffichage(){
        return $this->newSaisie (6);
    }

    public function newMobile(){
        return $this->newSaisie (5);
    }

    public function newHorsMedia(){
        return $this->newSaisie (7);
    }

    public function newTarif(){
        $medias = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')." ORDER BY name ASC");
        return view ("administration.Tarifs.formTarif", compact ('medias'));
    }

    public static function makeFormFilterCampagne(string $debutop, string $finop, $secteurop = 0, $annonceurop = 0, $operationop = 0, string $action): string {
        if ($action == "TELEVISION" || $action == "RADIO" || $action == "PRESSE" || $action == "INTERNET" || $action == "MOBILE" || $action == "AFFICHAGE" || $action == "HORS-MEDIA"):
            $listeDesSecteurs = FunctionController::arraySqlResult(" SELECT name, id FROM ".DbTablesHelper::dbTable('DBTBL_SECTEURS','dbtable')." ORDER BY name ASC");
            $cond = $secteurop != 0 ? " WHERE secteur = $secteurop " : "";
            $listeDesAnnonceurs = FunctionController::arraySqlResult(" SELECT raisonsociale, id FROM
                ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')." $cond ORDER BY raisonsociale ASC");

            $cond1 = $annonceurop != 0 ? " WHERE annonceur = $annonceurop ": "";
            $listeDesOperations = FunctionController::arraySqlResult(" SELECT name, id FROM
                ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','db')." $cond1 ORDER BY name ASC");

            return view ("administration.Saisies.form_filter_campagne", compact ('debutop','finop','secteurop','annonceurop','listeDesSecteurs','listeDesAnnonceurs','listeDesOperations','action','operationop'))->render ();
        endif;
    }

    public static function collecte(array $media, string $action){
        $listeDesMedia = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')." ORDER BY name ASC");
        Session::put('collecte',[]);
        if(Session::get('filter_param.action') != $action):
            Session::forget('filter_param');
        endif;
        if (!Session::has('filter_param')):
            $dsc1 = 1;
            $dsc = $dsc1 <= 7 ? 7 : $dsc1;
            $debutop = date('Y-m-d', mktime(0, 0, 0, date("n"), date("j") - $dsc, date("Y")));
            $ddfinfop = date('Y-m-d', mktime(0, 0, 0, date("n"), date("j") + 1, date("Y")));
            $debutopfr = date('d-m-Y', mktime(0, 0, 0, date("n"), date("j") - $dsc, date("Y")));
            $secteurfilteroperation = 0;
            $annonceurfilteroperation = 0;
            $opfilteroperation = 0;

            $donnees = [
                'dddebutfop' => $debutop,
                'ddfinfop' => $ddfinfop,
                'debutopfr' => $debutopfr,
                'filteroperation' => 'ok',
                'secteurfilteroperation' => $secteurfilteroperation,
                'annonceurfilteroperation' => $annonceurfilteroperation,
                'action' => $action,
                'opfilteroperation' => $opfilteroperation,
            ] ;
            Session::put('filter_param', $donnees);
        endif;
        $listeDesCampagnes = '';
        if(Session::has('filter_param')):
            $debutop = Session::get ('filter_param.dddebutfop');
            $ddfinfop = Session::get ('filter_param.ddfinfop');
            $debutopfr = Session::get ('filter_param.debutopfr');
            $secteurfilteroperation = Session::get ('filter_param.secteurfilteroperation');
            $annonceurfilteroperation = Session::get ('filter_param.annonceurfilteroperation');
            $opfilteroperation = Session::get ('filter_param.opfilteroperation');
            $listeDesCampagnes .= self::makeListeDesCampagnes ($media[0]['id']);
        endif;
        return view ("administration.Saisies.form", compact ('debutop','ddfinfop','debutopfr','secteurfilteroperation','annonceurfilteroperation','opfilteroperation','listeDesCampagnes','listeDesMedia','action','media'));
    }

    public static function makeListeDesCampagnes(int $typemedia):string {
        $datas = Session::get ('filter_param');
        $debutop = FunctionController::date2Fr ($datas['dddebutfop'],"Y-m-d");
        $ddfinop = FunctionController::date2Fr ($datas['ddfinfop'],"Y-m-d");
        $secteurfilteroperation = $datas['secteurfilteroperation'];
        $annonceurfilteroperation = $datas['annonceurfilteroperation'];
        $opfilteroperation = array_key_exists ("opfilteroperation",$datas) ? $datas['opfilteroperation'] : 0;
        $cond = '';
        $ctcond = "";

        $sqlq = " SELECT DISTINCT c.campagnetitle FROM
            ".DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db')." c, "
            .DbTablesHelper::dbTable('DBTBL_FORMATS','db')." f
            WHERE c.format = f.id  AND f.media = $typemedia AND c.adddate BETWEEN '%s' AND '%s'";

        $campagneTitle = FunctionController::arraySqlResult(sprintf($sqlq, $debutop, $ddfinop));
        $nb = count($campagneTitle);
        if($nb):
            $camptitle = [];
            foreach($campagneTitle as $rows):
                $camptitle[] = $rows['campagnetitle'];
            endforeach;
            $ctcond .= " AND ct.id IN (".join(',',$camptitle).") ";
        endif;
        $dateDeb = Session::get('filter_param.dddebutfop');
        $ladate = strtotime(date("Y-m-d", strtotime($dateDeb)));
        $debutop = date("Y-m-d", $ladate);
        Session::put('filter_param.debutop', $debutop);

        if (empty(!array_key_exists("filtre", $datas))):
            return $cond;
        endif;
        if ($debutop <= $ddfinop):
            $getConditionDate = '';
            $date_deb = $debutop;
            $date_fin = $ddfinop;

            $and = " AND ";

            if ($date_deb <= $date_fin && $date_deb != null && $date_fin != null):
                $getConditionDate .= " ct.adddate BETWEEN '$date_deb' AND '$date_fin'";
                $cond .= " $and ".$getConditionDate;
                $and = " AND";
            endif;

            if ($secteurfilteroperation != 0):
                $getConditionSecteur = " so.secteur = $secteurfilteroperation";
                $cond .= " $and ".$getConditionSecteur;
                $and = " AND";
            endif;

            if ($annonceurfilteroperation != 0):
                $getConditionAnnonceur = " o.annonceur = $annonceurfilteroperation";
                $cond .= " $and ".$getConditionAnnonceur;
                $and = " AND";
            endif;

            if ($opfilteroperation != 0):

                $optitle = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_OPERATIONS','db'),$opfilteroperation);
                $optitle = filter_var ($optitle, FILTER_SANITIZE_MAGIC_QUOTES);
                $getConditionOperation = " o.name LIKE '%$optitle%'";
                $cond .= " $and ".$getConditionOperation;
                $and = " AND ";
            endif;

            if ($cond != ""):
                $cond = " $cond ";
            endif;
        endif;
        $sql = "SELECT ct.id, ct.title, ct.adddate, o.name as operationname, o.id AS opID,so.raisonsociale,so.id AS annonceurID, so.secteur as secteurID,se.name AS secteurname
            FROM ".DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db')." ct ,
                 ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','db')."  o,
                 ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')." so,
                 ".DbTablesHelper::dbTable('DBTBL_SECTEURS','db')." se
             WHERE
             ct.id IN (SELECT ct.id
                    FROM ".DbTablesHelper::dbTable ('DBTBL_CAMPAGNES','db')." c,
                     ".DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLES','db')." ct,
                     ".DbTablesHelper::dbTable ('DBTBL_FORMATS','db')." f
                    WHERE c.format = f.id
                    AND c.campagnetitle = ct.id
                    AND f.media = $typemedia $cond)
            AND ct.operation = o.id AND  o.annonceur = so.id AND so.secteur = se.id";
        //dd ($sql);
        $datc = FunctionController::arraySqlResult($sql);
        $mediaID = $typemedia;
        return view ("administration.Saisies.tabbootstrapcampagne", compact ('datc','debutop','mediaID'))->render ();
    }

    public function chercherCampagne(Request $request){
        if (!$request->session()->has ('collecte')):
            $request->session()->put ('collecte',[]);
        endif;
        $campagnetitleID = $request->cptID;
        $media = $request->mediaID;
        $champmedia = Config::get('constantes.champmedia');
        $leschampsdumedia = array_key_exists ($media,$champmedia) ? $champmedia[$media] : [];
        $sqlcp1 = "SELECT c.* FROM
            ".DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db')." c,
            ".DbTablesHelper::dbTable('DBTBL_FORMATS','db')." f
             WHERE c.campagnetitle = $campagnetitleID AND c.format = f.id
                AND f.media = $media ORDER BY c.id DESC";

        $datc = FunctionController::arraySqlResult($sqlcp1);
        if(count($datc)):
            $cptitle = $datc[0]['campagnetitle'];
            $of = "SELECT offretelecom FROM  ". DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db') ." WHERE id = $cptitle ";

            $sqlop = FunctionController::arraySqlResult ($of);

            $offretelecom  = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_OFFRE_TELECOMS','db'),$sqlop[0]['offretelecom']);

            $tcible = DbTablesHelper::dbTable('DBTBL_CIBLES','db');
            $tformat = DbTablesHelper::dbTable('DBTBL_FORMATS','db');
            $tnature = DbTablesHelper::dbTable('DBTBL_NATURES','db');
            $taffichdim = DbTablesHelper::dbTable('DBTBL_AFFICHAGE_DIMENSIONS','db');
            $request->session()->put('collecte.cid', $datc[0]['id']);
            $request->session ()->save ();
            return view('administration.Saisies.formchampmedia', compact('datc','leschampsdumedia', 'offretelecom','tcible','tformat','tnature','taffichdim','media'))->render ();
        else:
            $request->session()->put('collecte', []);
            return "<i class=\"fa fa-close fa-3x text-rouge\" style='color: red;'></i>";
        endif;
    }

    /**
     * @param int $campagneID
     * @param int $media
     * @return string
     * @throws Throwable
     */
    public function frameFormSaisie(int $campagneID, int $media): string
    {
        $action = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_MEDIAS','db'),$media);
        $campagneTitleID = FunctionController::getChampTable (DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db'),$campagneID,'campagnetitle');
        return view ("administration.Saisies.frameForm", compact ('campagneID','media','action','campagneTitleID'))->render ();
    }

    /**
     * @param int $media
     * @return string
     */
    public static function createInputMedia(int $media): string
    {
        $html = "";
        $lesMedias = \config ("constantes.medias");
        $lemedia = strtoupper ($lesMedias[$media]);
        switch($lemedia):
            case 'TELEVISION':
            case 'RADIO':
                $html .= self::createInputTvRadio($media);
                break;

            case 'PRESSE':
                $html .= self::createInputPresse($media);
                break;

            case 'AFFICHAGE':
                $html .= self::createInputAffichage($media);
                break;

            /*case 'RADIO':
                $html .= self::createInputTvRadio($media);
                break;*/

            case 'INTERNET':
                $html .= self::createInputInternet($media);
                break;

            case 'MOBILE':
                $html .= self::createInputMobile($media);
                break;
            case 'HORS-MEDIA':
                $html .= self::createInputHorsMedia($media);
                break;
        endswitch;

        return $html;
    }

    public function getDataInSession(Request $request){
        if($request->action == "cid"):
            $var = $request->var;
            $val = $request->val;
            $request->session()->put("collecte.$var", $val);
            $request->session()->save();
        endif;
    }

    public function addInputMedia(Request $request){
        $action = $request->action;
        if ($action == 'pageinterneitem'):
            $lapage = $request->get('lapage');
            $k = $request->get('k');
            if ($lapage == 'Page interne') :
                return view ("administration.Saisies.pageInterne", compact ('k'))->render ();
            endif;
        endif;
        if ($action == 'getTarif'):
            $heure = $request->get('heure');
            $cid = array_key_exists('cid', $request->session()->get('collecte')) ? $request->session()->get('collecte.cid') : 0;
            $date = array_key_exists('date', $request->session()->get('collecte')) ? $request->session()->get('collecte.date') : date("Y-m-d");
            $support = array_key_exists('support', $request->session()->get('collecte')) ? $request->session()->get('collecte.support') : 0;
            if($request->session()->has('collecte.heure')):
                $heure = $request->session()->get('collecte.heure');
            endif;
            $tabDate = explode("-", $date);
            $leJour = date("N", mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]));
            $sql = "SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db')." WHERE id = $cid";
            if($cid != 0):
                $re = FunctionController::arraySqlResult($sql);
                $duree = $re[0]['duree'];
            else:
                $duree = 0;
            endif;
            $sqlT = "SELECT tarif FROM
                        ".DbTablesHelper::dbTable('DBTBL_TARIFS','db')."
                    WHERE jour = $leJour AND support = $support AND  heuredebut <= '$heure'
                    AND heurefin >= '$heure' AND duree >= $duree ORDER BY duree ASC LIMIT 1";

            $reT = FunctionController::arraySqlResult($sqlT);
            $tarif = count($reT) == 1 ? $reT[0]['tarif'] : 0;

            return <<<TAR
<input name="tarif" type="text" size="10" pattern="\d*" class="form-control" value="$tarif">
TAR;
        endif;

        if ($action == 'getCoef'):
            return <<<COEF
<input name="coeff" type="text" size="10" pattern="\d*"  class="form-control" value="0">
COEF;
        endif;

        if($action == "mobile_profil"):
            $operateur = $request->get('operateur');
            $sql = "SELECT id, name FROM
                    ".DbTablesHelper::dbTable('DBTBL_MOBILES','db')."
                 WHERE operateur = " . $operateur . " ORDER BY name ASC";
            $dt = FunctionController::arraySqlResult($sql);
            return view('administration.Saisies.profil_mobile', compact('dt'));
        endif;
    }

    public function changeDate(Request $request){
            $k =$request->all();
            $dt = $k['dt'];
            $plus = $k['plus'];
            if ($dt === "YES"):
                $plus += $plus;
            else:
                $request->session()->put('collecte.date', $dt);
                $request->session()->save();
            endif;
            return view('administration.Form.changeDate',compact('plus','dt'))->render ();
    }

    public function storePub(Request $request): JsonResponse
    {
        $data = $request->all();
        unset($data['_token'],$data['mediaUri'],$data['mediaID']);
        if(isset($data['campagnetitle'], $data['campagne'])):
            $data['dateajout'] = date("Y-m-d");
            $data['user'] = Auth::id();
            //$data['valide'] = 0;
            unset($data['campagnetitle']);
            if (($request->mediaID === 3) && $data['presse_page'] === "Page interne"):
                $data['presse_page'] = $data['pageinterne'];
                unset($data['pageinterne']);
            endif;

            if((int)$request->mediaID === 6):
                $data['localite'] = $data['commune'];
                unset($data['ville'],$data['commune']);
                $pubs = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUB_NON_VALIDES','db'))->insert ($data);
                if ($pubs):
                    $css_alert = " alert-success";
                    $message = "Données enregistré(s) avec succès!";
                else:
                    $css_alert = " alert-danger";
                    $message = "Veuillez obligatoirement choisir un panneau!";
                endif;
            elseif((int)$request->mediaID === 7):
                unset($data['format']);
                $points = $request->session()->get("pointsHorsMedia");
                //dd($data,$points);
                $pubs = DB::transaction (function () use($data,$points){
                    $pubID = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PUB_NON_VALIDES','db'))
                        ->insertGetId($data);
                    //*
                    foreach ($points as $r):
                        DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_COORDONNEES_HORS_MEDIAS','db'))
                            ->insert (
                                [
                                    'pub' => $pubID,
                                    'latitude' => $r['latitude'],
                                    'longitude' => $r['longitude'],
                                    'ville' => $r['ville'],
                                    'commune' => $r['commune'],
                                    'detail' => $r['detail_emplacement'],
                                ]
                            );
                    endforeach;//*/
                    return $pubID;
                });
                $css_alert = " alert-success";
                $message = "Pub enregistrée avec succès!";
                $request->session ()->forget ("pointsHorsMedia");
            else:
                $pubs = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PUB_NON_VALIDES','db'))
                  ->insertGetId($data);
                $css_alert = " alert-success";
                $message = "Pub enrégistrée avec succès!";
            endif;

            if($pubs):
                if (!$request->session ()->has ("supportPubIn")):
                    $request->session ()->put ("supportPubIn",$data['support']);
                else:
                    if ($request->session ()->get ("supportPubIn") !== $data['support']):
                        $request->session ()->put ("supportPubIn",$data['support']);
                    endif;
                endif;
                $request->session ()->save ();
            else:
                $css_alert = " alert-danger";
                $message = "Une erreur s\'est produite, merci de r&eacute;essayer plus tard";
            endif;
        else:
            $css_alert = " alert-danger";
            $message = "Veuillez obligatoirement choisir une campagne!";
        endif;
        $userID = Auth::id ();
        $listePubSaisie = self::mesSaisies($request->mediaID,$userID);

        return response ()->json ([
            'css_alert' => $css_alert,
            'message' => $message,
            'pub_saisie' => $listePubSaisie,
            'today' => $data['date']
        ]);
    }

    public static function saisiesDuJour(int $media, string $date, int $user = 0, int $valide = 0):string {
        $table = $valide === 0 ? DbTablesHelper::dbTable ("DBTBL_PUB_NON_VALIDES",'db') : DbTablesHelper::dbTable ("DBTBL_PUBS",'db');
        $userCond = $user === 0 ? " AND user != ".Auth::id ()." " : " AND user = $user ";
        $query = "SELECT * FROM $table WHERE created_at LIKE '%$date%' $userCond
         AND support
         IN(SELECT id FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." WHERE media = $media)
         ORDER BY created_at DESC";
        $pubs = FunctionController::arraySqlResult ($query);
        if (count ($pubs)):
            $thDuree = "";
            $action = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_MEDIAS','db'),$media);
            if ($action === "TELEVISION" || $action === "RADIO"):
                $thDuree = "<th>Dur&eacute;e</th>";
            endif;
            return view ("administration.Saisies.tabListePubSaisie", compact ('pubs','thDuree','action'))->render ();
        else:
            return "";
        endif;
    }

    public function getUserSaisie(Request $request){
        if (!array_key_exists ('k',$request->all ())):
            $user = Auth::id ();
            return self::showSaisie (date ("Y-m-d"),$user,$request->d);
        else:
             if ($request->k == 'autre'):
                 return self::showSaisie (date ("Y-m-d"),0,$request->d);
             endif;
        endif;
    }

    public static function showSaisie(string $date, int $user = 0, int $valide = 0){
        $medias = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')." ORDER BY name ASC");
        return view ("administration.Saisies.showSaisies", compact ('date','user','valide','medias'))->render ();
    }

    public function getFormSaisieFrame(Request $request){
        $campagne = $request->campagne;
        $media = $request->media;
        return view ("administration.Saisies.frameFormAjax",compact ('campagne','media'))->render ();
    }

    public function filterAnnonceurs(Request $request){
        $idcamptitle = $request->campagnetitle;
        $cond = $request->filter != "" ? " AND raisonsociale LIKE '%{$request->filter}%' " : "";

        $sql = "SELECT id,raisonsociale
            FROM ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','dbtable')." a
            WHERE id NOT IN(SELECT annonceur
                FROM
                    ".DbTablesHelper::dbTable('DBTBL_SPONSOR_CAMPAGNES','dbtable')."
                WHERE campagnetitle = $idcamptitle) %s ORDER BY a.raisonsociale ASC
            ";
        $listeDesAnnonceurs = FunctionController::arraySqlResult (sprintf ($sql,$cond));
        return view ("administration.Campagnes.listeDesAnnonceursItem", compact ('listeDesAnnonceurs','idcamptitle'))->render ();
    }

    public function importation(){
        return view ("administration.Saisies.formImportDatas");
    }


    public function storeDataImporter(Request $request){
          $this->validate ($request,
            [
            'fichier' => 'required|file|mimes:xlsx'
        ],
            [
            'fichier.required' => 'Veuillez obligatoirement charger un fichier!',
            'fichier.mimes' => 'Le fichier devrait être de type: xlsx',
        ]);

        $table = DbTablesHelper::dbTablePrefixeOff("DBTBL_INJECTIONS",'db');
        $dbtable = DbTablesHelper::dbTable("DBTBL_INJECTIONS",'db');
        $fichier = $request->file ('fichier');
        $fileSize = $fichier->getSize ();
        $tailleAutorisee = 2000000;
        $fileUploaded = "fichier";
        $target_dir = public_path ("upload".DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."");
        $userid = Auth::id ();
        $message = [];
        $uploadOk = 1;
        if (!is_null($_FILES[$fileUploaded])) :
            $target_file = $target_dir . $fichier->getBasename ();

            // Check if file already exists
            if (file_exists($target_file)) :
                $message[] = "Sorry, file already exists.";
                $uploadOk = 0;
            endif;
            // Check file size
            if ($fileSize > $tailleAutorisee) :
                $message[] = "Sorry, your file is too large.";
                $uploadOk = 0;
            endif;
            if ($uploadOk == 0) :
                $message[] = "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            else :
                if (!file_exists ($target_dir.$fichier->getClientOriginalName ())) :
                    $fichier->move ($target_dir,$fichier->getClientOriginalName ());
                    $message[] = "The file " . $fichier->getBasename () . " has been uploaded.";
                else :
                    $message[] = "Sorry, there was an error uploading your file.";
                endif;
            endif;
            if ($uploadOk == 1):
                $donnees = Excel::toArray(new DonneesImportees(),$fichier);
                $data = $donnees[0];
                if (array_key_exists (0,$data)):
                      $i = 0;
                      $donn = [];
                      foreach ($data as $r):
                            $donn[$i] = $r;
                            $donn[$i]["date"] = FunctionController::formaterDateExcelToDateNormal ($r['date']);
                            $donn[$i]["dateajout"] = FunctionController::formaterDateExcelToDateNormal ($r["dateajout"]);
                            $donn[$i]['heure'] = FunctionController::formaterHeureExcelToHeureNormal ($r['heure']);
                            $donn[$i]['user'] = $userid;
                            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_INJECTIONS','db'))
                                  ->insert ($donn[$i]);
                            $i++;
                      endforeach;
                endif;
            endif;
        endif;
        $tbl = ["secteur", "cible", "couverture", "media", "offretelecom", "internet_emplacement", "mobile", "nature"];
        $tblv2 = [];
        $tblv2["format"] = ["name", "media"];
        $tblv2["support"] = ["name", "media"];
        $tblv2["annonceur"] = ["name", "secteur"];
        $tblv3["operation"] = ["name", "annonceur", "couverture"];
        $tblv4["campagnetitle"] = ["title", "operation", "offretelecom"];
        $tbleSpecifique = ["operation", "campagnetitle"] ;

         $userid = Auth::id ();
        foreach ($tbl AS $tble):
            $fields = FunctionController::getListeDesChamps ($tble."s");
            $userChamps = in_array ('user',$fields) ? "'user' => $userid, " : "";
            $laTable = FunctionController::getTableName($tble."s");
            $requete = "SELECT DISTINCT $tble FROM $dbtable";
            $resultat = FunctionController::arraySqlResult ($requete);
            foreach ($resultat AS $r):
                $v = $r[$tble] ;
                if(intval($v) == 0 && trim($v) != ""):
                    $q = "SELECT id FROM $laTable WHERE name LIKE '".addslashes (trim($v))."'" ;
                    $resultId = FunctionController::arraySqlResult ($q);
                    if(count($resultId) >= 1):
                        $rID = $resultId[0]["id"] ;
                    else:
                        $stmtIn = DB::insert("INSERT INTO  $laTable (name,user) VALUES(?,?)",[trim ($v),$userid]);
                        $rID = DB::getPdo()->lastInsertId();;
                    endif;
                    $stmtu = DB::table ($table)
                        ->where ([$tble => $v])
                        ->update ([
                          $tble => $rID
                        ]);
                endif;
            endforeach;
        endforeach;

        foreach ($tblv2 AS $tble => $ligne):
            $fields = FunctionController::getListeDesChamps ($tble."s");
            $virg = '';
            $userChamps = "";
            $userAttr = "";
            if (in_array ('user',$fields)):
                $userAttr = ',user';
                $virg = ",?";
                $userChamps = $userid;
            endif;
            $ch1 = $ligne[0] ;
                $fk = $ligne[1] ;
                $laTable = FunctionController::getTableName($tble."s");
                $requete = "SELECT DISTINCT $tble, $fk FROM $dbtable";
                $resultat = FunctionController::arraySqlResult ($requete);
                foreach ($resultat AS $r):
                    $v = $r[$tble] ;
                    if(intval($v) == 0 && trim($v) != ""):
                        $k = $r[$fk] ;
                        $q = "SELECT id FROM $laTable WHERE $ch1 LIKE '".addslashes (trim($v))."' AND $fk = $k" ;
                        $resultId = FunctionController::arraySqlResult ($q);
                        if(count($resultId) >= 1):
                            $rID = $resultId[0]["id"];
                        else:
                            $stmtIn = DB::insert ("INSERT INTO $laTable ($ch1,$fk $userAttr) VALUES (?,? $virg) ",[trim ($v),$k,$userChamps]);
                             $rID = DB::getPdo()->lastInsertId();
                        endif;
                        $stmtu = DB::table ($table)
                             ->where ([$tble => $v, $fk => $k])
                             ->update ([
                                 $tble => $rID
                             ]);
                    endif;
                endforeach;//*/
        endforeach;

        foreach ($tblv3 AS $tble => $ligne):
            $fields = FunctionController::getListeDesChamps ($tble."s");
            $virg = '';
            $userChamps = "";
            $userAttr = "";
            if (in_array ('user',$fields)):
                $userAttr = ',user';
                $virg = ",?";
                $userChamps = $userid;
            endif;

            $ch1 = $ligne[0] ;
                $ch2 = $ligne[1] ;
                $ch3 = $ligne[2] ;
                $laTable = FunctionController::getTableName($tble."s");
                $requete = "SELECT DISTINCT $tble, $ch2, $ch3 FROM $dbtable";
                $resultat = FunctionController::arraySqlResult ($requete);
                foreach ($resultat AS $r):
                    $v = $r[$tble] ;
                    if(intval($v) == 0 && trim($v) != ""):
                        $k2 = $r[$ch2] ;
                        $k3 = $r[$ch3] ;

                        $q = "SELECT id FROM $laTable WHERE $ch1 LIKE '".addslashes(trim($v))."' AND $ch2 = $k2 AND $ch3 = $k3" ;
                        $resultId =FunctionController::arraySqlResult ($q);
                        if(count($resultId) >= 1):
                            $rID = $resultId[0]["id"] ;
                        else:
                            $today = date ("Y-m-d H:i:s");
                             $qu = "INSERT INTO $laTable ($ch1,$ch2,$ch3 $userAttr) VALUES (?,?,? $virg)";
                            if(in_array($tble,$tbleSpecifique)):
                                $qu = "INSERT INTO  $laTable ($ch1, $ch2, $ch3, user, adddate) VALUES(?, ?, ? $virg, '$today')" ;
                            endif;
                            $stmtIn = DB::insert ($qu,[FunctionController::quote(trim ($v)),$k2,$k3,$userChamps]);
                            $rID = DB::getPdo()->lastInsertId();
                        endif;
                        $stmtu = DB::table ($table)
                            ->where ([$tble => $v, $ch2 => $k2, $ch3 => $k3])
                            ->update ([
                                $tble => $rID
                            ]);
                    endif;
                endforeach;
            endforeach;

        foreach ($tblv4 AS $tble => $ligne):
            $fields = FunctionController::getListeDesChamps ($tble."s");
            $virg = '';
            $userChamps = "";
            $userAttr = "";
            if (in_array ('user',$fields)):
                $userAttr = ',user';
                $virg = ",?";
                $userChamps = $userid;
            endif;

            $ch1 = $ligne[0] ;
            $ch2 = $ligne[1] ;
            $ch3 = $ligne[2] ;
            $laTable = FunctionController::getTableName($tble."s");
            $requete = "SELECT DISTINCT $tble, $ch2, $ch3 FROM $dbtable";
            $resultat = FunctionController::arraySqlResult ($requete);

            foreach ($resultat AS $r):
                $v = $r[$tble] ;
                if(intval($v) == 0 && trim($v) != ""):
                    $k2 = $r[$ch2] ;
                    $k3 = $r[$ch3] ;

                    $q = "SELECT id FROM $laTable WHERE $ch1 LIKE ".FunctionController::quote(trim($v))." AND $ch2 = $k2" ;

                    $resultId = FunctionController::arraySqlResult ($q);
                    if(count($resultId) >= 1):
                        $rID = $resultId[0]["id"] ;
                    else:
                        if(in_array($tble,$tbleSpecifique)):
                            $adddate = date("Y-m-d") ;
                            $userid = Auth::id (); //Prendre l'id du user courant
                            $laRequeteDate = "SELECT date FROM $dbtable WHERE $tble = " . FunctionController::quote($v) . " AND $ch2 = $k2 ORDER BY date ASC LIMIT 1";
                            $dateResult = FunctionController::arraySqlResult ($laRequeteDate);
                            if(count($dateResult) >= 1):
                                $adddate = $dateResult[0]["date"] ;
                            endif;
                        endif;
                        if(is_int($k3)):
                            $qu = "INSERT INTO  $laTable ($ch1, $ch2, $ch3, adddate) VALUES(?, ?, ? ,'$adddate')" ;
                            $dq = [trim($v), $k2, $k3];
                        else:
                            $qu = "INSERT INTO  $laTable ($ch1, $ch2, user, adddate) VALUES(?, ?, $userid,'$adddate')" ;
                            $dq = [trim($v), $k2];
                        endif;
                        $stmtIn = DB::insert ($qu,$dq);
                        $rID = DB::getPdo()->lastInsertId();
                    endif;
                    $stmtu = DB::table ($table)
                        ->where ([
                            $tble => $v,
                            $ch2 => $k2,
                            $ch3 => $k3
                        ])
                        ->update ([$tble => $rID]);
                endif;
            endforeach;
        endforeach;
        Session::flash("success","Données injectées avec succès!");
        return back ();
    }

    public static function utilisateurSaisie():string {
        $medias = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')." ORDER BY name ASC");
        $userID = Auth::id ();
        return view ("administration.Saisies.lesSaisiesUtilisateurs", compact ('medias','userID'));
    }

    public function getFormTarif(Request $request){
         $media = $request->media;
         return view ("administration.Tarifs.frameFormTarif", compact ('media'))->render ();
    }

    public function formTarifFrame(int $media){
        $html = self::tarifInputMedia ($media);
        $html .= FormController::champSubmit ();
        return view ("administration.Tarifs.frameFormTarifBis", compact ('html','media'))->render ();
    }

    public static function tarifInputMedia(int $media){
        $lemedia = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_MEDIAS','db'),$media);
        $html = "";
        switch($lemedia):
            case 'TELEVISION':
                $html .= self::formTarifTv($media);
                break;
            case 'PRESSE':
                $html .= self::formTarifPresse($media);
                break;
            case 'AFFICHAGE':
                $html .= self::createInputAffichage($media);
                break;
            case 'RADIO':
                $html .= self::formTarifRadio($media);
                break;
            case 'INTERNET':
                $html .= self::formTarifInternet($media);
                break;
            case 'MOBILE':
                $html .= self::formTarifMobile($media);
                break;
        endswitch;
        return $html;
    }

    public function coupureTarif(Request $request){
        $coupure = $request->coupure;
        $input = "";
        if ($coupure == 'true'):
           $input = view ("administration.Tarifs.coupureInput")->render ();
        endif;
        return $input;
    }

    public function insertTarif(Request $request){
        $datas = $request->all ();
        unset($datas['_token']);
        unset($datas['media']);
        $insert = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_TARIFS','db'))
            ->insert ($datas);
        if ($insert):
           $request->session ()->flash ('success',"Tarif enrégistré avec succès !");
        else:
            $request->session ()->flash ('echec',"Une erreur est survenu, veuillez recommencer !");
        endif;
        return redirect ()->route ('saisie.formTarifFrame',$request->media);
    }

    public static function menuDeModificationDesSaisies():string {
        $userID = Auth::id ();
        $menu = '';
        $campagneSaisieNonValider = FunctionController::arraySqlResult ("SELECT COUNT(id) FROM ".DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLE_NON_VALIDES','db')." WHERE user = $userID");
        if (count ($campagneSaisieNonValider)):
            $route = route ('saisie.listeCampagnesNonValider');
            $title = "Modifier vos campagnes saisies";
            $menu .= view ("administration.Saisies.boutonMenuModif",compact ('route','title'))->render ();
        endif;
        $saisiesNonValider = FunctionController::arraySqlResult ("SELECT COUNT(id) FROM ".DbTablesHelper::dbTable ('DBTBL_PUB_NON_VALIDES','db')." WHERE user = $userID");
        if (count ($saisiesNonValider)):
            $route = route ('saisie.listeSaisiesNonValider');
            $title = "Modifier vos pubs saisies";
            $menu .= view ("administration.Saisies.boutonMenuModif",compact ('route','title'))->render ();
        endif;
        return view ("administration.Saisies.menuModif", compact ('menu'))->render ();
    }

    public function listeCampagnesNonValider(){
        $userID = Auth::id ();
        $campagneSaisieNonValider = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLE_NON_VALIDES','db')." WHERE user = $userID");
        return view ("administration.Saisies.listeDesCampagnesNonValide",compact ('campagneSaisieNonValider'));
    }

    public function listeSaisiesNonValider(){
        $userID = Auth::id ();
        $lesMedias = AdminController::listeDesMedias();
        $mesSaisies = [];
        foreach ($lesMedias as $id => $media):
            $saisiesNonValider = FunctionController::arraySqlResult ("SELECT p.* FROM
                ".DbTablesHelper::dbTable ('DBTBL_PUB_NON_VALIDES','db')." p,
                ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." s
                WHERE p.user = $userID AND p.support = s.id AND s.media = {$id}");
            if (count ($saisiesNonValider)):
                $mesSaisies[$id] = $saisiesNonValider;
            endif;
        endforeach;
        if (!Session::has ('mediaTabActive')):
            $firstkey = array_keys ($lesMedias)[0];
            Session::put ('mediaTabActive', $firstkey);
        endif;
        return view ("administration.Saisies.listeDesSaisiesNonValide",compact ('mesSaisies','lesMedias'));
    }

    /**
     * @throws Throwable
     */
    public function formUpdateSaisie(Request $request): string
    {
        $table = $request->table;
        $id = $request->pk;
        $media = $request->media;
        return self::createInputUpdateMedia ($id,$media,$table);
    }

    /**
     * @param int $pubID
     * @param int $mediaID
     * @param string $table
     * @return string
     * @throws Throwable
     */
    public static function createInputUpdateMedia(int $pubID, int $mediaID, string $table): string
    {
        $lemedia = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_MEDIAS','db'),$mediaID);
        $html = "";
        switch($lemedia):
            case 'TELEVISION':
            case 'RADIO':
                $html .= self::formUpdateInputTv($mediaID,$pubID,$table);
                break;
            case 'PRESSE':
                $html .= self::formUpdateInputPresse($mediaID,$pubID,$table);
                break;

            case 'AFFICHAGE':
                $html .= self::formUpdateInputAffichage($mediaID,$pubID,$table);
                break;

          /*  case 'RADIO':
                $html .= self::formUpdateInputTv($mediaID,$pubID,$table);
                break;*/

            case 'INTERNET':
                $html .= self::formUpdateInputInternet($mediaID,$pubID,$table);
                break;

            case 'MOBILE':
                $html .= self::formUpdateInputMobile($mediaID,$pubID,$table);
                break;
        endswitch;
        return view ("administration.Saisies.formUpdateSaisie",compact ('html','table','pubID','lemedia','mediaID'))->render ();

    }

    public function updateSaisie(Request $request){
        $donnees = $request->all ();
        unset($donnees['_token']);
        unset($donnees['table']);
        unset($donnees['pk']);
        unset($donnees['media']);
        $media = $request->media;
        $pref = env ('DB_PREFIX');
        $table = substr($request->table, mb_strlen($pref));
        $update = DB::table ($table)
            ->where ('id',$request->pk)
            ->update ($donnees);
        if ($update):
            Session::flash('success','Mise à jour éffectuée avec succès!');
        else:
            Session::flash('echec','Une erreur est survénue, veuillez récommencer!');
        endif;
        Session::put ("mediaTabActive", $media);
        return back ();
    }

    public function choisirCoordHorsMedia(Request $request){
        $lat = $request->lat;
        $lng = $request->lng;
        $data = ['latitude' => $lat,'longitude' => $lng];
        $form = view ("administration.Saisies.listeDesPoints",compact ('data'))->render ();
        return response ()->json (['formLngLat' => $form]);
    }

    public function ajouterPointHorsMedia(Request $request): JsonResponse
    {
        $data = $request->all ();
        unset($data['_token']);
        if (!$request->session ()->has ('pointsHorsMedia')):
            $request->session ()->put ('pointsHorsMedia',[]);
        endif;
        if (!count ($request->session ()->get ('pointsHorsMedia'))):
            $request->session ()->put ('pointsHorsMedia',[$data]);
        else:
            $request->session ()->push ('pointsHorsMedia',$data);
        endif;
        $request->session ()->save ();
        $listeDesPoints = $request->session ()->get ('pointsHorsMedia');
        $listeDesPointsHtml = view ("administration.Saisies.listeDesPointsHorsMedia",compact ('listeDesPoints'))->render ();
        return response ()->json ([
            'alert' => 'alert-success',
            'message' => 'validation des coordonnées avec succès!',
            'listeDesPoints' => $listeDesPointsHtml
        ]);
    }

    public function deletePointsHorsMedia(Request $request){
        $key = $request->key;
        $request->session ()->forget ("pointsHorsMedia.$key");
        $request->session ()->save ();
        $listeDesPoints = $request->session ()->get ('pointsHorsMedia');
        $listeDesPointsHtml = view ("administration.Saisies.listeDesPointsHorsMedia",compact ('listeDesPoints'))->render ();
        return response ()->json ([
            'listeDesPoints' => $listeDesPointsHtml
        ]);
    }

    public static function mesSaisies(int $mediaID,int $userID):string {
        $medias = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')."
        WHERE id IN(SELECT media FROM ".DbTablesHelper::dbTable ("DBTBL_SUPPORTS",'db')."
        WHERE id IN(SELECT support FROM ".DbTablesHelper::dbTable ('DBTBL_PUB_NON_VALIDES','db')." WHERE user = $userID))");

        $saisies = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ("DBTBL_PUB_NON_VALIDES",'db')." WHERE user = $userID ORDER BY date DESC");
        $pubsSaisies = [];
        foreach ($saisies as $r):
            $medID = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db'),$r['support'],'media');
            $pubsSaisies[$medID][] = $r;
        endforeach;
        $mediaActive = $mediaID;
        return view ("administration.Saisies.listePubSaisie",compact ('medias','pubsSaisies','mediaActive'))->render ();
    }

    public function newTypeDeComm(){
        $table = DbTablesHelper::dbTable ('DBTBL_TYPE_DE_PROMOS','db');
        return ModuleController::makeForm ($table);
    }

    public function newTypeDeService(){
        $table = DbTablesHelper::dbTable ('DBTBL_TYPE_DE_SERVICES','db');
        return ModuleController::makeForm ($table);
    }
}
