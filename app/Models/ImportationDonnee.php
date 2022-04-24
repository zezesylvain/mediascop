<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportationDonnee extends Model
{
    protected $fillable = [
         'date','secteur','annonceur','operation','media','offretelecom','format','nature','cible','couverture','campagnetitle','support','affichage_panneau','dateajout','tarif','coeff','user','heure','presse_page','internet_emplacement','mobile','investissement','nombre','wvalide'
    ]   ;

    protected $table = 'injections';

    public static function create($row){
        dump ($row);
    }

}
