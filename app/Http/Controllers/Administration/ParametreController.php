<?php

namespace App\Http\Controllers\Administration;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManagerStatic;

class ParametreController extends AdminController
{

    public function create(string $table) {
        return parent::create ($table);
    }

    public function newMedias(){
        $table = DbTablesHelper::dbTable ('DBTBL_MEDIAS','dbtables');
        return $this->create ($table);
    }

    public function newFormats(){
        $table = DbTablesHelper::dbTable ('DBTBL_FORMATS','dbtables');
        return $this->create ($table);
    }

    public function newCibles(){
        $table = DbTablesHelper::dbTable ('DBTBL_CIBLES','dbtables');
        return $this->create ($table);
    }

    public function newNatures(){
        $table = DbTablesHelper::dbTable ('DBTBL_NATURES','dbtables');
        return $this->create ($table);
    }

    public function newSupports(){
        $table = DbTablesHelper::dbTable ('DBTBL_SUPPORTS','dbtables');
        return $this->create ($table);
    }

    public function newSecteur(){
        $table = DbTablesHelper::dbTable ('DBTBL_SECTEURS','dbtables');
        return $this->create ($table);
    }

    public function newCouvertures(){
        $table = DbTablesHelper::dbTable ('DBTBL_COUVERTURES','dbtables');
        return $this->create ($table);
    }

    public function newAnnonceurs(){
        $secteurs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SECTEURS','db')." ORDER BY name ASC");
        $tableau = self::makeTableauAnnonceur ();
        $logo = '';
        return view ("administration.Parametres.annonceur",compact ('secteurs','tableau','logo'));
    }

    public static function makeTableauAnnonceur():string {
        $annonceurs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db')." ORDER BY raisonsociale ASC");
        $dataTableHeader = [
            'raisonsociale' => 'raisonsociale',
            'secteur' => 'Secteur',
            'couleur' => 'Couleur',
            'logo' => 'Logo',
        ];
        $databaseTable = DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db');
        return view ("administration.Parametres.tableDatasAnnonceurs", compact ('annonceurs','dataTableHeader','databaseTable'))->render ();
    }

    public function newDimensionInternet(){
        $table = DbTablesHelper::dbTable ('DBTBL_INTERNET_DIMENSIONS','dbtables');
        return $this->create ($table);
    }

    public function newDimensionAffichage(){
        $table = DbTablesHelper::dbTable ('DBTBL_AFFICHAGE_DIMENSIONS','dbtables');
        return $this->create ($table);
    }

    public function newProfilMobile(){
        $table = DbTablesHelper::dbTable ('DBTBL_PROFIL_MOBILES','dbtables');
        return $this->create ($table);
    }

    public function storeAnnonceurs(Request $request){
        //dd($request->all());
        $this->validate ($request,[
            "raisonsociale" => "required",
            "logo" => "",
            "couleur" => "required",
            "secteur" => "required",
        ]);
        $dir = "upload".DIRECTORY_SEPARATOR."annonceurs";
        if (!is_dir ($dir)):
            mkdir ($dir);
        endif;
        $datas = $request->all ();
        unset($datas["_token"]);
        if (!array_key_exists ("id",$request->all ())):
            $nomLogo = "" ;
            $image = $request->file('logo');
            if($image):
                $image_resize = ImageManagerStatic::make($image->getRealPath());
                $image_resize->resize(35,35);
                $nomLogo = "logo.".$image->getClientOriginalExtension();
            endif;
            $userID = Auth::id ();
            $new = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_ANNONCEURS','db'))
                ->insertGetId ([
                    'raisonsociale' => $request->raisonsociale,
                    'name' => $request->raisonsociale,
                    'logo' => $nomLogo,
                    'secteur' => $request->secteur,
                    'couleur' => $request->couleur,
                    'user' => $userID,
                ]);
            if ($new):
                $destination = public_path($dir.DIRECTORY_SEPARATOR.$new);
                if($image):
                    $image->move($destination,$nomLogo);
                    if (!is_dir($destination.DIRECTORY_SEPARATOR.'Map')):
                        mkdir($destination.DIRECTORY_SEPARATOR.'Map');
                    endif;
                    $image_resize->save($destination.DIRECTORY_SEPARATOR.'Map'.DIRECTORY_SEPARATOR.$nomLogo);
                endif;
                Session::flash("success","L'annonceur a été enregistré avec succès !");
            else:
                Session::flash("echec","Une erreur est survenu, veuillez recommencer !");
            endif;
            return back ();
        else:
            $pid = $datas["id"];
            if (array_key_exists ("logo",$request->all ())):
                $image = $request->file('logo');
                if($image):
                    $image_resize = ImageManagerStatic::make($image->getRealPath());
                    $image_resize->resize(35,35);
                    $nomLogo = "logo.".$image->getClientOriginalExtension();
                    endif;
                $datas["logo"] = $nomLogo;
                if (array_key_exists ("logoDel",$request->all ())):
                    unset($datas['logoDel']);
                    FunctionController::delTree (public_path ($dir.DIRECTORY_SEPARATOR.$pid));
                endif;
            endif;
            unset($datas['id']);
            $datas['user'] = Auth::id ();
            $update = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_ANNONCEURS','db'))
                ->where ('id',$pid)
                ->update ($datas);
            if ($image):
               if (array_key_exists ("logo",$request->all ())):
                   $destination = public_path($dir.DIRECTORY_SEPARATOR.$pid);
                   if($image):
                        $image->move($destination,$nomLogo);
                        if (!is_dir($destination.DIRECTORY_SEPARATOR.'Map')):
                            mkdir($destination.DIRECTORY_SEPARATOR.'Map');
                        endif;
                        $image_resize->save($destination.DIRECTORY_SEPARATOR.'Map'.DIRECTORY_SEPARATOR.$nomLogo);
                   endif;
               endif;
                Session::flash("success","L'annonceur a été modifié avec succès !");
                return redirect ()->route ("parametre.annonceurs");
            else:
                Session::flash("echec","Une érreur est survénue, veuillez récommencer !");
                return back ();
            endif;
        endif;
    }

    public function updateAnnonceur(int $annonceurId){
        $annonceur = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')." WHERE id = $annonceurId");
        $secteurs = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_SECTEURS','db')." ORDER BY name ASC");
        $tableau = self::makeTableauAnnonceur ();
        $logo = FunctionController::getIconAnnonceur($annonceurId);
        return view ("administration.Parametres.annonceur",compact ('secteurs','tableau','annonceur','logo'));
    }

    public function updateColor(Request $request){
        $coul = $request->get('coul') ;
        $id = $request->get('id') ;
        DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_ANNONCEURS','db'))
            ->where('id',$id)
            ->update([
                'couleur' => '#'.$coul,
            ]);
    }

    public static function getLogoAnnonceur(int $annonceurId,$vue = "table"):string {
        $dir = "upload".DIRECTORY_SEPARATOR."annonceurs".DIRECTORY_SEPARATOR.$annonceurId;
        $logo = "-";
        if (is_dir (public_path ($dir))):
            $glob = glob ($dir.DIRECTORY_SEPARATOR."*");
            $ct = count($glob);
            if (count($glob) === 1):
                $path = pathinfo($glob[0]);
                $src = $dir.DIRECTORY_SEPARATOR."logo.".$path['extension'];
            elseif ($ct === 2):
                $path = pathinfo($glob[1]);
                $src = $dir.DIRECTORY_SEPARATOR."logo.".$path['extension'];
            else:
                $src = '';
            endif;
            $logo = view ("administration.Parametres.logo", compact ('src','vue'))->render ();
        endif;
        return $logo;
    }

    public function changerLogo(Request $request){
        if ($request->var == 1):
            return view ("administration.Parametres.logoInput")->render ();
        endif;
    }

    public function gestionTablesFusion(){
        $tables = FunctionController::getDatabaseTable ();
        $t = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_FUSION_TABLES','db')." ORDER BY name ASC");
        $tablesFusions = [];
        foreach ($t as $r):
            $tablesFusions[] = $r['name'];
        endforeach;
        return view ("administration.Parametres.formTablesFusion",compact ('tables','tablesFusions'));
    }

    public function dependanceTablesFusion(){
        $listeTablesFusions = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_FUSION_TABLES','db')." ORDER BY name ASC");

        $lestables = "";
        if (Session::has ("tableFusionCheck")):
            $tables = FunctionController::getDatabaseTable ();
            $tableFusionID = Session::get ("tableFusionCheck");
            $libelleTableFusion = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_FUSION_TABLES','db'),$tableFusionID);
            $tablesDependants = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_FUSION_TABLES_DEPENDANCES','db')." WHERE fusion_table = {$tableFusionID}");
            $listeTablesDependants = [];
            foreach ($tablesDependants as $r):
                $listeTablesDependants[] = $r['name'];
            endforeach;
            $lestables .= view ("administration.Parametres.formTablesFusionDependanceMin",compact ('tables','listeTablesDependants','tableFusionID','libelleTableFusion'))->render ();
        endif;
        return view ("administration.Parametres.formTablesFusionDependance",compact ('listeTablesFusions','lestables'));
    }

    public function ajouterTableFusion(Request $request){
        $table = $request->table;
        $tablesDependants = self::getDependancesTable ($table);
        if ($request->etat == "true"):
            $tableID = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_FUSION_TABLES','db'))
                ->insertGetId ([
                    'name' => $table,
                ]);
            $message = "La table ".$table." ajoutée avec succès!";
            $alerte = "alert-success";
            if (count ($tablesDependants)):
                DB::transaction (function () use($tablesDependants,$tableID){
                     foreach ($tablesDependants as $r):
                         DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_FUSION_TABLES_DEPENDANCES','db'))
                             ->insert ([
                                 'name' => $r,
                                 'fusion_table' => $tableID,
                             ]);
                     endforeach;
                });
            endif;
        else:
            $tableID = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_FUSION_TABLES','db')." WHERE name = '{$table}'");
            DB::transaction (function () use($tablesDependants,$tableID){
                if (count ($tablesDependants)):
                    DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_FUSION_TABLES_DEPENDANCES','db'))
                        ->where ('fusion_table','=',$tableID)
                        ->delete ();
                    DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_FUSION_TABLES','db'))
                        ->where (['id' => $tableID])
                        ->delete ();
                endif;
            });
            $message = "La table ".$table." supprimée avec succès!";
            $alerte = "alert-success";
        endif;
        return response ()->json ([
            'message' => $message,
            'alerte' => $alerte
        ]);
    }

    public function ajouterTableFusionDependant(Request $request){
        if ($request->etat == "true"):
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_FUSION_TABLES_DEPENDANCES','db'))
                ->insert ([
                    'name' => $request->tableDependant,
                    'fusion_table' => $request->table,
                ]);
            $message = "La table ".$request->tableDependant." ajoutée avec succès!";
            $alerte = "alert-success";
        else:
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_FUSION_TABLES_DEPENDANCES','db'))
                ->where (['name' => $request->tableDependant,'fusion_table' => $request->table])
                ->delete ();
            $message = "La table ".$request->tableDependant." supprimée avec succès!";
            $alerte = "alert-danger";
        endif;
        return response ()->json ([
            'message' => $message,
            'alerte' => $alerte
        ]);
    }

    public function fusionTable(int $tableID){
        if (is_null ($tableID)):
            if (Session::has ("tableFusionCheck")):
                Session::forget ("tableFusionCheck");
            endif;
        else:
            Session::put ("tableFusionCheck",$tableID);
        endif;
        return redirect ()->route ("parametre.dependanceTablesFusion");
    }

    public function getDonneesFusion(int $tableID){
        if (is_null ($tableID)):
            if (Session::has ("tableFusionCheckDonnee")):
                Session::forget ("tableFusionCheckDonnee");
            endif;
        else:
            Session::put ("tableFusionCheckDonnee",$tableID);
        endif;
        return redirect ()->route ("parametre.faireFusion");
    }

    public function faireFusion(){
        $listeTablesFusions = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_FUSION_TABLES','db')." ORDER BY name ASC");
        $tableauDeDonnee = "";
        if (Session::has ('tableFusionCheckDonnee')):
            $tableFusionID = Session::get ("tableFusionCheckDonnee");
            $tableauDeDonnee = self::donneesTableFusion ($tableFusionID);
        endif;
        return view ("administration.Parametres.formFusion",compact ('listeTablesFusions','tableauDeDonnee'));
    }

    public static function donneesTableFusion(int $tableID){
        $libelleTableFusion = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_FUSION_TABLES','db'),$tableID);
        $table = FunctionController::getTableName ($libelleTableFusion);
        switch ($table):
            case DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLES','db') :
                $champs = " id, title as name ";
                break;
            case DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db') :
                $champs = " id, raisonsociale as name ";
                break;
            default:
                $champs = " id,name ";
                break;
        endswitch;
        $query = "SELECT $champs FROM $table ORDER BY id DESC " ;
        $donnees = FunctionController::arraySqlResult ($query);
        $dataTableHeader = array_keys ($donnees[0]);
        $formListeIDs = self::makeFormFusionIDs ($tableID);
        $ids = [];
        if (Session::has ("fusionTableIDs.$tableID")):
            $ids = Session::get ("fusionTableIDs.$tableID");
        endif;
        return view ("administration.Parametres.tableauFusionDatas",compact ('donnees','dataTableHeader','tableID','formListeIDs','ids'))->render ();
    }

    public function ajouterIdFusion(Request $request){
        //dump ($request->all ());
        $tableID = $request->tableID;
        $IDLigne = $request->id;
        if ($request->etat == "true"):
            if (!$request->session ()->has ('fusionTableIDs')):
                $request->session ()->put ('fusionTableIDs',[]);
            endif;
            if (!$request->session ()->has ("fusionTableIDs.$tableID")):
                $request->session ()->put ("fusionTableIDs.$tableID",[]);
            endif;
            if (!$request->session ()->has ("fusionTableIDs.$tableID.$IDLigne")):
                $request->session ()->put ("fusionTableIDs.$tableID.$IDLigne",$IDLigne);
            endif;
            $message = "La ligne ajoutée avec succès!";
            $alerte = "alert-success";
        else:
            $request->session ()->forget ("fusionTableIDs.$tableID.$IDLigne");
            $message = "La ligne retirée avec succès!";
            $alerte = "alert-danger";
        endif;
        $formFusionID = self::makeFormFusionIDs ($tableID);
        return response ()->json ([
            'message' => $message,
            'alerte' => $alerte,
            'formFusionID' => $formFusionID,
        ]);
    }

    public static function makeFormFusionIDs(int $tableID){
        $libelleTableFusion = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_FUSION_TABLES','db'),$tableID);
        $table = FunctionController::getTableName ($libelleTableFusion);
        switch ($table):
            case DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLES','db') :
                $champs = " id, title as name ";
                break;
            case DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db') :
                $champs = " id, raisonsociale as name ";
                break;
            default:
                $champs = " id,name ";
                break;
        endswitch;
        if (Session::has ("fusionTableIDs.$tableID")):
            if (count (Session::get ("fusionTableIDs.$tableID"))):
                $in = join (',',Session::get ("fusionTableIDs.$tableID"));
                $query = "SELECT $champs FROM $table WHERE id IN ($in)" ;
                $donnees = FunctionController::arraySqlResult ($query);
                $dataTableHeader = array_keys ($donnees[0]);
                return view ("administration.Parametres.tableauFusionDatasID",compact ('donnees','dataTableHeader','tableID'))->render ();
            endif;
        endif;
    }

    public function traiterFormFusion(Request $request){
        $tableID = $request->tableID;
        $idMaster = $request->id;
        $mess = "";
        if (!is_null ($idMaster)):
            $libelleTableFusion = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_FUSION_TABLES','db'),$tableID);

            $listeTablesDependants = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_FUSION_TABLES_DEPENDANCES','db')." WHERE fusion_table = {$tableID}");
            $listeIdFusion = $request->session ()->get ("fusionTableIDs.$tableID");
            unset($listeIdFusion[$idMaster]);
            if (count ($listeIdFusion)):
                $tablesDependants = [];
           //dd ($listeTablesDependants);
                foreach ($listeTablesDependants as $tdb):
                    $tablesDependants[] = FunctionController::getTableName ($tdb['name']);
                endforeach;
                $champstable = substr($libelleTableFusion,0,mb_strlen($libelleTableFusion)-1);

                $update = self::updateFusion ($libelleTableFusion,$champstable,$idMaster,$listeIdFusion,$tablesDependants) ;
                if ($update):
                    $mess = "Votre fusion s'est déroulée avec succès!";
                    $request->session ()->forget ("fusionTableIDs.$tableID");
                    $libelleTable = FunctionController::getTableName ($libelleTableFusion);
                    if($libelleTable == DbTablesHelper::dbTable ('DBTBL_CAMPAGNETITLES','db')):
                        $DossierdesVisuel = public_path("upload".DIRECTORY_SEPARATOR."campagnes".DIRECTORY_SEPARATOR);
                        foreach ($listeIdFusion as $v):
                            if($v != $idMaster) :
                                $dirp = "$DossierdesVisuel$v/*" ;
                                $t = glob($dirp);
                                if(count($t)) :
                                    foreach ($t as $filename) :
                                        $newdir = $DossierdesVisuel.$idMaster ;
                                        if(!is_dir($newdir)):
                                            mkdir($newdir) ;
                                            chmod($newdir, 0777) ;
                                        endif;
                                        $newname = str_replace($DossierdesVisuel.$v, $newdir, $filename) ;
                                        $namev = str_replace($DossierdesVisuel.$v, "", $filename) ;
                                        if(rename($filename, $newname)):
                                            $mess .= "<br>Le visuel $namev dans le dossier $v a &eacute;t&eacute; d&eacute;plac&eacute; avec succ&egrave;s" ;
                                        else :
                                            $mess .= "<br>Le visuel  $namev dans le dossier $v n'a pas &eacute;t&eacute; d&eacute;plac&eacute;" ;
                                        endif;
                                    endforeach;
                                endif;
                            endif;
                        endforeach;
                    endif;
                else:
                    $mess = "Attention un problème est survénue, veuillez récommencer la fusion ou si le problème persiste veuiller contacter l'administrateur système.";
                endif;
            else:
                $mess = "Vous n'avez aucune ligne à fusionner!";
            endif;
        else:
            $mess = "Veuillez faire le choix de la ligne à conserver!";
        endif;
        $formFusionID = self::makeFormFusionIDs ($tableID);
        return response ()->json ([
            'message' => $mess,
            'formFusionID' => $formFusionID,
        ]);
    }

    public static function updateFusion(string $table,string $champs,int $idMaster,array $listeIDs,array $tableDependant){
        $i = 0;
        $sql = [];
        $ok = false;
        $libelleTable = FunctionController::getTableName ($table);
        foreach ($tableDependant as $value):
            $i++;
            if ($libelleTable == DbTablesHelper::dbTable ('DBTBL_ANNONCEURS','db')):
                if (FunctionController::getTableName ($value) == DbTablesHelper::dbTable ('DBTBL_SPONSOR_CAMPAGNES','db')):
                    $titreCampagneMaster = [];
                    $verif = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SPONSOR_CAMPAGNES','db')." WHERE annonceur = $idMaster");
                    foreach ($verif as $r):
                        $titreCampagneMaster[] = $r['campagnetitle'];
                    endforeach;
                    $titreCampagneSuppr = [];
                    $ligneAsuppr = join (',',$listeIDs);
                     $v =  FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_SPONSOR_CAMPAGNES','db')." WHERE annonceur IN($ligneAsuppr)");
                     foreach ($v as $r):
                        $titreCampagneSuppr[] = $r['campagnetitle'];
                     endforeach;
                     $croisement = array_intersect ($titreCampagneSuppr,$titreCampagneMaster);
                     if (count ($croisement)):
                        $sql[] = "DELETE FROM ".DbTablesHelper::dbTable ('DBTBL_SPONSOR_CAMPAGNES','db')." WHERE annonceur IN($ligneAsuppr) ";
                     else:
                         $sql[] = "UPDATE ".DbTablesHelper::dbTable ('DBTBL_SPONSOR_CAMPAGNES','db')." SET annonceur = $idMaster WHERE annonceur IN($ligneAsuppr)";
                     endif;
                endif;
            endif;
            $sql[] = "UPDATE $value SET $champs = $idMaster WHERE $champs IN (".join(',',$listeIDs).")";
        endforeach;
        $transaction = DB::transaction(function () use ($sql,$libelleTable,$listeIDs){
            foreach ($sql as $value):
                DB::update($value);
            endforeach;
           $del = DB::delete("DELETE FROM $libelleTable WHERE id IN (".join(',',$listeIDs).")");
            return $del;
        });
        if ($transaction != null):
            $ok = true;
        endif;
        return $ok;
    }

    public static function getDependancesTable(string $table):array {
        $listeDesTableChamps = FunctionController::getTableListeField ();
        $dpce = [];
        $foreignKey = substr($table,0,mb_strlen($table)-1);
        foreach ($listeDesTableChamps as $key => $r):
            if (in_array ($foreignKey,$r)):
                $dpce[] = $key;
            endif;
        endforeach;
        return $dpce;
    }
}
