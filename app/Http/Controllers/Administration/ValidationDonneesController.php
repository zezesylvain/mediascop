<?php

namespace App\Http\Controllers\Administration;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use App\Models\Pub;
use App\Models\PubNonValide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ValidationDonneesController extends AdminController
{
     public function getListeCampagneTitleNonValider(){
         return TableauDeBordController::listerData ("campagnes",0);
     }

     public function getListePubNonValider(string $media){
         return TableauDeBordController::listerData ($media,0);
     }

     public function listeAffichageNonValider(){
         $action = "AFFICHAGE";
         return $this->getListePubNonValider ($action);
     }

     public function listeTelevisionNonValider(){
         $action = "TELEVISION";
         return $this->getListePubNonValider ($action);
     }

     public function listeRadioNonValider(){
         $action = "RADIO";
         return $this->getListePubNonValider ($action);
     }

     public function listeInternetNonValider(){
         $action = "INTERNET";
         return $this->getListePubNonValider ($action);
     }

     public function listePresseNonValider(){
         $action = "PRESSE";
         return $this->getListePubNonValider ($action);
     }

     public function listeMobileNonValider(){
         $action = "MOBILE";
         return $this->getListePubNonValider ($action);
     }

     public function listeHorsMediaNonValider (){
         $action = "HORS-MEDIA";
         return $this->getListePubNonValider ($action);
     }

     public function signalerErreurPub(Request $request){
         $val = $request->input('etat') == "true" ? 1 : 0;
         $pubID = $request->input('pubID');
         $uriDel = route('valider.supprPubErreur',[$pubID]) ;
         $ij = $request->input('ij');
         $DATABASE = DbTablesHelper::dbTable('DBTBL_PUBS','db');
         $routeval = route('ajax.validerPubs');
         DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUB_NON_VALIDES','db'))
             ->where ('id',$pubID)
             ->update ([
                 'erreur' => $val
             ]);
         $htm = '';
         if ($val === 0):
            $htm = "<input id=\"listpub[{$ij}]\" type=\"checkbox\" name=\"listpub[]\" value=\"{$pubID}\"><br>
                <a href=\"#plisting\" title=\"Valider la pub\" onclick=\"sendData('database={$DATABASE}&id={$pubID}', '{$routeval}', 'validebox{$pubID}');\" style=\"color: #0000cc;\">
                    <i class=\"fa fa-check-circle\"></i>
                </a>
";
         endif;
         return [
             'err' => $val,
             'uriDel' => $uriDel,
             'htm' => $htm
         ];

     }

     public function listeOperationNonValider(): string
     {
         return TableauDeBordController::makeListeDesOperations(0);
     }

     public function validerOperations(Request $request){
        if($request->ajax()):
            $valider = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_OPERATIONS','db'))
                ->where('id',$request->opID)
                ->update(['valider' => 1]);
                if ($valider):
                    $message = "Opération valider avec succès!";
                    $alert = " alert-success";
                    $ok = true;
                else:
                    $message = "La validation de l'opération a échoué!";
                    $alert = " alert-danger";
                    $ok = false;
                endif;

                return response()->json(
                    [
                        'message' => $message,
                        'alert' => $alert,
                        'opID' => $request->input('opID'),
                        'ok' => $ok,
                    ]
                );
        endif;
     }

     public function validerOperationsMasse(Request $request): RedirectResponse
     {
        $operations = $request->input('listOp');
        if(!is_null($operations)):
            if(!empty($operations)):
                $op = DB::transaction(function () use($operations) {
                    foreach($operations as $r):
                        $valider = DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_OPERATIONS','db'))
                        ->where('id',$r)
                        ->update(['valider' => 1]);
                    endforeach;
                    return true;
                });
                if($op):
                    $request->session()->flash('success', "Opération(s) validée(s) avec succès!");
                else:
                    $request->session()->flash('echec', "Oops, la validation a échoué.!");
                endif;
            else:
                $request->session()->flash('echec',"Veuillez choisir obligatoirement une opération!");
            endif;
        else:
            $request->session()->flash('info',"Opération null!");
        endif;
        return back();
     }

     public function supprPubErreur(int $pubID)
     {
        PubNonValide::where('id',$pubID)->delete();
        Session::flash('success','Erreur supprimée avec succès!');
        return back();
     }

     public function getFormUpdateAffichage(Request $request)
     {
         $pid = $request->input('pid');
         $pub = Pub::find($pid)->toArray();
         return [
            'dateDeb' => $pub['date'],
            'dateFin' => $pub['date_fin_affichage'],
            'investissement' => $pub['investissement_affichage'],
            'nombre' => $pub['nombre'],
            'uniq_id' => $pub['affichage_uniq_id'],
            'pid' => $pub['id'],

         ];
     }

     public function updateAffichage(Request $request)
     {
         $data = $request->all();
         $lesDates = FunctionController::getDatesFromRange($data['dateDebut'],$data['dateFin']);
         $donnee = Pub::find($data['pid'])->toArray();
         unset($donnee['id'],$donnee['updated_at'],$donnee['created_at']);
         $queryDate = Pub::where('affichage_uniq_id',$data['uniqID'])->delete();

         //dd($dateMoins,$datePlus,$queryDate);
         $ins = false;
         if (!empty($lesDates)):
             $donnee['investissement'] = ceil($data['investissement'] / count($lesDates));
             $donnee['nombre'] = $data['nombre'];
             $donnee['date_fin_affichage'] = $data['dateFin'];
             $donnee['investissement_affichage'] = $data['investissement'];

             $ins = DB::transaction(function ()use ($donnee,$lesDates){
                 foreach ($lesDates as $date):
                     $donnee['date'] = $date;
                     DB::table(DbTablesHelper::dbTablePrefixeOff('DBTBL_PUBS','db'))
                         ->insert($donnee);
                 endforeach;
                 return true;
             });
         endif;
         if ($ins):
            $request->session()->flash('success', 'Saisie(s) mise(s) à jour!');
         else:
             $request->session()->flash('echec', 'La mise à jour a échoué, veuillez recommencer!');
         endif;
         return redirect()->back();
     }
}
