<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Administration\IndicateurController;
use App\Http\Controllers\Administration\TableauDeBordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Models\Menu;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.w
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('logConnexion');
        //TableauDeBordController::sendSpeednewsMails(15125);
    }
    
    public static function menuTable(){
        $profil = intval(Auth::user()->profil);
        $tabM = Menu::with('groupemenu.icone', 'role.route', 'role.profilroles', 'menu_target', 'icone')
            ->whereHas('role', function($q) use ($profil) {
                $q->whereHas('profilroles', function($req) use ($profil) {
                    $req->where('profil', $profil);
                });
            })
            ->orderBy('groupemenu', 'ASC')->orderBy('rang', 'ASC')->get()->toArray();
            //dd($tabM);
        $data = [];
        $uri = Route::current()->uri;
        //$uri = "parametre/annonceurs/new";
        $active = false;
        $expended = [];
        foreach($tabM AS $r):
                $d = []  ; 
                $k = $r['groupemenu']['rang'];
                $gn = $r['groupemenu']['name'];
                $exp = $expended[$gn] ?? false;
                $expended[$gn] = $exp || $r['role']['uri'] == $uri ;
                $d['rang']  = $r['rang']  ; 
                $d['active']  = $r['role']['uri'] == $uri  ; 
                $d['title']  = $r['name']  ; 
                $d['menu_id']  = $r['id']  ; 
                $d['icone']  = $r['icone']['name']  ; 
                $d['url']  = $r['role']['uri']  ; 
                $d['menu_target']  = $r['menu_target']['name'] == 'Aucun' ? '' : $r['menu_target']['name'] ; 
                $d['route']  = $r['role']['route']  ; 
                $d['routeName']  = $r['role']['route']['name']  ; 
                $d['uri-parametres']  = $r['role']['parametres']  ; 
                if(!array_key_exists($k, $data)):
                    $data[$k] = [
                                    'groupemenu'    => $gn,
                                    'expanded'      => $expended[$gn],
                                    'sous-menus'    => [$r['rang'] => $d],
                                    'icone'         => $r['groupemenu']['icone']['name']
                                ];
                else:
                    $data[$k]['expanded'] = $expended[$gn];
                    $data[$k]['sous-menus'][$r['rang']] = $d;
                endif;
            //$data[$r['groupemenu']['rang']][$r['groupemenu']['name']] = $r;
        endforeach;
        ksort($data);
        return $data;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check ()):
            $user = Auth::id ();
            $indicateurs = new IndicateurController($user);
            //dd(Auth::user()->profil);
            return (int)Auth::user()->profil !== 6 ? view('homev1',compact('indicateurs')) : redirect('reporting');
        // return view('homev1',compact ('indicateurs'));
        else:
            return back ();
        endif;
    }
}
