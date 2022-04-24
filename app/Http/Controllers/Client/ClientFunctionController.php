<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\core\FunctionController;
use App\Helpers\DbTablesHelper;
use App\Http\Controllers\Client\PlanMediaController;

class ClientFunctionController extends Controller
{
    public static function showDocCampagne(int $cid){
        $resdoc = Session::get('docCampagnes');
        //dd($resdoc);
        $lesDocuments = [];
        if (!empty($resdoc[$cid])):
            $documents = [];
            $cheminfichier = 'upload'.DIRECTORY_SEPARATOR.'campagnes'.DIRECTORY_SEPARATOR.$cid;
            foreach ($resdoc[$cid] as $media => $r):
                foreach ($r as $data):
                    if (in_array($data['type'], ['jpg','jpeg','png','gif','svg'])):
                        $documents[$media]['img'][] = $data;
                    endif;
                    if (in_array($data['type'], ['mp3','wav'])):
                        $documents[$media]['audio'][] = $data;
                    endif;
                    if (in_array($data['type'], ['mp4','ogg'])):
                        $documents[$media]['video'][] = $data;
                    endif;
                endforeach;
            endforeach;
            $medias = Session::get("detailDesCampagnes.$cid.lesMediasDeLaCampagne");
            $tableMedia =  DbTablesHelper::dbTable("DBTBL_MEDIAS",'db') ;
            $lesMedias = self::getParamName($tableMedia, [1, 2, 3, 4, 5, 6]);
            //dd($documents, $lesMedias, $cheminfichier) ;
            $lesDocuments = compact('cheminfichier','documents','medias', 'lesMedias');
            return $lesDocuments;
            //return view('clients.campagnes.showDocCampagne',compact('cheminfichier','documents','medias', 'lesMedias'))->render();
        endif;
    }
    public static function getParamName(string $table, array $tab, string $colName = "name"): array{
        $data = [];
        $query = "
                SELECT id, $colName FROM $table 
                WHERE id IN (" . join(' ,', $tab) . ")
                ";
        $donnees = FunctionController::arraySqlResult($query);
        foreach($donnees AS $r):
            $data[$r['id']] = $r[$colName] ;
        endforeach;
        return $data;
    }

    public function detailCampagne(string $cid){
        $dataCampagneForPM = PlanMediaController::getPlanMediaDataForCampagne($cid); 
        $documents  = PlanMediaController::getDocCampagne($cid);
        $res = PlanMediaController::getParamCampagne($cid);
        $cheminfichier = 'upload'.DIRECTORY_SEPARATOR.'campagnes'.DIRECTORY_SEPARATOR.$cid;
        return  view('clients-v2.campagnes.detail', compact('res', 'documents', 'cid', 'dataCampagneForPM', 'cheminfichier'));
        /*
        $view = "";
        if(!empty($res)):
            $docCamp = '';
            if (Session::has('docCampagnes') && array_key_exists($cid,Session::get('docCampagnes'))):
                $docCamp = self::showDocCampagne($cid);
                
            endif;
            return  view('clients-v2.campagnes.detail', compact('res','docCamp', 'cid', 'dataCampagneForPM'));
        endif;
        return $view;//*/
    }
}
