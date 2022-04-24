<?php

namespace App\Http\Controllers\core;

use App\Http\Controllers\Controller;
use App\Models\Localite;
use App\WoodyModel;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Session ;
//use Illuminate\Support\Facades\DB;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Support\Facades\DB;
use App\Helpers;
use App\Helpers\DbTablesHelper;
use Illuminate\Support\Facades\Session;

class ModuleController extends Controller {

    //
    public static $champsInterdits = ["id", "created_at", "updated_at", "lastmodif", "deleted"];

    public function index() {
        $listDesTables = FunctionController::getDatabaseTable();
        return view("admin.module", compact("listDesTables"));
    }

    public function detailTable(Request $Request) {
        $data = $Request->all();
        $table = $data["table"];
        $dataTableData = FunctionController::getFieldOfTable($table);
        return view("Html.dataTableAjaxDyn", compact("dataTableData"));
        //return view("layouts.dataTableAjaxDyn", compact("dataTableData"));
    }

    public static function makeForm($table,$cond="",array $options = []) {
        $laTable = FunctionController::getTableName($table);
        $fields = FunctionController::getFieldOfTable($laTable);
        $formText = "";
        $formText .= "<input type='hidden' value='$table' name='table'>";
        foreach ($fields AS $it):
            if (!in_array($it["Field"], self::$champsInterdits)):
                $formText .= FunctionController::fieldInput($it["Field"], $it["Type"]);
            endif;
        endforeach;
        $formText .= FormController::champSubmit();
        $tbl = str_replace(env('DB_PREFIX'),'',$table);
        $tbl = ucfirst(str_replace('_',' ',$tbl));
        $laroute = route('traiterDefaultForm');
        $tableau = self::makeTable($table,$cond,$options);
        $vue = "formulaire-tableau";
        return view("template.Html.form", compact('formText','tbl','laroute','table','tableau','vue'));
    }

    public static function formStandart(string $table):string {
        $laTable = FunctionController::getTableName($table);
        $fields = FunctionController::getFieldOfTable($laTable);
        $formText = "";
        $formText .= "<input type='hidden' value='$table' name='table'>";
        foreach ($fields AS $it):
            if (!in_array($it["Field"], self::$champsInterdits)):
                $formText .= FunctionController::fieldInput($it["Field"], $it["Type"]);
            endif;
        endforeach;
        $formText .= FormController::champSubmit();
        $tbl = str_replace(env('DB_PREFIX'),'',$table);
        $tbl = ucfirst(str_replace('_',' ',$tbl));
        $laroute = route('traiterDefaultForm');
        $vue = "formulaire";
        return view("template.Html.form", compact('formText','tbl','laroute','table','vue'));
    }
    public static function tableauStandart(string $table, string $cond='',array $options = []):string {
        $laTable = FunctionController::getTableName($table);
        $tbl = str_replace(env('DB_PREFIX'),'',$table);
        $tbl = ucfirst(str_replace('_',' ',$tbl));
        $vue = "tableau";
        $tableau = self::makeTable($laTable,$cond);
        return view("template.Html.form", compact('tbl','table','tableau','vue'));
    }

    public static function makeTable($table,$cond="",$options=[]){
        $condition = $cond != "" ? $cond : " ORDER BY id DESC " ;
        $databaseTable = FunctionController::getTableName($table);
        $sql = "SELECT * FROM $databaseTable  $condition ";
        $dataTableData = FunctionController::arraySqlResult($sql);
        $pref = env ("DB_PREFIX");
        $t = trim(substr($table, mb_strlen($pref)));
        return view('woody.Html.dataTableAjax',compact('dataTableData','databaseTable','table','options','t'))->render();
    }

    public static function update(string $table, int $id):string {
        $laTable = FunctionController::getTableName($table);
        $data = FunctionController::arraySqlResult("SELECT * FROM $laTable WHERE id = $id");
        $fields = FunctionController::getFieldOfTable($laTable);
        $formText = "";
        $formText .= "<input type='hidden' value='$table' name='table'>";
        $formText .= "<input type='hidden' value='{$data[0]["id"]}' name='pid'>";
        foreach ($fields AS $it):
            if (!in_array($it["Field"], self::$champsInterdits)):
                $formText .= FunctionController::fieldInput($it["Field"], $it["Type"],$data[0][$it["Field"]]);
            endif;
        endforeach;
        $formText .= FormController::champSubmit();
        $tbl = str_replace(env('DB_PREFIX'),'',$table);
        $tbl = ucfirst(str_replace('_',' ',$tbl))." (MODIFIER)";
        $laroute = route('traiterDefaultForm');
        $tableau = self::makeTable($table,"");
        $vue = 'formulaire-tableau';
        return view("woody.Html.form", compact('formText','tbl','laroute','table','tableau','vue'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * Methode Pour faire les insertions et les updates des tables de l'application
     */
    public function traiterForm(Request $request){

        $DB = new WoodyModel();
        $DB->setTable($request->input('table'));

        $k = 'echec';
        $notification = 'Une erreur est survenue lors de l\'insertion des données';
        $table = $request->input('table');
        $pref = env('DB_PREFIX');
        $str = preg_match("/$pref/",$table);
        $TBL = $str == 1 ? substr($table,strlen(env('DB_PREFIX'))) : $table;
        $data = [];
        //dd ($request->all ());
        if($request->pid):
            $pid = $request->input('pid');
            foreach($request->all() as $key => $v):
                $data[$key] = $v;
            endforeach;
            unset($data['pid']);
            unset($data['_token']);
            unset($data['module']);
            unset($data['files']);
            unset($data['table']);
            $update = DB::table($TBL)->where('id',$pid)->update($data);
             if ($update):
                $k = 'success';
                $notification = 'Les données ont été mise à jour avec succes!';
            endif;
            //dd($k,$notification);
        endif;

        if (!$request->input('pid')):
            if($table == "users"):
                $notification = 'Pas accès à la table pour modif!';
                $k = 'success';
            else:

                foreach($request->all() as $key => $v):
                    $data[$key] = $v;
                endforeach;
                unset($data['pid']);
                unset($data['_token']);
                unset($data['table']);
                unset($data['files']);
                unset($data['table']);
                $create = DB::table($TBL)->insert($data);
                if($create):
                    $notification = 'Les données ont été inseré avec succes!';
                    $k = 'success';
                else:
                    $notification = 'Une erreur est survenue lors de l\'insertion des données!';
                    $k = 'echec';
                endif;
            endif;
        endif;
        $request->session()->flash($k,$notification);
        //Flashy::message($notification);
        return redirect()->back();
    }


    public function traiterFormMenu(Request $request){
        $data = $request->all();
        $table = $request->input('table');
        unset($data['_token']);
        unset($data['files']);
        unset($data['table']);

        if (!self::is_rang($table,$data['parent'],$data['rang'])):
            $create = DB::table($table)->insert($data);
            if($create):
                $notification = 'Les données ont été inseré avec succes!';
                $k = 'success';
            else:
                $notification = 'Une erreur est survenue lors de l\'insertion des données!';
                $k = 'echec';
            endif;
        else:
            $lerang = $data['rang'];
            $parent = $data['parent'];

            $where = " WHERE parent = $parent";
            $sql = "SELECT max(rang) AS rangMax  FROM ".FunctionController::getTableName($table)."  $where";
            $r = DB::select(DB::raw($sql));
            $rangMax = $r[0]->rangMax + 1;

            $data['rang'] = $rangMax;
            //dd($data);
            $createId = DB::table($table)->insertGetId($data);
            $tabReq = array();
            $tabReq[] = "UPDATE " .FunctionController::getTableName($table). " SET rang = 1000 WHERE parent = $parent AND rang = $lerang";
            $tabReq[] = "UPDATE " .FunctionController::getTableName($table). " SET rang = $lerang WHERE id = $createId";
            $tabReq[] = "UPDATE " .FunctionController::getTableName($table). " SET rang = $rangMax WHERE parent = $parent AND rang = 1000";

            $transac = DB::transaction(function () use($tabReq) {
                foreach ($tabReq as $item):
                    DbTablesHelper::dbraw($item);
                endforeach;
            });

            if($createId):
                $notification = 'Les données ont été inseré avec succes!';
                $k = 'success';
            else:
                $notification = 'Une erreur est survenue lors de l\'insertion des données!';
                $k = 'echec';
            endif;
        endif;

        $request->session()->flash($k,$notification);
        return redirect()->back();
    }

    public static function is_rang($table,$parent,$rang){
        $res = DbTablesHelper::getDatasByFields($table,['parent' => $parent,'rang' => $rang]);
        return count($res) ? true : false;
    }


    /**
     * Fonction pour télécharger un fichier sur le serveur
     * @param $file
     * @param $chemin le chemin pour aller chercher le fichier, il doit être donner sous le format "dossier1-sousdossier1-fichier"
     * @return
     */
    public static function getFile($file)
    {
        $file = str_replace("-",DIRECTORY_SEPARATOR,"$file");
        //dd($file);
        $existe = false;
        if (is_file($file)):
            $existe = true;
        endif;
        if ($existe):
            return response()->download($file);
        else:
             return "<h1>Echec de téléchargement ou Le fichier n'a pas été trouver pas sur le serveur!</h1>";
        endif;
    }

    public static function makeManualTable($datas){

    }

    public static function makeBreadCrumb(){
        $uri = FunctionController::makeCurrentUrl();
        $r = explode('/',$uri);
        $bread = str_replace('','-',$r[count($r)-1]);
        if (!Session::has("BreadCrumb.$bread")):
            $role = FunctionController::arraySqlResult("SELECT r.* FROM
                ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'))." r, 
                ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROUTES'))." rt 
                WHERE r.route = rt.id AND r.uri = '{$uri}'
            ");
            if (count($role)):
                Session::put("BreadCrumb.$bread", $role[0]["name"]);
            endif;
        endif;
        return self::getBreadCrumbsView(Session::get("BreadCrumb.$bread"));
    }

    public static function getBreadCrumbsView($bread){
        return view("Html.breadcrumb",compact('bread'))->render();
    }

    public function deleteData($table,$id){
        $del = DB::table($table)
            ->where('id',$id)
            ->delete();
        if ($del):
            Session::has('success','Donnée supprimer avec succès !');
        else:
            Session::has('echec','Une erreur est survenue, veuillez recommencer !');
        endif;
        return back();
    }

    public function inlineTexte(Request $Request) {
        $data = $Request->all();
        $position = $data["position"];
        $id = $data["id"];
        $table = $data["table"];
        $column = $data["column"];
        $valeur = $data["valeur"];
        $type = $data["type"];
        return FunctionController::inlineInput($id, $valeur, $column, $table, $type, $position);
    }

    public function inlineInput(Request $Request) {
        $data = $Request->all();
        $position = $data["position"];
        $id = $data["id"];
        $table = $data["table"];
        $column = $data["column"];
        $valeur = $data["valeur"];
        $type = $data["type"];
        return FunctionController::inlineInput($id, $valeur, $column, $table, $type, $position);
    }

    public function inlineUpdate(Request $Request) {
        $data = $Request->all();
        $position = $data["position"];
        $id = $data["id"];
        $table = $data["table"];
        $column = $data["column"];
        $valeur = $data["valeur"];
        $type = $data["type"];
        return FunctionController::inlineUpdate($id, $valeur, $column, $table, $type, $position);
    }

    public function changerCoord($action,Request $request){
        $html = "";
        if($action == 'lat'):
            $lat = $request->input('lat');
            $html .= "<div id=\"latDivItem\">
             <input value=\"$lat\" type=\"text\" class=\"form-control\" name=\"latitude\" readonly>
        </div>";
        endif;
        if($action == 'lng'):
            $lng =  $request->input('lng');
            $html .= "<div id=\"lngDivItem\">
            <input value=\"$lng\" type=\"text\" class=\"form-control\" name=\"longitude\" readonly>
        </div>";
        endif;
        return $html;
    }


    public function changeSessionValue(Request $request){
        $var = $request->input('var');
        $sessionName = $request->input('sessionname');
        $table = $request->input('table');
        if (!$request->session()->has($sessionName)):
            $request->session()->put($sessionName, $var);
        else:
            if ($request->session()->get($sessionName) != $var):
                $request->session()->put($sessionName, $var);
            endif;
        endif;
        $request->session()->save();
    }

    public function getSubmitBtn(Request $request){
        $action = $request->input('action');
        $txt = $request->input('txt');
        if ($action == "true"):
            return FormController::champSubmit($txt);
        else:
            return "";
        endif;
    }

    public function getFilsLocalite(Request $request){
        $pere = $request->input("localite");
        $chpname = array_key_exists('chpname',$request->all()) ? $request->input('chpname') : "parent";

        $localites = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_LOCALITES','db')." WHERE id = {$pere} OR parent = {$pere} ORDER BY parent ASC, name ASC");
        if (count($localites)):
            $grandPere = $pere != 0 ? FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_LOCALITES','db'),$pere,'parent') : -1;
            return view("woody.Html.champSelectLocalite",compact('localites','pere','grandPere','chpname'));
        endif;
    }

    public function storeLocalites(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ],[
            'name.required' => 'Le nom de la localité est obligatoire.',
            'latitude.required' => 'La latitude de la localité est obligatoire.',
            'longitude.required' => 'La longitude de la localité est obligatoire.',
        ]);
        $datas = $request->all ();
        unset($datas['_token']);
        if (!array_key_exists ('pid',$datas)):
            $datas['name'] = strtoupper (FunctionController::cleanNomPrenom ($datas['name']));
            $verif = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_LOCALITES','db')." WHERE parent = {$datas['parent']} AND name = '{$datas['name']}'");
            if (count ($verif) == 0):
                $insert = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_LOCALITES','db'))
                    ->insert ($datas);
                if ($insert):
                    $request->session ()->flash ('success', "La localité {$datas['name']} enrégistrée avec succès.");
                else:
                    $request->session ()->flash ('echec', "Une erreur est survenue, veuillez récommencer !");
                endif;
            else:
                $pere = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_LOCALITES','db'),$datas['parent']);
                $request->session ()->flash ('echec', "Une localité avec le même nom existe déjas dans la localité {$pere}, veuillez récommencer !");
            endif;
        else:
            $pid = $datas['pid'];
            unset($datas['pid']);
            $loc = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_LOCALITES','db'))
                ->where('id',$pid)
                ->update($datas);
            ;
            if ($loc):
                $request->session()->flash('success','Localité Mise à jour.');
            else:
                $request->session()->flash('echec','La mise n\'a pas été faite.');
            endif;
            return redirect()->route('bbmap.localite');
        endif;
        return back();
    }

    public function deleteModalDatas(Request $request){
        $table = $request->table;
        $id = $request->pk;
        return view ("woody.Html.formDelete",compact ('table','id'))->render ();
    }

    public function deleteDatas(string $table, int $id){
        $del = DB::delete ("DELETE FROM $table WHERE id = $id");
        if ($del):
            Session::flash('success',"L'enregistrement a été supprimée !");
        else:
            Session::flash('echec',"Une erreur est survenue, veuillez recommencer !");
        endif;
        return back ();
    }


    public static function makeZipArchive(array $files,string $rep, string $archiveName = "Archive"){
        $zip = new \ZipArchive();
        $archive = $rep.DIRECTORY_SEPARATOR.$archiveName.'.zip';
        if($zip->open($archive) == true):
            if ($zip->open($archive, \ZipArchive::CREATE)):
                foreach ($files as $file):
                    $zip->addFile($rep.DIRECTORY_SEPARATOR.$file,$file);
                endforeach;
                $zip->close();
            endif;
        endif;
    }

}
