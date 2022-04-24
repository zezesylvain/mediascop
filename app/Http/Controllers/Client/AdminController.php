<?php

namespace App\Http\Controllers\Client;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    protected $data;

    public function __construct ()
    {
        $this->data = \request ()->all ();
    }

    public static function formdate(int $num, array $option = array()):string {
        $j = date('j');
        Session::put('formvar.date.jour'.$num.'', $j) ;
        $opselectjour = '';
        for($i = 1; $i<= 31; $i++):
            $select = $i == $j ? '  selected="selected"' : '' ;
            $opselectjour .= '<option'.$select.' value="'.$i.'">'.$i.'</option>' ;
        endfor;
        $optjour = "";
        $option["j$num"] = $optjour;

        $m = date('n');
        $mois = array('Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Decembre') ;
        $opselectmois = '';
        Session::put('formvar.date.mois'.$num.'', $m) ;
        for($i = 1; $i<= 12; $i++):
            $select = $i == $m ? '  selected="selected"' : '' ;
            $opselectmois .= '<option'.$select.' value="'.$i.'">'.$mois[$i-1].'</option>' ;
        endfor;
        $optmois = "";
        $option["m$num"] = $optmois;

        $y = date('Y') ;
        $opselectyear = '';
        Session::put('formvar.date.annee'.$num.'', $y);
        $opselectyear .=  '<option selected="selected"  value="'.$y.'">'.$y.'</option>' ;
        for($i = 1; $i<= 5; $i++):
            $yy = $y - $i ;
            $opselectyear .=  '<option value="'.$yy.'">'.$yy.'</option>' ;
        endfor;
        $optannee = "";
        $option["y$num"] = $optannee;
        Session::save ();
        return view('clients.formReporting.inputdate',compact('opselectyear','opselectmois', 'opselectjour', 'option','num'))->render ();
    }
    
    public function gestionDesAbonnements(){
        $tSecteur = DbTablesHelper::dbTable('DBTBL_SECTEURS','db');
        $tUser = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_USERS'));
        $tProfil = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_PROFILS'));
        $userID = Auth::id();
        $profilID = FunctionController::getChampTable($tUser,$userID,'profil');
        $level = FunctionController::getChampTable($tProfil,$profilID,'level');
        $utilisateurs = FunctionController::arraySqlResult("SELECT u.* FROM $tUser u WHERE profil IN(SELECT id FROM $tProfil WHERE level <= $level) ORDER BY name ASC");
        return view("administration.Utilisateurs.listeDesAbonnements",compact('utilisateurs','tProfil'));
    }
    
    public static function getUserSecteur(int $userID){
        if (!Session::has("UsersSecteurs")):
            Session::put("UsersSecteurs",[]);
        endif;
        if (!Session::has("UsersSecteurs.$userID")):
            $tUsersSecteurs = DbTablesHelper::dbTable('DBTBL_USER_SECTEURS','db');
            $tSecteurs = DbTablesHelper::dbTable('DBTBL_SECTEURS','db');
            $ua = FunctionController::arraySqlResult("SELECT s.* FROM
                $tUsersSecteurs us,
                $tSecteurs s
                  WHERE us.user = $userID AND us.secteur = s.id AND us.actif = 1");
            $d = count($ua) ? $ua[0]['name'] : "-";
            Session::put("UsersSecteurs.$userID",$d);
        endif;
        return Session::get("UsersSecteurs.$userID");
    }
    
    public static function getUserSociete(int $userID){
        if (!Session::has("UsersAnnonceurs")):
            Session::put("UsersAnnonceurs",[]);
        endif;
        if (!Session::has("UsersAnnonceurs.$userID")):
            $tUsersAnnonceurs = DbTablesHelper::dbTable('DBTBL_USERS_ANNONCEURS','db');
            $tAnnonceurs = DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db');
            $ua = FunctionController::arraySqlResult("SELECT a.* FROM
                $tUsersAnnonceurs ua,
                $tAnnonceurs a
                  WHERE ua.user = $userID AND ua.annonceur = a.id AND ua.actif = 1");
            $d = count($ua) ? $ua[0]['name'] : "-";
            Session::put("UsersAnnonceurs.$userID",$d);
        endif;
        return Session::get("UsersAnnonceurs.$userID");
    }
    
    public  function chercherFormAbonnement(Request $request){
       $userSecteur = self::makeFormAbonnementSecteur($request->userID);
       $userSociete = self::makeFormAbonnementSociete($request->userID);
       $nomUtilisateur = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_USERS'),$request->userID);
       $tab = ['formAbonnementSecteur' => $userSecteur,
               'formAbonnementSociete' => $userSociete,
               'nomUtilisateur' => $nomUtilisateur,
           ];
      // dd($tab);
       return response()->json($tab);
    }
    
    public static function makeFormAbonnementSecteur(int $userID):array {
        $userSecteur = self::getUserSecteur($userID);
        $secteurs = FunctionController::arraySqlResult("SELECT * FROM
            ".DbTablesHelper::dbTable('DBTBL_SECTEURS','db')
            ." ORDER BY name ASC");
        if ($userSecteur !== '-'):
            $uSect = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_USER_SECTEURS','db')
                ." WHERE user = $userID AND actif = 1");
            $pid = $uSect[0]['id'];
            $uSecteur = $uSect[0]['secteur'];
        else:
            $uSecteur = 0;
            $uSect = [];
            $pid = 0;
        endif;
        return [
            'secteurs' => $secteurs,
            'userID' => $userID,
            'uSect' => $uSect,
            'userSecteur' => $userSecteur,
            'uSecteur' => $uSecteur,
            'pid' => $pid,
        ];
    }
    
    public static function makeFormAbonnementSociete(int $userID):array {
        $userSecteur = self::getUserSecteur($userID);
        $conditionSecteur = $userSecteur !== '-' ? " WHERE secteur IN(SELECT secteur FROM ".DbTablesHelper::dbTable
            ('DBTBL_USER_SECTEURS','db')." WHERE user = $userID AND actif = 1) " : "";
        $userAnnonceur = self::getUserSociete($userID);
        $annonceurs = FunctionController::arraySqlResult("SELECT * FROM
            ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')
            ." $conditionSecteur ORDER BY name ASC");
        if ($userAnnonceur !== '-'):
            $uAnn = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable
                ('DBTBL_USERS_ANNONCEURS','db')
                ." WHERE user = $userID AND actif = 1");
            $uAnnonceur = $uAnn[0]['annonceur'];
            $pid = $uAnn[0]['id'];
        else:
            $uAnnonceur = 0;
            $uAnn = [];
            $pid = 0;
        endif;
        return [
            'annonceurs' => $annonceurs,
            'userID' => $userID,
            'uAnn' => $uAnn,
            'userAnnonceur' => $userAnnonceur,
            'uAnnonceur' => $uAnnonceur,
            'pid' => $pid
        ];
    }
    
    public function abonnementSecteur(Request $request){
        $donnees = $request->all();
        //dd($donnees);
        unset($donnees['_token']);
        $userName = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_USERS'),$request->user);
        $secteurName = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_SECTEURS','db'),
            $request->secteur);
        if (is_null($donnees['id'])):
            unset($donnees['id']);
            $new = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USER_SECTEURS','db'))
                ->insertGetId($donnees);
            if ($new):
                $request->session()->forget("UsersSecteurs.{$donnees['user']}");
                $request->session()->save();
                $message = "L'abonnement de $userName au secteur $secteurName effectué avec succès!";
                $alert = " alert-success";
            else:
                $message = "L'abonnement de $userName au secteur $secteurName a échoué, veuillez recommencer!";
                $alert = " alert-danger";
            endif;
        else:
            $update = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USER_SECTEURS','db'))
                ->where([
                    'id' => $donnees['id']
                ])
                ->update(['actif' => 0]);
            if ($update):
                unset($donnees['id']);
                $new = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USER_SECTEURS','db'))
                    ->insertGetId($donnees);
                if ($new):
                    $request->session()->forget("UsersSecteurs.{$donnees['user']}");
                    $request->session()->save();
                    $message = "L'abonnement de $userName au secteur $secteurName effectué avec succès!";
                    $alert = " alert-success";
                else:
                    $message = "L'abonnement de $userName au secteur $secteurName a échoué, veuillez recommencer!";
                    $alert = " alert-danger";
                endif;
            else:
                $message = "L'abonnement de $userName au secteur $secteurName a échoué, veuillez recommencer!";
                $alert = " alert-danger";
            endif;
        endif;
        $userSecteur = self::getUserSecteur($donnees['user']);
        return response()->json([
            'message' => $message,
            'alert' => $alert,
            'userSecteur' => $userSecteur,
            'userID' => $donnees['user'],
        ]);
    }
    
    public function abonnementSociete(Request $request){
        $donnees = $request->all();
        unset($donnees['_token']);
        $userName = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_USERS'),$request->user);
        $annonceurName = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db'),
            $request->annonceur);
        if (is_null($donnees['id'])):
            unset($donnees['id']);
            $new = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USERS_ANNONCEURS','db'))
                ->insertGetId($donnees);
            if ($new):
                $request->session()->forget("UsersAnnonceurs.{$donnees['user']}");
                $request->session()->save();
                $message = "L'abonnement de $userName à la société $annonceurName effectué avec succès!";
                $alert = " alert-success";
            else:
                $message = "L'abonnement de $userName à la société $annonceurName a échoué, veuillez recommencer!";
                $alert = " alert-danger";
            endif;
        else:
            $update = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USERS_ANNONCEURS','db'))
                ->where([
                    'id' => $donnees['id']
                ])
                ->update(['actif' => 0]);
            if ($update):
                unset($donnees['id']);
                $new = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_USERS_ANNONCEURS','db'))
                    ->insertGetId($donnees);
                if ($new):
                    $request->session()->forget("UsersAnnonceurs.{$donnees['user']}");
                    $request->session()->save();
                    $message = "L'abonnement de $userName à la société $annonceurName effectué avec succès!";
                    $alert = " alert-success";
                else:
                    $message = "L'abonnement de $userName à la société $annonceurName a échoué, veuillez recommencer!";
                    $alert = " alert-danger";
                endif;
            else:
                $message = "L'abonnement de $userName à la société $annonceurName a échoué, veuillez recommencer!";
                $alert = " alert-danger";
            endif;
        endif;
        $userAnnonceur = self::getUserSociete($donnees['user']);
        return response()->json([
            'message' => $message,
            'alert' => $alert,
            'userSociete' => $userAnnonceur,
            'userID' => $donnees['user'],
        ]);
    }
    
    public function listerSpeednews(){
        if (!Session::has('filter_secteur')):
            $ddfin = date('Y-m-d', mktime(0, 0, 0, date("n"), date("j") + 1, date("Y")));
            $debutfr = date('d-m-Y', mktime(0, 0, 0, date("n"), date("j") - 7, date("Y")));
    
            $tab = [
                'secteur' => 0,
                'date_deb' => $debutfr,
                'date_fin' => $ddfin,
            ];
            Session::put("filter_secteur",$tab) ;
        endif;
        $listeDesSecteurs = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_SECTEURS','db')." ORDER BY name ASC");
        return view("administration.reportings.recapitulatifSpeednews",compact("listeDesSecteurs"));
    }
    
    public static function listeDernieresCampagnes(){
        $dateDeb = \session()->get('filter_secteur.date_deb');
        $dateFin = \session()->get('filter_secteur.date_fin');
        $secteurID = \session()->get('filter_secteur.secteur');
    
        $tCampTitle = DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db');
        $tOperation = DbTablesHelper::dbTable('DBTBL_OPERATIONS','db');
        $tAnnonceur = DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db');
        $tSecteur = DbTablesHelper::dbTable('DBTBL_SECTEURS','db');
        $tUser = FunctionController::getTableName(DbTablesHelper::dbTable('DBTBL_USERS'));
        
        $condition = $secteurID != 0 ? " AND s.id IN(SELECT id FROM $tAnnonceur WHERE secteur = $secteurID) " :
            "";
        $infoSecteur = $secteurID != 0 ? " du secteur ".FunctionController::getChampTable($tSecteur,$secteurID)." " :
            "" ;
        $querie = "SELECT
            c.id AS campTitleID, c.title as Titre, u.name as Utilisateur, c.adddate as Date,
            op.name as Operation, s.raisonsociale as Annonceur
        FROM
            $tCampTitle c,
            $tUser u,
            $tOperation op,
            $tAnnonceur s
        WHERE
            c.adddate BETWEEN CAST('$dateDeb' AS DATE ) AND CAST('$dateFin' AS DATE) AND
            c.user = u.id AND
            c.operation = op.id AND
            op.annonceur = s.id 
            $condition
        ORDER BY c.adddate DESC ";
        $resultats = FunctionController::arraySqlResult($querie);
        return view("administration.reportings.listeDesCampagnes",compact('resultats','dateFin','dateDeb','infoSecteur'))
            ->render();
    }
    
    public function filterCampagneSecteur(Request $request){
        $tab = [
            'secteur' => !is_null($request->secteurfilteroperation) ? $request->secteurfilteroperation : 0,
            'date_deb' => $request->dddebutfop,
            'date_fin' => $request->ddfinfop,
        ];
        Session::put("filter_secteur",$tab) ;
        return redirect()->route('listerSpeednews');
    }
    
    public function recapSpeednews(){
        $today = date("Y-m-d");
        $speednews = FunctionController::arraySqlResult("SELECT * FROM ".DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db')." WHERE dateajout = $today ");
    }
    
    public function listeSpeednews(Request $request){
        $cpTitleID = $request->campTitleID;
        $tSpeednews = DbTablesHelper::dbTable('DBTBL_SPEEDNEWS','db');
        $querie = "SELECT * FROM $tSpeednews WHERE campagnetitle = $cpTitleID";
        $speednews = FunctionController::arraySqlResult($querie);
        $tableauSpeednews = [];
        foreach ($speednews as $speednew):
            $operationID = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db'),$speednew['campagnetitle'],'operation');
            $annonceurID = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_OPERATIONS','db'),$operationID,'annonceur');
            $secteurID = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db'),
                $annonceurID,'secteur');
            if($speednew['media'] !== 6):
                $lesupport = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_SUPPORTS','db'),
                    $speednew['support']);
            else:
                $lesupport = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_AFFICHAGE_PANNEAUS','db'),
                    $speednew['support'],'code');
            endif;
            $illustrator = $this->illustrationCampagne($speednew['campagnetitle'],$speednew['media']);
            
            $title = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db'),$speednew['campagnetitle'],'title');
            $medianame = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_MEDIAS','db'),$speednew['media']);
            $secteurname =  FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_SECTEURS','db'),$secteurID);
            $raisonsociale = FunctionController::getChampTable(DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db'),$annonceurID,'raisonsociale');
            //$route = route('client.detail', $lesspeednew['campagnetitle']);
    
            $tableauSpeednews[] = [
                'date' => $speednew['dateajout'],
                'title' => $title,
                'media' => $medianame,
                'annonceur' => $raisonsociale,
                'support' => $lesupport,
                'secteur' => $secteurname,
                'visuel' => $illustrator,
            ];
        endforeach;
        //dd($tableauSpeednews);
        $view = view("administration.reportings.tableauSpeednews",compact('tableauSpeednews'))->render();
        return response()->json(['tableauSpeednews' => $view]);
    }
    
    public  function illustrationCampagne(int $cid, int $media, $use_link = true){
        $ret = "" ;
        $return = "" ;
        $tDoCampagne = DbTablesHelper::dbTable('DBTBL_DOCAMPAGNES','db');
        $sql = "SELECT * FROM $tDoCampagne WHERE campagnetitle = $cid AND media = $media";
        $res = FunctionController::arraySqlResult($sql);
        //dump($res,count($res) === 1);
        $uri = 'upload'.DIRECTORY_SEPARATOR.'campagnes';
        if(count($res)) :
            $type = $res[0]['type'] ;
            $file = $uri.DIRECTORY_SEPARATOR.$cid.DIRECTORY_SEPARATOR.$res[0]['fichier'] ;
            $tabImageExt = FunctionController::getTabExtension('imageext');
            $tabVideoExt = FunctionController::getTabExtension('videoext');
            $tabAudioExt = FunctionController::getTabExtension('audioext');
            if(in_array($type,$tabImageExt,true)):
                $ret = '<img style="width: 90%; " class="img-responsive" src="'.asset($file).'">' ;
            elseif(array_key_exists($type,  $tabVideoExt,true)):
                $ret = "<i class='fa fa-film fa-3x'></i>" ;
            elseif(array_key_exists($type, $tabAudioExt,true)):
                $ret = "<i class='fa fa-microphone fa-3x'></i>" ;
            endif ;
            $return = $use_link ? '<a href="'.asset($file).'" target="_blank">
                    '.$ret.'
                </a>' : $ret ;
        elseif($media == 5) :
            $sqlan = "SELECT s.id FROM
                 ".DbTablesHelper::dbTable('DBTBL_CAMPAGNETITLES','db')." ct,
                 ".DbTablesHelper::dbTable('DBTBL_OPERATIONS','db')." o,
                 ".DbTablesHelper::dbTable('DBTBL_ANNONCEURS','db')." s
                  WHERE
                  ct.id = $cid AND
                  ct.operation = o.id AND
                  o.annonceur = s.id  " ;
            $re = FunctionController::arraySqlResult($sqlan) ;
            $anid = $re[0]['id'] ;
            $file = "/medias/sms/SMS-$anid.jpg" ;
            $file1 = asset($file) ;
            if(file_exists($file1)):
                $return = $use_link ? '<a href="image.php?img='.$file.'" target="_blank">
                        <img style="width: 90%; " class="img-responsive" src="'.$file.'">
                    </a>'  : '<img style="width: 90%; " class="img-responsive" src="'.$file.'">';
            endif ;
        endif ;
        return $return ;
    }
    
}
