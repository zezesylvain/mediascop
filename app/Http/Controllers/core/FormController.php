<?php

namespace App\Http\Controllers\core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Support\Facades\DB;

class FormController extends Controller {

    //

    public static function champInput($champ, $type = 0, $value = "") {
        $typeInput = 'text';
        $require = "";
        $placeholder = "";
        if ($type == "email"):
            $typeInput = 'email';
        endif;
        if ($type == "password"):
            $typeInput = 'password';
        endif;
        $require = $champ == 'name' ? 'required' : $require;
        $require = $champ == 'credibilite' ? 'required' : $require;
        $placeholder = $champ == 'credibilite' ? "Trois(3) Chiffres de 0 à 100" : $placeholder;
        //dump($require);
        $libel = strtoupper($champ);
        $form = view('admin.html.champInput', compact('libel', 'champ', 'typeInput', 'value', 'require', 'placeholder'));
        return $form;
    }

    public static function champCleEtrangere($champ, $value = "", $col = null) {
        $libel = strtoupper($champ);
        //construction de formulaires de listes deroulantes
        $col = is_null($col) ? " id, name " : $col;
        $table = $champ . 's';
        //$errors = new ();
        if ($champ == "annonceur"):
            $col = " id,name ";
        elseif ($champ == "rapport"):
            $col = " id,titre AS name";
        elseif (in_array($champ, ['user', 'role', 'profil', 'groupemenu', 'autorisation', 'route'])):
            $table = 'woo_' . $champ . 's';
        endif;
        $sql = "SELECT $col FROM zdsb_" . $table . " ";
        $requete = WooDatabaseController::dbraw($sql);
        if (!empty($requete)):
            $form = view('admin.html.champCleEtrangere', compact('libel', 'champ', 'requete', 'value'))->render();
        else:
            Session::flash("echec", "La table $table n'a aucune ligne !");
            $form = "<div class ='col-xs-6 alert alert-danger'><a href='" . \route('ajouter', $table) . "' class='btn btn-link'><i class ='fa fa-hand-o-right'></i> <strong>Ajouter des données à la table fille $table !</strong></a></div>";
        endif;
        return $form;
    }

    public static function champFile($champ, $value = "") {
        $libel = strtoupper($champ);
        $form = "<div class=\"form-group col-xs-6\" >
    <label for=\" \">$libel</label>
    <input name=\"$champ\" size=\"10\"  type=\"file\" class=\"form-control\"   value=\"$value\">
</div>";
        return $form;
    }

    public static function champDate($champ, $value = "") {
        $libel = strtoupper($champ);
        $date = date("d/m/Y");
        $tabdate = explode('/', $date);
        $datejour = date("Y-m-d");
        $form = view('admin.html.champDate', compact('libel', 'champ', 'tabdate', 'datejour', 'value'))->render();
        return $form;
    }

    public static function champEnum($champ, $type, $value = "") {
        $str1 = substr($type, 5, -1);
        $str = explode(',', $str1);
        $libel = strtoupper($champ);
        $form = view('admin.html.champEnum', compact('libel', 'champ', 'type', 'str', 'value'));
        return $form;
    }

    public static function champText($champ, $type, $value = "") {
        $libel = strtoupper($champ);
        $form = "";
        $editeur = 'textedit';
        if ($type == 'text'):
            $form .= view('admin.html.champText', compact('libel', 'champ', 'type', 'editeur', 'value'));
        endif;
        if ($type == 'longtext'):
            $editeur = '';
            $form .= view('admin.html.champText', compact('libel', 'champ', 'type', 'editeur', 'value'));
        endif;
        return $form;
    }

    public static function champSubmit($text = " Soumettre ",$col = 12,$btn = "info") {
        return <<<ZDS
                    <div class="col-xs-$col col-sm-$col col-md-$col col-lg-$col form-group button-ap-list" style="margin-top: 10px;">
            <div class="button-ap-list responsive-btn">
           
                    <button id="Vform" class="btn btn-lg btn-block btn-$btn btn-3d active" type="submit">$text</button>
             
            </div>
        </div>
ZDS;
    }

    public static function formOption($database, $type = 'select', $id = 0, $name = 'name', $formname = '', $cond = '', $order = 'name', $pre = '', $suf = '') {
        switch ($type) :
            case 'checkbox' :
                $html = self::checkBox($database, $name, $formname, $cond, $order, $pre, $suf);
                break;
            case 'radio' :
                $html = self::radioBox($database, $id, $name, $formname, $cond, $order, $pre, $suf);
                break;
            default :
                $html = self::selectBox($database, $id, $name, $cond, $order, $pre, $suf);
        endswitch; // switch ($type) :
        return $html;
    }

    public static function selectBox($database, $id = 0, $name = 'name', $cond = '', $order = '', $pre = '', $suf = ''){
        $html = '';
        $orderby = $order == "" ? "ORDER BY $name ASC " : "ORDER BY $order ASC";
        $sqlq = DB::select(DB::raw("SELECT id, $name FROM $database $cond $orderby"));
        $inc = 0;
        foreach ($sqlq as $row) :
            $inc++;
            $selected = $id == $row->id ? ' selected="selected"' : '';
            $html .= '
                <option value="' . $row->id . '"' . $selected . '> ' . $pre . ' ' . $row->$name . ' ' . $suf . '</option>';
        endforeach;
        return $html;
    }

    public static function radioBox($database, $id = 0, $name = 'name', $formname = '', $cond = '', $order = 'name', $pre = '', $suf = '') {
        $html = '';
        $sqlq = " SELECT * FROM $database $cond ORDER BY $order ASC";
        $dt = DB::select(DB::raw($sqlq));
        $inc = 0;
        foreach ($dt as $row) :
            $inc++;
            $checked = $id == $row->id ? ' checked="yes"' : '';
            $html .= '
                 <input type="radio" name="' . $formname . '" value="' . $row->id . '" ' . $checked . '> ' . $pre . ' ' . $row->$name . ' ' . $suf;
        endforeach; //
        return $html;
    }

    public static function checkBox($database, $name = 'name', $formname = '', $cond = '', $order = 'name', $pre = '', $suf = '') {
        $html = '';
        $sqlq = " SELECT * FROM $database $cond ORDER BY $order ASC";
        $dt = DB::select(DB::raw($sqlq));
        $inc = 0;
        foreach ($dt as $row) :
            $inc++;
            $html .= '
              <br /><input type="checkbox" id="' . $formname . '[' . $inc . ']" name="' . $formname . '[]" value="' . $row->id . '"> ' . $pre . ' ' . $row->$name . ' ' . $suf;
        endforeach; //
        return $html;
    }

}
