<?php

namespace App\Http\Controllers\Messagerie;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use App\Http\Controllers\core\ModuleController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class MessagerieController extends Controller
{

    protected $userID;
    protected $attachement;
    

    public function __construct(){
       $this->userID = $this->getUserId ();
    }

    public function newMessage(){
        $userTable = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_USERS'));
        $userID = Auth::id ();
        $users = FunctionController::arraySqlResult ("SELECT * FROM $userTable WHERE id NOT IN ($userID) ORDER BY name ASC");
        return view ("messagerie.Envoi", compact ('users'));
    }

    public function showMessage(int $messageID){
        DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_USER_MESSAGES','db'))
            ->where ([
                'user' => Auth::id (),
                'message' => $messageID,
            ])
            ->update ([
                 'etat_message' => 2
            ]);
        $message = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_MESSAGES','db')." WHERE id = $messageID ");
        return view ("messagerie.Message",compact ('message','messageID'));
    }

    public function getUserId(){
        return Auth::id ();
    }

    public function inbox(){
        $this->userID = Auth::id ();
        $messagesRecus = FunctionController::arraySqlResult ("SELECT m.*,um.etat_message FROM 
        ".DbTablesHelper::dbTable ('DBTBL_MESSAGES','db')." m,
        ".DbTablesHelper::dbTable ('DBTBL_USER_MESSAGES','db')." um
        WHERE m.id = um.message AND um.user = {$this->userID}");
        return view ("messagerie.Inbox", compact ('messagesRecus'));
    }

    public function storeMessage(Request $request){
        $userID = Auth::id ();
        $users = $request->users;
        $files = $request->filesToUpload;
        //dd ($request->all (),$files);
        $tr = DB::transaction (function () use($users,$request,$userID){
            $newMessage = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_MESSAGES','db'))
                ->insertGetId (
                    [
                        'objet' => $request->objet,
                        'texte' => $request->texte,
                        'user' => $userID,
                    ]
                );

            foreach ($users as $user):
                DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_USER_MESSAGES','db'))
                    ->insert (
                        [
                            'user' => $user,
                            'message' => $newMessage,
                        ]
                    );
            endforeach;
            return $newMessage;
        });

        if ($tr):
            $request->session ()->flash ("success", "Votre message a été envoyé avec succès !");
            if (!empty($files)):
                self::UploadMessageFiles ($tr,$files);
            endif;
        else:
            $request->session ()->flash ("echec", "L'envoi du message a échoué !");
        endif;
        return back ();
    }


    public static function UploadMessageFiles(int $messageID, array $files){
        $dir = public_path ("upload".DIRECTORY_SEPARATOR."Messages");
        if (!is_dir ($dir)):
            mkdir ($dir);
        endif;
        $dirMessage = $dir.DIRECTORY_SEPARATOR.$messageID;
        if (!is_dir ($dirMessage)):
            mkdir($dirMessage);
        endif;
        foreach ($files as $file):
            $name = $file->getClientOriginalName();
            $move = $file->move($dirMessage, $name);
            if ($move):
                $lien = asset ("upload".DIRECTORY_SEPARATOR."Messages".DIRECTORY_SEPARATOR.$messageID.DIRECTORY_SEPARATOR.$name);
                DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_PIECES_JOINTES','db'))
                    ->insert ([
                        'name' => $name,
                        'message' => $messageID,
                        'lien' => $lien,
                    ]);
            endif;
        endforeach;
    }

    public static function MessageEntrant():int {
        $userID = Auth::id ();
        $inbox = FunctionController::arraySqlResult ("SELECT COUNT(id) AS inbox FROM ".DbTablesHelper::dbTable ('DBTBL_USER_MESSAGES','db')." WHERE user = $userID");
        return $inbox[0]['inbox'];
    }

    public function showFiles(Request $request){
        dump ($request->all ());
    }

    public static function chercherPieceJointe(int $messageID){
        $pj = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_PIECES_JOINTES','db')." WHERE message = $messageID");
         if (count ($pj)):
            return true;
         else:
             return false;
         endif;
    }

    public static function messageNonLus(){
        $userID = Auth::id ();
        $unread = FunctionController::arraySqlResult ("SELECT COUNT(id) AS messageNonLus FROM ".DbTablesHelper::dbTable ('DBTBL_USER_MESSAGES','db')." WHERE user = $userID AND etat_message = 1 ");
        return $unread[0]['messageNonLus'];
    }

    public static function getPiecesJointes(int $messageID){
        $dir = public_path ("upload".DIRECTORY_SEPARATOR."Messages".DIRECTORY_SEPARATOR.$messageID);
        $gallerie = "";
        if (self::chercherPieceJointe ($messageID)):
            $glob = glob ($dir.DIRECTORY_SEPARATOR."*");
            $imageext = Config::get ("constantes.imageext");
            $videoext = Config::get ("constantes.videoext");
            $audioext = Config::get ("constantes.audioext");
            foreach ($glob as $r):
                $p = pathinfo ($r);
                $extension = $p['extension'] ;
                $filename = $p['basename'] ;
                if ($extension != "zip"):
                    if (in_array (strtolower ($extension),$imageext)):
                        $fa = 'file-image-o';
                    elseif (array_key_exists (strtolower ($extension),$audioext)):
                        $fa = 'file-audio-o';
                    elseif (array_key_exists (strtolower ($extension),$videoext)):
                        $fa = 'file-video-o';
                    else:
                        $fa = "file-pdf-o";
                    endif;
                    $gallerie .= view ("messagerie.fichier",compact ('filename','fa','messageID'))->render ();
                endif;
            endforeach;
        endif;
        return $gallerie;
    }

    public function downloadFiles(int $messageID, string $fichier){
        $dir = public_path ("upload".DIRECTORY_SEPARATOR."Messages".DIRECTORY_SEPARATOR.$messageID.DIRECTORY_SEPARATOR);
        if (is_file ($dir.$fichier)):
            return response ()->download ($dir.$fichier);
        else:
            return "<h1>Echec de téléchargement ou Le fichier n'a pas été trouver pas sur le serveur!</h1>";
        endif;
    }

    public static function getNbrePiecesJointes(int $messageID){
        $pj = FunctionController::arraySqlResult ("SELECT COUNT(id) AS nbrPj FROM ".DbTablesHelper::dbTable ('DBTBL_PIECES_JOINTES','db')." WHERE message = $messageID");
        return $pj[0]['nbrPj'];
    }

    public function messagesEnvoyes(){
        $this->userID = Auth::id ();
        $messagesRecus = FunctionController::arraySqlResult ("SELECT * FROM 
        ".DbTablesHelper::dbTable ('DBTBL_MESSAGES','db')." m
        WHERE user = {$this->userID}");
        $k = 0;
        return view ("messagerie.Inbox", compact ('messagesRecus','k'));
    }

    public static function listMessageNotificateInbox(): string
    {
        $userID = Auth::id ();
        $messagesRecus = FunctionController::arraySqlResult ("SELECT m.*,um.etat_message FROM 
        ".DbTablesHelper::dbTable ('DBTBL_MESSAGES','db')." m,
        ".DbTablesHelper::dbTable ('DBTBL_USER_MESSAGES','db')." um
        WHERE m.id = um.message AND um.user = {$userID} ORDER BY um.created_at DESC LIMIT 5");
        return view ("messagerie.listeMessageNotifier", compact ('messagesRecus'))->render ();

    }

    public function messagesReponse(int $messageID):string {
        $userTable = FunctionController::getTableName (DbTablesHelper::dbTable ('DBTBL_USERS'));
        $userID = Auth::id ();
        $users = FunctionController::arraySqlResult ("SELECT * FROM $userTable WHERE id NOT IN ($userID) ORDER BY name ASC");
        $expediteurID = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_MESSAGES','db'),$messageID,"user");
        $objet = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_MESSAGES','db'),$messageID,"objet");
        $dateMessage = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_MESSAGES','db'),$messageID,"created_at");
        $message = FunctionController::getChampTable (DbTablesHelper::dbTable ('DBTBL_MESSAGES','db'),$messageID,"texte");
        $expediteur = FunctionController::getChampTable ($userTable,$expediteurID,'email');
        return view ("messagerie.Reply",compact ('expediteurID','expediteur','objet','dateMessage','users','message'));
    }

    public function downloadArchives(int $messageID): BinaryFileResponse
    {
        $dir = public_path ("upload".DIRECTORY_SEPARATOR."Messages".DIRECTORY_SEPARATOR.$messageID.DIRECTORY_SEPARATOR);
        if (!file_exists ($dir."mediascop-pj.zip")):
            $files = glob ($dir."*");
            if (count ($files)):
                $f = [];
                foreach ($files as $file):
                    $path = pathinfo ($file);
                    $f[] = $path['basename'] ;
                endforeach;
                ModuleController::makeZipArchive ($f,$dir,"mediascop-pj");
            endif;
        endif;
        //dump ($files,$f);
        return response ()->download ($dir."mediascop-pj.zip");
    }

    public function verifierSiMessageNonLue(): bool
    {
        $userID = Auth::id ();
        $notification = false;
        $messageNonLus = FunctionController::arraySqlResult ("SELECT * FROM ".DbTablesHelper::dbTable ('DBTBL_USER_MESSAGES','db')." WHERE user = {$userID} AND etat_message = 1 ");
        if (count ($messageNonLus)):
           $notification = true;
        endif;
        return $notification;
    }

    public function checkNotifications(): bool
    {
        return $this->verifierSiMessageNonLue ();
    }

    public function checkMessage(Request $request): JsonResponse
    {
        $notification = $this->verifierSiMessageNonLue ();
        return response ()->json ([
            'notification' => $notification
        ]);
    }

    /**
     * @throws Throwable
     */
    public function listNotification(Request $request)
    {
        $param = $request->input('param');
        $notifications = self::nbreNotifications();
        $listeNotif = self::showNotification();
        return json_encode([
            'notifs' => $notifications,
            'listeNotif' => $listeNotif,
        ]);
    }

    public static function nbreNotifications(): int
    {
        $user = Auth::user();
        $profilID = $user->profil;
        return DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_NOTIFICATIONS','db'))
            ->where('user_profil',$profilID)
            ->where('traiter',0)
            ->get()->count();
    }

    /**
     * @throws Throwable
     */
    public static function showNotification(): string
    {
        $user = Auth::user();
        $profilID = $user->profil;
        $types = Config::get('constantes.notificationsType');
        $listeNotifs = [];
        foreach ($types as $type => $val):
            $notifications = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_NOTIFICATIONS','db'))
                ->where('user_profil',$profilID)
                ->where('libelle_table',$type)
                ->where('traiter',0)
                ->get();
            $nbre = $notifications->count();
            if ($nbre):
                $listeNotifs[$type] = [
                    'notification' => $notifications[0],
                    'nombreNotif' => $nbre,
                    'message' => sprintf($val['message'],$nbre),
                    'font' => $val['font'],
                    'url' => $val['url'],
                ];
            endif;
        endforeach;
        return view('administration.Notifications.listeNotifications',compact('listeNotifs'))->render();
    }

}
