<?php

namespace App\Http\Controllers\Client;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ReportingController extends AdminController
{


    public function home(){
        $today = date ('Y-m-d');
        $date_debut = date ('Y-m').'-01';
        $date_fin = $today;
        Session::put('formvar.annonceur', []);
        Session::put('formvar.secteur', []);
        Session::put('formvar.media', []);
        Session::put('formvar.support', []);
        Session::put('formvar.format', []);
        
        Session::put('formvar.date.date_debut', $date_debut);
        Session::put('formvar.date.date_fin', $date_fin);

        $route = route('client.formvalider');
        $si_form_valide = false;
        $secteurs = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable("DBTBL_SECTEURS",'db')." ORDER BY name ASC");
        $natures = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable("DBTBL_NATURES",'db')." ORDER BY name ASC");
        $medias = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable("DBTBL_MEDIAS",'db')." ORDER BY name ASC");
        $menu_graphique = "";
        $menu_recherche = "active in";
        $nbrsecteur = count($secteurs);
        $nbrnature = count($natures);
        $nbrmedia = count($medias);
        return view('clients.index', compact('secteurs','natures','route','medias','si_form_valide','menu_graphique','menu_recherche','nbrsecteur','nbrnature','nbrmedia','date_fin','date_debut'));

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
        $conditionannonceur = $annonceurlist != '' ? " AND so.id IN " . $annonceurlist : '' ;
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

    public function formReport(){
        $data = $this->data;
        //dd ($data);
        unset($data["_token"]);
        $selection = $this->selection();

        $mediascop = new MediascopController($data);
        $lescampagneDetail = $mediascop->detailDesCampagnes;
        $investParAnnonceurEtParMedia = $mediascop->investParAnnonceurEtParMedia;
        $investParMedia = $mediascop->investParMedia;
        $investParAnnonceur = $mediascop->investParAnnonceur;
        $investParNature = $mediascop->investParNature;
        $investParOffretelecom = $mediascop->investParOffretelecom;
        $investParCible = $mediascop->investParCible;
        $partDeVoixParMedia = $mediascop->partDeVoixParMedia;
        $topNDesCampagnes = $mediascop->topNDesCampagnes;

        $investParCibleParAnnonceur = $mediascop->investParCibleParAnnonceur;
        $investParOffretelecomParAnnonceur = $mediascop->investParOffretelecomParAnnonceur;
        $investParNatureParAnnonceur = $mediascop->investParNatureParAnnonceur;
        $listMedia = $mediascop->listMedia;
        $listCible = $mediascop->listCible;
        $listNature = $mediascop->listNature;
        $listOffretelecom = $mediascop->listOffretelecom;
        $listDesAnnonceur = $mediascop->listDesAnnonceur;
        $lesspeednews = [];
        $investGlobalDeLaSelection = array_sum($investParAnnonceur);
        $autreSecteur = $mediascop->autreSecteur;
        $listDesCouleursDesAnnonceur = $mediascop->listDesCouleursDesAnnonceur;
        $lescampagneDetailSerialise = serialize($lescampagneDetail);


        Session::put('selection.lescampagneDetail', $lescampagneDetailSerialise);
        Session::put('selection.listMedia', serialize($listMedia));
        Session::put('selection.investParAnnonceur', serialize($investParAnnonceur));
        Session::put('selection.investParMedia', serialize($investParMedia));


        $investParAnnonceurEtParMediaJSON = $mediascop->investParAnnonceurEtParMediaJSON;
        $investParMediaParAnnonceur = $mediascop->investParMediaParAnnonceur;
        $lesDonneesParMediasEtParAnnonceur = array("abscisses" => $listMedia, "donnees" => $investParAnnonceurEtParMediaJSON);

        $hauteur = 400;
        $hauteur2 = 800;
        $ParSecteur = array("Secteur choisi" => $investGlobalDeLaSelection, "autres secteur" => $autreSecteur);

        $si_form_valide = true;
        $menu_graphique = "active in";
        $menu_recherche = "";

        $dataTableaux = [
            'topNDesCampagnes' => $topNDesCampagnes,'partDeVoixParMedia' => $partDeVoixParMedia,
            'investParAnnonceurEtParMedia' => $investParAnnonceurEtParMedia,'listDesAnnonceur' => $listDesAnnonceur,
            'investGlobalDeLaSelection' => $investGlobalDeLaSelection,'investParMedia' => $investParMedia,
            'listOffretelecom' => $listOffretelecom, 'investParOffretelecomParAnnonceur' => $investParOffretelecomParAnnonceur, 'lescampagneDetail' => $lescampagneDetail,
            'investParOffretelecom' => $investParOffretelecom, 'listNature' => $listNature,
            'investParNatureParAnnonceur' => $investParNatureParAnnonceur, 'investParNature' => $investParNature,
        ];

        $speednewTbody = $mediascop->makeSpeednewsTable();

        $listeCampagne = $this->makeListeCampagne($lescampagneDetail);
        $rapports = $this->makeRapports();
        $lestableaux = $this->makeTableaux($dataTableaux);

        $route = route('client.formvalider');
        $secteurs = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable("DBTBL_SECTEURS",'db')." ORDER BY name ASC");
        $natures = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable("DBTBL_NATURES",'db')." ORDER BY name ASC");;
        $medias = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable("DBTBL_MEDIAS",'db')." ORDER BY name ASC");;

        $nbrsecteur = count($secteurs);
        $nbrnature = count($natures);
        $nbrmedia = count($medias);

        return view('clients.index', compact('hauteur','hauteur2', 'ParSecteur','listDesCouleursDesAnnonceur','investParAnnonceur','investParCible','investParNature','investParOffretelecom','investParMedia','partDeVoixParMedia','investParMediaParAnnonceur','investParAnnonceurEtParMedia','selection','lesspeednews','si_form_valide','speednewTbody','listeCampagne','rapports','lestableaux','route','secteurs','natures','medias','menu_graphique','menu_recherche','nbrsecteur','nbrmedia','nbrnature'));
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


    public static function illustrationCampagne($cid, $media, $use_link = true){
        $ret = "" ;
        $return = "" ;
        $sql = "SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','db'). " WHERE campagnetitle = $cid AND media = $media";
        $res = FunctionController::arraySqlResult($sql);
        if(count($res) == 1) :
            $type = $res[0]['type'] ;
            $file = 'upload'.DIRECTORY_SEPARATOR.'campagnes'.DIRECTORY_SEPARATOR.$cid.DIRECTORY_SEPARATOR.$res[0]['fichier'] ;
            if(in_array($type, Config::get ("constantes.imageext"))):
                $ret = '<img style="width: 90%; " class="img-responsive" src="'.$file.'">' ;
            elseif(array_key_exists($type, Config::get ("constantes.videoext"))) :
                $ret = "<i class=\"fa fa-film fa-3x\"></i>" ;
            elseif(array_key_exists($type, Config::get ("constantes.audioext"))) :
                $ret = "<i class=\"fa fa-microphone fa-3x\"></i>" ;
            endif ;
            $return = $use_link ? '<a href="image.php?img='.$file.'" target="_blank">
                    '.$ret.'
                </a>' : $ret ;
        elseif($media == 5) :
            $sqlan = "SELECT s.id FROM 
                 ".DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db')." ct, 
                 ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','db')." o, 
                 ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')." s
                  WHERE 
                  ct.id = $cid AND 
                  ct.operation = o.id AND 
                  o.annonceur = s.id  " ;
            $re = FunctionController::arraySqlResult($sqlan) ;
            $anid = $re[0]['id'] ;
            $file = "medias".DIRECTORY_SEPARATOR."sms".DIRECTORY_SEPARATOR."SMS-$anid.jpg" ;
            $file1 = $_SERVER['DOCUMENT_ROOT'].$file ;
            if(file_exists($file1)):
                $return = $use_link ? '<a href="image.php?img='.$file.'" target="_blank">
                        <img style="width: 90%; " class="img-responsive" src="' . $file . '">
                    </a>'  : '<img style="width: 90%; " class="img-responsive" src="' . $file . '">';
            endif ;
        endif ;
        return $return ;
    }

    private function makeListeCampagne($lescampagneDetail){
        $campagneArray = serialize($lescampagneDetail);
        if (count($lescampagneDetail)):
            Session::put('selection.lescampagneDetail', $campagneArray);
            return view ("clients.campagnes.listeDesCampagnes", compact ('lescampagneDetail'))->render ();
        endif;
    }

    private function makeRapports(){
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

    public function detailSpeednews(int $cid){
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
        $resfirst = array('dateajout' => '', 'support' => '', 'median' => '', 'media' => '', 'mname' => '' ) ;
        $reslast = array('datefin' => '', 'support' => '', 'median' => '', 'mname' => '', 'media' => '' ) ;
        if(count($res) == 1) :
            $re = $res[0] ;
            $docsql = "SELECT 
                d.*, m.name as medianame 
              FROM " .DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','db'). " d,  " .DbTablesHelper::dbTable('DBTBL_MEDIAS','db'). " m  
              WHERE d.campagnetitle = $cid AND d.media = m.id";
            $resdoc = FunctionController::arraySqlResult($docsql);
            $sqlfirst = "SELECT 
                                sp.dateajout, sp.support, sp.media, m.name as mname 
                            FROM 
                                ".DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db')." sp, 
                                ".DbTablesHelper::dbTable('DBTBL_MEDIAS','db')." m
                            WHERE sp.campagnetitle = $cid  AND sp.media = m.id
                            ORDER BY sp.dateajout ASC LIMIT 1";

            $sqllast = "SELECT 
                                sp.datefin, sp.support, sp.media, m.name as mname 
                            FROM 
                                " .DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db'). " sp, 
                                " .DbTablesHelper::dbTable('DBTBL_MEDIAS','db'). " m
                            WHERE sp.campagnetitle = $cid  AND sp.media = m.id
                            ORDER BY sp.datefin DESC LIMIT 1";

            $resfirsts = FunctionController::arraySqlResult($sqlfirst);
            $reslasts = FunctionController::arraySqlResult($sqllast);
            if(count($resfirsts) == 1):
                $resfirst = $resfirsts[0] ;
                $reslast = $reslasts[0] ;
            endif;

            return view('clients.campagnes.detail', compact('resfirst','reslast','re','slider'));
        endif;

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
