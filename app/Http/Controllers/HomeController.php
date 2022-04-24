<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Administration\IndicateurController;
use App\Http\Controllers\Administration\TableauDeBordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return Auth::user()->profil !== 6 ? view('homev1',compact('indicateurs')) : redirect('reporting');
        // return view('homev1',compact ('indicateurs'));
        else:
            return back ();
        endif;
    }
}
