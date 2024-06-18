<?php

namespace App\Http\Controllers\core;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Menu;

class MenuMakerController extends Controller {

    public static function makeTabMenuBis() {
        //Session::forget("tabMenu");
        if (!Session::has("tabMenu") && !Session::has("groupTabMenu")) :
            $tablgpm = DbTablesHelper::dbTable('DBTBL_GROUPEMENUS');
            $tablrole = DbTablesHelper::dbTable('DBTBL_ROLES');
            $tabl = DbTablesHelper::dbTable('DBTBL_MENUS');
            $tableGroupeMenu = FunctionController::getTableName($tablgpm);
            $tableMenu = FunctionController::getTableName($tabl);
            $tableRole = FunctionController::getTableName($tablrole);
            $UserLevel = Session::get('UserLevel');
            $sqlgpm = "SELECT * FROM $tableGroupeMenu  ORDER BY rang ASC";

            //$sql = "SELECT * FROM $tableMenu WHERE level_menu <= '" . $UserLevel . "' AND groupemenu = %d ORDER BY rang ASC";
            $sql = "SELECT * FROM $tableMenu WHERE level_menu <= '" . $UserLevel . "' AND groupemenu = %d ORDER BY rang ASC";
            $result = DB::select(DB::raw($sqlgpm));
            $tabMenu = [];
            $groupTab = [];
            foreach ($result AS $item):
                $groupemenu = $item->id;
                $submenu = DB::select(DB::raw(sprintf($sql, $groupemenu)));
                if (count($submenu)) :
                    $groupTab[$item->name] = [];
                    $leGroupeMenu = [];
                    $leGroupeMenu["lesMenus"] = [];
                    $leGroupeMenu["label"] = $item->name;
                    $leGroupeMenu["icone"] = $item->icone;
                    foreach ($submenu AS $sm):
                        $lesMenus = [];
                        $lesMenus["label"] = $sm->name;
                        $lesMenus["url"] = $sm->url;
                        $lesMenus["icone"] = $sm->icone;
                        $leGroupeMenu["lesMenus"][] = $lesMenus;
                        $groupTab[$item->name][] = $sm->url;
                    endforeach;
                    $tabMenu[] = $leGroupeMenu;
                endif;
            endforeach;
            if (count($tabMenu)):
                Session::put("tabMenu", $tabMenu);
            endif;
            if (count($groupTab)):
                Session::put("groupTabMenu", $groupTab);
            endif;
        endif;
    }
    
    public static function menuTable(){
        $tabM = Menu::with('groupemenu', 'role', 'menu_target', 'icone')->get()->toArray();
        //dd($tabM);
    }
    
    public static function makeTabMenu() {
        //$tabM = Menu::with('groupemenu', 'role', 'menu_target', 'icone')->get()->toArray();
        //dd($tabM);
        $moduleID = self::getModuleCourant();
       // Session::forget("tabMenu");
       // Session::forget("groupTabMenu");
        if (!Session::has("tabMenu") && !Session::has("groupTabMenu")):
            Session::put("tabMenu", []);
            Session::put("groupTabMenu", []);
        endif;
        if (!Session::has("tabMenu.$moduleID") && !Session::has("groupTabMenu.$moduleID")):
            $tablgpm = DbTablesHelper::dbTable('DBTBL_GROUPEMENUS');
            $tablrole = DbTablesHelper::dbTable('DBTBL_ROLES');
            $tabl = DbTablesHelper::dbTable('DBTBL_MENUS');
            $tableGroupeMenu = FunctionController::getTableName($tablgpm);
            $tableMenu = FunctionController::getTableName($tabl);
            $tableRole = FunctionController::getTableName($tablrole);
            //$sqlgpm = "SELECT * FROM $tableGroupeMenu WHERE module = {$moduleID}  ORDER BY rang ASC";
            $sqlgpm = "SELECT * FROM $tableGroupeMenu WHERE module = {$moduleID}  ORDER BY rang ASC";
            $TabRoleUser = [3];
            $UserRoles = UtilisateursController::GetUserRoles();
            $TabRoleUser = !count($UserRoles) ? array_merge($TabRoleUser,$UserRoles) : $UserRoles;
            $cond = join(',',$TabRoleUser);
            $sql = "SELECT m.*,r.route FROM $tableMenu m, $tableRole r WHERE  m.groupemenu = %d AND m.role = r.id AND r.id IN($cond) ORDER BY m.rang ASC";
            $result = DB::select(DB::raw($sqlgpm));
            $tabMenu = [];
            $groupTab = [];
            foreach ($result AS $item):
                $groupemenu = $item->id;
                $submenu = DB::select(DB::raw(sprintf($sql, $groupemenu)));
                if (count($submenu)) :
                    $groupTab[$item->name] = [];
                    $leGroupeMenu = [];
                    $leGroupeMenu["lesMenus"] = [];
                    $leGroupeMenu["label"] = $item->name;
                    $leGroupeMenu["icone"] = $item->icone;
                    foreach ($submenu AS $sm):
                        
                        $lesMenus = [];
                        $lesMenus["label"] = $sm->name;
                        $LaRoute = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ROLES'),$sm->role,'route');
                        $url = DIRECTORY_SEPARATOR.FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ROUTES'),$LaRoute,'uri');
                        $lesMenus["url"] = $url;
                        $lesMenus["icone"] = $sm->icone;
                        $lesMenus["mtarget"] = $sm->menu_target;
                        $leGroupeMenu["lesMenus"][] = $lesMenus;
                        $groupTab[$item->name][] = $url;
                    endforeach;
                    $tabMenu[] = $leGroupeMenu;
                endif;
            endforeach;
            if (count($tabMenu)):
                Session::put("tabMenu.$moduleID", $tabMenu);
            endif;
            if (count($groupTab)):
                Session::put("groupTabMenu.$moduleID", $groupTab);
            endif;
        endif;
    }

    public static function expansion($label) {
        $laRoute = self::makeCurrentUrl();
        self::makeTabMenu();
        $moduleID = self::getModuleCourant();
        $groupTab = Session::get("groupTabMenu");
        $url = DIRECTORY_SEPARATOR.$laRoute;
        //dump ($groupTab[$moduleID],$label,$url);
        if (array_key_exists($label, $groupTab[$moduleID])) :
            return in_array($url, $groupTab[$moduleID][$label]);
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

    public static function makeMenuSimple():string {
        self::makeTabMenu();
        $moduleID = self::getModuleCourant();
        $tab = array_key_exists($moduleID,Session::get('tabMenu')) ? Session::get("tabMenu.$moduleID") : [];
        $HTML = "";
        if (!count($tab)):
            return "";
        endif;
        foreach ($tab AS $r) :
            if (is_array($r["lesMenus"])):
                $HTML .= self::makeMenuItemLevel($r["lesMenus"], $r["label"], $r["icone"]);
            else:
                $HTML .= self::makeMenuItem($r["url"], $r["label"], $r["icone"]);
            endif;
        endforeach;
        return $HTML;
    }

    public static function makeMenuItem($url, $label, $icone) {
        $base = env("APP_URL");
        $licone = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ICONES'), $icone);
        return <<<MENUT
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="{$label}">
                    <a class="nav-link" href="{$base}{$url}">
                      <i class="fa fa-fw fa-{$licone}"></i>
                      <span class="nav-link-text">{$label}</span>
                    </a>
                  </li>
MENUT;
    }

    public static function makeMenuItemLevel(array $tab, string $label, int $icone):string
    {
        $id = str_replace(" ", "", $label);
        $base = env("APP_URL");
        $mask = "<li class='%s'>
                        <a title=\"%s\" href=\"%s\" %s ><i class=\"fa fa-%s sub-icon-mg\" aria-hidden=\"true\" ></i> <span class=\"mini-sub-pro\">%s</span></a>
                 </li>";
        $li = "";
        $currentUrl = Route::current()->uri();
        foreach ($tab AS $it):
            $menIcone = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_ICONES'),$it['icone']);
            $target = '';
            //$target = $it['mtarget'] !== 1 ? "target='".FunctionController::getChampTable(DbTablesHelper::dbTable('DTBL_MENU_TARGETS'),$it['mtarget'])."'" : "";
            $baseUrl = str_replace('\\','/',$base.$it['url']);
            $menuActive = $currentUrl === substr($it['url'],1-strlen($it['url']),strlen($it['url'])) ? 'menu-active' : '';
            $li .= sprintf($mask, $menuActive, $it['label'], $baseUrl, $target, $menIcone, $it["label"]);
        endforeach;
        $expan = self::expansion($label);
        $expansion = $expan ? " aria-expanded='true'" : "";

        //$active = "menu-active";
        $active = "";
        $show = $expan ? " collapse in" : " collapse";
        $licone = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ICONES'), $icone);
        //dd ($expan,$expansion);
        return view ("template.Menus.menuListHtml", compact ('li','expansion','active','show','licone','id','label'))->render ();
    }

    public static function makeHtmlSousMenu(){
        if (!Session::has("SousMenu")):
            Session::put("SousMenu", []);
        endif;
        $currentUrl = FunctionController::makeCurrentUrl();
        $TblRole = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
        $TblMenu = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
        $userRoles = UtilisateursController::GetUserRoles();
        $cond = '';
        if (count($userRoles)):
            $cond = " AND id IN(".join(',',$userRoles).")";
        endif;
        $sql = "SELECT * FROM ".$TblRole." WHERE uri='$currentUrl' $cond";
        $role = FunctionController::arraySqlResult($sql);
        if (count($role)):
            $roleCourant = $role[0]['id'];
            $sqlq = "SELECT m.* FROM $TblMenu m WHERE m.groupemenu IN (SELECT groupemenu FROM $TblMenu WHERE role = {$roleCourant})";
            $lemenus = FunctionController::arraySqlResult($sqlq);
            $TabMenu = [];
            $grpmenu = count($lemenus) ? $lemenus[0]['groupemenu'] : 1;
            if (!Session::has("SousMenu.$grpmenu")):
                foreach ($lemenus as $menu):
                    if (in_array($menu['role'],Session::get('UserRoles'))):
                        $TabMenu[$menu["id"]] = $menu["role"];
                    endif;
                endforeach;
                Session::put("SousMenu.$grpmenu", $TabMenu);
                Session::put("GrpMenuCourant",$grpmenu);
            endif;
            Session::put("currentUri",$currentUrl);
            $lesSousMenus = Session::get("SousMenu.$grpmenu");
            return view("Html.menus", compact('lesSousMenus','roleCourant'))->render();
        else:
            return self::getRoutesGroupeMenu();
        endif;
    }
    
    public static function makeMenuTable(){
        $TEXT_NEW_ITEM = 'Nouveau menu';
        $TEXT_ITEM_TITLE = 'Menu';

        $headerdata = [
            '' => [
                'width' => '',
                'label' => '  ',
            ],
            'groupemenu' => [
                'width' => '',
                'label' => ' GROUPE MENUS  ',
            ],
            'name' => [
                'width' => '',
                'label' => ' NOMS ',
            ],
            'role' => [
                'width' => '',
                'label' => ' ROLES ',
            ],

            'level' => [
                'width' => '',
                'label' => ' LEVEL ',
            ],
            'rang' => [
                'width' => '',
                'label' => ' RANG ',
            ],
            'action' => [
                'width' => 'width="10%"',
                'label' => '  ',
            ],
        ];

        $dispheader = '';
        $nbtd = 0;
        foreach ($headerdata as $row) :
            $width = $row['width'];
            $label = $row['label'];
            $dispheader .= view("template.Tma.tableMenuHeader",compact('width','label'))->render();
            $nbtd++;
        endforeach;
        $contenu = '';
        $tablgpmenu = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'));
        $tablmenu = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
        $tablrole = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));

        $requete1 = " SELECT * FROM $tablgpmenu ORDER BY rang ASC";
        $resgpmenu = FunctionController::arraySqlResult($requete1);
        $i = 1;
        foreach ($resgpmenu as $rg) :
            $sqlmenu = "SELECT me.id, r.name AS role, r.id as roleID, me.name AS nom, me.rang AS rgmenu,me.level_menu AS level FROM  
                    $tablmenu me, 
                    $tablrole r
                  WHERE r.id = me.role  AND me.groupemenu = {$rg['id']}  ORDER BY me.rang ";

            $resmenu = FunctionController::arraySqlResult($sqlmenu);

            $y = 1;
            $j = count($resmenu);
            foreach ($resmenu as $r) :
                $grpMenuId = $rg['id'];
                $menuId = $r['id'];
                $rangMenu = $r['rgmenu'];

                $chup = "<span class=\"fa fa-chevron-up\"></span>";
                $chevronUp = $y == 1 ? $chup : view("template.Tma.chevronUp",compact('grpMenuId','menuId','rangMenu'))->render();
                $chdwn = "<span class=\"fa fa-chevron-down\"></span>";
                $chevronDown = $y == $j ? $chdwn : view("template.Tma.chevronDown",compact('grpMenuId','menuId','rangMenu'))->render();

                $contenu .= view("template.Tma.tableMenuContenu",compact('i','y','j','r','rg','chevronDown','chevronUp'))->render();
                $y++;
                $i++;
            endforeach;
        endforeach;
        return view("template.Tma.tableauMenus",compact('TEXT_ITEM_TITLE','TEXT_NEW_ITEM','dispheader','contenu'))->render();
    }

    public static function makeGroupeMenuTable(){
        $TEXT_NEW_ITEM = 'Nouveau';
        $TEXT_ITEM_TITLE = 'Groupe de Menu';

        $headerdata = array();

        $headerdata[''] = array(
            'width' => '',
            'label' => '  '
        ) ;

        $headerdata['name'] = array(
            'width' => '',
            'label' => ' NOM '
        ) ;


        $headerdata['icone'] = array(
            'width' => '',
            'label' => ' ICONE '
        );
        $headerdata['rang'] = array(
            'width' => '',
            'label' => ' RANG '
        );
        $headerdata['action'] = array(
            'width' => ' width="10%"',
            'label' => '  '
        );
        $dispheader = '';
        $nbtd = 0;
        foreach ($headerdata as $row) :
            $width = $row['width'];
            $label = $row['label'];
            $dispheader .= view("admin.Tma.tableMenuHeader",compact('width','label'))->render();
            $nbtd++;
        endforeach;

        $contenu = '';
        $requete1 = " SELECT * 
              FROM " .FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'))."
              WHERE deleted = 1  ORDER BY rang";
        $resgpmenu = FunctionController::arraySqlResult($requete1);
        $i = 1;
        $y = 1;
        $mod = 'groupMenu';
        foreach ($resgpmenu as $rg) :
            $j = count($resgpmenu);
            $grpMenuId = $rg['id'];
            $rangGrpMenu = $rg['rang'];

            $chup = "<span class=\"fa fa-chevron-up\"></span>";
            $chevronUp = $y == 1 ? $chup : view("admin.Tma.chevronUp",compact('grpMenuId','rangGrpMenu','mod'))->render();
            $chdwn = "<span class=\"fa fa-chevron-down\"></span>";
            $chevronDown = $y == $j ? $chdwn : view("admin.Tma.chevronDown",compact('grpMenuId','mod','rangGrpMenu'))->render();

            $contenu .= view("admin.Tma.tableGrpMenuContenu",compact('i','y','j','rg','chevronDown','chevronUp'))->render();
            $y++;
            $i++;
        endforeach;

        return view("admin.Tma.tableauGrpMenus",compact('TEXT_ITEM_TITLE','TEXT_NEW_ITEM','dispheader','contenu'))->render();
    }

    public static function makeMenuCourant($grpMenuID){
        $lesSousMenus = Session::get("SousMenu.$grpMenuID");
        $currentUrl = FunctionController::makeCurrentUrl();
        $TblRole = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
        $userRoles = UtilisateursController::GetUserRoles();
        $cond = '';
        if (count($userRoles)):
            $cond = " AND id IN(".join(',',$userRoles).")";
        endif;
        $sql = "SELECT * FROM ".$TblRole." WHERE uri='$currentUrl' $cond";
        $role = FunctionController::arraySqlResult($sql);
        $roleCourant = count($role) ? $role[0]['id'] : 0;
        return view("Html.menus", compact('lesSousMenus','roleCourant'))->render();
    }

    public static function getRoutesGroupeMenu(){
        $r = Request::route();
        $TblGroupeMenuRoute = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENU_ROUTES'));
        $TblRoute = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROUTES'));
        $route = FunctionController::arraySqlResult("SELECT * FROM $TblRoute WHERE uri = '{$r->uri}' ");
        if (count($route)):
            $groupeMenuRoutes = FunctionController::arraySqlResult("SELECT * FROM $TblGroupeMenuRoute WHERE groupemenu IN(SELECT groupemenu FROM $TblGroupeMenuRoute WHERE route = {$route[0]['id']})");
            if (count($groupeMenuRoutes)):
                $TblMenu = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
                $TblRole = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
                $userRoles = UtilisateursController::GetUserRoles();
                $cond = '';
                if (count($userRoles)):
                    $cond = " AND r.id IN(".join(',',$userRoles).")";
                endif;
                $tr = [];
                foreach ($groupeMenuRoutes as $v):
                    $tr[] = $v['route'];
                endforeach;
                $sql = "SELECT r.id FROM $TblRole r,$TblRoute rt WHERE r.route = rt.id AND r.route IN (".join(',',$tr).") $cond";
                $sqlq = "SELECT m.* FROM $TblMenu m WHERE m.groupemenu IN (SELECT groupemenu FROM $TblMenu WHERE role IN ($sql))";
                $lemenus = FunctionController::arraySqlResult($sqlq);
                $TabMenu = [];
                $grpmenu = $lemenus[0]['groupemenu'];
                if (!Session::has("SousMenu.$grpmenu")):
                    foreach ($lemenus as $menu):
                        if (in_array($menu['role'],Session::get('UserRoles'))):
                            $TabMenu[$menu["id"]] = $menu["role"];
                        endif;
                    endforeach;
                    Session::put("SousMenu.$grpmenu", $TabMenu);
                    Session::put("GrpMenuCourant",$grpmenu);
                endif;
                $lesSousMenus = Session::get("SousMenu.$grpmenu");
                $roleCourant = Session::get("currentUri");
                return view("Html.menus", compact('lesSousMenus','roleCourant'))->render();
            endif;
        endif;
    }

    public static function getModuleCourant(){
        //Session::forget ('moduleCourant');
        if (!Session::has('moduleCourant')):
            $uri = FunctionController::makeCurrentUrl();
            $TblRole = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_ROLES'));
            $TblMenu = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_MENUS'));
            $TblGrpMenu = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'));
            $menu = FunctionController::arraySqlResult("SELECT * FROM ".$TblMenu." WHERE role IN (SELECT id FROM ".$TblRole." WHERE uri = '$uri')");
            if (count($menu)):
                $moduleID = FunctionController::getChampTable($TblGrpMenu,$menu[0]['groupemenu'],'module');
                Session::put('moduleCourant', $moduleID);
            else:
                Session::put('moduleCourant', 1);
            endif;
        endif;
        return Session::get('moduleCourant');
    }

    public static function makeMenuMobile():string {
        self::makeTabMenu();
        $moduleID = self::getModuleCourant();
        $tab = array_key_exists($moduleID,Session::get('tabMenu')) ? Session::get("tabMenu.$moduleID") : [];
        $HTML = "";
        if (!count($tab)):
            return "";
        endif;

        foreach ($tab AS $r) :
            //dump ($r);
            if (is_array($r["lesMenus"])):
                $HTML .= self::makeMenuMobileItemLevel($r["lesMenus"], $r["label"], $r["icone"]);
            else:
                $HTML .= self::makeMenuItem($r["url"], $r["label"], $r["icone"]);
            endif;
        endforeach;
        return $HTML;
    }


    public static function makeMenuMobileItemLevel(array $tab, string $label, int $icone):string
    {
        $id = str_replace(" ", "", $label);
        $base = env("APP_URL");
        $mask = "
                 <li>
                        <a href='$base%s'>%s</a>
                 </li>
                 ";
        $li = "";
        foreach ($tab AS $it):
            $li .= sprintf($mask, $it["url"],$it["label"]);
        endforeach;
        return view ("template.Menus.menuMobileListHtml", compact ('li','id','icone','label'))->render ();
    }

}
