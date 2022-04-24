<?php

namespace App\Http\Controllers\core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class XeditableController extends Controller
{
    public function __construct ()
    {
    }

    /*
     * Les types de champs de xEditable:
     *  text, select, date, combodate,datetime, textearea,checklist,select2,
     * */

    /**
     * @param string $table
     * @param string $column
     * @param int $pk
     * @param string $type
     * @param  $texte
     * @param string $cpt
     */

    public static function xEditableJS(string $table, string $column, int $pk, string $type,$texte,$cpt = ''): string{
        $source = "";
        if ($type == "select"):
             $source = self::getSource($column,$type);
        endif;
        return view ("template.Interface.xEditable", compact ('column','pk','type','table','texte','source','cpt'))->render ();
    }
    
    public static function getXeditableTableSession($table){
        if(!Session::has ("x-edit-table")):
            Session::put ("x-edit-table", []);
        endif;
        if(!Session::has ("x-edit-table.$table")):
            Session::put ("x-edit-table.$table", $table);
        endif;
    }
    
    public function xEditableUpdate(Request $request){
        if ($request->ajax ()):
            $table = $request->get ("table");
            $set = !is_numeric ($request->value) ? addslashes ($request->value) : $request->value;
            DB::select (DB::raw ("UPDATE $table SET {$request->input ("name")} = '{$set}' WHERE id = {$request->input('pk')}"));
        endif;
    }

    public function updateDatas(Request $request){
        $table = FunctionController::getTableName ($request->table);
        $pk = $request->pk;
        $ligne = FunctionController::arraySqlResult ("SELECT * FROM $table WHERE id = $pk");
        $champsTable = FunctionController::getFieldOfTable ($table);
        //dd ($request->all (),$ligne,$champsTable);
        return view ("administration.template.Interface.formUpdateXeditable", compact ('table','pk','ligne','champsTable'))->render ();
    }

    public static function getColumnType(string $table, string $column):array {
        $table = FunctionController::getTableName ($table);
        $tableFields = FunctionController::getFieldOfTable ($table);
        $typeOptions = [];
        foreach ($tableFields as $field):
            if ($column != 'couleur'):
                if ($field['Field'] == $column):
                    $t = explode ('(',$field['Type']);
                    $typeOptions = self::definirOptionsColumns($t[0]);
                endif;
            else:
                $typeOptions = self::definirOptionsColumns("couleur");
            endif;
        endforeach;
        return $typeOptions;
    }

    public static function definirOptionsColumns(string $string):array {
        $string = strtolower ($string);
        switch ($string):
            case 'varchar':
                $type = 'text';
                $dateViewFormat = '';
                $dateFormat = '';
                break;
            case 'int':
                $type = "select";
                $dateViewFormat = '';
                $dateFormat = '';
                break;
            case 'date':
                $type = "date";
                $dateFormat = 'yyyy-mm-dd';
                $dateViewFormat = 'dd/mm/yyyy';
                break;
            case 'datetime':
                $type = "datetime";
                $dateFormat = 'yyyy-mm-dd hh:ii';
                $dateViewFormat = 'dd/mm/yyyy hh:ii';
                break;
            case 'timestamp':
                $type = "datetime";
                $dateFormat = 'yyyy-mm-dd hh:ii:ss';
                $dateViewFormat = 'dd/mm/yyyy hh:ii:ss';
                break;
            case 'couleur':
                $type = "couleur";
                $dateFormat = '';
                $dateViewFormat = '';
                break;
            default:
                $type = "text";
                $dateViewFormat = '';
                $dateFormat = '';
                break;
        endswitch;
        $options = [
            'type' => $type,
            'dateFormat' => $dateFormat,
            'dateViewFormat' => $dateViewFormat,
            ];
        return $options;
    }

    public static function getSource(string $column, string $type) {
        if ($type == "select"):
             $tablesIndexees = Session::get ('DatabaseTableIndexees');
            $source = '';
            if (FunctionController::is_foreignKey ($column)):
                $tablek = $column.'s';
                $table = $tablesIndexees[$tablek];
                $table = FunctionController::getTableName ($table);
                $columns = FunctionController::getListeDesChamps ($table);
                $clms = "";
                $clms .= in_array ('id',$columns) ? "id" : "";
                $clms .= in_array ('name',$columns) ? ",name" : "";
                $clms .= in_array ('title',$columns) ? ",title" : "";
                $datas = FunctionController::arraySqlResult ("SELECT $clms FROM $table ORDER BY id");
                $source = self::makeJsonFile ($datas);
            endif;
            return $source;
        endif;
    }

    public static function makeJsonFile(array $result):string {
        $virg = '';
        $Bigdata = '';

        foreach($result as $row) :
            $value = $row['id'];
            if (array_key_exists ('name',$row)):
                $text = $row['name'];
            elseif (array_key_exists ('title',$row)):
                $text = $row['title'];
            else:
                $text = $row['id'];
            endif;
            $Bigdata.=$virg."{value:".$value.",text:'".addslashes ($text)."'}";
            $virg = ',';
        endforeach;
        return $Bigdata;
    }

    public static function getDateViewFormat(string $type){
        if ($type == "date"):
            return "dd/mm/yyyy";
        endif;
        if ($type == "datetime"):
            return "dd/mm/yyyy hh:ii";
        endif;
    }
    public static function getDateFormat(string $typeOrigin, string $format){
        if ($typeOrigin == "timestamp"):
            return "dd/mm/yyyy hh:ii";
        endif;
        return $format;
    }
}
