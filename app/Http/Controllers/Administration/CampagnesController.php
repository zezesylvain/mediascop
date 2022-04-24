<?php

namespace App\Http\Controllers\Administration;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use App\Http\Controllers\core\ModuleController;
use App\Http\Controllers\core\XeditableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Operation;
use App\Models\Offretelecom;

class CampagnesController extends AdminController
{

    public function newOperations(){
        $table = DbTablesHelper::dbTable ('DBTBL_OPERATIONS','dbtables');
        $annonceurs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db')." ORDER BY raisonsociale ASC");
        $couvertures = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_COUVERTURES','db')." ORDER BY name ASC");
        $typecoms = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_TYPECOMS','db')." ORDER BY name ASC");
        $typeservices = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_TYPESERVICES','db')." ORDER BY name ASC");
        $donnees = ModuleController::makeTable ($table," ORDER BY  adddate DESC LIMIT 0, 500 ");
        $dateajout = date ("Y-m-d");
        return view ("administration.Operations.formOperation",compact ('typeservices', 'typecoms','donnees','annonceurs','couvertures','dateajout'));
    }

    public function newCampagnes(){
        //AdminController::addNotifications(DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db'),6);
        $action = "campagnes";
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
        if(Session::has('filter_param')):
        $listeDesOperations = '';
            $debutop = Session::get ('filter_param.dddebutfop');
            $ddfinop = Session::get ('filter_param.ddfinfop');
            $debutopfr = Session::get ('filter_param.debutopfr');
            $secteurfilteroperation = Session::get ('filter_param.secteurfilteroperation');
            $annonceurfilteroperation = Session::get ('filter_param.annonceurfilteroperation');

            $listeDesOperations .= self::makeListeDesOperation ();
        endif;
        return view ("administration.Campagnes.form",compact ('debutopfr','debutop','ddfinop','secteurfilteroperation','annonceurfilteroperation','action','listeDesOperations'));
    }
    

    public static function makeFormFilterCampagne(string $debutop, string $finop, $secteurop = 0, $annonceurop = 0, string $action,string $mod = "saisie",int $valide = 1): string {
        $listeDesSecteurs = FunctionController::arraySqlResult(" SELECT name, id FROM ".DbTablesHelper::dbTable('DBTBL_SECTEURS','dbtable')." ORDER BY name ASC");

        $cond = $secteurop != 0 ? " WHERE secteur = $secteurop " : "";
        $listeDesAnnonceurs = FunctionController::arraySqlResult(" SELECT raisonsociale, id FROM 
            ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','dbtable')." $cond ORDER BY raisonsociale ASC");
        return view ("administration.Campagnes.form_filter_campagne", compact ('debutop','finop','secteurop','annonceurop','listeDesSecteurs','listeDesAnnonceurs','action','mod','valide'))->render ();
    }

    public function filterCampagne($action, Request $request){
        if($request->get('filteroperation') == "ok"):
            $dsc1 = 1;
            $dsc = $dsc1 <= 7 ? 7 : $dsc1;
            $debutop = $request->dddebutfop;
            $ddfinop = $request->ddfinfop;
            $secteurfilteroperation = $request->secteurfilteroperation;
            $annonceurfilteroperation = $request->annonceurfilteroperation;
            $debutopfr = date('d-m-Y', mktime(0, 0, 0, date("n"), date("j") - $dsc, date("Y")));
            $donnees = [
                'dddebutfop' => $debutop,
                'ddfinfop' => $ddfinop,
                'debutopfr' => $debutopfr,
                'filteroperation' => 'ok',
                'secteurfilteroperation' => $secteurfilteroperation,
                'annonceurfilteroperation' => $annonceurfilteroperation,
                'action' => $action,
            ] ;
            if ($action == "campagnes"):
                Session::put('filter_param', $donnees);
                return redirect ()->route('saisie.campagne');
            else:
                $donnees['opfilteroperation'] = $request->opfilteroperation;
                Session::put('filter_param', $donnees);
                return redirect ()->route('saisie.'.strtolower ($action).'');
            endif;
        else:
            return redirect()->back();
        endif;

    }

    public function getTypeserviceBySecteur(Request $request){
        $secteur = $request->secteur;
        $cond = !is_null ($request->secteur) ? " WHERE secteur_id = $secteur " : "";

        $listeDesTypeservices = FunctionController::arraySqlResult ("SELECT * FROM 
            ".DbTablesHelper::dbTable('DBTBL_TYPESERVICES','dbtable')." 
            $cond ORDER BY name ASC") ;
        return response ()->json ($listeDesTypeservices);
    }

    public function listSelectAnnonceur(Request $request){
        $secteur = $request->secteur;
        $cond = !is_null ($request->secteur) ? " WHERE secteur = $secteur " : "";

        $listeDesAnnonceurs = FunctionController::arraySqlResult ("SELECT * FROM 
            ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','dbtable')." 
            $cond ORDER BY raisonsociale ASC") ;
        return response ()->json ($listeDesAnnonceurs);
    }

    public function listSelectOperation(Request $request){
        $annonceur = $request->annonceur;
        $cond = !is_null ($annonceur) ? " WHERE annonceur = $annonceur " : "";

        $listeDesOperations = FunctionController::arraySqlResult ("SELECT * FROM 
            ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','dbtable')." 
            $cond ORDER BY name ASC") ;
        return response ()->json ($listeDesOperations);
    }

    public static function makeListeDesOperation(){
        $datas = Session::get ('filter_param');
        $debutop = FunctionController::date2Fr ($datas['dddebutfop'],"Y-m-d");
        $ddfinop = FunctionController::date2Fr ($datas['ddfinfop'],"Y-m-d");
        $secteurfilteroperation = $datas['secteurfilteroperation'];
        $annonceurfilteroperation = $datas['annonceurfilteroperation'];
        $cond = '';
        if (empty(!array_key_exists("filtre", $datas))):
            return $cond;
        endif;
        if ($debutop <= $ddfinop):
            $getConditionDate = '';
            $date_deb = $debutop;
            $date_fin = $ddfinop;

            $and = " AND ";

            if ($date_deb <= $date_fin && $date_deb != null && $date_fin != null):
                $getConditionDate .= " o.adddate BETWEEN '$date_deb' AND '$date_fin'";
                $cond .= " $and ".$getConditionDate;
                $and = " AND";
            endif;

            if (intval($secteurfilteroperation) !== 0):
                $getConditionSecteur = " so.secteur = $secteurfilteroperation";
                $cond .= " $and ".$getConditionSecteur;
                $and = " AND";
            endif;

            if (intval($annonceurfilteroperation) !== 0):
                $getConditionAnnonceur = " o.annonceur = $annonceurfilteroperation";
                $cond .= " $and ".$getConditionAnnonceur;
            endif;
            
            if ($cond !== ""):
                $cond = " $cond ";
            endif;
        endif;

        $sql = "
            SELECT 
            o.id, o.name as operationname, o.adddate, so.raisonsociale, se.name as secteurname,se.id as secteurID,so.id as annonceurID
            FROM 
            ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','db')."  o,  
            ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')." so,  ".DbTablesHelper::dbTable('DBTBL_SECTEURS','db')." se
             WHERE  o.annonceur = so.id AND so.secteur = se.id $cond ORDER BY o.adddate DESC
            ";
        $datc = FunctionController::arraySqlResult($sql);
        return view ("administration.Campagnes.tabbootstrapoperation", compact ('datc','debutop'))->render ();
    }

    public function formCampagne(Request $request){
        if (array_key_exists ('v',$request->all ())):
            $name = $request->name;
            $nbrk = strlen($name);
            if($nbrk >= 3):
                $cond = " WHERE title = '$name'";
                $nb = FunctionController::nbrows(DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db'), $cond);
                if ($request->v == 1):
                    return view ("administration.Campagnes.isOk", compact ('nb'))->render ();
                endif;
                if ($request->v == 2):
                    return view ("administration.Campagnes.btn", compact ('nb'))->render ();
                endif;
            endif ;
        else:
            $operationID = $request->opid;
            $annonceurID = $request->annonceurID;
            $secteurID =$request->secteurID;
            $offretelecom = [];
            if($secteurID === 1):
                $offretelecom = FunctionController::arraySqlResult(" SELECT * FROM 
                ".DbTablesHelper::dbTable('DBTBL_OFFRE_TELECOMS','db')." 
                ORDER BY name ASC");
            endif;
            return view('administration.Campagnes.offreTelecomSelect', compact('offretelecom','operationID','annonceurID','secteurID'))->render ();
        endif;
    }

    public function storeCampTitle(Request $request){
        //dd($request->all());
        $title = $request->get('title');
        //$offretelecom = $request->get('offretelecom');
        $operation = $request->get('operation');
        $addate = date('Y-m-d');

        $idcamptitle = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNETITLE_NON_VALIDES','db'))
            ->insertGetId(
                [
                    "operation"     => $operation,
                    "title"         => $title,
                   // "offretelecom"  => $offretelecom,
                    "adddate"       => $addate,
                    "user"          => Auth::id(),
                ]
            );

        if($idcamptitle):
            Session::flash('success', 'Le titre de la campagne a été enregistré avec succès!');
            //return redirect()->route('saisie.creerCampagne', $idcamptitle);
            AdminController::addNotifications(DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLE_NON_VALIDES','db'),$idcamptitle);
        else:
            Session::flash('echec', 'L\'enregistrement du titre de la campagne a échoué!');
        endif;
        return back ();
    }

    public function creerCampagne(int $idcamptitle){
        $listeDesAnnonceurs = self::getSponsors($idcamptitle, false);
        $listeDesMedias = FunctionController::arraySqlResult("SELECT id, name FROM
            ".DbTablesHelper::dbTable('DBTBL_MEDIAS','dbtable')." 
            ORDER BY name ASC");
        foreach($listeDesAnnonceurs as $row):
            $listAnnonceur[$row['id']] = $row['raisonsociale'];
        endforeach;
        $listeDesSponsors = self::getSponsors($idcamptitle,true);
        $defaultvalue = [
            'presse_calibre' => 1,'presse_couleur' => 1,'profil_mobile' => 1,'offretelecom' => 1,'internet_dimension' => 1,'affichage_dimension' => 1,'duree' => 0,
        ];

        $sqldc = "SELECT d.*, m.name as medianame 
            FROM 
                ".DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','dbtable') . " d,
                ".DbTablesHelper::dbTable('DBTBL_MEDIAS','dbtable'). " m  
            WHERE d.campagnetitle = $idcamptitle 
            AND d.media = m.id";

        $docampagnes = FunctionController::arraySqlResult($sqldc);
        $uploadDir = public_path ("upload".DIRECTORY_SEPARATOR."campagnes".DIRECTORY_SEPARATOR.$idcamptitle);
        return view('administration.Campagnes.form2', compact('idcamptitle','listeDesAnnonceurs','listeDesMedias','defaultvalue','listeDesSponsors','docampagnes','uploadDir'));
    }

    public static function makeTableTitle(int $campagnetitle):string {
        $campagneInfos = FunctionController::arraySqlResult("
            SELECT ct.title, o.name as operation,o.id AS opid,a.raisonsociale,s.name as secteur,of.name as offretelecom,of.id AS offretelecomid 
            FROM 
                ".DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','dbtable')." ct, 
                ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','dbtable')." o, 
                ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','dbtable')." a,
                ".DbTablesHelper::dbTable('DBTBL_SECTEURS','dbtable')." s,
                ".DbTablesHelper::dbTable('DBTBL_OFFRE_TELECOMS','dbtable')." of 
             WHERE ct.operation = o.id AND ct.offretelecom = of.id AND o.annonceur = a.id AND a.secteur = s.id AND ct.id = $campagnetitle
        ");
        if(count($campagneInfos)):
            return view ("administration.Campagnes.tableauCampagneInfos", compact ('campagneInfos','campagnetitle'))->render ();
        endif;
    }

    public static function makeListeCampagnes(int $campagnetitle) {
        $campagnes = FunctionController::arraySqlResult("
            SELECT * 
            FROM 
            ".DbTablesHelper::dbTable('DBTBL_CAMPAGNES','dbtables')."
            WHERE campagnetitle = $campagnetitle ORDER BY id ASC
        ");

        if (!empty($campagnes)):
            $query = "SELECT m.name,m.id FROM 
                ".DbTablesHelper::dbTable('DBTBL_MEDIAS','db')." m, 
                ".DbTablesHelper::dbTable('DBTBL_FORMATS','db')." f 
                WHERE f.media = m.id AND  f.id = %d";

            $datas = [];
            $databaseTable = DbTablesHelper::dbTable('DBTBL_CAMPAGNES','dbtables');
            foreach($campagnes as $r):
                $media = FunctionController::arraySqlResult (sprintf ($query,$r['format']));
                $mm = self::diversOfTableRow ($media,$r,$databaseTable,$r['id']);
                $suppr = "";
                if(!self::verifierSiSaisieSurCamp($r['id'])):
                    $suppr .= "<a href=\"#\" data-toggle=\"modal\" data-target=\"#myModalSuppr{$r['id']}\" title=\"Supprimer\"> 
                        <i class=\"fa fa-trash rouge\"></i>
                    </a> ";
                endif;

                $format = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_FORMATS','db'),$r['format']);

                $nature =  FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_NATURES','db'),$r['nature']);

                $cible =  FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_CIBLES','db'),$r['cible']);

                $databaseTable = DbTablesHelper::dbTable('DBTBL_CAMPAGNES','db');
                $typeFormat = XeditableController::getColumnType ($databaseTable,"format");
                $formatexte = XeditableController::xEditableJS($databaseTable,"format",$r['id'],$typeFormat['type'],$format);

                $typeNature = XeditableController::getColumnType ($databaseTable,"nature");
                $naturetexte = XeditableController::xEditableJS($databaseTable,"nature",$r['id'],$typeNature['type'],$nature);

                $typeCible = XeditableController::getColumnType ($databaseTable,"cible");
                $cibletexte =XeditableController::xEditableJS($databaseTable,"cible",$r['id'],$typeCible['type'],$cible);

                $datas[] = [
                    'media' => $media[0]['name'],
                    'format' => $formatexte,
                    'nature' => $naturetexte,
                    'cible' => $cibletexte,
                    'divers' => $mm,
                    'action' => $suppr,
                ];
            endforeach;
            return view ("administration.Campagnes.listeCampagnes", compact ('campagnes','campagnetitle','datas'))->render ();
        endif;

    }

    public static function diversOfTableRow(array $media, $item, string $databaseTable, int $pk){
        $mm = "";
        switch ($media[0]['name']):
            case 'TELEVISION' :
            case 'RADIO' :
            $mm = "Dur&eacute;e = " . XeditableController::xEditableJS ($databaseTable,'duree',$pk,'text',$item['duree']);
            break;
            case 'PRESSE' :
                $calibre = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_PRESSE_CALIBRES','db'),$item['presse_calibre']);
                $couleur = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_PRESSE_COULEURS','db'),$item['presse_couleur']);
                $typeFormatCalibre = XeditableController::getColumnType ($databaseTable,"presse_calibre");
                $typeFormatCouleur= XeditableController::getColumnType ($databaseTable,"presse_couleur");
                $vCalibre = XeditableController::xEditableJS ($databaseTable,'presse_calibre',$pk,$typeFormatCalibre['type'],$calibre);
                $vCouleur = XeditableController::xEditableJS ($databaseTable,'presse_couleur',$pk,$typeFormatCouleur['type'],$couleur);
                $mm = "Calibre = {$vCalibre}<br>" .
                    "Couleur = {$vCouleur}<br>"
                ;
                break;
            case 'INTERNET' :
                $internetDim = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_INTERNET_DIMENSIONS','db'),$item['internet_dimension']);
                $typeFormatDimension= XeditableController::getColumnType ($databaseTable,"internet_dimension");
                $vDimension = XeditableController::xEditableJS ($databaseTable,'internet_dimension',$pk,$typeFormatDimension['type'],$internetDim);
                $mm = "Dimension = " . $vDimension;
                break;
            case 'MOBILE' :
                $message = $item['mobilemessage'];
                $mm =  XeditableController::xEditableJS ($databaseTable,'mobilemessage',$pk,'text',$message);
                break;
            case 'AFFICHAGE' :
                $affichageDim = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_AFFICHAGE_DIMENSIONS','db'),$item['affichage_dimension']);
                $typeFormatDimAff = XeditableController::getColumnType ($databaseTable,'affichage_dimension');
                //dd($typeFormatDimAff);
                $vDimAff = XeditableController::xEditableJS ($databaseTable,'affichage_dimension',$pk,$typeFormatDimAff['type'],$affichageDim);
                $mm = "Dimension = " .$vDimAff;
                break;
        endswitch;
        return $mm;
    }

    public function ajouterSponsors(Request $request){
        $v = $request->v ;
        $idcamptitle = $request->idCampTitle;
        $annonceur = $request->annonceur;
        if ($v == 'in'):
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_SPONSOR_CAMPAGNES','dbtable'))
               ->insert ([
                   'annonceur' => $annonceur,
                   'campagnetitle' => $idcamptitle,
               ]);
        endif;
        if ($v == 'out'):
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_SPONSOR_CAMPAGNES','dbtable'))
                ->where ([
                    'annonceur' => $annonceur,
                    'campagnetitle' => $idcamptitle,
                ])
                ->delete ();
        endif;
        $listeDesAnnonceurs = self::getSponsors($idcamptitle, false);
        
        $listeDesSponsors = self::getSponsors($idcamptitle,true);
        return view ("administration.Campagnes.boxAnnonceurSponsors", compact ('listeDesAnnonceurs','listeDesSponsors','idcamptitle'))->render ();
    }

    public static function createChampsInputCampagne(int $idcamptitle):string {
        $listeDesMedias = FunctionController::arraySqlResult ("SELECT * FROM
            ".DbTablesHelper::dbTable ('DBTBL_MEDIAS','db')." ORDER BY name ASC");
        return view ("administration.Campagnes.inputFormCampagne",compact ('idcamptitle','listeDesMedias'))->render ();
    }

    public function formInputCampagneOld(Request $request){
        $v = $request->v;
        $media = $request->media;
        if ($v == 'format'):
            $listeDesFormats = FunctionController::arraySqlResult ("SELECT id, name
                FROM ".DbTablesHelper::dbTable ('DBTBL_FORMATS','db')." 
                WHERE media = $media
                ORDER BY name ASC");
            $listeDesNatures = FunctionController::arraySqlResult ("SELECT id, name 
                FROM ".DbTablesHelper::dbTable ('DBTBL_NATURES','db')."
                ORDER BY name ASC");
            $listeDesCibles = FunctionController::arraySqlResult ("SELECT id, name
                FROM ".DbTablesHelper::dbTable ('DBTBL_CIBLES','db')." 
                ORDER BY name ASC");
            return view ("administration.Campagnes.formformat", compact ('listeDesCibles','listeDesFormats','listeDesNatures'))->render ();
        endif;
        if ($v == 'specific'):
            $defaultvalue = [
                'presse_calibre' => 1,'presse_couleur' => 1,'profil_mobile' => 1,'offretelecom' => 1,'internet_dimension' => 1,'affichage_dimension' => 1,'duree' => 0
            ];
            switch($media):
                case 1:
                    return view('administration.Campagnes.formfcampmediaspecific2',compact('defaultvalue'))->render ();
                    break;
                case 2:
                    return view('administration.Campagnes.formfcampmediaspecific2',compact('defaultvalue'))->render ();
                    break;
                case 3:
                    $pressecouleur = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PRESSE_COULEURS','db'))
                        ->orderBy('name', 'asc')
                        ->get();
                    $pressecalibre = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PRESSE_CALIBRES','db'))
                        ->orderBy('name', 'asc')
                        ->get();
                    return view('administration.Campagnes.formfcampmediaspecific3', compact('pressecalibre','pressecouleur','defaultvalue'))->render ();
                    break;

                case 4:
                    $interdimension = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_INTERNET_DIMENSIONS','db'))
                        ->orderBy('name', 'asc')
                        ->get();

                    return view('administration.Campagnes.formfcampmediaspecific4',compact('interdimension','defaultvalue'))->render ();
                    break;

                case 5:
                    return view('administration.Campagnes.formfcampmediaspecific5', compact('defaultvalue'))->render ();
                    break;
                case 6:
                    $affichdimension = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_AFFICHAGE_DIMENSIONS','db'))
                        ->orderBy('name', 'asc')
                        ->get();
                    return view('administration.Campagnes.formfcampmediaspecific6', compact('affichdimension','defaultvalue'))->render ();
                    break;
                case 7:
                    return view('administration.Campagnes.formfcampmediaspecific7', compact('defaultvalue'))->render ();
                    break;
                default:
                    return '';
                    break;
            endswitch;
        endif;
        if ($v == 'btn'):
            return view ("administration.Campagnes.boutonValider")->render ();
        endif;
    }
    public function formInputCampagne(Request $request){
        $media = $request->mediaID;
        $listeDesFormats = FunctionController::arraySqlResult ("SELECT id, name
            FROM ".DbTablesHelper::dbTable ('DBTBL_FORMATS','db')."
            WHERE media = $media
            ORDER BY name ASC");
        $listeDesNatures = FunctionController::arraySqlResult ("SELECT id, name
            FROM ".DbTablesHelper::dbTable ('DBTBL_NATURES','db')."
            ORDER BY name ASC");
        $listeDesCibles = FunctionController::arraySqlResult ("SELECT id, name
            FROM ".DbTablesHelper::dbTable ('DBTBL_CIBLES','db')."
            ORDER BY name ASC");
        $listeDesTypesPromos = FunctionController::arraySqlResult ("SELECT id, name
            FROM ".DbTablesHelper::dbTable ('DBTBL_TYPE_PROMOS','db')."
            ORDER BY name ASC");
        $listeDesTypesServices = FunctionController::arraySqlResult ("SELECT id, name
            FROM ".DbTablesHelper::dbTable ('DBTBL_TYPE_SERVICES','db')."
            ORDER BY name ASC");
        $listeDesDimensions = [];
        $listeDesCalibres = [];
        $listeDesCouleurs = [];
        $affDimension = [];
        $defaultvalue = [
                'presse_calibre' => 1,'presse_couleur' => 1,'profil_mobile' => 1,'offretelecom' => 1,'internet_dimension' => 1,'affichage_dimension' => 1,'duree' => 0
            ];
        $autreChpSelect = false;
            switch($media):
                case 2:
                case 1:
                    $form = view('administration.Campagnes.formfcampmediaspecific2',compact('defaultvalue'))->render();
                    break;
                case 3:
                    $pressecouleur = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PRESSE_COULEURS','db'))
                        ->orderBy('name', 'asc')
                        ->get();
                    $pressecalibre = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PRESSE_CALIBRES','db'))
                        ->orderBy('name', 'asc')
                        ->get();
                    $listeDesCalibres = $pressecalibre;
                    $listeDesCouleurs = $pressecouleur;
                    $autreChpSelect = true;
                    $form =  view('administration.Campagnes.formfcampmediaspecific3', compact('pressecalibre','pressecouleur','defaultvalue'))->render ();
                    break;
                case 4:
                    $interdimension = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_INTERNET_DIMENSIONS','db'))
                        ->orderBy('name', 'asc')
                        ->get();
                    $listeDesDimensions = $interdimension;
                    $autreChpSelect = true;
                    $form =  view('administration.Campagnes.formfcampmediaspecific4',compact('interdimension','defaultvalue'))->render ();
                    break;
                case 5:
                    $form =  view('administration.Campagnes.formfcampmediaspecific5', compact('defaultvalue'))->render ();
                    break;
                case 6:
                    $affichdimension = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_AFFICHAGE_DIMENSIONS','db'))
                        ->orderBy('name', 'asc')
                        ->get();
                    $autreChpSelect = true;
                    $affDimension = $affichdimension;
                    $form = view('administration.Campagnes.formfcampmediaspecific6', compact('affichdimension','defaultvalue'))->render ();
                    break;
                case 7:
                    $form = view('administration.Campagnes.formfcampmediaspecific7', compact('defaultvalue'))->render ();
                    break;
                default:
                    $form = '';
                    break;
            endswitch;
       
            $btn = view ("administration.Campagnes.boutonValider")->render ();
            
            return response()->json([
                'formats' => $listeDesFormats,
                'natures' => $listeDesNatures,
                'cibles' => $listeDesCibles,
                'btn' => $btn,
                'form' => $form,
                'autreChpSelect' => $autreChpSelect,
                'media' => $media,
                'pcalibre' => $listeDesCalibres,
                'pcouleur' => $listeDesCouleurs,
                'listeDesDimensions' => $listeDesDimensions,
                'affichageDimensions' => $affDimension,
                'typePromos' => $listeDesTypesPromos,
                'typeServices' => $listeDesTypesServices,
            ]);
    }

    public function storeCampagne(Request $request){
        $addate = date('Y-m-d H:i:s');
        $begindate = date('Y-m-d').' '.date('H:i:s');
        $parrain = "";
        $campagnetitle = $request->campagnetitleID;
        $newCampagne = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNES','db'))
            ->insert(
                [
                    'campagnetitle' => $campagnetitle,
                    'format' => $request->get('format'),
                    'nature' => $request->get('nature'),
                    'cible' => $request->get('cible'),
                    'type_promo' => $request->get('type_promo'),
                    'type_service' => $request->get('type_service'),
                    'duree' => $request->get('duree'),
                    'presse_calibre' => $request->get('presse_calibre'),
                    'presse_couleur' => $request->get('presse_couleur'),
                    'mobilemessage' => $request->get('mobilemessage'),
                    'profil_mobile' => $request->get('profil_mobile'),
                    'internet_dimension' => $request->get('internet_dimension'),
                    'affichage_dimension' => $request->get('affichage_dimension'),
                    'user' => Auth::id(),
                    'adddate' => $addate,
                    'begindate' => $begindate,
                    'parrain' => $parrain,
                ]
            );
        if($newCampagne):
            Session::flash('success', "Campagne enrégistrée avec succès!");
            return redirect ()->route ('saisie.creerCampagne', [$campagnetitle]);
        else:
            Session::flash('echec', "Campagne non enrégistrée à cause d'une erreur, veuillez récommencer!");
            return back ();
        endif;
    }

    public function diminuer(string $date, string $action){
        $ladate = strtotime(date("Y-m-d", strtotime($date)) . " -1 week");
        $debutop = date("Y-m-d", $ladate);
        Session::put('filter_param.dddebutfop', $debutop);
        $act = $action == "campagnes" ? "campagne" : strtolower ($action);
        return redirect()->route ("saisie.$act");
    }

    public static function getDocCampagnes(int $campagnetitleID){
        $sql = "SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','db')."  WHERE  campagnetitle = $campagnetitleID ORDER BY id ASC";
        $result = FunctionController::arraySqlResult($sql);
        $dirc = public_path("upload".DIRECTORY_SEPARATOR."campagnes".DIRECTORY_SEPARATOR.$campagnetitleID);
        $lesvisuels = glob($dirc.DIRECTORY_SEPARATOR."*");
        $visuels = "";

        if (!empty($result) || !empty($lesvisuels)):
            $tabm = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_MEDIAS','db')." ORDER BY name ASC ");
            $tableauDesMedias = [];
            foreach($tabm as $rm):
                $tableauDesMedias[$rm['id']] = $rm;
            endforeach;
            $medopt = "" ;
            foreach($tableauDesMedias as $rm) :
                $medopt .= '<option value="'.$rm['id'].'">'.$rm['name'].'</option>';
            endforeach;

            $tabvisuels = [];
            if (count($result)):
                foreach ($result as $item):
                    $tabvisuels[$item['fichier']] = $item;
                endforeach;
            endif;
            $route = route('ajax.bdselect');
            $route1 = route('ajax.putMediaInBd');
            $rdel = route('ajax.delcampfile');

            foreach ($lesvisuels as $v):
                $tab = pathinfo($v);
                $extension = $tab['extension'] ;
                $filename = $tab['filename'] ;
                $basename = $tab['basename'];
                $texte = $filename ;
                $iii = 0 ;
                $imageext = Config::get ("constantes.imageext");
                $videoext = Config::get ("constantes.videoext");
                $audioext = Config::get ("constantes.audioext");
                $media = view ("administration.Campagnes.mediaCampagne",compact ('imageext','audioext','videoext','campagnetitleID','extension','filename','basename'))->render ();
                $tableDoCampagne = DbTablesHelper::dbTable ('DBTBL_DOCAMPAGNES','db');
                $tableMedia = DbTablesHelper::dbTable ('DBTBL_MEDIAS','db');
                if(!array_key_exists($basename, $tabvisuels)):
                    $iii++ ;
                    $mediatexte = view ("administration.Campagnes.mediaTexte", compact ('basename','extension','route1','filename','campagnetitleID','medopt','iii','tableDoCampagne','tableMedia'))->render ();
                    $deleteitem = view ("administration.Campagnes.deleteMediaItem",compact ('iii','basename','campagnetitleID','rdel'))->render ();
                else :
                    $itemid =  $tabvisuels[$basename]['id'] ;
                    $itemmedia =  $tabvisuels[$basename]['media'] ;
                    $mediatexte = view ("administration.Campagnes.mediaTexte", compact ('itemid','itemmedia','route','tableauDesMedias','tableMedia','tableDoCampagne'))->render ();
                    $deleteitem = view ("administration.Campagnes.deleteMediaItem",compact ('itemid','basename','campagnetitleID','rdel'))->render ();
                endif;
                $texteformat = str_replace('_',' ',$texte);
                $txt = FunctionController::truncateStr($texteformat,50);
                $visuels .= view ("administration.Campagnes.visuel",compact ('texteformat','txt','mediatexte','deleteitem','media','extension'))->render ();
            endforeach;
            return view ("administration.Campagnes.listVisuels", compact ('visuels'))->render ();
        endif;
    }

    public function uploadFiles(Request $request){
        if (!is_dir($request->input('uploadDir'))):
            mkdir($request->input('uploadDir'));
        endif;
        $image = $request->file('file');
        $imageName = $image->getClientOriginalName();
        $image->move($request->input('uploadDir'),$imageName);
        return response()->json(['success'=>$imageName]);
    }

    public function putMediaInBd(Request $request){
        $data = $request->all() ;
        $data['adddate'] = date('Y-m-d H:i:s') ;
        $insert = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_DOCAMPAGNES','db'))
            ->insert($data);
        $campagnetitleid = $data['campagnetitle'] ;

        $html = self::getDocCampagnes($campagnetitleid);
        return $html;
    }

    public function delcampfile(Request $request){
        $data = $request->all() ;
        $campagnetitle = $data['campagnetitle'] ;
        $fichier = $data['fichier'] ;
        if(array_key_exists('bdid', $data)) :
            $bdid = $data['bdid'] ;
            $cond = " WHERE id = $bdid AND campagnetitle = $campagnetitle AND fichier = '$fichier'" ;
            DB::select(DB::raw("DELETE FROM ".DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','db')." $cond "));
        endif;
        $file = public_path("upload".DIRECTORY_SEPARATOR."campagnes".DIRECTORY_SEPARATOR.$campagnetitle.DIRECTORY_SEPARATOR.$fichier);
        $thumbfile = public_path("upload".DIRECTORY_SEPARATOR."campagnes".DIRECTORY_SEPARATOR.$campagnetitle.DIRECTORY_SEPARATOR."thumbfile".DIRECTORY_SEPARATOR.$fichier) ;
        if(file_exists($file)) :
            unlink($file) ;
        endif;
        if(file_exists($thumbfile)) :
            unlink($thumbfile) ;
        endif;
        return self::getDocCampagnes($campagnetitle);
    }

    public function bdselect(Request $request){
        $datable = $request->get( 'tableMedia');
        $table = $request->get( 'table');
        $colname = $request->get( 'colname');
        $id = $request->get( 'id');
        $col = $request->get( 'col');
        $pid = $request->get( 'pid');
        $cond = $request->get( 'cond');
        $value = $request->get( 'value');
        $sql = "SELECT id, $colname FROM $datable $cond" ;
        $re =  FunctionController::arraySqlResult($sql);
        $route = route('ajax.bdupdate');
        return view ("administration.Campagnes.champSelectMedias",compact ('col','id','table','datable','colname','pid','cond','route','re','value'))->render ();
    }

    public function bdupdate(Request $request){
        $datable = $request->get( 'datable');
        $table = $request->get( 'table');
        $colname = $request->get( 'colname');
        $id = $request->get( 'id');
        $col = $request->get( 'col');
        $pid = $request->get( 'pid');
        $cond = $request->get( 'cond');
        $value = $request->get( 'value');
        $upsql = "UPDATE $table SET $col = $value WHERE id = $id" ;
        FunctionController::arraySqlResult($upsql);
        $sql = "SELECT $colname FROM $datable WHERE id = $value" ;
        $re =  FunctionController::arraySqlResult($sql);
        $lavaleur =  $re[0][$colname] ;
        $route = route('ajax.bdselect');
        return view ("administration.Campagnes.champSelectMedias",compact ('col','id','table','datable','colname','pid','cond','route','re','value','lavaleur'))->render ();
    }

    public function storeOperations(Request $request){
        $this->validate ($request,[
            'name' => 'required',
            'annonceur' => 'required',
            'couverture' => 'required',
            'adddate' => 'required',
        ]);

        $datas = $request->all ();
        unset($datas["_token"]);
        if (!array_key_exists ("id",$request->all ())):
            $userID = Auth::id ();
            $datas['user'] = $userID;
            $datas['adddate'] = $datas['adddate']." ".date ("H:i:s");
            $new = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_OPERATIONS','db'))
                ->insertGetId ($datas);
            if ($new):
                Session::flash("success","L'opération a été enregistré avec succès !");
            else:
                Session::flash("echec","Une erreur est survenu, veuillez recommencer !");
            endif;
            return back ();
        else:
            $pid = $datas["id"];
            unset($datas['id']);
            $datas['user'] = Auth::id ();
            $update = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_OPERATIONS','db'))
                ->where ('id',$pid)
                ->update ($datas);
            if ($update):
                Session::flash("success","L'opération a été modifié avec succès !");
                return redirect ()->route ("saisie.operation");
            else:
                Session::flash("echec","Une érreur est survénue, veuillez récommencer !");
                return back ();
            endif;
        endif;
    }
    
    public function validerCampTitleForm(Request $request){
        $listCampTitles = $request->listcamp;
        if (count ($listCampTitles)):
            $update = DB::transaction (function () use($listCampTitles){
                foreach ($listCampTitles as $pub):
                    DB::table (DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNETITLE_NON_VALIDES','db'))
                        ->where ('id',$pub)
                        ->update ([
                            'valide' => 1
                        ]);
                    DB::insert (DB::raw ("INSERT INTO 
                        ".DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLES','db')." 
                        (operation, offretelecom, title, adddate, user)
                        SELECT operation, offretelecom, title, adddate, user 
                        FROM 
                        ".DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLE_NON_VALIDES','db')." 
                        WHERE id = $pub"));
                    DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_CAMPAGNETITLE_NON_VALIDES','db'))
                        ->where('id', $pub)
                        ->delete ();
                endforeach;
            });
            Session::flash("success", "Titre de campagne validée(s) avec succès!");
        else:
            Session::flash("info","Veuillez choisir au moins un titre de campagna!");
        endif;
        return back ();

    }
    
    /**
     * Juste pour la mise a jour de la colonne typesevice_id de operation
     * Pour les operation ayant une offreTelecom
     * 
     */
    public static function updateOperation(){
        $requete = "SELECT DISTINCT(`operation`), `offretelecom` 
                    FROM `zdsb_campagnetitles` WHERE `offretelecom` != 1";
        $data = $listeDesSecteurs = FunctionController::arraySqlResult($requete);
        foreach($data AS $r):
            $do = "SELECT `typeservice_id` FROM zdsb_offretelecoms WHERE id = $r[offretelecom]";
            $s = FunctionController::arraySqlResult($do)[0]['typeservice_id'];
            //dd(count($data), $data[0], $s);
            Operation::where('id', $r['operation'])->update(['typeservice_id' => intval($s)]);
        endforeach;
        echo "<h1>", count($data), " operations mises a jour</h1>";
        exit();
        
    }
}
