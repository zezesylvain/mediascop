<?php

namespace App\Http\Controllers\Administration;

use App\Exports\FicheMediaExport;
use App\Exports\PubsExports;
use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use App\Http\Controllers\core\ModuleController;
use App\Http\Controllers\core\XeditableController;
use App\Jobs\SendEmailJob;
use App\Mail\Speednews;
use App\Models\Annonceur;
use App\Models\Cible;
use App\Models\Couverture;
use App\Models\Format;
use App\Models\Media;
use App\Models\Nature;
use App\Models\Secteur;
use App\Models\Support;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class TableauDeBordController extends AdminController
{

    protected $condition;

    public $genericQuery = "
        SELECT
                %s
        FROM
                zdsb_pubs p,
                zdsb_campagnetitles ct,
                zdsb_campagnes c,
                zdsb_supports sup,
                zdsb_formats f,
                zdsb_cibles ci,
                zdsb_medias m,
                zdsb_couvertures co,
                zdsb_annonceurs so,
                zdsb_operations o,
                zdsb_affichage_dimensions di,
                zdsb_internet_dimensions dim,
                zdsb_presse_calibres  ca,
                zdsb_presse_couleurs coul,
                zdsb_vz_users u,
                zdsb_secteurs se
        WHERE
                p.campagne = c.id AND
                p.support = sup.id AND
                p.user = u.id AND
                c.campagnetitle = ct.id AND
                c.format = f.id AND
                c.cible = ci.id AND
                c.affichage_dimension = di.id AND
                c.presse_couleur = coul.id AND
                c.internet_dimension = dim.id AND
                c.presse_calibre = ca.id AND
                sup.media = m.id AND
                ct.operation = o.id AND
                o.annonceur = so.id AND
                so.secteur = se.id AND
                o.couverture = co.id 
                %s AND
                p.date BETWEEN '%s' AND '%s'
        ORDER BY p.date DESC";

    public $exportSelect = "
                p.date,
                m.name AS media,
                sup.name AS support,
                f.name AS format,
                ci.name AS cible,
                se.name AS secteur,
                so.raisonsociale AS annonceur,
                ct.title AS title,
                o.name AS operation,
                c.duree, 
                (p.tarif + p.investissement) AS tarif, 
                p.coeff,
                p.heure,
                u.name AS utilisateur,
                co.name AS couverture,
                coul.name AS couleur,
                ca.name AS calibre,
                dim.name AS dimension,
                di.name AS dimension_du_panneaux,
                c.mobilemessage AS message_sms
                ";
    public $sqllistpub = "
                p.id, p.date, p.tarif, p.coeff,
                ct.title as campagnetitle,
                sup.name as supportname,
                so.raisonsociale
                ";

    public $entete = array(
        'date' => 'DATE', 'heure' => 'HEURE', 'couverturename' => 'COUVERTURE',
        'naturename' => 'NATURE',
        'formatname' => 'FORMAT',
        'ciblename' => 'CIBLE', 'supportname' => 'SUPPORT',
        'raisonsociale' => 'ANNONCEUR', 'campagnetitle' => 'CAMPAGNE',
        'operationtitle' => 'OPERATION', 'secteurname' => 'SECTEUR',
        'offretelecom' => 'OFRE TELECOM',
        'tarif' => 'TARIF', 'coef' => 'COEF',
        'duree' => 'DUREE', 'couleurname' => 'COULEUR',
        'calibrename' => 'CALIBRE', 'dimension' => 'DIMENSION (Internet)',
        'dimensiondupaneau' => 'DIM PANNEAU', 'messagesms' => 'CONTENU SMS',
        'medianame' => 'MEDIA',
        'username' => 'USER',
        'tranche_horaire' => 'TRANCHE HORAIRE'
    );


    public $data;

    public function listeCampagnes(){
        $action = "campagnes";
        return self::listerData ($action);
    }

    public function listeOperations(){
        $action = "operations";
        return self::listerData ($action);
    }

    public function listePubsTelevision(){
        $action = "TELEVISION";
        return self::listerData ($action);
    }

    public function listePubsRadio(){
        $action = "RADIO";
        return self::listerData ($action);
    }

    public function listePubsAffichage(){
        $action = "AFFICHAGE";
        return self::listerData ($action);
    }

    public function listePubsPresse(){
        $action = "PRESSE";
        return self::listerData ($action);
    }

    public function listePubsInternet(){
        $action = "INTERNET";
        return self::listerData ($action);
    }

    public function listePubsMobile(){
        $action = "MOBILE";
        return self::listerData ($action);
    }

    public static function getCondition(){
        $datas = Session::get ('filter_param');
        $debutop = FunctionController::date2Fr ($datas['dddebutfop'],"Y-m-d");
        $ddfinop = FunctionController::date2Fr ($datas['ddfinfop'],"Y-m-d");
        $secteurfilteroperation = $datas['secteurfilteroperation'];
        $annonceurfilteroperation = $datas['annonceurfilteroperation'];
        $cond = '';
        $chp = 'adddate';
        if ($datas['action'] == "campagnes"):
            $key = "ct";
        elseif ($datas['action'] == "operations"):
            $key = "o";
        else:
            $chp = 'date';
            $key = "p";
        endif;
        if (empty(!array_key_exists("filtre", $datas))):
            return $cond;
        endif;
        if ($debutop <= $ddfinop):
            $getConditionDate = '';
            $date_deb = $debutop;
            $date_fin = $ddfinop;

            $and = " AND ";

            if ($date_deb <= $date_fin && $date_deb != null && $date_fin != null):
                $getConditionDate .= " $key.$chp BETWEEN '$date_deb' AND '$date_fin'";
                $cond .= " $and ".$getConditionDate;
                $and = " AND";
            endif;

            if ($datas['action'] == "campagnes" || $datas['action'] == "operations"):
                if ($secteurfilteroperation):
                    $getConditionSecteur = " a.secteur = $secteurfilteroperation";
                    $cond .= " $and ".$getConditionSecteur;
                    $and = " AND";
                endif;

                if ($annonceurfilteroperation):
                    $getConditionAnnonceur = " o.annonceur = $annonceurfilteroperation";
                    $cond .= " $and ".$getConditionAnnonceur;
                endif;

                if ($cond != ""):
                    $cond = " $cond ";
                endif;
            else:
                if ($secteurfilteroperation):

                    $condi = "p.campagne IN(SELECT id FROM
                    ".DbTablesHelper::dbTable ('DBTBL_CAMPAGNES','db')."
                    WHERE campagnetitle
                        IN(SELECT id FROM
                        ".DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLES','db')."
                        WHERE operation IN(SELECT id FROM
                        ".DbTablesHelper::dbTable ('DBTBL_OPERATIONS','db')."
                        WHERE annonceur IN(SELECT id FROM
                        ".DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db')."
                        WHERE secteur %s))))";
                    $cc = "";
                    if ($annonceurfilteroperation):
                        $getConditionAnnonceur = " IN(SELECT secteur FROM
                        ".DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db')."
                        WHERE id = $annonceurfilteroperation)";
                       $cc .= sprintf ($condi,$getConditionAnnonceur);
                    else:
                        $getConditionSecteur = " = $secteurfilteroperation";
                        $cc .= sprintf ($condi,$getConditionSecteur);
                    endif;
                    $cond .= " $and ".$cc;
                endif;
            endif;
        endif;
        return $cond;
    }

    public static function makeListeDesCampagnes(int $valide = 1,$ajax = null){
        $cond = self::getCondition ();
        $DATABASE = $valide == 0  ? DbTablesHelper::dbTable ("DBTBL_CAMPAGNETITLE_NON_VALIDES",'db') : DbTablesHelper::dbTable ("DBTBL_CAMPAGNETITLES",'db');
        $condValide = $valide == 0 ? " AND ct.valide = $valide " : "";
        $condLoad = "";
        $view = 'tablistecampagnes';
        if ($ajax === true):
            $view = 'tablistecampagnesLoad';
            $condLoad = " AND ct.created_at LIKE '%".date('Y-m-d')."%' ";
        endif;

        $sql = "SELECT ct.id, ct.title, ct.adddate, ct.adddate, o.name as operationname, a.raisonsociale
                FROM
                     ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','db') ." o,
                     ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db') ." a,
                      {$DATABASE} ct
                WHERE ct.operation = o.id AND o.annonceur = a.id $cond $condValide $condLoad ORDER BY ct.adddate DESC,ct.id DESC ";
        //dump($sql);
        $tabdata = FunctionController::arraySqlResult($sql);
        $routeval = route ('ajax.validerPubs');
        return view ("administration.TableauDeBords.$view", compact ('tabdata','valide','DATABASE','routeval'))->render ();
    }

    public static function filterCampagne(Request $request){
        if($request->get('filteroperation') == "ok"):
            $dsc1 = 1;
            $dsc = $dsc1 <= 7 ? 7 : $dsc1;
            $debutop = $request->dddebutfop;
            $ddfinop = $request->ddfinfop;
            $secteurfilteroperation = $request->secteurfilteroperation;
            $annonceurfilteroperation = $request->annonceurfilteroperation;
            $debutopfr = date('d-m-Y', mktime(0, 0, 0, date("n"), date("j") - $dsc, date("Y")));
            $action = strtolower($request->action);
            $donnees = [
                'dddebutfop' => $debutop,
                'ddfinfop' => $ddfinop,
                'debutopfr' => $debutopfr,
                'filteroperation' => 'ok',
                'secteurfilteroperation' => $secteurfilteroperation,
                'annonceurfilteroperation' => $annonceurfilteroperation,
                'action' => $request->action,
            ] ;

            Session::put('filter_param', $donnees);
            $mod = $request->pvalider == 0 ? "valider" : "dashbord";
            return redirect ()->route("$mod.$action");
        else:
            return redirect()->back();
        endif;
    }

    public static function listerData(string $action, int $valide = 1)
    {
        if (Session::has ("filter_param.action") && $action != Session::get("filter_param.action")):
            Session::forget ("filter_param");
            Session::forget ("PubsValide");
            Session::forget ("CampagnesValide");
        endif;
        $dsc1 = 1;
        $dsc = $dsc1 <= 7 ? 7 : $dsc1;
        $debutop = date('Y-m-d', mktime(0, 0, 0, date("n"), date("j") - $dsc, date("Y")));
        $ddfinop = date('Y-m-d', mktime(0, 0, 0, date("n"), date("j") + 1, date("Y")));
        //Session::forget ('filter_param');
        $debutopfr = date('d-m-Y', mktime(0, 0, 0, date("n"), date("j") - $dsc, date("Y")));
        $secteurfilteroperation = 0;
        $annonceurfilteroperation = 0;

        if(!Session::has('filter_param')):
            $donnees = [
                'dddebutfop' => $debutop,
                'ddfinfop' => $ddfinop,
                'debutopfr' => $debutopfr,
                'filteroperation' => 'ok',
                'secteurfilteroperation' => $secteurfilteroperation,
                'annonceurfilteroperation' => $annonceurfilteroperation,
                'action' => $action,
            ] ;
            Session::put('filter_param', $donnees);
        endif;
        $listeDonnees = '';
        if(Session::has('filter_param')):
            $debutop = Session::get ('filter_param.dddebutfop');
            $ddfinop = Session::get ('filter_param.ddfinfop');
            $debutopfr = Session::get ('filter_param.debutopfr');
            $secteurfilteroperation = Session::get ('filter_param.secteurfilteroperation');
            $annonceurfilteroperation = Session::get ('filter_param.annonceurfilteroperation');
            $listeDonnees .= self::makeListe ($action,$valide);
        endif;
        $mod = "dashbord";
        $listeDesMedia = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')." ");
        $media = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')." WHERE name = '$action'");
        return view ("administration.TableauDeBords.liste",compact ('debutopfr','debutop','ddfinop','secteurfilteroperation','annonceurfilteroperation','action','listeDonnees','mod','listeDesMedia','media','valide'));
    }

    public static function makeListe(string $action,int $valide = 1){
        if ($action == 'campagnes'):
            return self::makeListeDesCampagnes ($valide);
        elseif ($action == 'operations'):
            return self::makeListeDesOperations ();
        else:
            return self::makeListeDesMedias($action,$valide);
        endif;
    }

    public static function makeListeDesOperations(int $valider = 1, $ajax = null)
    {
        $cond = $valider == 1 ? self::getCondition () : "";
        $view = $valider == 1 ? "listeOperations" : "listeOperationsValider";

        $sql = "SELECT
                        o.id, o.adddate, o.name as operationname, a.id as societeid, a.raisonsociale, s.name as secteurname
                        FROM
                             ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','db')." o,
                             ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')." a,
                             ".DbTablesHelper::dbTable('DBTBL_SECTEURS','db'). " s
                        WHERE
                            o.annonceur = a.id  AND a.secteur = s.id AND o.valider = $valider $cond ORDER BY o.adddate DESC ";

        $dataTableData = FunctionController::arraySqlResult($sql);
        $databaseTable = DbTablesHelper::dbTable ('DBTBL_OPERATIONS','db');
        return view ("administration.TableauDeBords.$view",compact ('dataTableData','databaseTable'))->render ();
    }

    public static function makeListeDesMedias(string $action, int $valide){
        $tablePubs = $valide == 1 ? "DBTBL_PUBS" : "DBTBL_PUB_NON_VALIDES";
        $cond = self::getCondition ();
        $lesMedias = config ("constantes.medias");
        $mediaID = array_keys ($lesMedias,strtolower ($action))[0];

        $champs = $action == "HORS-MEDIAJJ" ? " p.type_service, p.type_promo, " : "";
        $champErr = $valide == 0 ? " ,p.erreur " : "";
        $champsAffichage = $action === "AFFICHAGE" ? " ,p.date_fin_affichage" : "";
        $sqlpub = "SELECT p.id, p.date,p.support,$champs p.campagne, p.tarif, p.coeff, p.investissement, p.heure, p.internet_emplacement, p.presse_page, p.user {$champErr} {$champsAffichage}
                FROM
                ".DbTablesHelper::dbTable($tablePubs,'db')." p,
                ".DbTablesHelper::dbTable('DBTBL_SUPPORTS','db')." s
                WHERE  p.support = s.id
                    AND s.media = {$mediaID} $cond";
        $listpub = FunctionController::arraySqlResult($sqlpub);
        //dump($listpub);
        if (count ($listpub)):
            $thDuree = "";
            $thHorsMedia = "";
            $thOperation = "";
            $thHeure = "";
            $thEmplacement = "";
            $thAffichage = "";
            if ($action == "TELEVISION" || $action == "RADIO"):
                $thDuree = $valide == 0 ? "<th data-field=\"duree\" data-editable=\"true\" data-editable-type=\"text\">Dur&eacute;e</th>" : "<th>Dur&eacute;e</th>";
                $thOperation = "<th>Opération</th>";
                $thHeure = $valide == 0 ? "<th data-field=\"heure\" data-editable=\"true\" data-editable-type=\"text\">Heure</th>" : "<th>Heure</th>";
            endif;
            if ($action == "HORS-MEDIA"):
                $thHorsMedia .= $valide == 0 ? "<th data-field=\"type_service\" data-editable=\"true\" data-editable-type=\"text\">Type de service</th>" : "<th>Type de service</th>";
                $thHorsMedia .= $valide == 0 ? "<th data-field=\"type_promo\" data-editable=\"true\" data-editable-type=\"text\">Type de promo</th>" : "<th>Type de promo</th>";
            endif;
            if ($action === "INTERNET" || $action === "PRESSE"):
                $thEmplacement = "<th>Emplacement</th>";
            endif;
            if ($action === "AFFICHAGE"):
                $editable = $valide === 0 ? "data-field=\"date_fin_affichage\" data-editable=\"true\"
                                                    data-editable-type=\"date\"
                                                    data-editable-viewformat=\"dd/mm/yyyy\"
                                                    data-editable-format=\"yyyy-mm-dd\" " : "";
                $editableInvest = $valide === 0 ? "data-field='investissement' data-editable='true' data-editable-type='text'" : "";
                $thAffichage = "<th {$editable}>Date Fin</th><th {$editableInvest}>Invest.</th>";
            endif;

            $tBody = self::tablePubContent ($listpub,$action,$valide);
            $databaseTable = $valide == 0 ? DbTablesHelper::dbTable ('DBTBL_PUB_NON_VALIDES','db') : DbTablesHelper::dbTable ('DBTBL_PUBS','db');
            $lesSupports = FunctionController::arraySqlResult ("SELECT id, name FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." WHERE media = {$mediaID}");
            $source = XeditableController::makeJsonFile ($lesSupports);
            return view ("administration.TableauDeBords.tabListePubs",compact ('listpub','action','thDuree','thHorsMedia','tBody','databaseTable','source','valide','thHeure','thOperation','thEmplacement','thAffichage'))->render ();
        endif;
    }

    public static function tablePubContent(array $listePubs, string $action,int $valide = 1):string 
    {
        $tBody = "";
        $ij=0;
       // $tableCampTitle = $valide == 1 ? 'DBTBL_CAMPAGNETITLES' : 'DBTBL_CAMPAGNETITLE_NON_VALIDES';
        $tableCampTitle = 'DBTBL_CAMPAGNETITLES';
        $DATABASE =  DbTablesHelper::dbTable('DBTBL_PUBS','db');
        //dd($listePubs);
        foreach ($listePubs as $row) :
            $rowID = $row['id'];
            $supportID = $row['support'];
            $support = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_SUPPORTS','db'), $row['support']);
            $campagnetitleID = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_CAMPAGNES','db'),$row['campagne'],'campagnetitle');
            $campagnetitle = FunctionController::getChampTable (DbTablesHelper::dbTable ($tableCampTitle,'db'),$campagnetitleID,'title');
            $rowvalide = $valide;
            $number = $row['tarif'] + $row['investissement'];
           // dump($row);
            $investTarif = FunctionController::formatNumber($number,0,",",".");
            $rowcoeff = $row['coeff'] ;
            $rowtarif = $investTarif;
            $rowdate = $row['date'];
            $rowsupport = $support;
            $userSaisie = FunctionController::getChampTable (FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_USERS')),$row['user']);
            $active = "<span class='fa fa-check'></span>";
            $routeval = route('ajax.validerPubs');
            $tdDuree = "";
            $tdHorsMedia = "";
            $tdOperation = "";
            $tdHeure = "";
            $tdEmplacement = "";
            $tdAffichage = "";
            if ($action === "TELEVISION" || $action === "RADIO"):
                $getduree = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db'),$row['campagne'],"duree");
                $rowduree = $getduree;
                $tdDuree = "<td>".$rowduree."</td>";
                $operationID = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db'),$campagnetitleID,'operation');
                $operation = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_OPERATIONS','db'),$operationID,'name');
                $tdOperation = "<td>".$operation."</td>";
                $tdHeure = "<td>".$row['heure']."</td>";
            endif;
            if ($action === "PRESSE" || $action === "INTERNET"):
                $champ = $action === "PRESSE" ? 'presse_page' : 'internet_emplacement';
                $tbEmpl = $action === "PRESSE" ? DbTablesHelper::dbTable('DBTBL_PRESSE_PAGES','db') : DbTablesHelper::dbTable('DBTBL_INTERNET_EMPLACEMENTS','db');
                $donneeEmplacement = FunctionController::getChampTable($tbEmpl,$row[$champ],'name');
                $tdEmplacement = "<td>".$donneeEmplacement."</td>";
            endif;
            if ($action === "AFFICHAGE"):
                $dateTaff = $valide === 1 ? FunctionController::date2Fr($row['date_fin_affichage']) : $row['date_fin_affichage'];
                $tdAffichage .= "<td>{$dateTaff}</td><td>{$row['investissement']}</td>";
            endif;
            if ($rowvalide === 0):
                 $ij++;
            endif;
            $checkErr = "";
            if (isset($row['erreur'])):
               $checkErr = $row['erreur'] === 1 ? "checked" : "";
            endif;
            $tdErreur = $valide === 0 ? "<td><input id=\"erreur\" type=\"checkbox\" name=\"erreur\" value=\"\" onclick='signalerErreur({$row["id"]},this.checked,$ij)' {$checkErr}></td>" : "";
            $tBody .= view ("administration.TableauDeBords.tBodyPubs",compact ('rowsupport','rowdate','rowvalide','rowtarif','rowcoeff','DATABASE','active','campagnetitle','tdDuree','tdHorsMedia','rowvalide','rowID','ij','routeval','supportID','action','userSaisie','tdErreur','tdOperation','tdHeure','tdEmplacement','checkErr','tdAffichage'))->render ();
        endforeach;
        return $tBody;
    }

    public function choisirPubs(string $media, int $valider){
        $media = strtolower ($media);
        Session::put ("PubsValide", $valider);
        return redirect ()->route ("dashbord.$media");
    }

    public function choisirCampagnes(int $valider): \Illuminate\Http\RedirectResponse
    {
        Session::put ("CampagnesValide", $valider);
        return redirect ()->route ("dashbord.campagnes");
    }

    public function validerPubs(Request $request)
    {
        $action = '';
        $table = FunctionController::getTableNameSansPrefixe ($request->input('database'));
        $id = $request->input('id');
        $tableP = DbTablesHelper::dbTablePrefixeOff('DBTBL_PUB_NON_VALIDES','db');
        $champs = 'date, campagne, support, affichage_panneau, dateajout,tarif,coeff,user,heure,presse_page,internet_emplacement,mobile,investissement,nombre,localite,point_id,date_fin_affichage,affichage_uniq_id';
        if (array_key_exists ('k',$request->all ())):
            $champs = 'operation, offretelecom, title, adddate, user';
            $tableP = DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNETITLE_NON_VALIDES','db');
            $table = DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNETITLES','db');
            $action = 'campagnes';
        endif;
        $query = "SELECT %s FROM %s WHERE id = %d";
        DB::transaction (function () use($id,$table,$tableP,$champs,$query,$request){
            $tablePP = FunctionController::getTableName ($tableP);
            $dinvalid = FunctionController::arraySqlResult (sprintf ($query,$champs,$tablePP,$id));
            $data = $dinvalid[0];
            $data['heure'] = $data['heure'] === null ? date('H:i') : $data['heure'];
            $media = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_SUPPORTS','db'),$data['support'],'media');
            if ($media === 6):
                $lesDates = FunctionController::getDatesFromRange($data['date'],$data['date_fin_affichage']);
                $invest = ceil($data['investissement']/count($lesDates));
                $data['investissement_affichage'] = $data['investissement'];
                $data['investissement'] = $invest;
                $insID = DB::transaction(function ()use ($lesDates,$data){
                    foreach ($lesDates as $laDate):
                        $data['date'] = $laDate;
                        DB::table(DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUBS','db'))->insert($data);
                    endforeach;
                    return true;
                });
            else:
                $insID = DB::table ($table)->insertGetId ($data);
            endif;
            DB::table ($tableP)->where('id', $id)->delete ();
            if (!array_key_exists ('k',$request->all ())):
                self::speedNews ($insID);
            endif;
        });
        if (!array_key_exists ('k',$request->all ())):
            return view ("administration.TableauDeBords.checkedPubs")->render ();
        else:
            return [
                'db' => $request->input('database'),
                'routeListeData' => route('ajax.listeSaisieValider',[$action]),
                'resAction' => 'saisieValiderItem',
                'valideBox' => view ("administration.TableauDeBords.checkedPubs")->render (),
            ];
        endif;
    }

    public function FormvaliderPubs(Request $request): \Illuminate\Http\RedirectResponse
    {
        $listPubs = $request->input('listpub');
        $query = "SELECT date,campagne,support,affichage_panneau,dateajout,tarif,coeff,user,heure,presse_page,internet_emplacement,mobile,investissement,nombre,localite,point_id,date_fin_affichage,affichage_uniq_id
                FROM
                ".DbTablesHelper::dbTable ('DBTBL_PUB_NON_VALIDES','db')."
                WHERE id = %d";
        if (!empty($listPubs)):
            DB::transaction (function () use($listPubs,$query){
                foreach ($listPubs as $pub):
                    $pubNonValider = FunctionController::arraySqlResult (sprintf ($query,$pub));
                    $data = $pubNonValider[0];
                    $data['heure'] = $data['heure'] === null ? date('H:i') : $data['heure'];
                    $media = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_SUPPORTS','db'),$data['support'],'media');
                    if ($media === 6):
                        $lesDates = FunctionController::getDatesFromRange($data['date'],$data['date_fin_affichage']);
                        $invest = ceil($data['investissement']/count($lesDates));
                        $data['investissement_affichage'] = $data['investissement'];
                        $data['investissement'] = intval($invest);
                        $pubID = DB::transaction(function ()use ($lesDates,$data){
                            foreach ($lesDates as $laDate):
                                $data['date'] = $laDate;
                                DB::table(DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUBS','db'))->insert($data);
                            endforeach;
                            return true;
                        });
                    else:
                        $pubID = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUBS','db'))
                            ->insertGetId ($data);
                    endif;

                    DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUB_NON_VALIDES','db'))
                        ->where('id', $pub)
                        ->delete ();

                    self::speedNews($pubID);
                endforeach;
            });
            Session::flash("success", "Pubs validée(s) avec succès!");
        else:
            Session::flash("info","Veuillez choisir au moins une pub!");
        endif;
        return back ();
    }

    public function detailMedias(Request $request){
        return self::getDetailMedias ($request->docID);
    }

    public static function getDetailMedias(int $docID){
        $docampagne = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_DOCAMPAGNES','db')." WHERE id = {$docID}");
        return view ("administration.TableauDeBords.detailMedias", compact ('docampagne'))->render ();
    }

    public function saveTableExport(){
        $tables = FunctionController::getDatabaseTable ();
        $t = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_TABLE_EXPORTS','db')." ORDER BY name ASC");
        $tablesExports = [];
        foreach ($t as $r):
            $tablesExports[] = $r['name'];
        endforeach;
        return view ("administration.TableauDeBords.saveTableExportForm",compact ('tables','tablesExports'));
    }

    public function ajouterTableExport(Request $request){
       if ($request->etat == "true"):
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_TABLE_EXPORTS','db'))
               ->insert ([
                   'name' => $request->table
               ]);
            $message = "La table ".$request->table." ajoutée avec succès!";
            $alerte = "alert-success";
       else:
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_TABLE_EXPORTS','db'))
                ->where (['name' => $request->table])
                ->delete ();
           $message = "La table ".$request->table." supprimée avec succès!";
           $alerte = "alert-success";
       endif;
       return response ()->json ([
           'message' => $message,
           'alerte' => $alerte
       ]);
    }

    public function export(){
        //Session::forget('exportTable');
       $listeTables = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_TABLE_EXPORTS','db')." ORDER BY name ASC");
        $tableauDeDonnees = [];
        $filtreTable = "";
        if (Session::has ("exportTable")):
            $laTable = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_TABLE_EXPORTS','db'),Session::get('exportTable'));
            $table = FunctionController::getTableName ($laTable);
            if ($table == DbTablesHelper::dbTable ('DBTBL_PUBS','db')):
                $secteurs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SECTEURS','db')." ORDER BY name ASC ");
                $annonceurs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db')." ORDER BY name ASC ");
                $medias = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')." ORDER BY name ASC ");
                $supports = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db')." ORDER BY name ASC ");
                $formats = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_FORMATS','db')." ORDER BY name ASC ");
                $couvertures = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_COUVERTURES','db')." ORDER BY name ASC ");
                $natures = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_NATURES','db')." ORDER BY name ASC ");
                $cibles = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_CIBLES','db')." ORDER BY name ASC ");
                $table = $laTable;
                $filtreTable = view ("administration.TableauDeBords.filtreTablePubs",compact ('secteurs','annonceurs','medias','supports','formats','couvertures','natures','cibles','table'))->render ();
            else:
                $filtreTable = self::getFiltreTable ($table);
                if (Session::has ("QueryExportTable") && !array_key_exists ($table,Session::get ("QueryExportTable"))):
                    Session::forget ("QueryExportTable.$table");
                endif;
                $tableauDeDonnees = self::tableauDeDonneesExport ($table);
            endif;
       endif;
       return view ("administration.TableauDeBords.listeDesExports",compact ('listeTables','tableauDeDonnees','filtreTable'));
    }
    public function exportTable(int $tableID){
        Session::put ("exportTable",$tableID);
       return redirect ()->route ("dashbord.export");
    }

    public static function tableauDeDonneesExport(string $table){
        $listeDesChamps = FunctionController::getListeDesChamps ($table);
        $champs = join (',',$listeDesChamps);
        $sql = "SELECT $champs FROM $table LIMIT 1,10";
        if (Session::has("QueryExportTable") && array_key_exists ($table,Session::get ("QueryExportTable"))):
            $sql = Session::get ("QueryExportTable.$table");
        endif;
       // dump ($sql);
        $dataTableData = FunctionController::arraySqlResult($sql);
        return view ("administration.TableauDeBords.dataTableDatas",compact ('dataTableData','table'))->render ();
    }

    public static function getFiltreTable(string $table):string {
        $table = FunctionController::getTableName ($table);
        $listeDesChamps = FunctionController::getFieldOfTable ($table);
        unset($listeDesChamps[0]);
        $html = "";
        $i = 0;
        foreach ($listeDesChamps as $listeDesChamp):
            $champ = $listeDesChamp['Field'];
            $typeChamp = $listeDesChamp['Type'];
            if (FunctionController::is_displayable ($table,$champ)):
                $i++;
                if (!FunctionController::is_foreignKey ($champ)):
                    if (FunctionController::getTypeField ($typeChamp) == "date"):
                        $html .= view ("administration.TableauDeBords.filtreTableChDate",compact ('champ','i'))->render ();
                    else:
                        $html .= view ("administration.TableauDeBords.filtreTableChInput",compact ('champ','i'))->render ();
                    endif;
                else:
                    $chpTable = FunctionController::getTableName ($champ."s");
                    $datas = FunctionController::arraySqlResult ("SELECT id,name FROM $chpTable ORDER BY name ASC");
                    $html .= view ("administration.TableauDeBords.filtreTableInputFKey",compact ('datas','champ','i'))->render ();
                endif;
            endif;
        endforeach;
        return view ("administration.TableauDeBords.filtreTable",compact ('html','table'))->render ();
    }

    public static function speedNews(int $pubID):void {
        $pubs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_PUBS','db')." WHERE id = $pubID");
        if (count ($pubs)):
            $campagneTitle = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_CAMPAGNES','db'),$pubs[0]['campagne'],'campagnetitle');
            $mediaID = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db'),$pubs[0]['support'],'media');
            if (!self::checkIfSpeednewsExist($mediaID,$campagneTitle)):
                $tdata['campagnetitle'] = $campagneTitle;
                $tdata['media'] = $mediaID;
                $tdata['dateajout'] = $pubs[0]['date'];
                if($pubs[0]['heure'] == null):
                    //$tdata['heure'] = "00:00";
                    $tdata['heure'] = date('H:i');
                else:
                    $tdata['heure'] = $pubs[0]['heure'];
                endif;
                $tdata['datefin'] = $pubs[0]['date'];
                $tdata['support'] = $pubs[0]['support'];
                DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_SPEEDNEWS','db'))
                    ->insert($tdata);
            else:
                $today = date('Y-m-d');
                DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_SPEEDNEWS','db'))
                    ->where([
                        'media' => $mediaID,
                        'campagnetitle' => $campagneTitle
                    ])
                    ->update(
                        [
                            'datefin' => $today
                        ]
                    );
            endif;
        endif;
    }

    public static function checkIfSpeednewsExist(int $mediaID,int $campagneTitle):bool{
        $check = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_SPEEDNEWS','db'))
                ->where([
                    'campagnetitle' => $campagneTitle,
                    'media' => $mediaID
                ])->get()->count();
        return $check;
    }

    public function formExportQuery(Request $request){
        $datas = [];
        $operateur = $request->operateur;
        $champsValues = $request->champsValues;
        $champs = $request->champs;
        $table = $request->table;
        $i = 0;
        $condition = "";
        $and = "";
        $listeDesChamps = FunctionController::getListeDesChamps ($table);
        $leschamps = join (',',$listeDesChamps);

        $Query = "SELECT $leschamps FROM $table %s";
        foreach ($champsValues as $k => $r):
            $i++;
            if (!is_null ($r)):
                $datas[$k]['operateur'] = $operateur[$k];
                $datas[$k]['value'] = $champsValues[$k];
                $datas[$k]['champs'] = $champs[$k];
                if (in_array ($operateur[$k],['IN','NOT IN'])):
                    if (is_array ($r) && count ($r)):
                        $rr = array_filter ($r);
                        $join = join (',',$rr);
                        $datas[$k]['condition'] = " {$champs[$k]} IN({$join})";
                        $and = $and == "" ? " WHERE " : " AND ";
                        $condition .= " {$and} {$champs[$k]} IN({$join})";
                    endif;
                elseif(in_array ($operateur[$k],["LIKE","NOT LIKE","<",">","<=",">=","!="])):
                    $datas[$k]['condition'] = " {$champs[$k]} {$operateur[$k]} '$r' ";
                    $and = $and == "" ? " WHERE " : " AND ";
                    $condition .= "{$and} {$champs[$k]} {$operateur[$k]} '$r' ";
                elseif($operateur[$k] == "LIKE %...%"):
                    $datas[$k]['condition'] = " {$champs[$k]} LIKE'%{$r}%' ";
                    $and = $and == "" ? " WHERE " : " AND ";
                    $condition .= "{$and} {$champs[$k]} LIKE'%{$r}%' ";
                elseif($operateur[$k] == "NOT LIKE %...%"):
                    $datas[$k]['condition'] = " {$champs[$k]} LIKE'%{$r}%' ";
                    $and = $and == "" ? " WHERE " : " AND ";
                    $condition .= "{$and} {$champs[$k]} NOT LIKE'%{$r}%' ";
                else:
                    $datas[$k]['condition'] = " {$champs[$k]} {$operateur[$k]} '$r' ";
                    $and = $and == "" ? " WHERE " : " AND ";
                    $condition .= "{$and} {$champs[$k]} {$operateur[$k]} '$r' ";
                endif;
            endif;
        endforeach;
        if ($condition != ""):
            $sql = sprintf ($Query,$condition);
            Session::put ("QueryExportTable",[$table => $sql]);
        endif;
        return redirect ()->route ('dashbord.export');
    }

    public function getOperateurChamps(Request $request){
        $param = $request->param;
        $table = $request->table;
        $multiple = false;
        if ($param == 'IN' || $param == 'NOT IN'):
            $multiple = true;
        endif;
        return json_encode ($multiple);
    }

    public function getDonneesDeTable(Request $request){
        $table = $request->table;
        $champs = $request->champs;
        $fk = $request->fk;
        $texte = $request->texte;
        $ordered = $request->orderByChamps;
        if (!is_null ($fk)):
            $result = FunctionController::arraySqlResult ("SELECT * FROM $table WHERE $champs = $fk");
        else:
            $result = FunctionController::arraySqlResult ("SELECT * FROM $table ORDER BY $ordered");
        endif;
        return [
            'selectText' => " {$texte}",
            'donnees' => $result
        ];
    }

    public function getDonneesPubs(Request $request){
        $donnees = $request->all ();
        $resultat = [];
        if (is_array($donnees)) :
            $resultat = self::makeDonneesPubs ($donnees);
        endif;
        $request->session ()->put ("donneesPubsExports",$resultat);
        $request->session ()->save ();
        $link = view ("administration.TableauDeBords.exportDatasPubs",compact ('resultat'))->render ();

        return response ()->json ([
            'link' => $link
        ]);
    }

    public static function makeDonneesPubs(array $donnees):array {
            $tReporting = DbTablesHelper::dbTable ('DBTBL_REPORTINGS','db');
            $tSecteur = DbTablesHelper::dbTable ('DBTBL_SECTEURS','db');
            $tAnnonceur = DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db');
            $tMedia = DbTablesHelper::dbTable ('DBTBL_MEDIAS','db');
            $tSupport = DbTablesHelper::dbTable ('DBTBL_SUPPORTS','db');
            $tFormat = DbTablesHelper::dbTable ('DBTBL_FORMATS','db');
            $tNature = DbTablesHelper::dbTable ('DBTBL_NATURES','db');
            $tCouverture = DbTablesHelper::dbTable ('DBTBL_COUVERTURES','db');
            $tCible = DbTablesHelper::dbTable ('DBTBL_CIBLES','db');
            $tAffichageDim = DbTablesHelper::dbTable ('DBTBL_AFFICHAGE_DIMENSIONS','db');
            $tPresseCoul = DbTablesHelper::dbTable ('DBTBL_PRESSE_COULEURS','db');
            $tPresseCalibre = DbTablesHelper::dbTable ('DBTBL_PRESSE_CALIBRES','db');
            $tInternetDim = DbTablesHelper::dbTable ('DBTBL_INTERNET_DIMENSIONS','db');
            $tOperation = DbTablesHelper::dbTable ('DBTBL_OPERATIONS','db');
            $tCampagneTitle = DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLES','db');
            $genericQuery = "SELECT %s FROM
                    $tReporting rep,
                    $tAnnonceur an,$tSecteur sec,$tMedia m,$tSupport sup,
                    $tFormat f,$tNature n,$tCouverture couv,$tCible ci,$tPresseCalibre ca,
                    $tCampagneTitle camp,$tAffichageDim aff,$tPresseCoul pr,$tInternetDim inter,
                    $tOperation op
                    WHERE an.id = rep.annonceur AND sec.id = rep.secteur AND m.id = rep.media
                    AND sup.id = rep.support AND f.id = rep.format AND n.id = rep.nature
                    AND couv.id = rep.couverture AND ci.id = rep.cible AND ca.id = rep.presse_calibre
                    AND camp.id = rep.campagnetitle AND aff.id = rep.affichage_dimension
                    AND pr.id = rep.presse_couleur AND inter.id = rep.internet_dimension
                    AND op.id = rep.operation %s AND rep.date BETWEEN '%s' AND '%s'
                    ORDER BY rep.date DESC
                    ";
            $exportSelect = "
                rep.date,
                rep.heure,
                couv.name AS couverture,
                f.name AS format,
                n.name AS nature,
                ci.name AS cible,
                sup.name AS support,
                an.raisonsociale AS annonceur,
                camp.title AS title,
                op.name AS operation,
                sec.name AS secteur,
                (rep.tarif + rep.investissement) AS tarif, rep.coeff,
                rep.duree, pr.name AS couleur,
                ca.name AS calibre,
                inter.name AS dimension,
                aff.name AS dimension_du_panneaux,
                m.name AS media
                ";
            $date_debut = date ('Y-m-d');
            $date_fin = date ('Y-m-d');
            if (array_key_exists('date_debut', $donnees)):
                $date_debut = $donnees['date_debut'];
            endif;
            if (array_key_exists('date_fin', $donnees)):
                $date_fin = $donnees['date_fin'];
            endif;
            $and = " AND ";
            $where = " WHERE ";
            $conditionAnnonceur = "";
            if (array_key_exists('secteur', $donnees) && !is_null($donnees['secteur'])):
                $conditionAnnonceur = "$and
                             rep.secteur =  " . $donnees['secteur'];
            endif;
            if (array_key_exists('annonceur', $donnees) && !is_null($donnees['annonceur'])):
                $conditionAnnonceur = "$and
                                     rep.annonceur =  " . $donnees['annonceur'];
            endif;
            $condition = "";
            $condition .= $conditionAnnonceur;

            if (array_key_exists('media', $donnees) && !is_null($donnees['media'])):
                if (array_key_exists('support', $donnees) && !is_null($donnees['support'])):
                    $condition .="$and
                             rep.support =  " . $donnees['support'];
                else:
                    $condition .="$and
                             rep.media =  " . $donnees['media'];
                endif;
                if (array_key_exists('format', $donnees) && !is_null($donnees['format'])):
                    $condition .= "$and
                             rep.id =  " . $donnees['format'];
                endif;
            endif;
            if (array_key_exists('nature', $donnees) && !is_null($donnees['nature'])):
                $condition .= "$and
                             rep.nature =  " . $donnees['nature'];
            endif;
            if (array_key_exists('couverture', $donnees) && !is_null($donnees['couverture'])):
                $condition .= "$and
                             rep.couverture =  " . $donnees['couverture'];
            endif;
            if (array_key_exists('cible', $donnees) && !is_null($donnees['cible'])):
                $condition .= "$and
                             rep.cible =  " . $donnees['cible'];
            endif;

            $exportQuery = sprintf ($genericQuery,$exportSelect,$condition,$date_debut,$date_fin);
            return FunctionController::arraySqlResult ($exportQuery);
     }

     public function getCsvPubs(){
        $file = "export-" . date('Y-m-d_hsi') . ".xlsx";
        $donnees = Session::get ("donneesPubsExports");
        $export = new PubsExports($donnees);
        return Excel::download ($export,$file);
     }

     public static function sendSpeednewsMails(int $speednewsID){
         $speednew = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db')." WHERE id = $speednewsID");
         $mediaID = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_SUPPORTS','db'),$speednew[0]['support'],'media');
         $campagneTitle = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db')." WHERE id = {$speednew[0]['campagnetitle']}");
         $doCampagne = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','db')." WHERE campagnetitle = {$speednew[0]['campagnetitle']} AND media = {$mediaID}");
         $pub = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_REPORTINGS','db')." WHERE campagnetitle = {$campagneTitle[0]['id']} AND media = {$speednew[0]['media']} AND support = {$speednew[0]['support']}");
         $usersSecteur = FunctionController::arraySqlResult("SELECT u.* FROM
            ".DbTablesHelper::dbTable('DBTBL_USER_SECTEURS','db')." us,
            ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_USERS'))." u
             WHERE us.secteur = {$pub[0]['secteur']} AND us.user = u.id ");
         if (!empty($usersSecteur)):
             $annonceur = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db'),$pub[0]['annonceur']);
             $titreOp = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_OPERATIONS','db'),$pub[0]['operation']);
             $nature = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_NATURES','db'),$pub[0]['nature']);
             $typeDeService = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_TYPE_DE_SERVICES',
                 'db'),$pub[0]['type_service']);
             $cible = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_CIBLES','db'),$pub[0]['cible']);
             $media = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_MEDIAS','db'),$mediaID);
             //dump($media);
             $support = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_SUPPORTS','db'),$speednew[0]['support']);
             $format = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_FORMATS','db'),$pub[0]['format']);
             $secteur = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_SECTEURS','db'),$pub[0]['secteur']);
//            $emplacement = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_EMPLACEMENTS','db'), $pub[0]['type_service']);
             $donnees = [
                 'secteur' => $secteur,
                 'annonceur' => $annonceur,
                 'titre_op' => $titreOp,
                 'nature' => $nature,
                 'type_service' => $typeDeService,
                 'cible' => $cible,
                 'media-support' => $media." / ".$support,
                 'format' => $format,
                 'emplacement_trancheH' => ''
             ];
             $doc = [];
             if (count($doCampagne)):
                $doc["docCampagne"] = public_path("upload".DIRECTORY_SEPARATOR."campagnes".DIRECTORY_SEPARATOR.$pub[0]['campagne'].DIRECTORY_SEPARATOR.$doCampagne[0]['fichier']);
             endif;
             foreach ($usersSecteur as $r):
                 $mail = ['msg' => $donnees,'fichiers' => $doc];
                 //Mail::to($r['email'])->queue(new Speednews($mail));
                 Mail::to($r['email'])->send(new Speednews($mail));
            endforeach;
         endif;
     }

     public function listeSaisieValider(string $saisie)
     {
         $valider = 1;
         if ($saisie === 'campagnes'):
             return self::makeListeDesCampagnes($valider,true);
         endif;
     }

     public function newExport()
     {
         $secteurs = Secteur::all()->toArray();
         $formats = Format::all()->toArray();
         $annonceurs = Annonceur::all()->toArray();
         $medias = Media::all()->toArray();
         $natures = Nature::all()->toArray();
         $cibles = Cible::all()->toArray();
         $couvertures = Couverture::all()->toArray();
         $supports = Support::all()->toArray();

         $date_debut = date('Y-m-d');
         $date_fin = date('Y-m-d');
         $condition = "";
         $sqlpubbis = sprintf($this->genericQuery, $this->sqllistpub, $condition, $date_debut, $date_fin);
         $listpub = FunctionController::arraySqlResult($sqlpubbis);
         $nbt = count($listpub);
         //dd($sqlpubbis,$listpub);
         $export = true;
         $html = "";
         return view('administration.reportings.formexport', compact('date_debut','date_fin','secteurs','formats','medias','natures','cibles','couvertures','supports','annonceurs','listpub','nbt','export','html'));

     }

    public function filtreDonnees(Request $request)
    {
        $laTable = $request->input('laTable');
        $iditem = $request->input('iditem');
        $id = $request->input('id');
        $laCible = $request->input('cible');
        $optvalue = "";
        $datas = [];
        if ($laCible === 'annonceur' && $laTable === 'secteur'):
            $datas = Annonceur::where('secteur',$id)->get()->toArray();
            $optvalue = 'Choisir un annonceur';
        endif;
        if ($laCible === 'support' && $laTable === 'media'):
            $datas = Support::where('media',$id)->get()->toArray();
            $optvalue = 'Choisir un support';
        endif;
        if ($laCible === 'format' && $laTable === 'media'):
            $datas = Format::where('media',$id)->get()->toArray();
            $optvalue = 'Choisir un format';
        endif;
        //dump($request->all(),$datas);
        return [
            'result' => $datas,
            'optvalue' => $optvalue,
            'cible' => $iditem
        ];
    }

    public function exportData(Request $request)
    {
        //dd($request->all());
        $html= "";
        $export = false;
        $this->data = $request->all();
        if (!empty($this->data)) :
            $date_debut = $this->data['date_debut'];
            $date_fin = $this->data['date_fin'];
            $and = " AND ";
            $conditionAnnonceur = "";
            if (array_key_exists('secteur', $this->data) && !empty($this->data['secteur'])):
                $conditionAnnonceur = "$and
                             so.secteur =  " . $this->data['secteur'];
            endif;
            if (array_key_exists('annonceur', $this->data) && !empty($this->data['annonceur'])):
                $conditionAnnonceur = "$and
                                     so.id =  " . $this->data['annonceur'];
            endif;
            $condition = "";
            $condition .= $conditionAnnonceur;

            if (array_key_exists('media', $this->data) && !empty($this->data['media'])):
                if (array_key_exists('support', $this->data) && !empty($this->data['support'])):
                    $condition .="$and
                             sup.id =  " . $this->data['support'];
                else:
                    $condition .="$and
                             sup.media =  " . $this->data['media'];
                endif;
                if (array_key_exists('format', $this->data) && !empty($this->data['format'])):
                    $condition .= "$and
                             f.id =  " . $this->data['format'];
                endif;
            endif;
            if (array_key_exists('nature', $this->data) && !empty($this->data['nature'])):
                $condition .= "$and
                             n.id =  " . $this->data['nature'];
            endif;
            if (array_key_exists('couverture', $this->data) && !empty($this->data['couverture'])):
                $condition .= "$and
                             co.id =  " . $this->data['couverture'];
            endif;
            if (array_key_exists('cible', $this->data) && !empty($this->data['cible'])):
                $condition .= "$and
                             ci.id =  " . $this->data['cible'];
            endif;
            if (array_key_exists('listpub', $this->data) || array_key_exists('checkall', $this->data)):
                if (array_key_exists('listpub', $this->data)):
                    $datafield = $this->data['listpub'];
                    $INid = '(' . join(',', $datafield) . ')';
                    $condition .= "$and
                             p.id IN $INid ";
                endif;
                $exportQuery = sprintf($this->genericQuery, $this->exportSelect, $condition, $date_debut, $date_fin);
                $entete = $this->entete;
                $ress = FunctionController::arraySqlResult($exportQuery);
                $nbl = count($ress);
                $file = "export-" . date('Y-m-d_hsi') . ".xlsx";

                $path = "upload".DIRECTORY_SEPARATOR."export";
                Excel::store(new FicheMediaExport($ress),$path.DIRECTORY_SEPARATOR.$file);

               /* if (!is_dir($path)):
                    mkdir($path);
                endif;
                $fichier = public_path($path.DIRECTORY_SEPARATOR.$file);
                $fp = fopen($fichier, 'w+');
                $in = 0;
                fputcsv($fp, $entete, ';');
                foreach ($ress as $fields) :
                    if($fields['heure'] == ""):
                        $fields['tranche_horaire'] = "";
                    else:
                        if($fields['media'] == 'TELEVISION' ||$fields['media'] == 'RADIO'):
                            $t = explode(':',$fields['heure']);
                            $t2 = intval($t[0]) == 23 ? "00" : intval($t[0])+1;
                            $trh = $t[0]."h - ".$t2."h";
                        else:
                            $trh = "";
                        endif;
                        $fields['tranche_horaire'] = $trh;
                    endif;
                    $nbpart = intval($in % 10);
                    fputcsv($fp, $fields, ';');
                    $in++;
                endforeach;
                fclose($fp) ;*/

                $html = "
            <h3>Nombre de lignes export&eacute;es: $nbl <br>
                <a href=\"".route("reporting.getCSV", $file)."\">Cliquez ici pour t&eacute;l&eacute;charger le fichier</a>
            </h3>";
                $export = true;
            endif;
            $sqlpubbis = sprintf($this->genericQuery, $this->sqllistpub, $condition, $date_debut, $date_fin);
            $listpub = FunctionController::arraySqlResult($sqlpubbis);
            $nbt = count($listpub);

            $secteurs = Secteur::all()->toArray();
            $formats = Format::all()->toArray();
            $annonceurs = Annonceur::all()->toArray();
            $medias = Media::all()->toArray();
            $natures = Nature::all()->toArray();
            $cibles = Cible::all()->toArray();
            $couvertures = Couverture::all()->toArray();
            $supports = Support::all()->toArray();

            return view('administration.reportings.formexport', compact('export','date_debut','date_fin','secteurs','formats','annonceurs','medias','natures','cibles','couvertures','supports','html','nbt','listpub'));
        endif;
    }

    public function getCSV($file){
        //return response()->download(public_path("upload/export/$file"));
        return response()->download(storage_path("app/upload/export/$file"));
    }

    public function getFile($file,$chemin)
    {
        $chemins = str_replace("-",DIRECTORY_SEPARATOR,"$chemin");
        return response()->download(public_path($chemins."/".$file));
    }

    public function exportDataFichierMedia($data)
        {
        $date_debut = $data['date_debut'];
        $date_fin = $data['date_fin'];
        $and = " AND ";
        $conditionAnnonceur = "";
        if (array_key_exists('secteur', $data) && !empty($data['secteur'])):
            $conditionAnnonceur = "$and
                         so.secteur =  " . $data['secteur'];
        endif;
        if (array_key_exists('annonceur', $data) && !empty($data['annonceur'])):
            $conditionAnnonceur = "$and
                                 so.id =  " . $data['annonceur'];
        endif;
        $condition = "";
        $condition .= $conditionAnnonceur;

        if (array_key_exists('media', $data) && !empty($data['media'])):
            if (array_key_exists('support', $data) && !empty($data['support'])):
                $condition .="$and
                         sup.id =  " . $data['support'];
            else:
                $condition .="$and
                         sup.media =  " . $data['media'];
            endif;
            if (array_key_exists('format', $data) && !empty($data['format'])):
                $condition .= "$and
                         f.id =  " . $data['format'];
            endif;
        endif;
        if (array_key_exists('nature', $data) && !empty($data['nature'])):
            $condition .= "$and
                         n.id =  " . $data['nature'];
        endif;
        if (array_key_exists('couverture', $data) && !empty($data['couverture'])):
            $condition .= "$and
                         co.id =  " . $data['couverture'];
        endif;
        if (array_key_exists('cible', $data) && !empty($data['cible'])):
            $condition .= "$and
                         ci.id =  " . $data['cible'];
        endif;
        if (array_key_exists('listpub', $data) || array_key_exists('checkall', $data)):
            if (array_key_exists('listpub', $data)):
                $datafield = $data['listpub'];
                $INid = '(' . join(',', $datafield) . ')';
                $condition .= "$and
                         p.id IN $INid ";
            endif;
        endif;
        $exportQuery = sprintf($this->genericQuery, $this->exportSelect, $condition, $date_debut, $date_fin);
        return FunctionController::arraySqlResult($exportQuery);
    }


}
