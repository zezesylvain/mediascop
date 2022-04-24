<?php

namespace App\Http\Controllers\Template;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TemplateAdminController extends Controller
{
    public function __construct ()
    {
    }

    public function showPdfViewer(){
        $pdf = asset("template".DIRECTORY_SEPARATOR."pdf".DIRECTORY_SEPARATOR."mamunur.pdf");
        return view ("administration.template.Interface.pdfviewer", compact ('pdf'));
    }

    public function showxEditable(){
        $sql = "SELECT * FROM emails ORDER BY name ASC, email ASC ";
        $datas = DB::select ($sql);
        //dd ($datas);
        return view ("administration.template.Interface.xeditable",compact ('datas'));
    }

    public function showFileManager(){
        return view ("administration.template.Interface.filemanager");
    }

    public function updatexEditable(Request $request){
        if ($request->ajax ()):
            $tableP = $request->session ()->get ('x-edit-table');
            $table = $request->session ()->get ("DatabaseTableIndexees.$tableP");

            DB::select (DB::raw ("UPDATE $tableP SET {$request->input ("name")} = '{$request->input('value')}' WHERE id = {$request->input('pk')}"));
            
            /*DB::table ($table)
                ->where ('id', $request->input('pk'))
                ->update ([
                    $request->input ("name") => $request->input('value')
                ]);*/
        endif;
    }
}
