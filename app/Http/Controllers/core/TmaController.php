<?php

namespace App\Http\Controllers\core;

use App\Helpers\DbTablesHelper;
use App\Models\GroupeMenu;
use App\Models\Menu;
use App\Models\Profil;
use App\Models\ProfilRole;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TmaController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }


    public function fieldDefaultParam(){
        $tables = FunctionController::getDatabaseTable();
        return view("template.Tma.fieldParamForm", compact('tables'));
    }

    public static function fieldDefault(){
        return view("template.Tma.formFieldDefault")->render();
    }


    public function storeFieldDefault(Request $request){
        $datas = $request->all();

        unset($datas['_token']);
        $insert = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_FIELDDEFAULTS'))->insert($datas);
        if ($insert):
            Session::flash('success', 'Données inserées avec success');
        else:
            Session::flash('echec', 'Une erreur est survenue !');
        endif;

        return back();
    }


    public function getChampsTable(Request $request){
        $action = $request->input("table");
        if ($action != "Toutes les tables"):
            $table = env("DB_PREFIX").$action;
            return self::treatChampsTable($table);
        else:
            return self::fieldDefault();
        endif;
    }

    public static function treatChampsTable($table){
        $datab = FunctionController::arraySqlResult("SHOW COLUMNS FROM $table");
        $tableau = '';
        $r = route('ajax.traitementChampsParam');
        if (count($datab)):
            $tableau .= '<table class="table table-bordered table-responsive table-striped">
<tr>
    <th>CHAMPS</th>
    <th>DISPLAY</th>
    <th>INLINE</th>
    <th></th>
</tr>';
            foreach ($datab as $row):
                if ($row['Field'] != "id"):
                    $displayable = FunctionController::is_displayable($table,$row['Field']) ? "checked='checked'" : "";
                    $inlineyable = FunctionController::is_inlinable($table,$row['Field']) ? "checked='checked'" : "";
                    //dump($displayable,$inlineyable);
                    $tableau .= "<tr>
                            <td>{$row['Field']}</td>
                            <td>
                                 <input type='checkbox' name='' $displayable onclick='sendData(\"table={$table}&field={$row['Field']}&action=display&etat=\"+this.checked,\"{$r}\",\"tableItem\")'>
                            </td>
                            <td>
                                 <input type='checkbox' name='' $inlineyable   onclick='sendData(\"table={$table}&field={$row['Field']}&action=inline&etat=\"+this.checked,\"{$r}\",\"tableItem\")'>
                            </td>
                            <td></td>
</tr>                                ";

                endif;
            endforeach;
            $tableau .= '</table>';
            //dd();
        endif;
        return $tableau;
    }


    public function traitementChampsParam(Request $request){
        $tbl = substr($request->input('table'),strlen(env('DB_PREFIX')));
        $table = $tbl;
        $field = $request->input('field');
        $etat = $request->input('etat');
        $action = $request->input('action');

        $verif = DbTablesHelper::getDatasByFields(DbTablesHelper::dbTablePrefixeOff('DBTBL_FIELDPARAMS'),['field' => $field, 'table' => $table]);
        //dd($verif,$request->input('table'));

        if ($verif->count()):
            if ($action == 'display'):
                $var = $etat == 'true'? 1 : 0;
                $champs = "display";
            endif;

            if ($action == 'inline'):
                $var = $etat == 'true'? 1 : 0;
                $champs = "inline";
            endif;

            if (isset($var) && isset($champs)):
                DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_FIELDPARAMS'))
                    ->where('id','=',$verif[0]->id)
                    ->update(
                        [
                            $champs => $var
                        ]
                    );
            endif;
        endif;
        if ($verif->count() == 0):
            if ($action == 'display'):
                $var = $etat == 'true'? 1 : 0;
                $champs = "display";
            endif;

            if ($action == 'inline'):
                $var = $etat == 'true'? 1 : 0;
                $champs = "inline";
            endif;

            if (isset($var) && isset($champs)):
                DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_FIELDPARAMS'))
                    ->insert(
                        [
                            $champs => $var,
                            'table' => $table,
                            'field' => $field,
                        ]
                    );
            endif;
        endif;

        $tabFieldDefault = [];
        $tbl = DbTablesHelper::dbTable('DBTBL_FIELDPARAMS');
        $table = FunctionController::getTableName($tbl);

        $re = FunctionController::arraySqlResult("SELECT * FROM  $table");
        foreach ($re AS $v):
            $tabFieldDefault[FunctionController::getTableName($v["table"])] [$v["field"]] = ["display" => $v["display"], "inline" => $v["inline"]];
        endforeach;
        Session::put("TableFieldParam", $tabFieldDefault);

        return self::treatChampsTable($request->input('table'));
    }

    public function gestionDesRoles(){
        $tblroute = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROUTES'));
        $tblrole = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
        $listeRoles = ModuleController::makeTable($tblrole);
        $routes = FunctionController::arraySqlResult("SELECT * FROM $tblroute WHERE est_role = 1 ORDER BY id ASC");
        return view("template.Tma.formRoles",compact('routes','listeRoles'));
    }

    public function gestionDesRoutes(){
        $databaseTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROUTES'));
        //$routes = ModuleController::makeForm($tbl);
        $dataTableData = FunctionController::arraySqlResult("SELECT * FROM $databaseTable ORDER BY NAME ASC ");
        return view("template.Tma.formRoutes",compact('dataTableData','databaseTable'));
    }

    public function makeFormRole(Request $request){
        $routeID = $request->input('route');
        $tblroute = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROUTES'));
        $trim = trim($routeID);
        $exp = explode('---',$trim);
        $laroute = FunctionController::arraySqlResult("SELECT * FROM $tblroute WHERE id={$exp[0]}");
        if (count($laroute)):
            $routeParam = DB::table(DbTablesHelper::dbTable('DBTBL_ROUTE_PARAMS'))
                ->where('route', $laroute[0]['id'])
                ->get();
            $roleExist = DB::table(DbTablesHelper::dbTable('DBTBL_ROLES'))
                ->where('route', $laroute[0]['id'])
                ->get();
            return view("template.Tma.formRoleStep2",compact('laroute','routeParam','roleExist'))->render();
        else:
            return FunctionController::sendMessageFlash('danger','Cette route n\'existe pas!');
        endif;
    }

    public function traiterFormRole(Request $request){
        $data = $request->all();
        $laroute = DB::table(DbTablesHelper::dbTable('DBTBL_ROUTES'))
            ->where('id', $data['routeID'])
            ->get();

        $valeurRoute = $laroute[0]->uri;
        $pattern = '#{.*?}#';
        preg_match_all($pattern, $valeurRoute, $pregmacht);
        $patt = array();
        $t = $pregmacht[0];
        foreach ($t as $key => $item):
            $patt[] = "/$item/";
        endforeach;
        $i = 1;
        $replacement = [];
        $placement = [];
        foreach ($data as $key => $datum):
            if($key == "url-$i"):
                $replacement[] = "$datum";
                $placement[] = "";
                $i++;
            endif;
        endforeach;
        //$url = preg_replace($patt,$replacement,$valeurRoute);
        $parametres = count($replacement) ? join('/',$replacement) : "-";
        $urii = preg_replace($patt,$placement,$valeurRoute);
        $uri = explode("//",$urii);
       // dd($data,$parametres);
        $verif = DB::table(DbTablesHelper::dbTable('DBTBL_ROLES'))
            ->where('route', $data['routeID'])
            ->where('name', $data['name'])
            ->get();
        if(count($verif) == 0):
            $newRole = DB::table(DbTablesHelper::dbTable('DBTBL_ROLES'))
                ->insert(
                    [
                        'route' => $data['routeID'],
                        'name' => $data['name'],
                        'uri' => ''.$uri[0].'',
                        'parametres' => ''.$parametres.'',
                    ]
                );
            if($newRole):
                FunctionController::refreshRoles();
                Session::flash('success', 'Role enregistré!');
            else:
                Session::flash('echec', 'Une erreur est survenue, Veuillez recommencer!');
            endif;
        else:
            Session::flash('echec', 'Ce role existe déjas !');
        endif;

        return back();
    }

    public static function estRole(Request $request){
        $estRole = $request->input('estRole');
        $routeID = $request->input('routeID');
        $val = $estRole == "true" ? 1 : 0;

        $action = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_ROUTES'))
            ->where('id',$routeID)
            ->update(
                [
                    'est_role' => $val,
                ]
            );
        if ($val == 1 && $action):
            return self::makeFormRoleItem($routeID);
        elseif ($val == 0 && $action):
            return "";
        endif;
    }

    public function traiterFormProfil(Request $request){
        $datas = $request->all();
        unset($datas['_token']);
        if (!array_key_exists('pid',$datas)):
            $ver =  DB::table(DbTablesHelper::dbTable('DBTBL_PROFILS'))
                ->where('name', $datas['name'])
                ->where('level', $datas['level'])
                ->get();
            if (!count($ver)):
                $insert = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PROFILS'))
                    ->insert($datas);
                if ($insert):
                    Session::flash("success","Profil enregistré !");
                else:
                    Session::flash("echec", "Une erreur est survenu, veuillez recommencer !");
                endif;
            else:
                Session::flash("echec","Le Profil {$datas['name']} existe déjas !");
            endif;
        endif;

        if (array_key_exists('pid',$datas)):
            $pid = $request->input('pid');
            unset($datas['pid']);
            $update = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PROFILS'))
                ->where('id',$pid)
                ->insert($datas);
            if ($update):
                Session::flash("success","La mise à jour du profil s'est bien déroulée !");
            else:
                Session::flash("echec", "Une erreur est survenu, veuillez recommencer !");
            endif;
        endif;
        return back();
    }

    public function makeProfilRole(int $profilID)
    {
        $userProfil = Auth::user()->profil;
        $userLevel = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_PROFILS'),$userProfil,'level');
        $profils = Profil::where('level','<=',$userLevel)->orderBy('name')->get()->toArray();
        $profilroles = ProfilRole::where('profil',$profilID)->get()->toArray();
        $groupeMenus = GroupeMenu::orderBy('id')->get()->toArray();
        $menus = Menu::orderBy('rang')->get()->toArray();
        $men = [];
        $lesRole = [];
        foreach ($profilroles as $r) :
            $lesRole[] = $r['role'];
        endforeach;
        foreach ($menus as $menu):
            foreach ($groupeMenus as $groupeMenu):
                if ($groupeMenu['id'] === (int)$menu['groupemenu']):
                    if (in_array((int)$menu['role'],$lesRole)):
                        $men[$groupeMenu['id']][] = [
                            'role' => $menu['role'],
                            'name' => FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ROLES'),$menu['role']),
                            'check' => true,
                        ];
                    else:
                        $men[$groupeMenu['id']][] = [
                            'role' => $menu['role'],
                            'name' => FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ROLES'),$menu['role']),
                        ];
                    endif;
                endif;
            endforeach;
        endforeach;
        $route = route('ajax.profilRole');
        return view('template.Tma.gestionDesRoles',compact('men','profils','profilID','route'));
    }

    public function profilRole(Request $request){
        $key = $request->input('key');
        if ($key == 1):
            $profilId = $request->get('profil') ;
            $roleId = $request->get('role') ;
            $bool = $request->get('bool');
            if($profilId != 0 && $roleId != 0 ):
                if($bool == "true"):
                    $data = array('profil'=>$profilId,
                        'role' =>  $roleId) ;
                    DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PROFILROLES'))
                        ->insert($data);
                else:
                    DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PROFILROLES'))
                        ->where([
                            'role' => $roleId,
                            'profil' => $profilId,
                        ])
                        ->delete();
                endif;
            endif;
        endif;

        if ($key == 2):
            $profil = $request->get('profil');
            if($profil == 0):
                $profil = filter_input(INPUT_GET, 'profil') ;
            endif;
            $lesroles = $request->get('role') ;
            $bool = $request->get('bool') ;
            $roles = explode('-', $lesroles) ;
            if($bool=='true'):
                $row = array('profil' => $profil);
                foreach ($roles as $v) :
                    $row['role'] = $v ;
                     DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PROFILROLES'))
                        ->insert($row);
                endforeach;
            else:
                DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PROFILROLES'))
                    ->where([
                        'profil' => $profil
                    ])
                    ->delete();
            endif;
        endif;
    }

    public function gestionDuMenus(){
        $tableauMenu = MenuMakerController::makeMenuTable();
        $grpMenus = FunctionController::arraySqlResult("SELECT * FROM 
          ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'))."
           WHERE deleted = 1 ORDER BY rang ASC");
        return view("template.Tma.formMenu",compact('tableauMenu','grpMenus'));
    }

    public function gestionGroupeDeMenu(){
        $tableauGrpMenu = MenuMakerController::makeGroupeMenuTable();
        $icones = FunctionController::arraySqlResult("SELECT * FROM 
          ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ICONES'))."  ORDER BY name ASC");
        return view("template.Tma.formGrpMenu",compact('tableauGrpMenu','icones'));
    }

    public function modifierMenu($menuID){
        $tableauMenu = MenuMakerController::makeMenuTable();
        $tablMenus = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
        $tablRoles = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));

        $lemenu = FunctionController::arraySqlResult("SELECT * FROM 
          ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'))."
           WHERE id = {$menuID}");
        $lemenu = $lemenu[0];
        $grpMenus = FunctionController::arraySqlResult("SELECT * FROM 
          ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'))."
           WHERE deleted = 1 ORDER BY rang ASC");
        $tMenuTarget = FunctionController::getTableName(DbTablesHelper::dbTable('DTBL_MENU_TARGETS'));
        $targetMenu = FunctionController::arraySqlResult("SELECT * FROM $tMenuTarget");
    
        $roles =  FunctionController::arraySqlResult("SELECT * FROM 
                  {$tablRoles} 
                  WHERE id NOT IN (SELECT role FROM {$tablMenus} WHERE id NOT IN ($menuID))");

        return view("template.Tma.formMenu",compact('tableauMenu','grpMenus','lemenu','roles','targetMenu'));
    }

    public function modifierGrpMenu($grpMenuID){
        $tableauGrpMenu = MenuMakerController::makeGroupeMenuTable();
        $tablGrpMenus = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'));

        $grpMenus = FunctionController::arraySqlResult("SELECT * FROM 
          $tablGrpMenus 
           WHERE id = $grpMenuID AND deleted = 1 ORDER BY rang ASC");
        $datas = $grpMenus[0];
        $icones = FunctionController::arraySqlResult("SELECT * FROM
          ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ICONES'))."  ORDER BY name ASC");
        return view("template.Tma.formGrpMenu",compact('tableauGrpMenu','icones','datas'));
    }

    public function storeMenu(Request $request){
        $datas = $request->all();
        unset($datas['_token']);
        if (!array_key_exists('pid',$datas)):
            $menuID = DB::table(DbTablesHelper::dbTable('DBTBL_MENUS'))
                ->insertGetId($datas);
            if ($menuID):
                $roleTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
                $routeID = FunctionController::getChampTable($roleTable,$datas['role'],'route');
                self::ajouterRouteDansGroupeMenu($routeID,$menuID);
                Session::flash('success', "La création du menu s'est bien déroulé!");
            else:
                Session::flash('echec', "Une erreur s'est produite, veuillez récommencer!");
            endif;
            return back();
        else:
            $tablMenus = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
            $idMenu = $datas['pid'];
            $grpMenusOld = FunctionController::getChampTable($tablMenus,$idMenu,'groupemenu');
            unset($datas['pid']);
            $updated = DB::table(DbTablesHelper::dbTable('DBTBL_MENUS'))
                ->where('id',$idMenu)
                ->update($datas);
            if ($updated):
                if ($grpMenusOld != $datas['groupemenu']):
                    self::OrdonnerMenuASC($grpMenusOld);
                    $roleTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
                    $routeID = FunctionController::getChampTable($roleTable,$datas['role'],'route');
                    DB::table(DbTablesHelper::dbTable('DBTBL_GROUPEMENU_ROUTES'))
                        ->where([
                            'route' => $routeID,
                        ])
                        ->update(
                            [
                                'groupemenu' => $datas['groupemenu'],
                            ]
                        );
                endif;
                Session::flash('success', "La Mise à jour du menu s'est bien déroulé!");
                return redirect(route('gestionDuMenus'));
            else:
                Session::flash('echec', "Une erreur s'est produite, veuillez récommencer!");
                return back();
            endif;
        endif;
    }

    public function storeGrpMenu(Request $request){
        $datas = $request->all();
        unset($datas['_token']);
        if (!array_key_exists('pid',$datas)):
            $grpMenus = FunctionController::arraySqlResult("SELECT * FROM 
          ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'))."
           WHERE deleted = 1 ORDER BY rang DESC LIMIT 1");
            $datas['rang'] = $grpMenus[0]['rang'] + 1;
            $insert = DB::table(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'))
                ->insert($datas);
            if ($insert):
                Session::flash('success', "La création du groupe de menu s'est bien déroulé!");
            else:
                Session::flash('echec', "Une erreur s'est produite, veuillez récommencer!");
            endif;
            return back();
        else:
            $idGrpMenu = $datas['pid'];
            unset($datas['pid']);
            $updated = DB::table(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'))
                ->where('id',$idGrpMenu)
                ->update($datas);
            if ($updated):
                Session::flash('success', "La Mise à jour du groupe de menu s'est bien déroulé!");
                return redirect(route('gestionGroupeDeMenu'));
            else:
                Session::flash('echec', "Une erreur s'est produite, veuillez récommencer!");
                return back();
            endif;
        endif;
    }


    public function getFormMenu(Request $request){
        $grpMenu = $request->input('grpmenu');
        if (array_key_exists('v',$request->all()) && $request->input('v') == 'modif'):
            $grpMenuOld = $request->input('grpmenuOld');
            if ($grpMenu != $grpMenuOld):
                $menus = FunctionController::arraySqlResult("SELECT id  FROM 
                      ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'))." 
                      WHERE groupemenu = {$grpMenu} ORDER BY rang ASC");

                $rangSuiv = count($menus) + 1;
                //dump($menus,$rangSuiv);
                return <<<FOR
<input type="hidden" value="$rangSuiv" name="rang">
FOR;
            else:
                return '';
            endif;
        endif;
        if (!is_null($grpMenu)):
            $tMenuTarget = FunctionController::getTableName(DbTablesHelper::dbTable('DTBL_MENU_TARGETS'));
            $targetMenu = FunctionController::arraySqlResult("SELECT * FROM $tMenuTarget");
            if (array_key_exists('action',$request->all())):
                $tablMenus = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
                $tablRoles = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
               // self::OrdonnerMenuASC($grpMenu);
                $menus = FunctionController::arraySqlResult("SELECT id FROM 
              $tablMenus 
              WHERE groupemenu = {$grpMenu} ");
                $rangSuiv = count($menus) + 1;
                $roleID = $request->role;
                return view("template.Tma.formMenuAjaxItem",compact('roleID','rangSuiv','targetMenu'))->render();
            else:
                $tablMenus = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
                $tablRoles = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
                self::OrdonnerMenuASC($grpMenu);
                $menus = FunctionController::arraySqlResult("SELECT id FROM 
              $tablMenus 
              WHERE groupemenu = {$grpMenu} ");
                $rangSuiv = count($menus) + 1;
                $roles =  FunctionController::arraySqlResult("SELECT * FROM 
                  {$tablRoles} 
                  WHERE id NOT IN (SELECT role FROM {$tablMenus})");
                return view("template.Tma.formMenuAjax",compact('roles','rangSuiv','targetMenu'))->render();
            endif;
        endif;
    }

    public static function OrdonnerMenuASC($grpMenu){
        $tablMenus = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
        FunctionController::ordered($tablMenus,"groupemenu = $grpMenu ");
    }

    public function ordonnerMenu(Request $request){
        $groupemenu = $request->get( 'groupemenu');
        $menuid = $request->get( 'menuid');
        $rgmenu = $request->get( 'rang');
        $direction = $request->get( 'direction');
        $tablMenus = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
        FunctionController::ordered($tablMenus," groupemenu=$groupemenu");

        $cond = $direction == "ASC" ? " rang > $rgmenu" : " rang < $rgmenu";
        
        $sql = "SELECT id, rang FROM $tablMenus WHERE groupemenu = $groupemenu AND $cond ORDER BY rang $direction LIMIT 1";
        $res = FunctionController::arraySqlResult($sql);
        $rang0 = $res[0]['rang'];
        $idMenuDep = $res[0]['id'];
        $tabReq = array();
        $tabReq[] = "UPDATE $tablMenus SET rang = 1000 WHERE id = $idMenuDep";
        $tabReq[] = "UPDATE $tablMenus SET rang = $rang0 WHERE id = $menuid";
        $tabReq[] = "UPDATE $tablMenus SET rang = $rgmenu WHERE id = $idMenuDep";

        //dd($tabReq);
         DB::transaction(function () use($tabReq) {
            foreach ($tabReq as $item):
                DB::connection(env('DB_CONNECTION'))->update($item);
            endforeach;
        });
        return MenuMakerController::makeMenuTable();
    }

    public function delMenus(Request $request){
        $menid = $request->input('menu');
        $tablMenus = DbTablesHelper::dbTable('DBTBL_MENUS');
        $delete = DB::table($tablMenus)->where('id',$menid)->delete();
        if ($delete):
            $grpMenus = FunctionController::getChampTable(FunctionController::getTableName($tablMenus),$menid,'groupemenu');
            self::OrdonnerMenuASC($grpMenus);
        endif;
        return MenuMakerController::makeMenuTable();
    }


    public function ordonnergrpmenu(Request $request){
        $groupemenu = $request->get( 'groupemenu');
        $rgmenu = $request->get( 'rang');
        $direction = $request->get( 'direction');
        $cond = $direction == "ASC" ? " rang > $rgmenu" : " rang < $rgmenu";
        $sql = "SELECT id, rang FROM " .WooModuleController::dbTableSysteme('DBTBL_GROUPEMENUS'). " WHERE  $cond ORDER BY rang $direction LIMIT 1";
        $res = WooDatabaseController::dbraw($sql);

        $rang0 = $res[0]->rang;
        $idMenuDep = $res[0]->id;
        $tabReq = array();
        $tabReq[] = "UPDATE " .WooModuleController::dbTableSysteme('DBTBL_GROUPEMENUS'). " SET rang = 1000 WHERE id = $idMenuDep";
        $tabReq[] = "UPDATE " .WooModuleController::dbTableSysteme('DBTBL_GROUPEMENUS'). " SET rang = $rang0 WHERE id = $groupemenu";
        $tabReq[] = "UPDATE " .WooModuleController::dbTableSysteme('DBTBL_GROUPEMENUS'). " SET rang = $rgmenu WHERE id = $idMenuDep";

        $transac = DB::transaction(function () use($tabReq) {
            foreach ($tabReq as $item):
                WooDatabaseController::dbraw($item);
            endforeach;
        });
        $urlajouter = route('tma.ajouter', WooModuleController::dbTable('DBTBL_GROUPEMENUS'));
        $module = WooModuleController::dbTable('DBTBL_GROUPEMENUS');
        return WooTmaController::makeGroupeMenuTable($module,$urlajouter);
    }

    public function groupeMenusRoutes(){
        $databaseTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROUTES'));
        $grpRouteTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENU_ROUTES'));

        $dataTableData = FunctionController::arraySqlResult("SELECT id, name, uri, prefix FROM $databaseTable WHERE id NOT IN(SELECT route FROM $grpRouteTable) ORDER BY NAME ASC ");
        $grpMenusTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'));
        $datagrpMenusTable = FunctionController::arraySqlResult("SELECT * FROM $grpMenusTable WHERE deleted = 1 ORDER BY rang ASC ");
        //dd($dataTableData,$datagrpMenusTable);
        return view("template.Tma.formGroupemenuRoutes", compact('datagrpMenusTable','dataTableData','databaseTable'));
    }

    public static function routeDeMenu($routeID){
        $roleTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
        $menuTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));

        $leMenu = FunctionController::arraySqlResult("SELECT * FROM $menuTable WHERE role IN (SELECT id FROM $roleTable WHERE route = $routeID)");
        if (count($leMenu)):
            self::ajouterRouteDansGroupeMenu($routeID,$leMenu[0]['id']);
            return $leMenu[0]['id'];
        else:
            return 0;
        endif;
    }

    public static function ajouterRouteDansGroupeMenu($routeID,$menuID){
        $grpRouteTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENU_ROUTES'));
        $menuTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
        $groupeMenus = FunctionController::getChampTable($menuTable,$menuID,"groupemenu");
        $verif = FunctionController::arraySqlResult("SELECT * FROM $grpRouteTable WHERE route = $routeID AND groupemenu = $groupeMenus");
        if (count($verif) == 0):
            DB::table(DbTablesHelper::dbTable('DBTBL_GROUPEMENU_ROUTES'))
                ->insert(
                    [
                        'groupemenu' => $groupeMenus,
                        'route' => $routeID,
                        'est_menu' => 1,
                    ]
                );
        endif;
    }

    public function ajouterRoutesDsGroupeMenus(Request $request)
    {
        if ($request->input('checked') == "true"):
            if ($request->input('groupemenu') != null):
                $grpRouteTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENU_ROUTES'));

                $verif = FunctionController::arraySqlResult("SELECT * FROM $grpRouteTable WHERE route = {$request->input('routeID')} ");
                if (count($verif) == 0):
                    $new = DB::table(DbTablesHelper::dbTable('DBTBL_GROUPEMENU_ROUTES'))
                        ->insert(
                            [
                                'groupemenu' => $request->input('groupemenu'),
                                'route' => $request->input('routeID'),
                            ]
                        );
                    if ($new):
                        $routeTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROUTES'));
                        $grpTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'));
                        $routeName = FunctionController::getChampTable($routeTable,$request->input('routeID'),'name');
                        $grpMenuName = FunctionController::getChampTable($grpTable,$request->input('groupemenu'),'name');
                        $msg = "la route {$routeName} appartient désormais au groupe de menus $grpMenuName !";
                        return DbTablesHelper::msg($msg,"success");
                    else:
                        $msg = "Une erreur est survenue, veuillez récommencer !";
                        return DbTablesHelper::msg($msg,"danger");
                    endif;
                else:
                    $routeTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROUTES'));
                    $routeName = FunctionController::getChampTable($routeTable,$request->input('routeID'),'name');
                    $msg = "La route $routeName existe déjas dans un groupe!";
                    return DbTablesHelper::msg($msg,"danger");
                endif;
            else:
                $msg = "Veuillez obligatoirement choisir un groupe de menu !";
                return DbTablesHelper::msg($msg);
            endif;
        endif;
    }

    public function deleteGrpMenuRoute(Request $request){
        $del = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_GROUPEMENU_ROUTES'))
            ->where([
                'id' => $request->grpRouteID,
            ])
            ->delete();
       // dd($request->all(),$del);
        return self::makeRoutesDeGroupeMenus($request->grpMenuID);
    }

    public function getRoutesDeGroupeMenus(Request $request){
        if ($request->input('grpMenu') != null):
            return self::makeRoutesDeGroupeMenus($request->input('grpMenu'));
        endif;
    }


    public function makeRoutesDeGroupeMenus($grpMenu){
        $routeTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROUTES'));
        $grpRouteTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENU_ROUTES'));
        $grpTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'));
        $grpMenuName = FunctionController::getChampTable($grpTable,$grpMenu,'name');
        $routes = FunctionController::arraySqlResult("SELECT r.*,gr.id AS groupeRouteID,gr.est_menu,gr.groupemenu FROM $routeTable r,$grpRouteTable gr WHERE gr.groupemenu = {$grpMenu} AND gr.route = r.id ");
        return view("template.Tma.listeRoutesDeGroupeMenus", compact('routes','grpMenuName'))->render();
    }

    public function ajx(){
        return FunctionController::sendMessageFlash('danger','Vous n\'avez pas accès à cette réssource !');
    }

    public function gestionModules(){
        $table = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MODULES'));
        $tableIcone = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ICONES'));
        $icones = FunctionController::arraySqlResult("SELECT * FROM ".$tableIcone." ORDER BY name ASC");
       return view('template.Tma.formModule',compact('table','icones'));
    }

    public function storeModule(Request $request){
        $table = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MODULES'));
        $name = strtolower(FunctionController::cleanNomPrenom($request->name));
        $verification = FunctionController::arraySqlResult("SELECT * FROM $table WHERE name = '{$name}'");
        if (count($verification) == 0):
            $insert = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_MODULES'))
                ->insert([
                    'name' => $request->name,
                    'description' => $request->description,
                    'icone' => $request->icone,
                    'activated' => $request->activated,
                ]);
            if ($insert):
                Session::flash("success","Module crée avec succès!");
            else:
                Session::flash("echec","Une erreur est survenue, veuillez récommencer!");
            endif;
        else:
            Session::flash("echec","Ce module existe déjas !");
        endif;
        return back();
    }

    public static function makeFormRoleItem(int $larouteID,int $codeErr=0):String{
        $routeParam = DB::table(DbTablesHelper::dbTable('DBTBL_ROUTE_PARAMS'))
            ->where('route', $larouteID)
            ->get();
        $roleExist = DB::table(DbTablesHelper::dbTable('DBTBL_ROLES'))
            ->where('route', $larouteID)
            ->get();
        $libelleRoute = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ROUTES'),$larouteID);
        return view("template.Tma.formRoleStep2Item",compact('larouteID','routeParam','roleExist','libelleRoute','codeErr'))->render();

    }

    public static function makeFormMenuItem(int $roleID,int $codeErr=0):String{
        $role = DB::table(DbTablesHelper::dbTable('DBTBL_ROLES'))
            ->where('id', $roleID)
            ->get();
        $grpMenus = FunctionController::arraySqlResult("SELECT * FROM 
          ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'))."
           WHERE deleted = 1 ORDER BY rang ASC");
        return view("template.Tma.formMenuItem",compact('role','grpMenus','codeErr'))->render();
    }

    public function storeRoleItem(Request $request){
        $data = $request->all();
        $laroute = DB::table(DbTablesHelper::dbTable('DBTBL_ROUTES'))
            ->where('id', $data['route'])
            ->get();
        if (!is_null($request->role)):
            $verif = DB::table(DbTablesHelper::dbTable('DBTBL_ROLES'))
                ->where('route', $data['route'])
                ->where('name', $data['role'])
                ->get();
            if(count($verif) == 0):
                $newRole = DB::table(DbTablesHelper::dbTable('DBTBL_ROLES'))
                    ->insertGetId(
                        [
                            'route' => $data['route'],
                            'name' => $data['role'],
                            'uri' => $laroute[0]->uri,
                            'parametres' => '-',
                        ]
                    );
                if($newRole):
                    $codeErr = 1;
                    FunctionController::refreshRoles();
                    return self::makeFormMenuItem($newRole,$codeErr);
                else:
                    $codeErr = 2;
                endif;
            else:
                $codeErr = 3;
            endif;
        else:
            $codeErr = 4;
        endif;
        return self::makeFormRoleItem($data['route'],$codeErr);
    }

    public function storeMenuItem(Request $request){
        if (!is_null($request->menu) & !is_null($request->level)& !is_null($request->groupemenu)):
            $menuID = DB::table(DbTablesHelper::dbTable('DBTBL_MENUS'))
                ->insertGetId([
                    'name' => $request->menu,
                    'groupemenu' => $request->groupemenu,
                    'rang' => $request->rang,
                    'role' => $request->role,
                    'level_menu' => $request->level,
                    'icone' => 9,
                ]);
            if ($menuID):
                $roleTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
                $routeID = FunctionController::getChampTable($roleTable,$request->role,'route');
                self::ajouterRouteDansGroupeMenu($routeID,$menuID);
                $codeErr = 4;
            else:
                $codeErr = 2;
            endif;
        else:
            $codeErr = 5;
        endif;
        return self::makeFormMenuItem($request->role,$codeErr);
    }

    public function createIcones(){
        $table = DbTablesHelper::dbTablePrefixeOff('DBTBL_ICONES');
        return ModuleController::makeForm ($table);
    }

    public function createProfils(){
        $databaseTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_PROFILS'));
        $dataTableData = FunctionController::arraySqlResult("SELECT * FROM $databaseTable ORDER BY NAME ASC ");
        $options = [
            "uri" => "makeProfilRole",
            "icone" => "fa-users",
        ];
        return view("woody.Tma.formProfil",compact('databaseTable','dataTableData'));
    }

    public function tableFieldDefault(){
        $tables = FunctionController::getDatabaseTable();
        return view("woody.Tma.tableFieldDefaultForm", compact('tables'));
    }

    public function getListTableField(Request $request){
        $listeChamps = "";
        if (!is_null ($request->table)):
            $table = FunctionController::getTableName ($request->table);
            $champs = FunctionController::getListeDesChamps ($request->table);

            $tfieldDefault = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_DEFAULT_LABELS'));
            $champsDefaults = FunctionController::arraySqlResult ("SELECT * FROM $tfieldDefault WHERE latable = '$table'");
            $leChampsDefault = "";
            if (count ($champsDefaults)):
                $leChampsDefault = $champsDefaults[0]['field'];
            endif;
            foreach ($champs as $v):
                if ($v != 'id'):
                    $checked = $v == $leChampsDefault ? "checked" : "";
                    $listeChamps .= "<tr><td>{$v}</td><td><input type='checkbox' {$checked} id='field-{$v}' value='{$v}' onchange=\"addTableDefaultField('".$table."',this)\"></td></tr>";
                endif;
            endforeach;
        endif;
        return response ()->json ([
            'listeChamps' => $listeChamps
        ]);
    }

    public function addTableField(Request $request){
        $table = $request->latable;
        $check = $request->etat;
        $field = $request->field;
        $tfieldDefault = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_DEFAULT_LABELS'));
        $ifFieldExist = FunctionController::arraySqlResult ("SELECT * FROM $tfieldDefault WHERE latable LIKE '$table'");
        if (!count ($ifFieldExist)):
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_DEFAULT_LABELS'))
                ->insert ([
                    'latable' => $table,
                    'field' => $field
                ]);
            $message = "Le champs $field ajouté comme champs par defaut de la table $table!";
            $alert = " alert-success";
        else:
            if ($check == 'true'):
                $message = "La table $table a déjàs un champs par défaut!";
                $alert = " alert-info";
            else:
                DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_TABLEDEFAULTFIELDS'))->where ('id','=',$ifFieldExist[0]['id'])->delete ();
                $message = "Le champs par défaut de la table $table rétirer avec succès!";
                $alert = " alert-danger";
            endif;
        endif;
        return response ()->json ([
            'message' => $message,
            'alerte' => $alert
        ]);
    }

    public function checkRoleToProfil(Request $request)
    {
        $etat = $request->input('etat');
        $profilID = $request->input('profilID');
        $roleID = $request->input('roleID');
        if ($etat === 'true'):
             DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PROFILROLES'))
                ->insert([
                    'profil' => $profilID ,
                    'role' =>  $roleID
                ]);
            $message = 'Role ajouté avec succès!';
        else:
             DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PROFILROLES'))
                ->where(
                    [
                    'profil' => $profilID ,
                    'role' =>  $roleID
                    ])->delete();
            $message = 'Role rétiré avec succès!';
        endif;
        return [
            'message' => $message
        ];
    }
}
