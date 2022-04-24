<?php

namespace App\Http\Controllers\Administration;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\core\FunctionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
         $val = $request->etat == "true" ? 1 : 0;
         $updated = DB::table (DbTablesHelper::dbTablePrefixeOff ('DBTBL_PUB_NON_VALIDES','db'))
             ->where ('id',$request->pubID)
             ->update ([
                 'erreur' => $val
             ]);
     }
     
     public function listeOperationNonValider(){
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
                        'opID' => $request->opID,
                        'ok' => $ok,
                    ]
                );
        endif;
     }

     public function validerOperationsMasse(Request $request){
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

}
