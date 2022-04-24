<?php

namespace App\Http\Middleware;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\Administration\AdminController;
use App\Http\Controllers\core\FunctionController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Demarrage
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //AdminController::generateSpeednews();
        if (!$request->ajax()):
            $this->chargementTables();
            $this->chargerColonnesModifiablesTables();
            $this->getListeRoutes();
        endif;
        //$this->getTableListeField();
        return $next($request);
    }

    public function chargementTables(){
        if(!Session::has("DatabaseTable")):
            $conn = DB::getDefaultConnection();
            $pref = config()->get("database.connections.$conn.prefix");
            $app_pref = env("APPLI_PREFIX");
            $listDesTables = [];
            $listDesTablesIndexees = [];
            $data = DB::select(DB::raw("SHOW TABLES"));
            $bd = config()->get("database.connections.$conn.database");
            $key = "Tables_in_".$bd."";
            foreach($data AS $row):
                $table = str_replace($pref, "", $row->$key) ;
                $index = str_replace($app_pref, "", $table);
                $listDesTables[] = $table;
                $listDesTablesIndexees[$index] = $table;
                //FunctionController::getFieldOfTable ($row->$key);
            endforeach;
            Session::put("DatabaseTable", $listDesTables);
            Session::put("DatabaseTableIndexees", $listDesTablesIndexees);
            $t = [];
            foreach ($listDesTables AS $a):
                $t[] = str_replace($app_pref, "", substr($a, 0, -1));
            endforeach;
            Session::put("foreignKey", $t);
        endif;
    }

    public function chargerColonnesModifiablesTables(){
        if (!Session::has("FieldDefaultParam")):
            $tabFieldDefault = [];
            $table = DbTablesHelper::dbTable('DBTBL_FIELDDEFAULTS');
            $table = FunctionController::getTableName($table);
            //dump($table);
            $re = FunctionController::arraySqlResult("SELECT * FROM $table");
            foreach ($re AS $v):
                $tabFieldDefault[$v["field"]] = $v;
            endforeach;
            Session::put("FieldDefaultParam", $tabFieldDefault);
        endif;
        if (!Session::has("TableFieldParam")):
            $tabFieldDefault = [];
            $tbl = DbTablesHelper::dbTable('DBTBL_FIELDPARAMS');
            $table = FunctionController::getTableName($tbl);

            $re = FunctionController::arraySqlResult("SELECT * FROM  $table");
            foreach ($re AS $v):
                $tabFieldDefault[FunctionController::getTableName($v["table"])] [$v["field"]] = ["display" => $v["display"], "inline" => $v["inline"]];
            endforeach;
            Session::put("TableFieldParam", $tabFieldDefault);
        endif;
    }

    private function getListeRoutes(){
        $routes = FunctionController::getRoutes();
        $this->insertNewRoute($routes);
    }

    private function insertNewRoute($routeCollections){
        $Tbl = DbTablesHelper::dbTable('DBTBL_ROUTES');
        $tblroute = FunctionController::getTableName($Tbl);
        //dd($tblroute,$Tbl,Session::get('TableName'));
        foreach($routeCollections as $key => $val):
            if($val->getName() != null && !preg_match("#debugbar#",$val->getName())):

                $prefix = isset($val->action["prefix"]) ? $val->action["prefix"] : "Aucun";
                $verifier = FunctionController::arraySqlResult("SELECT * FROM ".$tblroute." WHERE  name='{$val->getName()}' AND uri='{$val->uri}' AND prefix='{$prefix}'");

                if (count($verifier) == 0):
                    $controller = isset($val->action["controller"]) ? $val->action["controller"] : "";

                    $routeID = DB::table($Tbl)
                        ->insertGetId(
                            [
                                'name'       =>   $val->getName(),
                                'uri'    =>   $val->uri,
                                'namespace'    =>   $val->action["namespace"],
                                'prefix'    =>   $prefix,
                                'controller' => $controller
                            ]
                        );

                    if ($routeID):
                        $this->routeParametres($routeID);
                    endif;
                endif;
            endif;
        endforeach;
    }

    private function routeParametres($routeID){
        $laroute = DB::table(DbTablesHelper::dbTable("DBTBL_ROUTES"))
            ->select("id","uri","name")
            ->where('id',$routeID)
            ->get();
        $this->insertNewRouteParam($laroute);
    }

    private function insertNewRouteParam($route){

        $val = str_replace('/{',',',$route[0]->uri);
        $val = str_replace('}','',$val);
        $val = explode(',',$val);
        //dump($val);
        unset($val[0]);
        if(!empty($val)):
            $dir[$route[0]->name] = $val;
            $i = 1;
            foreach ($dir[$route[0]->name] as $value):
                DB::table(DbTablesHelper::dbTable('DBTBL_ROUTE_PARAMS'))
                    ->insert(
                        [
                            'route'     =>  $route[0]->id,
                            'parametre' =>  $value,
                            'numero'    =>  $i
                        ]
                    );
                $i++;
            endforeach;
        endif;
    }

    public function getTableListeField(){
        return FunctionController::getTableListeField();
    }
}
