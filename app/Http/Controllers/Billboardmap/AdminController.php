<?php

namespace App\Http\Controllers\Billboardmap;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\CoreController;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class AdminController extends CoreController
{

    public function __construct ()
    {
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

    /**
     * @param $localiteid
     * @return array
     */
    public static function getLignee($localiteid): array
    {
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

    /**
     * @param $id
     * @return array
     */
    public static function getAilleux($id): array
    {
        $id0 = $id;
        $tab = [$id];
        while ($id0 != 1):
            $p = self::getParent($id0);
            $tab[] = $p;
            $id0 = $p;
        endwhile;
        return array_reverse($tab);
    }

    /**
     * @param $id
     * @return array
     */
    public static function getAilleuxBis($id): array
    {
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

    /**
     * @param $id
     * @return mixed
     */
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

    /**
     * @param $id
     * @return string
     */
    public static function getLigneeDeLocalite($id): string
    {
        $ailleux = self::getAilleuxBis($id);
        unset($ailleux[array_search(1,$ailleux)]);
        $lignee = self::formatDeLignee($ailleux);
        return $lignee;
    }

    /**
     * @param array $selection
     * @return string
     * @throws \Throwable
     */
    public static function afficherLaSelection(array $selection): string
    {
        return view('bbmap.selection.selection',compact('selection'))->render();
    }
}
