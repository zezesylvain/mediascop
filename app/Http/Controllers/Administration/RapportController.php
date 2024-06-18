<?php

namespace App\Http\Controllers\Administration;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use App\Models\Rapport;
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
     public static function makeSpeedNewsData(int $ctid){
        dd(SpeedNew::with(
                'campagnetitle.operation.annonceur.secteur',
                'campagnetitle.docampagnes',
                'media',
                'support'
            )
            ->where('campagnetitle', $ctid)
            ->get()->toArray());
            
            
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
                    ORDER BY r.dateajout DESC";
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
            $basename = $tab['basename'] ?? '';
            $extension = $tab['extension'] ?? '';
            $filename = $tab['filename'] ?? '';
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

    public static function makeListeRapportsCharges()
    {
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

        $rapports = [];
        foreach ($lesvisuels as $r):
            $tab = pathinfo($r);
            $basename = $tab['basename'] ?? '';
            $extension = $tab['extension'] ?? '';
            $filename = $tab['filename'] ?? '';
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
            $indic++;
            $rapports[$indic] = [
                'secteurs' => $secteurs,
                'periodicite' => $periodicite,
                'newbasename' => $newbasename,
                'indic' => $indic,
                'texte' => $texte,
                'motcles' => $motcles,
                'coolnamebis' => $coolnamebis,
            ];
        endforeach;
        return view("administration.Rapports.TableauDesRapports",compact('rapports'))->render();
    }

    public function getFormRapport(Request $request){
        $form = self::makeFormVisuelRapport();
        return response()->json(['formRapport' => $form]);
    }
    
    public function validerRapport(Request $request){
        $indic = $request->input('id-form');
        $this->validate($request,[
            'secteur'.$indic => 'required',
            'title'.$indic => 'required',
            //'motcles'.$indic => 'required',
            'periodicite'.$indic => 'required',
            'begindate'.$indic => 'required',
            'enddate'.$indic => 'required',
        ],[
            "secteur{$indic}.required" => 'Le secteur est obligatoire.',
            "title{$indic}.required" => 'Le titre du rapport est obligatoire.',
            //"motcles{$indic}.required" => 'Les mots clés sont obligatoire.',
            "periodicite{$indic}.required" => 'La periodicité est obligatoire.',
            "begindate{$indic}.required" => 'La date de début validité est obligatoire.',
            "enddate{$indic}.required" => 'La date de fin validité est obligatoire.',

        ]);
        //dump($request->all());
        $data = array();
        $dirc = public_path("upload".DIRECTORY_SEPARATOR."rapports".DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR);
        $dirRapport = public_path("upload".DIRECTORY_SEPARATOR."rapports".DIRECTORY_SEPARATOR);
        $idForm = $request->get('id-form');
        $indice = $request->get('indice');
        $data['title'] = $request->get( 'title'.$indic);
        $data['motcle'] = $request->get('motcles'.$indic);
        $data['secteur'] = $request->get('secteur'.$indic);
        $data['periodicite'] = $request->get('periodicite'.$indic);
        $data['periode'] = $request->get('begindate'. $indic). " au " . $request->get('enddate'. $indic );
        $data['dateajout'] = date('Y-m-d');
        $data['file'] = $request->get('file'.$indic, FILTER_SANITIZE_STRING);
        $data['user'] = Auth::id();
        $data['auteurs'] = 'NS CONSULTING';
       // dd($data);
        $form_var = true;
        $newrapport = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_RAPPORTS','db'))
            ->insert($data);
        if ($newrapport) :
            $valider = true;
            $message = 'Rapport enregistré avec succès!';
            $alert = 'success';
            $file = $data['file'];
            copy($dirc.$file,$dirRapport.$file);
            unlink($dirc.$file);
        else :
            $valider = false;
            $message = "Echec d'enregistrement du Rapport!";
            $alert = 'echec';
        endif;
        $request->session()->flash($alert,$message);
       /* return response()->json([
            'message' => $message,
            'alert' => $alert,
            'valider' => $valider,
            'idForm' => $idForm,
        ]);*/
       return redirect()->back();
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
        if (file_exists(public_path('upload'.DIRECTORY_SEPARATOR.'rapports'.DIRECTORY_SEPARATOR.$rapp))):
            return response()->download(public_path('upload'.DIRECTORY_SEPARATOR.'rapports'.DIRECTORY_SEPARATOR.$rapp));
        else:
            Session::flash('success','Le fichier n\'existe pas!');
            return back();
        endif;
    }

    public function downloadFile(string $cid){
        $file = decrypt($cid);
        return response()->download($file);
    }
    public function deleteRapport(string $rid){
        $suppr = Rapport::destroy([$rid]);
        if ($suppr):
            Session::flash('success', 'Rapport supprimé avec succès!');
        else:
            Session::flash('echec', 'La suppression du rapport a échoué!');
        endif;
        return redirect()->back();
    }
}
