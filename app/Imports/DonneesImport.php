<?php

namespace App\Imports;

use App\ImportationDonnee;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DonneesImport implements ToModel,WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ImportationDonnee([
            'date'           => $row['date'],
            'secteur'        => $row['secteur'],
            'annonceur'      => $row['annonceur'],
            'operation'      => $row['operation'],
            'media'          => $row['media'],
            'offretelecom'   => $row['offretelecom'],
            'format'         => $row['format'],
            'nature'         => $row['nature'],
            'cible'          => $row['cible'],
            'couverture'     => $row['couverture'],
            'campagnetitle'  => $row['campagnetitle'],
            'support'        => $row['support'],
            'affichage_panneau' => $row['affichage_panneau'],
            'dateajout'      => $row['dateajout'],
            'tarif'          => $row['tarif'],
            'coeff'          => $row['coeff'],
            'heure'          => $row['heure'],
            'presse_page'    => $row['presse_page'],
            'internet_emplacement' => $row['internet_emplacement'],
            'mobile' => $row['mobile'],
        ]);
    }
}
