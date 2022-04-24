<?php

namespace App\Http\Controllers\core;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\XeryaAdmin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class FunctionController extends Controller {

    public static $typeDate;

    public function __construct()
    {

    }

    public static function makeTabMenu() {
        if (!Session::has("tabMenu") && !Session::has("groupTabMenu")) :
            $tabl = DbTablesHelper::dbTable('DBTBL_MENUS');
            $table = self::getTableName($tabl);
            $UserLevel = Session::get('UserLevel');
            $sql = "SELECT * FROM $table WHERE level_menu <= '".$UserLevel."' AND parent = %d ORDER BY rang ASC";
            $result = DB::select(DB::raw(sprintf($sql, 0)));
            $tabMenu = [];
            $groupTab = [];
            foreach ($result AS $item):
                $groupTab[$item->name] = [];
                $lemenu = [];
                $lemenu["label"] = $item->name;
                $lemenu["icone"] = $item->icone;
                if ($item->url == "#"):
                    $parent = $item->id;
                    $submenu = DB::select(DB::raw(sprintf($sql, $parent)));
                    $lessousmenus = [];
                    foreach ($submenu AS $sm):
                        $lesousmenu = [];
                        $groupTab[$sm->name] = [];
                        if ($sm->url == "#"):
                            $p = $sm->id;
                            $tabssm = DB::select(DB::raw(sprintf($sql, $p)));
                            $lessoussousmenus = [];
                            foreach ($tabssm AS $ssm):
                                $groupTab[$item->name][] = $ssm->url;
                                $groupTab[$sm->name][] = $ssm->url;
                                $lesoussousmenu ["url"] = $ssm->url;
                                $lesoussousmenu ["label"] = $ssm->name;
                                $lessoussousmenus[] = $lesoussousmenu;
                            endforeach;
                            $lesousmenu["url"] = $lessoussousmenus;
                        else:
                            $groupTab[$item->name][] = $sm->url;
                            $groupTab[$sm->name][] = $sm->url;
                            $lesousmenu["url"] = $sm->url;
                        endif;
                        $lesousmenu["label"] = $sm->name;
                        $lessousmenus[] = $lesousmenu;
                    endforeach;
                    $lemenu["url"] = $lessousmenus;
                else:
                    $lemenu["url"] = $item->url;
                    $groupTab[$item->name][] = $item->url;
                endif;
                $tabMenu[] = $lemenu;
            endforeach;
            if (count($tabMenu)):
                Session::put("tabMenu", $tabMenu);
            endif;
            if (count($groupTab)):
                Session::put("groupTabMenu", $groupTab);
            endif;
        endif;
    }

    public static function makeTabMenubis() {
        //Session::flush() ;
        if (!Session::has("tabMenu") && !Session::has("groupTabMenu")) :
            $tabl = DbTablesHelper::dbTable('DBTBL_MENUS');
            $table = self::getTableName($tabl);
            $UserLevel = Session::get('UserLevel');
            $sql = "SELECT * FROM $table WHERE level_menu <= '".$UserLevel."' AND parent = %d ORDER BY rang ASC";
            $result = DB::select(DB::raw(sprintf($sql, 0)));
            $tabMenu = [];
            $groupTab = [];
            foreach ($result AS $item):
                $groupTab[$item->name] = [];
                $lemenu = [];
                $lemenu["label"] = $item->name;
                $lemenu["icone"] = $item->icone;
                if ($item->url == "#"):
                    $parent = $item->id;
                    $submenu = DB::select(DB::raw(sprintf($sql, $parent)));
                    $lessousmenus = [];
                    foreach ($submenu AS $sm):
                        $lesousmenu = [];
                        $groupTab[$sm->name] = [];
                        if ($sm->url == "#"):
                            $p = $sm->id;
                            $tabssm = DB::select(DB::raw(sprintf($sql, $p)));
                            $lessoussousmenus = [];
                            foreach ($tabssm AS $ssm):
                                $groupTab[$item->name][] = $ssm->url;
                                $groupTab[$sm->name][] = $ssm->url;
                                $lesoussousmenu ["url"] = $ssm->url;
                                $lesoussousmenu ["label"] = $ssm->name;
                                $lessoussousmenus[] = $lesoussousmenu;
                            endforeach;
                            $lesousmenu["url"] = $lessoussousmenus;
                        else:
                            $groupTab[$item->name][] = $sm->url;
                            $groupTab[$sm->name][] = $sm->url;
                            $lesousmenu["url"] = $sm->url;
                        endif;
                        $lesousmenu["label"] = $sm->name;
                        $lessousmenus[] = $lesousmenu;
                    endforeach;
                    $lemenu["url"] = $lessousmenus;
                else:
                    $lemenu["url"] = $item->url;
                    $groupTab[$item->name][] = $item->url;
                endif;
                $tabMenu[] = $lemenu;
            endforeach;
            if (count($tabMenu)):
                Session::put("tabMenu", $tabMenu);
            endif;
            if (count($groupTab)):
                Session::put("groupTabMenu", $groupTab);
            endif;
        endif;
    }

    public static function expansion($label) {
        $laRoute = self::makeCurrentUrl();
        self::makeTabMenu();
        $groupTab = Session::get("groupTabMenu");
        $url = "/$laRoute";
        if (array_key_exists($label, $groupTab)) :
            return in_array($url, $groupTab[$label]);
        else:
            return false;
        endif;
    }

    public static function makeCurrentUrl() {
        $uri = Route::current()->uri;
        $paramName = Route::current()->parameterNames;
        $parameters = Route::current()->parameters;
        foreach ($paramName AS $pn):
            $uri = str_replace('{' . $pn . '}', $parameters[$pn], $uri);
        endforeach;
        return $uri;
    }

    public static function is_active($url) {
        $laRoute = self::makeCurrentUrl();
        return $url == "/$laRoute";
    }

    public static function makeMenuSimple($idMenu = "") {
        self::makeTabMenu();
        $tab = Session::get("tabMenu");
        $HTML = "";
        if (!count($tab)):
            return redirect("/");
        endif;

        foreach ($tab AS $r) :
            if (is_array($r["url"])):
                $HTML .= self::makeMenuItemLevel($r["url"], $r["label"], $r["icone"], $idMenu);
            else:
                $HTML .= self::makeMenuItem($r["url"], $r["label"], $r["icone"]);
            endif;
        endforeach;
        return $HTML;
    }

    public static function makeMenuItem($url, $label, $icone) {
        $base = env("APP_URL");
        $licone = self::getChampTable(DbTablesHelper::dbTable('DBTBL_ICONES'), $icone);
        return <<<MENUT
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="{$label}">
                    <a class="nav-link" href="{$base}{$url}">
                      <i class="fa fa-fw fa-{$licone}"></i>
                      <span class="nav-link-text">{$label}</span>
                    </a>
                  </li>
MENUT;
    }

    public static function makeMenuItemLevel($tab, $label, $icone, $idMenu) {
        $id = str_replace(" ", "", $label);
        $base = env("APP_URL");
        $mask = "
                 <li>
                        <a href=\"$base%s\">%s</a>
                 </li>
                 ";
        $li = "";
        foreach ($tab AS $it):
            if (is_array($it["url"])):
                $li .= self::makeMenuItemThirdLevel($it["url"], $it["label"]);
            else:
                $li .= sprintf($mask, $it["url"], $it["label"]);
            endif;
        endforeach;
        $expan = self::expansion($label);
        $expansion = $expan ? " aria-expanded=\"true\"" : "";
        $show = $expan ? " show" : "";
        $licone = self::getChampTable(DbTablesHelper::dbTable('DBTBL_ICONES'), $icone);
        return <<<MENUT
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="{$label}">
                    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#{$id}" data-parent="#{$idMenu}"{$expansion}>
                      <i class="fa fa-fw fa-{$licone}"></i>
                      <span class="nav-link-text">{$label}</span>
                    </a>
                    <ul class="sidenav-second-level collapse{$show}" id="{$id}">
                      {$li}
                    </ul>
                  </li>
MENUT;
    }

    public static function makeMenuItemThirdLevel($tab, $label) {
        $id = str_replace(" ", "", $label);
        $base = env("APP_URL");
        $mask = "
                 <li %s>
                        <a href=\"$base%s\">%s</a>
                 </li>
                 ";
        $li = "";
        foreach ($tab AS $it):
            $class = self::is_active($it["url"]) ? " class=\"active\"" : "";
            $li .= sprintf($mask, $class, $it["url"], $it["label"]);
        endforeach;
        $expan = self::expansion($label);
        $expansion = $expan ? " aria-expanded=\"true\"" : "";
        $show = $expan ? " show" : "";
        $sactive = $expan ? "  class=\"active\"" : "";
        return <<<MENUT
                <li{$sactive}>
                    <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#{$id}"{$expansion}>{$label}</a>
                    <ul class="sidenav-third-level collapse{$show}" id="{$id}">
                      {$li}
                    </ul>
                  </li>
MENUT;
    }


    public static function inputInline($id, $valeur, $colonne, $baseDeDonnees, $chemin, $position, $action) {

        return <<<INPUT
        <input type="text" name="{$colonne}" value="{$valeur}" onchange="sendData('position={$position}&id={$id}&bd={$baseDeDonnees}&colonne={$colonne}&chemin={$action}&valeur='+this.value, '{$chemin}', '{$position}');">

INPUT;
    }

    public static function inlineTexte($id, $valeur, $column, $table, $type, $position) {
        $chemin = route("inlineInput");
        $lavaleur = self::is_foreignKey($column) ? self::selectColumnInTable(env("DB_PREFIX") . "$column" . "s", $valeur, "name") : $valeur;
        $lavaleur = $lavaleur == "" ? "-" : $lavaleur;
        return <<<INPUT
        <a href="#test-{$column}-{$id}" title="Double clic pour modifier"  class="text-inline"
            onclick="sendData('position={$position}&id={$id}&table={$table}&column={$column}&valeur={$valeur}&type={$type}', '{$chemin}', '{$position}');" style="border-bottom:dotted 1.5px #ff0000!important;">
                {$lavaleur}
       </a>

INPUT;
    }

    public static function inlineInput($id, $valeur, $column, $table, $type, $position) {
        $chemin = route("inlineUpdate");
        if (self::is_foreignKey($column)):
            return self::inlineSelect($id, $valeur, $column, $table, $type, $position);
        endif;
        return <<<INPUT
        <input type="text" name="{$column}" value="{$valeur}" class="form-control"
            onchange="sendData('position={$position}&id={$id}&table={$table}&column={$column}&type={$type}&valeur='+this.value, '{$chemin}', '{$position}');"
          onfocusout="sendData('position={$position}&id={$id}&table={$table}&column={$column}&type={$type}&valeur='+this.value, '{$chemin}', '{$position}');">

INPUT;
    }

    public static function inlineSelect($id, $valeur, $column, $table, $type, $position) {
        if (!self::is_foreignKey($column)):
            return self::inlineInput($id, $valeur, $column, $table, $type, $position);
        endif;
        $chemin = route("inlineUpdate");
        $bdtbl = env("DB_PREFIX") . $column . "s";
        $bdtable = FunctionController::getTableName($bdtbl);
        $data = DB::select(DB::raw(" SELECT id, name FROM $bdtable ORDER BY name ASC"));
        $option = "";
        foreach ($data AS $r) :
            $selected = $r->id == $valeur ? " selected=\"selected\"" : "";
            $option .= "<option value=\"" . $r->id . "\"$selected> " . $r->name . " </option>";
        endforeach;
        return <<<SELECT
                    <select name="{$column}" class="form-control"
            onchange="sendData('position={$position}&id={$id}&table={$table}&column={$column}&type={$type}&valeur='+this.value, '{$chemin}', '{$position}');"
          onfocusout="sendData('position={$position}&id={$id}&table={$table}&column={$column}&type={$type}&valeur='+this.value, '{$chemin}', '{$position}');">
                        {$option}
                  </select>
SELECT;
    }

    public static function verifierSiTableExiste($table){
        if (in_array($table,Session::get('DatabaseTable'))):

        endif;
    }


    public static function inlineUpdate($id, $valeur, $column, $table, $type, $position) {
        self::updateColumnInTable($table, $column, $valeur, $id);
        if ($table == "zd_menus") :
            Session::forget("tabMenu");
            Session::forget("groupTabMenu");
        endif;
        return self::inlineTexte($id, $valeur, $column, $table, $type, $position);
    }

    public static function updateColumnInTable($table, $column, $valeur, $id) {
        $laValeur = is_numeric($valeur) ? $valeur : "'$valeur'";
        $sql = " UPDATE $table SET $column = $laValeur WHERE id = $id";
        Session::put("funcVar.$table.$id.$column", $valeur);
        return DB::update($sql);
        //Session::forget("tabMenu"); // Mise a jour de la valeur en session
        //endif;
    }

        public static function selectColumnInTable($tabl, $id, $column) {
        $table = self::getTableName($tabl);
        if (Session::has("funcVar.$table.$id.$column")):
            return Session::get("funcVar.$table.$id.$column");
        else:
            if (!Session::has("funcVar")):
                Session::put("funcVar", []);
            elseif (!Session::has("funcVar.$table")):
                Session::put("funcVar.$table", []);
            endif;
            $re = self::arraySqlResult("SELECT * FROM $table WHERE id = $id");
            if (count($re)):
                Session::put("funcVar.$table.$id", $re[0]);
                return Session::get("funcVar.$table.$id.$column");
            endif;
        endif;
        return "-";
    }
//*
    public static function getChampTable($tabl, $id, $column = "name") {
        $table = self::getTableName($tabl);
        if (Session::has("funcVar.$table.$id.$column")):
            return Session::get("funcVar.$table.$id.$column");
        else:
            if (!Session::has("funcVar")):
                Session::put("funcVar", []);
            elseif (!Session::has("funcVar.$table")):
                Session::put("funcVar.$table", []);
            endif;
            $re = self::arraySqlResult("SELECT * FROM $table WHERE id = $id");
            if (count($re)):
                Session::put("funcVar.$table.$id", $re[0]);
                return Session::get("funcVar.$table.$id.$column");
            endif;
        endif;
        return "-";
    }
//*/
/*
    public static function getChampTable($tabl, $id, $column = "name") {
        $table = self::getTableName($tabl);
        if (Session::has("funcVar.$table.$id.$column")):
            return Session::get("funcVar.$table.$id.$column");
        else:
            if (!Session::has("funcVar")):
                Session::put("funcVar", []);
            elseif (!Session::has("funcVar.$table")):
                Session::put("funcVar.$table", []);
            endif;
            $re = self::arraySqlResult("SELECT * FROM $table");
            foreach($re AS $it):
                $pid = $it["id"] ;
                Session::put("funcVar.$table.$pid", $it);
            endforeach;   
            return Session::get("funcVar.$table.$id.$column");
        endif;
        return "-";
    }
    
    //*/
    public static function getFieldDefaultParam() {
        if (!Session::has("FieldDefaultParam")):
            $tabFieldDefault = [];
            $table = DbTablesHelper::dbTable('DBTBL_FIELDDEFAULTS');
            $table = self::getTableName($table);
            $re = self::arraySqlResult("SELECT * FROM $table");
            foreach ($re AS $v):
                $tabFieldDefault[$v["field"]] = $v;
            endforeach;
            Session::put("FieldDefaultParam", $tabFieldDefault);
        endif;
    }

    public static function getTableFieldParam() {
        if (!Session::has("TableFieldParam")):
            $tabFieldDefault = [];
            $tbl = DbTablesHelper::dbTable('DBTBL_FIELDPARAMS');
            $table = self::getTableName($tbl);

            $re = self::arraySqlResult("SELECT * FROM  $table");
            foreach ($re AS $v):
                $tabFieldDefault[self::getTableName($v["table"])] [$v["field"]] = ["display" => $v["display"], "inline" => $v["inline"]];
            endforeach;
            Session::put("TableFieldParam", $tabFieldDefault);
        endif;
    }


    public static function is_displayable($table, $field) {
        self::getFieldDefaultParam();
        self::getTableFieldParam();
        $resp = true;
        if (Session::has("TableFieldParam.$table.$field.display")):
            $resp = Session::get("TableFieldParam.$table.$field.display") ? true : false;
        elseif (Session::has("FieldDefaultParam.$field.display")):
            $resp = Session::get("FieldDefaultParam.$field.display") ? true : false;
        endif;
        return $resp;
    }

    public static function is_inlinable($table, $field) {
        $resp = false;
        if (!self::is_displayable($table, $field)):
            return false;
        else:
            //dump(Session::get("FieldDefaultParam.$field"));
            if (Session::has("TableFieldParam.$table.$field.inline")):
                $resp = Session::get("TableFieldParam.$table.$field.inline") ? true : false;
            elseif (Session::has("FieldDefaultParam.$field.inline")):
                $resp = Session::get("FieldDefaultParam.$field.inline") ? true : false;
            endif;
            return $resp;
        endif;
    }

    public static function arraySqlResult($sql) {
        $re = DB::select(DB::raw($sql));
        $data = [];
        foreach ($re AS $row):
            $t = [];
            foreach ($row AS $k => $v) :
                $t[$k] = $v;
            endforeach;
            $data[] = $t;
        endforeach;
        return $data;
    }

    public static function getTypeField($type) {
        $tb = explode("(", $type);
        return $tb[0];
    }

    public static function fieldInput($champ, $type, $valeur = "", $param = []) {
        //dump ($champ,$type);
        if (self::is_foreignKey($champ)):
            return self::fieldSelect($champ, $type, $valeur, $param);
        endif;
        $libelle = ucfirst(str_replace('_',' ',$champ));
        $typeInput = "text";
        $require = $param["require"] ?? "";
        $placeholder = $param["placeholder"] ?? $libelle;
        $classChampsDate = "";
        if ($type == "date"):
            return self::fieldDate ($champ,$type,$valeur,$param);
        endif;
        if (preg_match ('#enum#',$type) == 1):
            return self::fieldEnum ($champ,$type,$valeur,$param);
        endif;
        if (preg_match ('#tinyint#',$type) == 1):
            return self::fieldEntier ($champ,$type,$valeur,$param);
        endif;
        if ($type == "text"):
            return self::fieldTexte ($champ,$type,$valeur,$param);
        endif;
        if (preg_match ('#varchar#',$type) == 1 && $champ == "couleur"):
            return self::fieldColor ($champ,$type,$valeur,$param);
        endif;
        return <<<INPUT
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <label for="{$champ}"> {$libelle}</label>
                <input name="{$champ}" class="form-control {$classChampsDate}" type="{$typeInput}" value="{$valeur}" {$require} placeholder="{$placeholder}" id="{$champ}">
        </div>
INPUT;
    }

    public static function fieldSelect($champ, $type, $valeur = "", $param = []) {
        if (!self::is_foreignKey($champ)):
            return self::fieldInput($champ, $type, $valeur = "", $param = []);
        endif;
        if ($champ == "user" && preg_match ('#int#',$type) == 1):
            return self::fieldUser ($champ,$type,$valeur,$param);
        endif;
        $bdtbl = self::getTableName($champ . "s");
        $data = DB::select(DB::raw(" SELECT id, name FROM $bdtbl ORDER BY name ASC"));
        $option = "<option>----------</option>";
        foreach ($data AS $r) :
            $selected = $r->id == $valeur ? " selected=\"selected\"" : "";
            $option .= "<option value=\"" . $r->id . "\"$selected> " . $r->name . " </option>";
        endforeach;
        $libelle = ucfirst(str_replace('_',' ',$champ));
        return view ("template.Html.champSelect",compact ('option','libelle','champ','valeur','type'))->render ();
    }

    public static function fieldDate(string $champ, string $type, string $valeur = "", array $param = []){
        $libelle = ucfirst(str_replace('_',' ',$champ));
        return view ("template.Html.champDatePicker", compact ('champ','type','valeur','param','libelle'))->render ();
    }

    public static function fieldEnum(string $champ, string $type, string $valeur = "", array $param = []){
        $libelle = ucfirst(str_replace('_',' ',$champ));
        $chaine = self::trouver_ma_chaine ($type,'(',')');
        $list = explode (',',$chaine);
       // dd ($chaine,$list);
        return view ("template.Html.champEnum", compact ('champ','type','valeur','param','libelle','list'))->render ();
    }

    public static function fieldTexte(string $champ, string $type, string $valeur = "", array $param = []){
        $libelle = ucfirst(str_replace('_',' ',$champ));
        $editeur = "summernote";
        return view ("template.Html.champText", compact ('champ','type','valeur','param','libelle','editeur'))->render ();
    }

    public static function fieldEntier(string $champ, string $type, string $valeur = "", array $param = []){
        $libelle = ucfirst(str_replace('_',' ',$champ));
        return view ("template.Html.champEntier", compact ('champ','type','valeur','param','libelle'))->render ();
    }

    public static function fieldColor(string $champ, string $type, string $valeur = "", array $param = []){
        $libelle = ucfirst(str_replace('_',' ',$champ));
        return view ("template.Html.champCouleur", compact ('champ','type','valeur','param','libelle'))->render ();
    }

    public static function fieldUser(string $champ, string $type, string $valeur = "", array $param = []){
        $libelle = ucfirst(str_replace('_',' ',$champ));
        $userID = $valeur == "" ? Auth::id () : $valeur;
        return view ("template.Html.champUser", compact ('champ','type','valeur','param','libelle','userID'))->render ();
    }

    public static function trouver_ma_chaine($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    public static function ec($val, $column) {
        if($column == "parent"):
            return self::getLigneeDeLocalite ($val);
        endif;
        if($column == "icone"):
            return self::getIconeHtml ($val);
        endif;
        if (!self::is_foreignKey($column)):
            return $val;
        else :
            return self::getChampTable($column . "s", $val, "name");
        endif;
    }

    public static function getDatabaseTable() {
        if (!Session::has("DatabaseTable")):
            $pref = env("DB_PREFIX");
            $app_pref = env("APPLI_PREFIX");
            $listDesTables = [];
            $listDesTablesIndexees = [];
            $data = DB::select(DB::raw("SHOW TABLES"));
            $bd = env("DB_DATABASE");
            $key = "Tables_in_$bd";
            foreach ($data AS $row) :
                $table = str_replace($pref, "", $row->$key) ;
                $index = str_replace($app_pref, "", $table);
                $listDesTables[] = $table;
                $listDesTablesIndexees[$index] = $table;
            endforeach;
            Session::put("DatabaseTable", $listDesTables);
            Session::put("DatabaseTableIndexees", $listDesTablesIndexees);
            $t = [];
            foreach ($listDesTables AS $a):
                $t[] = str_replace($app_pref, "", substr($a, 0, -1));
            endforeach;
            Session::put("foreignKey", $t);
        endif;
        return Session::get("DatabaseTable");
    }


    public static function getTableName(string $table):string {
        if (!Session::has("TableName")):
            Session::put("TableName", []);
        endif;
        if (!Session::has("TableName.$table")):
            self::getDatabaseTable();
            $pref = env("DB_PREFIX");
            $app_pref = env("APPLI_PREFIX");
            $index = str_replace($app_pref, "", str_replace($pref, "", $table)) ;
            if (array_key_exists($index, Session::get("DatabaseTableIndexees"))):
                Session::put("TableName.$table", $pref.Session::get("DatabaseTableIndexees.$index"));
            else:
                return false ;
            endif;
        endif;
        return Session::get("TableName.$table");
    }

    public static function getFieldOfTable(string $table):array {
        if (!Session::has("FieldOfTable.$table")):
            $tabl = self::getTableName($table);
            $datab = DB::select(DB::raw("SHOW COLUMNS FROM $tabl"));
            $dataTableData = [];
            foreach ($datab AS $row):
                $tb = [];
                foreach ($row AS $k => $v):
                    $tb[$k] = $v;
                endforeach;
                $dataTableData[] = $tb;
            endforeach;
            Session::put("FieldOfTable.$table", $dataTableData);
        endif;
        return Session::get("FieldOfTable.$table");
    }

    public static function is_foreignKey($field) {
        if (!Session::has("is_foreignKey")):
            Session::put("is_foreignKey", []);
        endif;
        if (!Session::has("is_foreignKey.$field")):
            self::getDatabaseTable();
            Session::put("is_foreignKey.$field", in_array($field, Session::get("foreignKey")));
        endif;
        return Session::get("is_foreignKey.$field");
    }

    public static function makeInputFormFieldParent($table = "menus") {
        $laTable = FunctionController::getTableName($table);
        $sql = "SELECT id, name FROM $laTable WHERE parent IN (%s) ORDER BY name ASC";
        $result = DB::select(DB::raw(sprintf($sql, "0")));
        $tabp = [];
        $html = "";
        foreach ($result AS $r):
            $tabp[] = $r->id;
            $html .= "
                      <option value=\"" . $r->id . "\">" . $r->name . "</option>";
        endforeach;
        $pp = join(", ", $tabp);
        $result1 = DB::select(DB::raw(sprintf($sql, $pp)));
        foreach ($result1 AS $r):
            $html .= "<option value=\"" . $r->id . "\"> --" . $r->name . "</option>";
        endforeach;
        $route = route("makeFormRang");
        return <<<SELECT
                    <div class="form-group col-sm-6" >
                            <label for="parent"> Parent</label>
                                <select name="parent" class="form-control" onchange="sendData('menuId='+this.value+'&table={$table}', '{$route}', 'rangMenuId');">
                                    <option value="0">Choisir un groupe de menu</option>
                                    {$html}
                              </select>
                   </div>
SELECT;
    }

    public static function makeInputFormFieldRang($parent = 0, $table = "menus") {
        $laTable = FunctionController::getTableName($table);
        $where = " WHERE parent = $parent";
        $sql = "SELECT max(rang) AS rangMax  FROM $laTable $where";
        $r = DB::select(DB::raw($sql));
        $rangMax = $r[0]->rangMax + 1;
        $html = "";
        for ($i = $rangMax; $i >= 1; $i--):
            $selected = $i == $rangMax ? " selected=\"selected\"" : "";
            $html .= "
                  <option value=\"$i\"$selected>$i <i class=\"fa fa-home\"></i></option>";
        endfor;
        return <<<SELECT
                    <div class="form-group col-sm-6" id="rangMenuId">
                            <label for="rang"> Rang</label>
                                <select name="rang" class="form-control">
                                    {$html}
                              </select>
                   </div>
SELECT;
    }

    public static function makeInputFormFieldIcone($table = "icones") {
        $laTable = FunctionController::getTableName($table);
        $sql = "SELECT *  FROM $laTable";
        $result = DB::select(DB::raw($sql));
        $html = "<option value=''>Choisir une icone</option>";
        foreach ($result AS $r):
            $tabp[] = $r->id;
            $html .= "
                      <option value=\"" . $r->id . "\">" . $r->name . "</option>";
        endforeach;
        return <<<SELECT
               <div class="form-group col-sm-6" >         
                            <label for="rang"> Icone</label>
                                <select name="icone" class="form-control">
                                    {$html}
                              </select>
       </div>            
SELECT;
    }

    public  function makeFormRang(Request $Request) {
        $menuId = $Request->input("menuId");
        $table = $Request->input("table");
        return self::makeInputFormFieldRang($menuId,$table);
    }

    public static function cleanStr($str){
        $accents = Array("/é/", "/è/", "/ê/", "/ç/", "/à/", "/â/", "/î/", "/ï/", "/ù/", "/ô/");
        $sans = Array("e", "e", "e", "c", "a", "a", "i", "i", "u", "o");
        $chaine = preg_replace($accents, $sans, $str);
        $entre =  array(' ', '?', '!', '.','...', ',', ':', "'", '&', '(', ')', '"','/', '-', '\\', '__', '___');
        $sortie = array('_', '_', '_', '_', '','_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');
        $chaine1 = str_replace($entre, $sortie, $chaine);
        //var_dump($chaine,$chaine1);
        return $chaine1;
    }

    public static function cleanNomPrenom($str){
        $accents = Array("/é/", "/è/", "/ê/", "/ç/", "/à/", "/â/", "/î/", "/ï/", "/ù/", "/ô/");
        $sans = Array("e", "e", "e", "c", "a", "a", "i", "i", "u", "o");
        $chaine = preg_replace($accents, $sans, $str);
        return $chaine;
    }

    public static function cleanStingUpperOrLower($str,$transform = "L"){
        $chaine = FunctionController::cleanStr($str);
        $chaine = str_replace('_',' ',$chaine);
        $chaine = $transform == "U" ? strtoupper($chaine) : strtolower($chaine);
        return $chaine;
    }

    public static function date2Fr($date,$format = "d/m/Y"){
        return (new \DateTime($date))->format($format);
    }

    public static function formatNumber($number,$decimal = 2,$dec_point = ".",$thousand = ",")
    {
        return number_format($number,$decimal,$dec_point,$thousand);
    }

    public static function arrondir($number,$precision = 2){
        $number = self::formatNumber($number);
        return floatval($number);
    }

    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public static function makeListeDate(){
        $dates = [];
        $jourFr = Config::get('constantes.jourFr');
        for($i=0;$i<31;$i++):
            $time = mktime(0,0,0,date('n'),date('j')-$i,date('Y'));
            if (intval(date("w",$time))):
                $dates[date("Y-m-d",$time)] = $jourFr[date("w",$time)].date(" d/m/Y",$time);
            endif;
        endfor;
        return $dates;
    }
    public static function joursDuMois($mois,$annee,$jourJ=0){
        $dates = [];
        $jourFr = Config::get('constantes.jourFr');
        $jourJ = $jourJ == 0 ? intval(date('d')) : intval(date('t',mktime(0, 0, 0, $mois, 1, $annee)));
        for($i=0;$i<$jourJ;$i++):
            $j = $i+1;
            $time = mktime(0,0,0,$mois,$j,$annee);
            $dates[date("Y-m-d",$time)] = $jourFr[date("w",$time)].date(" d/m/Y",$time);
        endfor;
        //dd($jourJ,intval($jourJ),$dates);
        return $dates;
    }

    public static function makeListeMois($nombre,$mois,$jours,$annee){
        $dates = [];
        $moisFr = Config::get('constantes.moisFr');
        for($i=0;$i<$nombre;$i++):
            $time = mktime(0,0,0,$mois+$i,$jours,$annee);
            if (intval(date("n",$time))):
                $dates[date("Y-m-d",$time)] = $moisFr[date("n",$time)].date(" d/m/Y",$time);
            endif;
        endfor;
        return $dates;
    }

    public static function getRoutes(){
        return Route::getRoutes();
    }

    public static function sendMessageFlash($alerte,$message){
        return view('Html.Message',compact('alerte','message'))->render();
    }

    public static function refreshRoles(){
        $sqlq = "SELECT role FROM ".self::getTableName(DbTablesHelper::dbTable('DBTBL_PROFILROLES'))." WHERE profil = ".Auth::user()->profil." ORDER BY id ASC ";
        $result = self::arraySqlResult($sqlq);
        if(!empty($result)):
            $tabRole = [];
            foreach ($result as $item):
                $tabRole[] = $item['role'];
            endforeach;
            Session::put('userRoles', $tabRole);
        endif;
        return Session::get('userRoles');
    }

    public static function validerDate($date){
        $exp = explode('/',$date);
        $ladate = "";
        if (count($exp) == 3):
            $jj = $exp[0];
            $mm = $exp[1];
            $aaaa = $exp[2];
            $mktime = mktime(0,0,0,$mm,$jj,$aaaa);
            $ladate = date("Y-m-d",$mktime);
        endif;
        return $ladate;
    }

    public static function envDB($key){
        $conn = DB::getDefaultConnection();
        $k = strtolower($key);
        $connTable = Config::get("database.connections.$conn");
        if (array_key_exists($k,$connTable)):
            return $connTable[$k];
        endif;
    }

    public static function getAge($date_naissance)
    {
        $arr1 = explode('/', $date_naissance);
        $arr2 = explode('/', date('d/m/Y'));

        if(($arr1[1] < $arr2[1]) || (($arr1[1] == $arr2[1]) && ($arr1[0] <= $arr2[0])))
            return $arr2[2] - $arr1[2];

        return $arr2[2] - $arr1[2] - 1;
    }

    public static function calculRang(array $tab) {
        $tableau = $tab;
        arsort($tableau);
        $cles = array_keys($tableau);
        //dd($tableau,$cles);
        $array = [];
        $rang = [];
        $rang[0] = 1;
        $array[$cles[0]] = 1;
        $k = count($tableau);
        for($i=1;$i<$k;$i++):
            $rang[$i] = $tableau[$cles[$i]] == $tableau[$cles[$i-1]] ? $array[$cles[$i-1]] : $i+1;
            $array[$cles[$i]] = $rang[$i];
        endfor;

        return $array;
    }

    public static function movefile($file1,$file2){
        return copy($file1,$file2);
    }

    public static function makeMiniatureImage($image,$rep,$long=200,$haut=100,$thumbDir="thumb"){
        //dd(pathinfo($image,PATHINFO_EXTENSION),$image,$rep);
        if (in_array(pathinfo($image,PATHINFO_EXTENSION),Config::get("constantes.imageext"))):
            $fichier = pathinfo($image,PATHINFO_BASENAME);
            $dest1 = $rep.DIRECTORY_SEPARATOR.$thumbDir;
            if (!is_dir($dest1)):
                mkdir($dest1);
            endif;
            if (!is_file($dest1.DIRECTORY_SEPARATOR.$fichier)):
                $manager = new ImageManager();
                $im = $manager->make($image);
                $im->fit($long,$haut)->save($dest1.DIRECTORY_SEPARATOR.$fichier);
            endif;
        endif;
    }

    public static function truncateStr($str,$nbrCaractere=15){
        $string = $str;
        if (strlen($str) > $nbrCaractere):
            $w = substr($str,0,$nbrCaractere);
            $string = <<<STR
    <span data-toggle="tooltip" data-placement="top" title="{$str}">{$w}...</span>
STR;

        endif;
        return $string;
    }

    // ---------------------------------------------------------------------
    //  Générer un mot de passe aléatoire
    // ---------------------------------------------------------------------
    public static function genererMDP ($longueur = 8){
        // initialiser la variable $mdp
        $mdp = "";
        // Définir tout les caractères possibles dans le mot de passe,
        // Il est possible de rajouter des voyelles ou bien des caractères spéciaux
        $possible = "2346789abcdfghjkmnpqrtvwxyzABCDFGHJKLMNPQRTVWXYZ";
        $obliger = "&@+*.=#";
        $suplement = date('Y');
        // obtenir le nombre de caractères dans la chaîne précédente
        // cette valeur sera utilisé plus tard
        $longueurMax = strlen($possible);
        if ($longueur > $longueurMax) :
            $longueur = $longueurMax;
        endif ;
        // initialiser le compteur
        $i = 0;
        // ajouter un caractère aléatoire à $mdp jusqu'à ce que $longueur soit atteint
        while ($i < $longueur) :
            // prendre un caractère aléatoire
            $caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);
            // vérifier si le caractère est déjà utilisé dans $mdp
            if (!strstr($mdp, $caractere)) :
                // Si non, ajouter le caractère à $mdp et augmenter le compteur
                $mdp .= $caractere;
                $i++;
            endif ;
        endwhile ;
        //Ajouter un caractère spécial

        // prendre un caractère aléatoire
        $caractere2 = substr($obliger, mt_rand(0, strlen($obliger)-1), 1);
        // vérifier si le caractère est déjà utilisé dans $mdp
        if (!strstr($mdp, $caractere2)) :
            // Si non, ajouter le caractère à $mdp et augmenter le compteur
            $mdp .= $caractere2;
        endif ;
        // retourner le résultat final
        return $mdp.$suplement;
    }

    public static function countTableItem(String $table, String $cond = null): int {
        $condition = $cond == "" ? "" : " WHERE $cond " ;
        $table = self::getTableName($table);
        $query = "SELECT COUNT(*) AS nb FROM $table $condition";
        $res = self::arraySqlResult($query);
        return $res[0]['nb'];
    }

    public static function isOrdered(String $table, String $cond = null):boolean{
        $condition = $cond == "" ? "" : " WHERE $cond " ;
        $table = self::getTableName($table);
        $nbreElt = self::countTableItem($table,$cond);
        $query = self::arraySqlResult("SELECT rang FROM $table $condition ORDER BY rang DESC LIMIT 1");
        $rangmaxi = $query[0]['rang'];
        return $rangmaxi == $nbreElt;
    }

    public static function ordered(String $table, String $cond = null){
        $condition = $cond == "" ? "" : " WHERE $cond " ;
        $table = self::getTableName($table);
        $items = FunctionController::arraySqlResult("SELECT id, rang FROM $table $condition ORDER BY rang ASC ");

        DB::transaction(function () use ($table,$items,$condition,$cond){
            $i = 0;
            $rangmaxi = self::getRangMax($table,$cond);
            $rangmaxi2 = 2*$rangmaxi;
            DB::update(DB::raw("UPDATE $table SET rang = rang + $rangmaxi2 $condition"));
            foreach ($items as $item):
                $i++;
                DB::update(DB::raw("UPDATE $table SET rang = $i WHERE id = {$item['id']} "));
            endforeach;
        });
    }

    public static function getRangMax(String $table, String $cond = null):int{
        $condition = $cond == "" ? "" : " WHERE $cond " ;
        $table = self::getTableName($table);
        $query = self::arraySqlResult("SELECT rang FROM $table $condition ORDER BY rang DESC LIMIT 1");
        $rangmaxi = count ($query) ? $query[0]['rang'] : 0;
        return $rangmaxi ;
    }

    public static function move(String $table, String $itemId, String $direction, String $cond = null){
        $rangMax = self::getRangMax($table,$cond);
        $rang = FunctionController::arraySqlResult("SELECT id, rang FROM $table WHERE id = $itemId");
        //$rang = FunctionController::getChampTable($table,$itemId,"rang");
        $rang = $rang[0]['rang'];
        //dd($rang,$rangMax,$direction,$itemId);
        self::ordered($table,$cond);
        if ($direction == "up" and $rang == 1):
            return $rang;
        endif;
        if ($direction == "down" and $rang == $rangMax):
            return $rang;
        endif;

        $rangNew = $direction == "up" ?  $rang - 1 : $rang + 1;
        $req = FunctionController::arraySqlResult("SELECT id, rang FROM $table WHERE rang = $rangNew ");
        $idRangNew = $req[0]['id'];

        DB::transaction(function () use($table,$rang,$rangMax,$rangNew,$itemId,$idRangNew) {
            DB::update(DB::raw("UPDATE $table SET rang = $rangMax +1  WHERE id = $idRangNew"));
            DB::update(DB::raw("UPDATE $table SET rang = $rangNew WHERE id = $itemId"));
            DB::update(DB::raw("UPDATE $table SET rang = $rang WHERE id = $idRangNew"));
        });
    }

    public static function moveUpDown(String $table, int $itemId, int $rangNew, String $cond = null){
        $rangMax = self::getRangMax($table,$cond);
        $rang = FunctionController::arraySqlResult("SELECT id, rang FROM $table WHERE id = $itemId");
        $rang = $rang[0]['rang'];
        self::ordered($table,$cond);
        if ($rangNew > $rangMax):
            self::ordered($table,$cond);
            return $rang; 
        else:
            $req = FunctionController::arraySqlResult("SELECT id, rang FROM $table WHERE rang = $rangNew");
            $idRang = $req[0]['id'];

            DB::transaction(function () use($table,$rang,$rangMax,$rangNew,$itemId,$idRang) {
                DB::update(DB::raw("UPDATE $table SET rang = $rangMax +1  WHERE id = $idRang"));
                DB::update(DB::raw("UPDATE $table SET rang = $rangNew WHERE id = $itemId"));
                DB::update(DB::raw("UPDATE $table SET rang = $rang WHERE id = $idRang"));
            });
        endif;
    }

    public static function getRawTable($argumentTable,$categorie = "config"){
        $db = $categorie != "config" ? DbTablesHelper::dbTable($argumentTable,'dbtables') : DbTablesHelper::dbTable($argumentTable);
        return self::getTableName($db);
    }

    public static function archiveSqlErrors(int $code, String $message){
       /* $q = "SELECT * FROM
                        ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_SQL_ERRORS_ARCHIVES'))."
                         WHERE code = $code  ";

        $verif = FunctionController::arraySqlResult($q);
        dd($verif);
        if (!count($verif)):*/
            DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_SQL_ERRORS_ARCHIVES'))
                ->insert([
                   'code' => $code,
                   'message' => $message,
                ]);
        /*else:
            DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_SQL_ERRORS_ARCHIVES'))
                ->where('id', $verif[0]['id'])
                ->update([
                    'code' => $code,
                    'message' => $message,
                ]);
        endif;*/
    }

    public static function xEditable(string $table, string $column, string $pk, string $type,$texte) {
        $item = XeditableController::xEditableJS ($table,$column,$pk,$type,$texte) ;
        return $item;
    }

    public static function nbrows($table, $cond = '') {
        $sql = DB::select(DB::raw(
            "SELECT COUNT(*) as nbRow FROM  ".$table."  $cond "
        ));
        $nbRow = $sql[0]->nbRow;
        return $nbRow;
    }

    public static function writefilecode(string $file, string $ext):string {
        $imageext = Config::get("constantes.imageext");
        $audioext = Config::get("constantes.audioext");
        $videoext = Config::get("constantes.videoext");

        return view ("template.Html.fileCode", compact ('file','ext','imageext','audioext','videoext'))->render ();
    }

    public static function routeUpdate(string $route,array $id):string {
        $params = join (',',$id);
        return route($route,[$params]);
    }

    public static function UsersConnected():string {
        return UtilisateursController::UsersConnected ();
    }

    public static function numberUserConnected():int{
        return UtilisateursController::numberUserConnected ();
    }

    public static function formaterHeureExcelToHeureNormal($heure) {
        if (!is_double ($heure)):
            return $heure;
        endif;
        $heureBrut = $heure * 24 ;
        $h = floor($heureBrut) ;
        $min = intval (60 * ($heureBrut - $h)) ;
        return "{$h}:{$min}";
    }

    public static function formaterDateExcelToDateNormal($date) {
        if (!is_numeric ($date)):
            return $date;
        endif;
        $date = date("Y-m-d", mktime(0, 0, 0, 0, ($date - 36494), 0)) ;
        return  $date;
    }

    public static function getListeDesChamps(string $table){
        Session::forget("listeDeschampsTables");
        if (!Session::has("listeDeschampsTables.$table")):
            $champs = self::getFieldOfTable($table);
            $dataTableData = [];
            foreach ($champs AS $row):
                if (FunctionController::is_displayable ($table,$row['Field'])):
                    $dataTableData[] = $row['Field'];
                endif;
            endforeach;
            Session::put("listeDeschampsTables.$table", $dataTableData);
        endif;
        return Session::get("listeDeschampsTables.$table");
    }

    public static function quote(string $string):string {
        return DB::getPdo()->quote($string);
    }


    private static function getFils($parentid = array()) {
        $tab = array();
        $sql = "SELECT id FROM " .DbTablesHelper::dbTable('DBTBL_LOCALITES','db'). " WHERE parent IN (" . join(', ', $parentid) . ")";
        $resultatfils = FunctionController::arraySqlResult($sql);
        foreach ($resultatfils as $row) :
            $tab[] = $row['id'];
        endforeach;
        return $tab;
    }


    public static function getLignee($localiteid) {
        $tab[] = $localiteid;
        $lesfils = self::getFils($tab);
        $aunfils = count($lesfils) >= 1;
        while ($aunfils) :
            $tab = array_merge($tab, $lesfils);
            $lesfils = self::getFils($lesfils);
            $aunfils = count($lesfils) >= 1;
        endwhile;
        return $tab;
    }

    public static function getAilleux($id){
        $id0 = $id;
        $tab = [$id];
        while ($id0 != 1):
            $p = self::getParent($id0);
            $tab[] = $p;
            $id0 = $p;
        endwhile;
        return array_reverse($tab);
    }

    public static function getAilleuxBis($id){
        $p = self::getParent($id);
        if ($p == 0):
            return [$id];
        endif;
        if($p == 1):
            return [$p,$id];
        else:
            return array_merge(self::getAilleuxBis($p),[$id]);
        endif;
    }

    public static function getParent($id){
        if(Session::has("parent.$id")):
            return Session::get("parent.$id");
        else:
            $sql = "SELECT parent FROM " .DbTablesHelper::dbTable('DBTBL_LOCALITES','db'). " WHERE id = $id ";

            $parent = FunctionController::arraySqlResult($sql);
            if(count($parent)):
                Session::put("parent.$id",$parent[0]['parent']);
                return $parent[0]['parent'];
            else:
                return $id;
            endif;

        endif;
    }

    public static function formatDeLignee($tab){
        $data = [];
        foreach ($tab as $value):
            $data[] = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_LOCALITES','db'), $value);
        endforeach;
        $str = join('|',$data);
        return $str;
    }

    public static function getLigneeDeLocalite($id){
        $ailleux = self::getAilleuxBis($id);
        //unset($ailleux[array_search(1,$ailleux)]);
        $lignee = self::formatDeLignee($ailleux);
        return $lignee;
    }

    public static function getIconeHtml(int $iconeID){
        $icone = self::getChampTable (DbTablesHelper::dbTable ('DBTBL_ICONES'),$iconeID,"name");
        return "<i class='fa fa-$icone'></i>";
    }

    public static function getTableNameSansPrefixe(string $table):string {
        if (!Session::has("TableNameSansPrefixe")):
            Session::put("TableName", []);
        endif;
        if (!Session::has("TableNameSansPrefixe.$table")):
            self::getDatabaseTable();
            $pref = env("DB_PREFIX");
            $index = str_replace($pref, "", $table) ;
            Session::put("TableNameSansPrefixe.$table", $index);
        endif;
        return Session::get("TableNameSansPrefixe.$table");
    }

    public static function echoValue($column, $value, $table = NULL) {
        if(!self::isForeignKey($column)):
            if($column == "parent"):
                if(!$value):
                    return "" ;
                endif;
                return self::getColumnInTable($table, $value) ;
            endif;
            if($column == "code" && $table == "icones") :
                return "<i class=\"fa fa-$value\"></i>" ;
            endif;
            return $value ;
        endif;
        if($column == "icon"):
            $text = self::getColumnInTable("icons", $value, "code") ;
            return "<i class=\"fa fa-$text\"></i>" ;
        endif;
        return self::getColumnInTable($column."s", $value) ;
    }

}
