<?php

namespace App\Http\Controllers\Administration;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RapportController extends Controller
{
    public function __construct() { }
    
    public function nouveauRapport(){
        $uploadDir = public_path("upload".DIRECTORY_SEPARATOR."rapports".DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR);
        return view("administration.Rapports.formrapport", compact('uploadDir'));
    
    }
    
    public function showReport(){
        $tRapport = DbTablesHelper::dbTable('DBTBL_RAPPORTS','db');
        $tSecteur = DbTablesHelper::dbTable('DBTBL_SECTEURS','db');
        $tPeriodicite = DbTablesHelper::dbTable('DBTBL_PERIODICITES','db');
        $sqlrapp = "SELECT r.*, se.name as secteurname, pe.name as periodicitename
                    FROM $tRapport r,
                       $tSecteur se,
                      $tPeriodicite pe
                    WHERE r.secteur = se.id AND r.periodicite = pe.id
                    ORDER BY r.dateajout ASC";
        $lesrapports = FunctionController::arraySqlResult($sqlrapp);
        $database = $tRapport;
        return view("administration.Rapports.tableauRapports",compact('lesrapports','database'));
    }
    
    public static function iconeT($ext) {
        switch ($ext):
            case 'xlsx' :
            case 'xls' :
                $n = 'excel';
                break;
            case 'docx' :
            case 'doc' :
                $n = 'word';
                break;
            case 'pptx' :
            case 'ppt' :
                $n = 'powerpoint';
                break;
            case 'pdf' :
                $n = 'pdf';
                break;
            default :
                $n = $ext;
        endswitch;
        return $n;
    }
    
    public static function makeFormVisuelRapport(){
        $dirc = public_path("upload".DIRECTORY_SEPARATOR."rapports".DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR);
        $html = '';
        $lesvisuels = glob($dirc . "*");
        $cond = " WHERE file = '%s'";
        $tPeriodicite = DbTablesHelper::dbTable('DBTBL_PERIODICITES','db');
        $tSecteur = DbTablesHelper::dbTable('DBTBL_SECTEURS','db');
        $sqlv = "SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_RAPPORTS','db')." WHERE file = '%s'";
        $indic = 0;
        $sqlcs = " SELECT * FROM $tSecteur  ORDER BY id ASC";
        $sqlp = " SELECT * FROM $tPeriodicite  ORDER BY id ASC";
        $secteurs = FunctionController::arraySqlResult($sqlcs);
        $periodicite = FunctionController::arraySqlResult($sqlp);
    
    
        foreach ($lesvisuels as $r):
            $tab = pathinfo($r);
            $basename = $tab['basename'];
            $extension = $tab['extension'];
            $filename = $tab['filename'];
            $filenamebis = $filename;
            $condition = sprintf($cond, $filename);
            $inc = 1;
            $newfilename = FunctionController::cleanStr($filename) ;
            if($newfilename != $filename):
                $coolname = str_replace($filename, $newfilename, $r) ;
                if(rename($r, $coolname)):
                    $filename = $newfilename ;
                endif;
            else:
                $coolname = $r;
            endif;
        
            $str = explode(DIRECTORY_SEPARATOR,$r);
            $coolnamebis = $str[count($str)-1];
            $sql = "SELECT COUNT(*) as nbRow FROM " .DbTablesHelper::dbTable('DBTBL_RAPPORTS','db') . $cond;
            $nbrows = FunctionController::arraySqlResult($sql);
            while ($nbrows[0]['nbRow'] > 0):
                $filenamebis = "$filename-$inc";
                $inc++;
                $condition = sprintf($cond, $filenamebis);
            endwhile;
            $texte = str_replace('-', ' ', $filenamebis);
            $motcles = str_replace('-', ', ', $filenamebis);
            $newbasename = "$filenamebis.$extension";
            if($coolname != $dirc . $newbasename) :
                rename($coolname, $dirc . $newbasename);
            endif;
            $html .= view("administration.Rapports.formUniqueRapport",compact('secteurs','periodicite','newbasename','indic','texte','motcles','coolnamebis'))->render();
            $indic++;
        endforeach;
        return $html;
    }
    
    public function getFormRapport(Request $request){
        $form = self::makeFormVisuelRapport();
        return response()->json(['formRapport' => $form]);
    }
    
    public function validerRapport(Request $request){
            $data = array();
            $dirc = public_path("upload".DIRECTORY_SEPARATOR."rapports".DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR);
            $dirRapport = public_path("upload".DIRECTORY_SEPARATOR."rapports".DIRECTORY_SEPARATOR);
            $idForm = $request->get('id-form');
            $indice = $request->get('indice');
            $data['title'] = $request->get( 'title');
            $data['motcle'] = $request->get('motcles');
            $data['secteur'] = $request->get('secteur');
            $data['periodicite'] = $request->get('periodicite');
            $data['periode'] = $request->get('begindate'. $indice). " au " . $request->get('enddate'. $indice );
            $data['dateajout'] = date('Y-m-d');
            $data['file'] = $request->get('file', FILTER_SANITIZE_STRING);
            $data['user'] = Auth::id();
            $form_var = true;
            //dd($request->all(),$data);
            $newrapport = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_RAPPORTS','db'))
                ->insert($data);
            if ($newrapport) :
                $valider = true;
                $message = 'Rapport enregistré avec succès!';
                $alert = ' alert-success';
                $file = $data['file'];
                copy($dirc.$file,$dirRapport.$file);
                unlink($dirc.$file);
            else :
                $valider = false;
                $message = "Echec d'enregistrement du Rapport!";
                $alert = ' alert-danger';
            endif;
        return response()->json([
            'message' => $message,
            'alert' => $alert,
            'valider' => $valider,
            'idForm' => $idForm,
        ]);
    }
    
    public function delFormRapport(Request $request){
        $dirc = public_path("upload".DIRECTORY_SEPARATOR."rapports".DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR);
        $file = $request->get('file');
        $files = $dirc.$file;
        $suppr = unlink($files);
        if ($suppr) :
            $valider = true;
            $message = 'Rapport supprimé avec succès!';
            $alert = ' alert-success';
        else :
            $valider = false;
            $message = 'Echec de suppression du Rapport!';
            $alert = ' alert-danger';
        endif;
        return response()->json([
            'message' => $message,
            'alert' => $alert,
            'valider' => $valider,
            'idForm' => $request->input('idForm'),
        ]);
    
    }

    public function getFile(string $rapport){
        $rapp = decrypt($rapport);
        return response()->download(public_path('upload'.DIRECTORY_SEPARATOR.'rapports'.DIRECTORY_SEPARATOR.$rapp));
    }
    public function downloadFile(string $cid){
        $file = decrypt($cid);
        return response()->download($file);
    }
}
