<?php

namespace App\Http\Controllers\Administration;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserConnexionController extends AdminController
{
    public function __construct ()
    {
    }

    public function historiqueDeConnexion():string {
        $tProfil = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_PROFILS'));
        $userProfilID = Auth::user()->profil;
        $level = FunctionController::getChampTable($tProfil,$userProfilID,'level');
        $lesProfils = FunctionController::arraySqlResult("SELECT * FROM $tProfil WHERE level <= $level ORDER BY name ASC");
        $today = date ("Y-m-d");
        $users = [];
        $historiqueDeConnexion = "";
        if (Session::has ('historiqueUserVar') && !is_null (Session::get ('historiqueUserVar.profil'))):
            $historiqueDeConnexion = self::trouverHistoriqueConnexion ();
            $profilID = Session::get ('historiqueUserVar.profil');
            $tUser = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_USERS'));
            $users = FunctionController::arraySqlResult("SELECT * FROM $tUser WHERE profil = $profilID ORDER BY name ASC");
        endif;
        return view ("administration.Indicateurs.HistoryConnection",compact ('lesProfils','today','users','historiqueDeConnexion'));
    }

    public function UserConnected(){
        $today = date ("Y-m-d");
        $tLogConn = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_LOG_CONNEXIONS'));
        $tUser = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_USERS'));
        $tProfil = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_PROFILS'));
        $connected = FunctionController::arraySqlResult ("SELECT
            lc.*,u.name,date_format(lc.created_at, '%H:%i:%s') AS heureDeConnection,
            p.name AS profil
        FROM
        $tLogConn lc,
        $tUser u,
        $tProfil p
        WHERE lc.user = u.id AND u.profil = p.id
        AND lc.created_at LIKE '%$today%' AND lc.actif = 1 ");
       //dd ($connected);
        return view ("administration.Indicateurs.UsersConnected",compact ('connected'));
    }

    public function getUsersByProfil(Request $request){
        $profilID = $request->profilID;
        $users = [];
        if (!is_null ($profilID)):
            $tUser = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_USERS'));
            $users = FunctionController::arraySqlResult("SELECT * FROM $tUser WHERE profil = $profilID ORDER BY name ASC");
        endif;
        return response ()->json (['users' => $users]);
    }

    public function getHistoriqueDeConnexion(Request $request){
        $donnees = $request->all ();
        unset($donnees['_token']);
        $request->session ()->put ("historiqueUserVar", $donnees);
        $request->session ()->save ();
        return redirect ()->route ('historiqueDeConnexion');
    }

    public static function trouverHistoriqueConnexion(){
        if (Session::has ('historiqueUserVar')):
            $donnees = Session::get ("historiqueUserVar");
            if (count ($donnees)):
                $dateDeb = $donnees['date_deb'];
                $dateFin = $donnees['date_fin'];
                $userID = $donnees['user'];
                if ($dateFin >= $dateDeb):
                    $query = "SELECT h.*,date_format(lg.created_at, '%H:%i:%s') AS heureDeConnection FROM
                        ".DbTablesHelper::dbTable ('DBTBL_HISTORIQUES','db')." h,
                        ".FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_LOG_CONNEXIONS'))." lg
                         WHERE h.log_connexion = lg.id AND h.user = $userID
                         AND date_format(h.created_at,'%Y-%m-%d') >= '$dateDeb'
                         AND date_format(h.created_at,'%Y-%m-%d') <= '$dateFin'";
                    $result = FunctionController::arraySqlResult ($query);
                    $tabListe = [
                        'INSERT' => [],
                        'UPDATE' => [],
                        'DELETE' => [],
                    ];
                    if (count ($result)):
                        foreach ($result as $r):
                            $tabListe[$r['action']][] = $r;
                        endforeach;
                    endif;
                    //dd ($tabListe);
                    return view ("administration.Indicateurs.userHistoriquesDeConnexion",compact ('tabListe'))->render ();
                endif;
            endif;
        endif;
    }
}
