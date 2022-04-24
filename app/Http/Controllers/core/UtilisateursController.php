<?php

namespace App\Http\Controllers\core;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\Client\AdminController;
use App\Http\Requests\RenitPassWordRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class UtilisateursController extends Controller
{

    protected $table;

    public function __construct()
    {
        $this->table = DbTablesHelper::dbTablePrefixeOff('DBTBL_USERS');
    }

    public function create(){
        $table = $this->table;
        $today = date('Y-m-d');
        $enddate =  date("Y-m-d", strtotime("+1 year"));

        $userProfilID = Auth::user()->profil;
        $profilTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_PROFILS'));
        $level = FunctionController::getChampTable($profilTable,$userProfilID,'level');
        $lesprofils = FunctionController::arraySqlResult("SELECT * FROM $profilTable WHERE level <= $level");
        $cond = " WHERE profil IN(SELECT id FROM $profilTable WHERE level <= $level)";
        $options = ['routeUpdate' => 'updateUsers'];
        //$options = [];
        $listeUsers = ModuleController::makeTable($this->table,$cond,$options);
    
        $secteurs = FunctionController::arraySqlResult("SELECT * FROM
            ".DbTablesHelper::dbTable('DBTBL_SECTEURS','db')
            ." ORDER BY name ASC");
        
        $annonceurs = FunctionController::arraySqlResult("SELECT * FROM
            ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')
            ." ORDER BY name ASC");

        return view('template.Utilisateurs.form', compact('table','today','enddate','lesprofils','listeUsers','secteurs','annonceurs'));
    }
    
    public function storeUser(Request $request){
        $data = $request->all();
        unset($data['_token'],$data['table']);
        if (!array_key_exists('id',$data)):
           // dd(array_key_exists('secteur',$data) && !is_null($data['secteur']));
            unset($data['nom'],$data['prenoms'],$data['secteur'],$data['annonceur']);
            $data['password'] = bcrypt($request->get('password'));
            $data['name'] = $request->nom." ".$request->prenoms;
            $queries = DB::transaction(function () use($data,$request){
                $insert = DB::table(DbTablesHelper::dbTable('DBTBL_USERS'))
                    ->insertGetId($data);
                if (array_key_exists('secteur',$request->all()) && !is_null($request->input('secteur'))):
                    DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USER_SECTEURS','db'))
                                ->insert([
                                    'user' => $insert,
                                    'secteur' => $request->input('secteur'),
                                ]);
                endif;
                if (array_key_exists('annonceur',$request->all()) && !is_null($request->input('annonceur'))):
                    DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USERS_ANNONCEURS','db'))
                        ->insert([
                            'user' => $insert,
                            'annonceur' => $request->input('annonceur'),
                        ]);
                endif;
                return true;
            });
            if ($queries):
                $data['id'] = $queries;
                $request->session()->flash('success', "Utilisateur ".$data['name']." ajouté !");
            else:
                $request->session()->flash('echec', "Une erreur est survenue. Veuillez récommencer !");
            endif;
            //return back();
        else:
            if (array_key_exists('password',$data)):
                $data['password'] = bcrypt($request->get('password'));
            endif;
            $queries = DB::transaction(function () use($data,$request){
                unset($data['secteur'],$data['annonceur']);
                $insert = DB::table(DbTablesHelper::dbTable('DBTBL_USERS'))
                    ->where('id',$request->id)
                    ->update($data);
                if (array_key_exists('secteur',$request->all()) && !is_null($request->input('secteur'))):
                    $verSecteur = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USER_SECTEURS','db'))
                        ->where([
                            'user' => $data['id'],
                            'secteur' => $request->input('secteur'),
                            'actif' => 1,
                        ])->get() ;
                    if (count($verSecteur) === 1 && $verSecteur[0]->secteur !== $request->input('secteur')):
                        $upd = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USER_SECTEURS','db'))
                            ->where([
                                'user' => $data['id'],
                                'secteur' => $request->input('secteur'),
                                'actif' => 1,
                            ])->update(['actif' => 0]);
                        if ($upd):
                            DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USER_SECTEURS','db'))
                                ->insert([
                                    'user' => $data['id'],
                                    'secteur' => $request->input('secteur'),
                                ]);
                            $request->session()->forget("UsersSecteurs.{$data['id']}");
                            $request->session()->save();
                        endif;
                    else:
                        DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USER_SECTEURS','db'))
                            ->insert([
                                'user' => $data['id'],
                                'secteur' => $request->input('secteur'),
                            ]);
                    endif;
                    //AdminController::getUserSecteur($data['id']);
                endif;

                if (array_key_exists('annonceur',$request->all()) && !is_null($request->input('annonceur'))):
                    $verAnnonceur = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USERS_ANNONCEURS','db'))
                        ->where([
                            'user' => $data['id'],
                            'annonceur' => $request->input('annonceur'),
                            'actif' => 1,
                        ])->get() ;
                    if (count($verAnnonceur) && $verAnnonceur[0]->annonceur !== $request->input('annonceur')):
                        $upd = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USERS_ANNONCEURS','db'))
                            ->where([
                                'user' => $data['id'],
                                'annonceur' => $request->input('annonceur'),
                                'actif' => 1,
                            ])->update(['actif' => 0]);
                        if ($upd):
                            DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USERS_ANNONCEURS','db'))
                                ->insert([
                                    'user' => $data['id'],
                                    'annonceur' => $request->input('annonceur'),
                                ]);
                            $request->session()->forget("UsersSecteurs.{$data['user']}");
                            $request->session()->save();
                        endif;
                    else:
                        DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USERS_ANNONCEURS','db'))
                            ->insert([
                                'user' => $data['id'],
                                'annonceur' => $request->input('annonceur'),
                            ]);
                    endif;
                    //AdminController::getUserSociete($data['id']);
                endif;
                return $insert;
            });
            if ($queries):
                $request->session()->flash('success', "Les informations de l'Utilisateur ".$data['name']." Modifiées avec succès !");
            else:
                $request->session()->flash('echec', "Une erreur est survenue. Veuillez récommencer !");
            endif;
        endif;

        return back();
    }

    public function updateUser(Request $request){
        $data = $request->all();
        unset($data['_token']);
        unset($data['table']);
        unset($data['userid']);
        if (array_key_exists('password',$data)):
            $data['password'] = bcrypt($request->get('password'));
        endif;
        $queries = DB::transaction(function () use($data,$request){
            $insert = DB::table(DbTablesHelper::dbTable('DBTBL_USERS'))
                ->where('id',$request->userid)
                ->update($data);
            return $insert;
        });

        if ($queries):
            $request->session()->flash('success', "Les informations de l'Utilisateur ".$data['name']." Modifiées avec succès !");
        else:
            $request->session()->flash('echec', "Une erreur est survenue. Veuillez récommen&ccedil;er !");
        endif;
        return back();
    }

    public static function GetUserRoles(){
        if (!Session::has("UserRoles")):
            $userProfil = Auth::user()->profil;
            $sql = "SELECT role FROM 
            ".FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_PROFILROLES'))." WHERE
            profil = $userProfil 
            ";
            $res = FunctionController::arraySqlResult($sql);
            $Tab = [];
            foreach ($res as $re):
                $Tab[] = $re['role'];
            endforeach;
            Session::put("UserRoles", $Tab);
        endif;
        return Session::get("UserRoles");
    }
    
    public function monCompte(){
        $userid = Auth::id();
        return view('woody.Tma.formMyPasswordUpdate',compact('userid'));
    }

    public function renitMyPassword(RenitPassWordRequest $request){
        $data = $request->all();
        unset($data['_token']);
        $user = Auth::user ();
        if(Hash::check($data['ancien_mot_de_passe'], $user->password)):
            if($data['nouveau_mot_de_passe'] == $data['password']):
                unset($data['ancien_mot_de_passe']);
                unset($data['nouveau_mot_de_passe']);
                $lesdonnees = $data;
                
                $lesdonnees['password'] = bcrypt($data['password']);
                DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USERS'))
                    ->where('id', $user->id)
                    ->update($lesdonnees);

                $request->session()->flash('success', 'Votre mot de passe a été réïnitialisé avec succès !');
            else:
                $request->session()->flash('echec','Le mot de passe de confirmation ne correspond pas à votre nouveau mot de passe!');
            endif;
        else:
            $request->session()->flash('echec','Le mot de passe renseigné ne correspond pas!');
        endif;
        return back();
    }

    public static function getUserInterface(){
        $modules = UtilisateursController::modulesUser();
        $tableModule = FunctionController::getTableName( DbTablesHelper::dbTable('DBTBL_MODULES'));
        return view("admin.home",compact('tableModule','modules'));
    }

    public static function  modulesUser(){
        if (!Session::has("modulesUtilisateur")):
            $tablgpm = DbTablesHelper::dbTable('DBTBL_GROUPEMENUS');
            $tablMenu = DbTablesHelper::dbTable('DBTBL_MENUS');
            $tableGroupeMenu = FunctionController::getTableName($tablgpm);
            $tableMenu = FunctionController::getTableName($tablMenu);
            $UserRoles = UtilisateursController::GetUserRoles();

            $TabRoleUser = [3];
            $TabRoleUser = !count($UserRoles) ? array_merge($TabRoleUser,$UserRoles) : $UserRoles;
            $cond = join(',',$TabRoleUser);
            //$grpDeMenus = FunctionController::arraySqlResult("SELECT groupemenu FROM $tableMenu WHERE role IN ($cond)");
            $modules = FunctionController::arraySqlResult("SELECT DISTINCT module FROM $tableGroupeMenu WHERE id IN(SELECT DISTINCT groupemenu FROM $tableMenu WHERE role IN ($cond))");
            $res = [];
            if (count($modules)):
                foreach ($modules as $module):
                    $res[] = $module['module'];
                endforeach;
            endif;
            Session::put('modulesUtilisateur',$res);
        endif;
        return Session::get('modulesUtilisateur');
    }

    public function updateUsers($table,$id){
        $data = DbTablesHelper::getDataById($table,$id);
        $userProfilID = Auth::user()->profil;
        $profilTable = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_PROFILS'));
        $level = FunctionController::getChampTable($profilTable,$userProfilID,'level');
        $lesprofils = FunctionController::arraySqlResult("SELECT * FROM $profilTable WHERE level <= $level");
        $cond = " WHERE profil IN(SELECT id FROM $profilTable WHERE level <= $level)";
        $options = ['routeUpdate' => 'updateUsers'];
        $listeUsers = ModuleController::makeTable($this->table,$cond,$options);
        $secteurs = FunctionController::arraySqlResult("SELECT * FROM
            ".DbTablesHelper::dbTable('DBTBL_SECTEURS','db')
            ." ORDER BY name ASC");
        $annonceurs = FunctionController::arraySqlResult("SELECT * FROM
            ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')
            ." ORDER BY name ASC");
        $userSecteur = AdminController::getUserSecteur($id);
        $userAnnonceur = AdminController::getUserSociete($id);
        $secteur = 0;
        $annonceur = 0;
        if ($userSecteur !== '-'):
            $us = FunctionController::arraySqlResult("SELECT * FROM
            ".DbTablesHelper::dbTable('DBTBL_USER_SECTEURS','db')
                ." WHERE user = $id AND actif = 1 ");
            $secteur = $us[0]['secteur'];
        endif;
        if ($userAnnonceur !== '-'):
            $ua = FunctionController::arraySqlResult("SELECT * FROM
            ".DbTablesHelper::dbTable('DBTBL_USERS_ANNONCEURS','db')
                ." WHERE user = $id AND actif = 1 ");
            $annonceur = $ua[0]['annonceur'];
        endif;
        return view('template.Utilisateurs.form', compact('table','lesprofils','listeUsers','data','secteurs','annonceurs','annonceur','secteur'));
    }

    public function reinitPassword(Request $request){
        if ($request->v == 'yes'):
            $newMDP = FunctionController::genererMDP();
            return view("template.Utilisateurs.reinitPassword",compact('newMDP'))->render();
        else:
            return '';
        endif;
    }

    public static function getUserId(){
        return Auth::id ();
    }

    public static function logConnexion(){
        $userID = Auth::id ();
        $timeLife = time () + intval (env ("SESSION_LIFETIME"))*60;
        DB::transaction (function () use($userID,$timeLife){
            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_LOG_CONNEXIONS'))
                ->where('timelife','<', time ())
                ->update ([
                    'actif' => 0
                ]);

            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_LOG_CONNEXIONS'))
                ->where ([
                    'user' => $userID,
                    'actif' => 1,
                    ])
                ->update ([
                    'actif' => 0,
                    'timelife' => time (),
                ]);

            DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_LOG_CONNEXIONS'))
                ->insert ([
                    'user' => $userID,
                    'actif' => 1,
                    'timelife' => $timeLife,
                    'session_id' => Session::getId (),
                    'ip' => \Request::ip(),
                ]);
        });
    }

    public static function captureDeconnexion(){
        $userID = Auth::id ();
        DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_LOG_CONNEXIONS'))
            ->where ([
                'user' => $userID,
                'ip' => \Request::ip(),
                'actif' => 1,
            ])
            ->update ([
                'actif' => 0,
                'timelife' => time ()
            ]);
    }

    public static function UsersConnected(){
        $tUser = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_USERS'));
        $tLogConn = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_LOG_CONNEXIONS'));
        $tProfil = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_PROFILS'));
        $userID = Auth::id ();
        $users = FunctionController::arraySqlResult ("SELECT u.*,p.name as profilName FROM 
            $tUser u, $tLogConn lc, $tProfil p
             WHERE lc.actif = 1 AND lc.user = u.id AND u.profil = p.id 
             AND u.id NOT IN (SELECT id FROM $tUser WHERE id = $userID)
             ORDER BY lc.created_at DESC ");
        return view ("template.Utilisateurs.listeUsersConnectes", compact ('users'))->render ();
    }

    public static function numberUserConnected(){
        $nbre = FunctionController::arraySqlResult ("SELECT COUNT(user) AS nbre FROM ".FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_LOG_CONNEXIONS'))." WHERE actif = 1");
        return $nbre[0]["nbre"];
    }

    public function monProfil(){
        $view = 0;
        return self::makeProfilView ($view);
    }

    public static function makeProfilView(int $view):string {
        $userID = Auth::id ();
        $table = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_USERS'));
        $profilTable = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_PROFILS'));
        $username = FunctionController::getChampTable ($table,$userID,"name");
        $profilID = FunctionController::getChampTable ($table,$userID,"profil");
        $userProfil = FunctionController::getChampTable ($profilTable,$profilID,"name");
        return view ("woody.Utilisateurs.monProfil", compact ('userID','username','userProfil','view'));
    }

    public static function photoUser(int $userID,string $taille = "Moyen"):string {
        $rep = "upload".DIRECTORY_SEPARATOR."Profils";
        $dir = public_path ($rep);
        if (!is_dir ($dir)):
           mkdir ($dir);
        endif;
        $dir1 = $dir.DIRECTORY_SEPARATOR.$userID;
        if (!is_dir ($dir1)):
            mkdir ($dir1);
        endif;
        $photoDir = $dir1.DIRECTORY_SEPARATOR."Photos";
        if (!is_dir ($photoDir)):
            mkdir ($photoDir);
        endif;
        $files = glob ($photoDir.DIRECTORY_SEPARATOR.'*');
        $photoProfil = "";
        if (count ($files)):
            $path = pathinfo ($files[0]);
            $photoProfil = asset ($rep.DIRECTORY_SEPARATOR.$userID.DIRECTORY_SEPARATOR."Photos".DIRECTORY_SEPARATOR.$userID.'-'.$taille.'.'.$path['extension']);
        endif;
        return view ("woody.Utilisateurs.photoProfil",compact ('photoProfil'));
    }

    public function changerPhotoProfil(){
        return self::makeProfilView (1);
    }
    public function chargerUserPhoto(Request $request){
        request()->validate ([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $photo = $request->file("photo");
        $userID = Auth::id ();
        $rep = "upload".DIRECTORY_SEPARATOR."Profils".DIRECTORY_SEPARATOR.$userID;
        $dir = public_path ($rep);
        if (!is_dir ($dir)):
            mkdir ($dir);
        endif;
        $photoDir = $dir.DIRECTORY_SEPARATOR."Photos";
        if (!is_dir ($photoDir)):
            mkdir ($photoDir);
        endif;
        $avatar = $photo->getRealPath ();
        $tailles = ['Petit','Moyen','Grand'];
        $img = Image::make($avatar);
        foreach ($tailles as $t):
            if ($t == 'Petit'):
               $img->fit(76,76);
            endif;
            if ($t == 'Moyen'):
                $img->fit(200,200);
            endif;
            if ($t == 'Grand'):
                $img->fit(600);
            endif;
            $img->save(public_path ().DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."Profils".DIRECTORY_SEPARATOR.$userID.DIRECTORY_SEPARATOR."Photos".DIRECTORY_SEPARATOR.$userID.'-'.$t.'.'.strtolower ($photo->extension ()));
        endforeach;
        return back ();
    }

    public function validerPhoto(Request $request) {
        $photo = $request->input("photo");
    }

    public static function chercherPhotoUtilisateur(int $userID,string $taille):string {
        $rep = "upload".DIRECTORY_SEPARATOR."Profils";
        $dir = public_path ($rep);
        if (!is_dir($dir)):
            mkdir($dir);
        endif;
        $dir1 = $dir.DIRECTORY_SEPARATOR.$userID;
        if (!is_dir ($dir1)):
            mkdir ($dir1);
        endif;
        $photoDir = $dir1.DIRECTORY_SEPARATOR."Photos";
        if (!is_dir ($photoDir)):
            mkdir ($photoDir);
        endif;
        $photoProfil = "<i class=\"fa fa-user fa-5x\"></i>";
        $files = glob ($photoDir.DIRECTORY_SEPARATOR.'*');
        if (count ($files)):
            $path = pathinfo ($files[0]);
            $uri = asset ($rep.DIRECTORY_SEPARATOR."Photos".DIRECTORY_SEPARATOR.$userID.'-'.$taille.'.'.$path['extension']);
            $photoProfil = "<img src='$uri' alt='' />";
        endif;
        return $photoProfil;
    }

    public function droitsUtilisateurs(int $profilID) {
        $userProfil = Auth::user()->profil;
        $grpMenus = FunctionController::arraySqlResult ("SELECT * FROM 
            ".FunctionController::getTableName(DbTablesHelper::dbTable ('DBTBL_GROUPEMENUS'))." 
            ORDER BY name ASC");
        $tabs = [];
        $sql = "SELECT * FROM ".FunctionController::getTableName(DbTablesHelper::dbTable ('DBTBL_MENUS'))." WHERE groupemenu = %d ";

        foreach ($grpMenus as $r):
            $menus = FunctionController::arraySqlResult (sprintf ($sql,$r['id']));
            if (count ($menus)):
                foreach ($menus as $v):
                   $tabs[$r['id']][$v['id']] = $v['role'];
                endforeach;
            endif;
        endforeach;
        dd ($profilID,$grpMenus,$tabs);

    }

    public static function UserSecteur(){
        $req = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USER_SECTEURS','db'))
            ->where('user',Auth::id())
            ->get();
        return $req->count() ? $req[0]->secteur : 0;
    }
}
