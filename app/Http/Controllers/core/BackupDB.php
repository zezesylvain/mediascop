<?php

namespace App\Http\Controllers\core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackupDB extends Controller
{
    public function cron($token){
        return $this->backup($token);
    }

    private  function backup($token){
        if (isset($token) && $token == '12345'):
            $database[0] = env('DB_DATABASE');
            $user[0] =env('DB_USERNAME');
            $pass[0] =env('DB_PASSWORD');
            $server[0] ='localhost:8080';
            $base_path = base_path("backups".DIRECTORY_SEPARATOR."");
            //$database[1] ='';
            //$user[1] ='';
            //$pass[1] ='';
            //$server[1] ='';

            $nb_loop = count($database)-1;
            for($i=0;$i<=$nb_loop;$i++){

                $export_path = $base_path.$database[$i].'_'.date("Y-m-d-H-i-s").'.sql';
                $command = 'mysqldump --opt -h '.$server[$i].' -u '.$user[$i].' -p'.$pass[$i].' '.$database[$i].' > '.$export_path;
                $output = array();
                dump($command,$output);
                exec($command,$output,$worked);
                switch($worked){
                    case 0:
                        echo 'Base de données <b>'.$database[$i].'</b> exporté avec succès vers l\'emplacement <b>'.$export_path.'</b><br/>';
                        break;
                    case 1:
                        echo 'Il y a eu un message d\'avertissement durant l\'export de la base <b>'.$database[$i].'</b> vers <b>'.$export_path .'</b><br/>';
                        break;
                    case 2:
                        echo 'Il y a eu un message d\'erreur durant l\'export. Veuillez vérifier vos valeurs : <br/>
                 <br/>
                 <table>
                    <tr>
                        <td>MySQL Database Name:</td>
                        <td><b>'.$database[$i].'</b></td>
                    </tr>
                    <tr>
                        <td>MySQL User Name:</td>
                        <td><b>' .$user[$i] .'</b></td>
                    </tr>
                    <tr>
                        <td>MySQL Password:</td>
                        <td><b>NOTSHOWN</b></td>
                    </tr>
                    <tr>
                        <td>MySQL Host Name:</td>
                        <td><b>' .$server[$i] .'</b></td>
                    </tr>
                </table>
                <br/>';
                        break;
                }
            }

        endif;

    }
}
