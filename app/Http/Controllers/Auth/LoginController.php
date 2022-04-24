<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\core\UtilisateursController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo(){
        UtilisateursController::logConnexion ();
        //dd(Auth::user()->profil);
        return Auth::user()->profil !== 6 ? '/home' : '/reporting';
    }

    public function logout ( Request $request )
    {
        UtilisateursController::captureDeconnexion ();
        $this->guard ()->logout ();
        $request->session ()->invalidate ();
        return $this->loggedOut ($request) ?: redirect ('/');
    }
}
