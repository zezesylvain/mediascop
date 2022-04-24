<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Speednew;
use App\Models\Reporting;
use App\Models\Docampagne;
use App\Models\Campagnetitle;

class PlanMediaController extends Controller
{
    public static function  getPlanMediaDataForCampagne(int $cid):array{
        $data = [];
        $tblReportings = DbTablesHelper::dbTable("DBTBL_REPORTINGS",'db') ;
        $tableMedia =  DbTablesHelper::dbTable("DBTBL_MEDIAS",'db') ;
        $tableSupport =  DbTablesHelper::dbTable("DBTBL_SUPPORTS",'db') ;
        $query = "
                SELECT 
                        COUNT(*) AS insertion, SUM(`tarif`) AS invest, `media`, `support`, `date` 
                FROM $tblReportings 
                WHERE `campagnetitle` = $cid GROUP BY `media`, `support`, `date` 
                ORDER BY `date` ASC";

        $donnees = FunctionController::arraySqlResult($query);
        $periode = ['debut' => "", 'fin' => ''];
        $i = 0;
        $lesDatesForPM = [];
        $lesDatesMoisForPM = [];
        $dataPMCampagne = [];
        $dataPMBySupport = [];
        $totalSupport = [];
        $totalMedia = [];
        $dataPMByDate = [];
        $tabMedias = [];
        $tabSupports = [];
        foreach($donnees AS $d):
            extract($d);
            if($i == 0):
                $periode = [
                              "debut" =>  ['date' => $date, 'support' => $support],
                               "fin" => ['date' => $date, 'support' => $support]
                            ];
            else:
                if($date > $periode['fin']['date']):
                    $periode['fin'] =  ['date' => $date, 'support' => $support];
                endif;
            endif;
            $dataPMBySupport[$media][$support][$date] = [
                                            'insertion' => $insertion, 
                                            'invest' =>  $invest
                                        ] ;
            $dataPMByDate[$date][$media][$support] = [
                                            'insertion' => $insertion, 
                                            'invest' =>  $invest
                                        ] ;
            $dateur = self::makeDateTab($date);
            extract($dateur) ;
            if(!array_key_exists($media, $totalMedia)):
                $tabMedias[] = $media;
                $totalMedia[$media] =[
                                            'insertion' => 0, 
                                            'invest' =>  0
                                        ] ;
            endif;
            $totalMedia[$media]['insertion'] += $insertion;
            $totalMedia[$media]['invest'] += $invest;
            if(!array_key_exists($support, $totalSupport)):
                $tabSupports[] = $support;
                $totalSupport[$support] = [
                                'insertion' => 0, 
                                'invest' =>  0
                            ] ;
            endif;
            $totalSupport[$support]['insertion'] += $insertion ;
            $totalSupport[$support]['invest'] += $invest; 
            if(!array_key_exists($date, $lesDatesForPM)):
                $lesDatesForPM[$date] = $jour;
                $lesDatesMoisForPM[$mois][] = $date;
            endif;
            $i++;
        endforeach;
        $lesMedias = self::getParamName($tableMedia, $tabMedias);
        $lesSupports = self::getParamName($tableSupport, $tabSupports);
        return compact('donnees', 'lesDatesForPM', 'lesDatesMoisForPM', 
                        'dataPMBySupport', 'totalMedia', 'totalSupport',
                        'dataPMByDate', 'periode', 'lesMedias', 'lesSupports');
    }
    public static function getDocCampagne(int $cid):array{
        $docs = [];
        $lesd = Docampagne::where('campagnetitle', $cid)->get()->toArray();
        foreach ($lesd AS $r):
            $media = $r['media'];
            $type = $r['type'];
            if (in_array($type, ['jpg','jpeg','png','gif','svg'])):
                $docs[$media]['img'][] = $r;
            endif;
            if (in_array($type, ['mp3','wav'])):
                $docs[$media]['audio'][] = $r;
            endif;
            if (in_array($type, ['mp4','ogg'])):
                $docs[$media]['video'][] = $r;
            endif;
        endforeach;
        return $docs;
    }
    public static function makeDateTab($date){
        $t = explode('-', $date) ;
        $mois = date("M-Y", mktime(0,0,0,$t[1],$t[2], $t[0])) ;
        $jour = $t[2];
        return compact('mois', 'jour');
    }
    public static function getParamCampagne($cid){
        $d1 = Campagnetitle::with('operation.annonceur.secteur')->where('id', $cid)->get()->toArray();
        $data = [];
        if(!empty($d1)):
            $d = $d1[0];
            $data['Titre'] = $d['title'];
            $data['Operation'] = $d['operation']['name'];
            $data['Annonceur'] = $d['operation']['annonceur']['raisonsociale'];
            $data['Secteur'] = $d['operation']['annonceur']['secteur']['name'];
        endif;
        return $data;
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
    public static function getReportingData(int $cid){
        $laCampagne = Campagnetitle::with('operation.annonceur.secteur')->where('id', $cid)->get()->toArray();
        $data = Reporting::where('campagnetitle', $cid)->orderBy('date', 'ASC')->get()->toArray();
        extract(self::initialise());
        if(count($laCampagne)):
            $laC = $laCampagne[0];
            $infoCampagne = [
                        'id'            => $laC['id'], 'title'    => $laC['operation']['title'],
                        'annonceur'     => $laC['operation']['annonceur']['name'],
                        'secteur'       => $laC['operation']['annonceur']['secteur']['name'],
                        'investTotal'   => 0,
                        'invest'        => [],
                        'insertionTotal'=> 0,
                        'insertion'     => []
                ];
            $lesMedias[] = $r['media'];
            $lesSupports[] = $r['support'];
            $lesMedias[] = $r['media'];
            $lesMedias[] = $r['media'];
            $lesMedias[] = $r['media'];
            $lesMedias[] = $r['media'];
        endif;
        foreach($data AS $r):
            $infoCampagne['investTotal'] += $r['invest'];
            $infoCampagne['insertion'] += $r['insertion'];
        endforeach;
    }
    public static function initialise(){
        
        $lesDatesForPM = [];
        $lesDatesMoisForPM = [];
        $dataPMCampagne = [];
        $dataPMBySupport = [];
        $totalSupport = [];
        $totalMedia = [];
        $dataPMByDate = [];
        $tabMedias = [];
        $tabSupports = [];
        $infoCampagne = [];
        return compact('lesDatesForPM', 'tabMedias', 'dataPMByDate', 'totalMedia', 
                        'totalSupport', 'dataPMBySupport', 'dataPMCampagne', 
                        'lesDatesMoisForPM', 'tabSupports', 'infoCampagne');
    }
    public static function GenerateInverseColor($hexcolor){

        $hexcolor       = trim($hexcolor);

        $r = hexdec(substr($hexcolor,0,2));
        $g = hexdec(substr($hexcolor,2,2));
        $b = hexdec(substr($hexcolor,4,2));
        $yiq = (($r*299)+($g*587)+($b*114))/1000;
        return ($yiq >= 128) ? 'black' : 'white';

}
}
