<?php

namespace App\Http\Controllers\Administration;

use App\Mail\Speednews;
use App\Models\User;
use App\Models\UserSpeednews;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
}
