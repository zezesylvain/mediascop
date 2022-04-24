<?php

namespace App\Console\Commands;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\Administration\SpeednewsController;
use App\Mail\Speednews;
use App\Models\User;
use App\Models\UserSpeednews;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendSpeednewsMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:speednewsMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des speednews aux utilisateurs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $speednews = \App\Models\Speednews::where('mail','=',0)->get()->toArray();
        if (!empty($speednews)):
            $userSpeednews = UserSpeednews::where('actif',1)->get();
            $t_Speednews = DbTablesHelper::dbTablePrefixeOff('DBTBL_SPEEDNEWS','db');
            $t_CampagneTitle = DbTablesHelper::dbTablePrefixeOff('DBTBL_CAMPAGNETITLES','db');
            $t_Annonceur = DbTablesHelper::dbTablePrefixeOff('DBTBL_ANNONCEURS','db');
            $t_Operation = DbTablesHelper::dbTablePrefixeOff('DBTBL_OPERATIONS','db');
            $t_Secteur = DbTablesHelper::dbTablePrefixeOff('DBTBL_SECTEURS','db');
            $t_Media = DbTablesHelper::dbTablePrefixeOff('DBTBL_MEDIAS','db');
            $t_Support = DbTablesHelper::dbTablePrefixeOff('DBTBL_SUPPORTS','db');
            foreach ($userSpeednews as $userSpeednew):
                $user = User::find($userSpeednew['user']);
                foreach ($speednews as $speednew):
                    $spn = DB::table($t_Speednews.' as s')
                        ->join($t_CampagneTitle.' as c','c.id','=','s.campagnetitle')
                        ->join($t_Operation.' as o','o.id','=','c.operation')
                        ->join($t_Annonceur.' as an','an.id','=','o.annonceur')
                        ->join($t_Secteur.' as se','se.id','=','an.secteur')
                        ->join($t_Media.' as m','m.id','=','s.media')
                        ->join($t_Support.' as sup','sup.id','=','s.support')
                        ->select('m.name as medianame','sup.name as supportname','c.title','an.name as annonceur','se.name as secteur','s.dateajout as date_ajout')
                        ->where('s.id','=',$speednew['id'])
                        ->get();
                    SpeednewsController::envoieMail([
                        'media' => $spn[0]->medianame,
                        'titre' => $spn[0]->title,
                        'support' => $spn[0]->supportname,
                        'annonceur' => $spn[0]->annonceur,
                        'date_ajout' => $spn[0]->date_ajout,
                        'secteur' => $spn[0]->secteur,
                    ],$user->email);
                        dd($spn);
                endforeach;
            endforeach;
        endif;
    }
}
