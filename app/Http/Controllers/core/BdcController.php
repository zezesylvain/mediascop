<?php

namespace App\Http\Controllers\core;

use App\Helpers\DbTablesHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BdcController extends Controller
{
    public function bdcForm(Request $request){
        $grp = $request->input('grp');
        $docTbl = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_DOCUMENTATIONS'));
        $docs = FunctionController::arraySqlResult("SELECT * FROM ".$docTbl." WHERE groupemenu = {$grp}");
        $grpMenusTbl = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'));
        $grpMenu = FunctionController::getChampTable($grpMenusTbl,$grp);
        if (count($docs)):
            return view("Html.listeDocTableau",compact('docs','grpMenu'));
        endif;
    }

    public static function makeDocumentationForm(){
        $grpMenusTbl = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'));
        $grpMenus = FunctionController::arraySqlResult("SELECT * FROM ".$grpMenusTbl." WHERE deleted = 1");
        if (count($grpMenus)):
            $user = Auth::id();
            return view("Html.formDoc",compact('grpMenus','user'))->render();
        endif;
    }

    public function storeDoc(Request $request){
        $datas = $request->all();
        unset($datas['_token']);
        unset($datas['files']);
        $insert = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_DOCUMENTATIONS'))
            ->insert($datas);
        if ($insert):
            Session::flash('success',"Votre documentation est pris en compte!");
        else:
            Session::flash('echec',"Attention une erreur est survénue, veuillez récommencer!");
        endif;
        return back();
    }

    public function consulterDoc(){
        $grpMenusTbl = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_GROUPEMENUS'));
        $grpMenus = FunctionController::arraySqlResult("SELECT * FROM ".$grpMenusTbl." WHERE deleted = 1");
        if (count($grpMenus)):
            return view("Html.listeDocumentation",compact('grpMenus'))->render();
        endif;
    }
}
