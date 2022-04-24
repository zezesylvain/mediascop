<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 21/11/2017
 * Time: 02:14
 */

namespace App\Helpers;


use App\Http\Controllers\AppBases\DonneesDeBaseController;
use App\Http\Controllers\AppBases\DonneesDeBaseInterface;
use App\Http\Controllers\core\MenuMakerController;
use App\Http\Controllers\core\UtilisateursController;
use App\Http\Controllers\XeryaAdmin\AdminController;
use App\WoodyModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DbTablesHelper
{

   public function __construct()
   {

   }

    /**
     * @param $key La clé de la table dans le fichier de constante des tables
     * @param string $categorie La catégorie de table à choisir entre les tables de configuration(configtable) ou  celles de l'application (dbtables)
     * @return string retourne le nom de la table trouvée
     */

    public static function dbTable($key,$categorie = 'configtable'){
        $table = $categorie == 'configtable' ? Config::get("configtable.$key") : Config::get("dbtables.$key");
        return $table;
    }

    public static function dbTablePrefixeOff($key,$categorie = 'configtable'){
        $table = $categorie == 'configtable' ? Config::get("configtable.$key") : Config::get("dbtables.$key");
       // $pref = env("DB_PREFIX");
        $pref = Config::get("database.connections.mysql.prefix");
        $test = explode($pref, $table);
        $tabl = count($test) == 1 ? "$table" : trim(substr($table, mb_strlen($pref)));
        return $tabl;
    }

    /**
     * Methode qui utilise notre modèle général pour récupéré toute les lignes d'une table
     * @param $table La table
     * @return \Illuminate\Support\Collection
     */
    public static function getDatasAll($table){
        $DB = new WoodyModel();
        $DB->setTable($table);
        $resultat = $DB->get();
        return $resultat;
    }

    /**
     * Methode qui utilise notre modèle général pour récupéré une ligne de la table
     * @param $table la table
     * @param $id l'identifiant de la table
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public static function getDataById($table,$id){
        $DB = new WoodyModel();
        $DB->setTable($table);
        $resultat = $DB->findOrFail($id);
        return $resultat;
    }

    public static function getDatasByFields($table,$field=[],$get = []){
        $DB = new WoodyModel();
        $DB->setTable($table);
        $gets = count($get) ? $get : ["*"];
        $resultat = $DB->where($field)->get($gets);
        return $resultat;
    }

    public static function dbraw($sql)
    {
        return DB::select(DB::raw($sql));
    }

    public static function getDbTableConfig(){
        if (!Session::has('DatabaseTableConfig')):
            $pref = env("DB_PREFIX");
            $applipref = env("APPLI_PREFIX");
            $listDesTables = [];
            $data = DB::select(DB::raw("SHOW TABLES"));
            $bd = env("DB_DATABASE");
            $key = "Tables_in_$bd";
            foreach ($data AS $row) :
                $str = explode('_',$row->$key);
                if (in_array(str_replace("_","",$applipref),$str)):
                    $pp = str_replace($applipref, "", $row->$key);
                    $listDesTables[] = str_replace($pref, "", $pp);
                endif;
            endforeach;
            Session::put("DatabaseTableConfig", $listDesTables);
           // dd($listDesTables,Session::get('DatabaseTableConfig'));
        endif;
        return Session::get('DatabaseTableConfig');
    }

    public static function trouverMaChaine($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    public static function formatTableHeader($valeur){
        return ucfirst(str_replace('_', ' ', $valeur));
    }

    public static function formatTitle($valeur){
        $prefix = env('DB_PREFIX');
        return ucfirst(str_replace('_', ' ', substr($valeur,strlen($prefix))));
    }

    public static function titreHtml($title,$level=3,$style="",$fa=""){
        return view("template.Html.titre",compact('title','style','fa','level'))->render();
    }

    public static function msg($message,$alerte="info",$dim="sm-12"){
        return view("template.Html.Message",compact('message','alerte','dim'))->render();
    }

    public static function makeFormModule(){
        $modulesUser = UtilisateursController::modulesUser();
        if (count($modulesUser)) {
            $moduleCourant = MenuMakerController::getModuleCourant();
            $moduleTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MODULES'));
            return view("administration.utilisateurs.formModules", compact('modulesUser','moduleCourant','moduleTable'));
        }
    }

    public static function changePeriodeClasse($periode){
        $periodeCourant = Session::get('periode');
        $ecoleID = AdminController::getEcole ();
        $anneeID = AdminController::getAnneeCourant ();
        if ($periode == Session::get('periode')):
            Session::forget("PeriodeClasse");
            $lesclasses = AdminController::chercherToutesLesClasses($ecoleID,$anneeID);
            foreach ($lesclasses as $lesclass):
                AdminController::getPeriodeClasse($lesclass['id']);
            endforeach;
        endif;
        //dump($periode,$periodeCourant,Session::get("PeriodeClasse"));
    }
}
