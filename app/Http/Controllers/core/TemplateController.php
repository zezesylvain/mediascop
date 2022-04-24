<?php

namespace App\Http\Controllers\core;

use App\Helpers\DbTablesHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class TemplateController extends Controller
{
    public function __construct ()
    {
    }

    /**
     * @param String $pdf Le chemin complet du pdf sous forme de lien
     * @return String
     * @throws \Throwable
     */
    public static function pdfViewer(String $pdf):string{
        return view ("template.Interface.pdf_viewer", compact ('pdf'))->render ();
    }

    /**
     * @param string $table
     * @param string $libelle
     * @param string $url
     * @param string $id
     * @param string $dataType
     * @param int $dataPk
     * @param string $dataPlacement
     * @param string $dataPlaceholder
     * @param string $dataTitle
     * @return string
     * @throws \Throwable
     */
    public static function xEditable(string $table, string $libelle, string $id="firstname", string $dataType="text", int $dataPk=1, string $dataPlacement="right", string $dataPlaceholder="Required", string $dataTitle=""):string{
        return view ("template.Interface.xEditable", compact ('libelle','id','dataType','dataPk','dataPlaceholder','dataPlacement','dataTitle','url','table'))->render ();
    }

    public function templateFunction(){
        $roles = ModuleController::makeTable (DbTablesHelper::dbTable ('DBTBL_ROLES')) ;
        return view ("template.Interface.index",compact ('roles'));
    }

    public static function breadcrumb(){
        $url = Route::current ();
        $uri = explode ('/', $url->uri);
        $route = $url->uri;
        return view ("woody.Interface.breadcrumb", compact ('uri','route'))->render ();
    }
}
