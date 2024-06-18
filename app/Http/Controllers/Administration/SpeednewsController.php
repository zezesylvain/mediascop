<?php

namespace App\Http\Controllers\Administration;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use App\Mail\SendSpeednews;
use App\Models\Media;
use App\Models\Reporting;
use App\Models\Secteur;
use App\Models\Speednew;
use App\Models\User;
use App\Models\UserSecteur;
use App\Models\UserSpeednews;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SpeednewsController extends Controller
{
    /**
     * @return Factory|Application|View
     */
    public function formAddUserSpeedNews()
    {
        $users = User::where('activated',1)
            ->orderBy('name','asc')
            ->get()->toArray();
        $userSpeednews = collect(UserSpeednews::where('actif',1)->get()->toArray())->map(function ($users){
            return $users['user'];
        })->toArray();
        return view('administration.Speednews.form',compact('users','userSpeednews'));
    }

    /**
     * @param array $speednewMessage
     * @param string $userMail
     * @return mixed
     */
    public static function envoieMail(array $speednewMessage,string $userMail)
    {
        return Mail::to($userMail)->send(new Speednews($speednewMessage));
    }

    /**
     * @param Request $request
     */
    public function addUserSpeedNews(Request $request)
    {
        $userID = $request->input('userID');
        $etat = $request->input('etat');
        if ($etat === 'true'):
            $userSpeedNews = UserSpeednews::where('user',$userID)->get();
            if ($userSpeedNews->count()):
               $userSpeedNews->actif = 1;
               $userSpeedNews->save();
            else:
                UserSpeednews::create([
                    'user' => $userID
                ]);
            endif;
        else:
            UserSpeednews::where('user',$userID)
                ->update([
                    'actif' => 0
                ]);
        endif;
    }

    public static function sendEmail(string $dt)
    {
        $h = 0;
        //$dt = date('H:i');
        switch ($dt):
            case '06:00':
                $h = 1;
                break;
            case '12:00':
                $h = 2;
                break;
            case '18:00':
                $h = 3;
                break;
        endswitch;
        if ($h !== 0):
            $speednews = self::getRecapSpeednews($h);
            if (!empty($speednews)):
                $leJourLetter = config("constantes.jourFr.".date('w'));
                $leJourNumber = date('d');
                $leMoisLetter = config("constantes.moisFr.".date('n'));
                $annee = date('Y');
                $ladateDuJour = $leJourLetter.', '.$leJourNumber.' '.$leMoisLetter.' '.$annee;
                foreach ($speednews as $speednew):
                    self::makeMailSpeednews($speednew,2,$ladateDuJour);
                endforeach;
            endif;
        endif;
    }

    public static function makeMailSpeednews(array $donnees,int $type,string $dateDay)
    {
        //dd($donnees);
        foreach($donnees['user'] as $r):
                Mail::to($r)->send(new SendSpeednews($donnees['statistique'],$dateDay,$type));
        endforeach;
    }

    public static function getInfosSpeednews(int $h)
    {
        $req = Speednew::with(
            'campagnetitle.operation.annonceur.secteur',
            'campagnetitle.docampagnes',
            'campagnetitle.operation.typeservice',
            'campagnetitle.campagnes.cible',
            'media',
            'support'
        );
        //$today = date('Y-m-d');
        $today = '2022-09-06';
        $m = date("m"); // Month value
        $de = date("d"); // Today's date
        $y = date("Y"); // Year value
        $m = "09"; // Month value
        $de = "06"; // Today's date
        $y = "2022"; // Year value
        if ($h === 1):
            $laDate = date('Y-m-d', mktime(0,0,0,$m,($de-1),$y));
            $q = $req->where('dateajout',$laDate);
        endif;
        if ($h === 2):
            $q = $req->where('dateajout','=',$today)
                ->whereTime('heure','>=','06:00:00')
                ->whereTime('heure','<=','12:00:00');
        endif;
        if ($h === 3):
            $q = $req->where('dateajout','=',$today)
                ->whereTime('heure','>=','06:00:00')
                ->whereTime('heure','<=','18:00:00');
        endif;
        $data = $q->get()->toArray();
        return $data;
    }

    public static function getNumberPubs(int $campagneTitleID,array $medias)
    {
        $res = [
            'TELEVISION' => 0,
            'RADIO' => 0,
            'PRESSE' => 0,
            'MOBILE' => 0,
            'AFFICHAGE' => 0,
            'INTERNET' => 0,
            'HORS MEDIA' => 0,
        ];
        $req = Reporting::with('campagnetitle','media')
            ->where('campagnetitle',$campagneTitleID)
            ->get()
            ->toArray();
        foreach ($medias as $media):
            foreach ($req as $item):
                if ($item['media']['id'] === $media['id']):
                    $res[$media['name']] += 1;
                else:
                    $res[$media['name']] = 0;
                endif;
            endforeach;
        endforeach;
        return $res;
    }

    public static function getRecapSpeednews(int $h)
    {
        $secteurs = Secteur::all()->toArray();

        $req = Speednew::with(
            'campagnetitle.operation.annonceur.secteur',
            'campagnetitle.reportings.media'
        );
        $laDate = date('Y-m-d');
        $m = date("m"); // Month value
        $de = date("d") - 1; // Today's date
        $y = date("Y"); // Year value
        if ($h === 1):
            $laDate = date('Y-m-d', mktime(0,0,0,$m,$de,$y));
            $q = $req->where('dateajout',$laDate);
        endif;
        if ($h === 2):
            $q = $req->where('dateajout','=',$laDate)
                ->whereTime('heure','>=','06:00:00')
                ->whereTime('heure','<=','12:00:00');
        endif;
        if ($h === 3):
            $q = $req->where('dateajout','=',$laDate)
                ->whereTime('heure','>=','06:00:00')
                ->whereTime('heure','<=','18:00:00');
        endif;
        $speednews = $q->get()->toArray();

        $speednewsSecteur = [];

        foreach ($secteurs as $secteur):
            foreach ($speednews as $speednew):
                if ($secteur['id'] === $speednew['campagnetitle']['operation']['annonceur']['secteur']['id']):

                    $speednewsSecteur[$secteur['id']]['speednews'][] = $speednew;
                    $speednewsSecteur[$secteur['id']]['user']= self::getUserSecteur($secteur['id']);
                endif;
            endforeach;
        endforeach;
        //dd($speednewsSecteur);
        $medias = Media::all()->toArray();
        $arr = [];
        if (!empty($speednewsSecteur)):
            foreach ($speednewsSecteur as $secteur => $speednews):
                foreach ($speednews['speednews'] as $speednew):
                    $mediaStats = self::getNumberPubs($speednew['campagnetitle']['id'],$medias);
                    $donnees = [
                        'ANNONCEUR' => $speednew['campagnetitle']['operation']['annonceur']['name'],
                        'TITRE D\'OPERATION' => $speednew['campagnetitle']['operation']['name'],
                        'TOT INSERTIONS' => count($speednew['campagnetitle']['reportings']),
                        'SECTEUR' => $speednew['campagnetitle']['operation']['annonceur']['secteur']['id'],
                    ];
                    $arr[$secteur]['statistique'][] = array_merge($mediaStats,$donnees);
                endforeach;
                $arr[$secteur]['user'] = $speednews['user'];
            endforeach;
        endif;
        return $arr;
    }

    public static function getUserSecteur(int $secteur)
    {
        $us = [];
        $users = UserSecteur::with('user')
            ->where('secteur',$secteur)
            ->where('actif',1)
            ->get()->toArray();
        foreach ($users as $user):
            $us[] = $user['user']['email'];
        endforeach;
        return $us;
    }

    public static function formatColumn(int $val)
    {
        if ($val === 0):
            return '';
        else:
            return $val;
        endif;
    }
}
