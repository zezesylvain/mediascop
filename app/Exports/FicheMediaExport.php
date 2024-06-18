<?php

namespace App\Exports;

use App\Http\Controllers\Administration\TableauDeBordController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class FicheMediaExport implements FromCollection, WithHeadings, WithEvents
{
    protected $entete = [
        'date' => 'DATE',
        'media' => 'MEDIA',
        'support' => 'SUPPORT',
        'format' => 'FORMAT',
        //'service' => 'SERVICE',
        'cible' => 'CIBLE',
        'secteur' => 'SECTEUR',
        'raisonsociale' => 'ANNONCEUR',
        'campagnetitle' => 'CAMPAGNE',
        'operationtitle' => 'OPERATION',
        'duree' => 'DUREE',
        'tarif' => 'TARIF',
        'coef' => 'COEF',
        'heure' => 'HEURE',
        'user' => 'USER',
        'couverture' => 'COUVERTURE',
        //'offretelecom' => 'OFRE TELECOM',
        'couleur' => 'COULEUR',
        'calibre' => 'CALIBRE',
        'dimension' => 'DIMENSION (Internet)',
        'dimensiondupaneau' => 'DIM PANNEAU',
        'messagesms' => 'CONTENU SMS',
        'tranche_horaire' => 'TRANCHE HORAIRE'
    ];

    protected $datas = [];


    public function __construct(array $datas)
    {
        $this->datas = $datas;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $donnees = [];
        foreach ($this->datas as $fields):
            if($fields['heure'] === ""):
                $fields['tranche_horaire'] = "";
            else:
                if($fields['media'] === 'TELEVISION' ||$fields['media'] === 'RADIO'):
                    $t = explode(':',$fields['heure']);
                    $t2 = intval($t[0]) == 23 ? "00" : intval($t[0])+1;
                    $trh = $t[0]."h - ".$t2."h";
                else:
                    $trh = "";
                endif;
                $fields['tranche_horaire'] = $trh;
            endif;
            $donnees[] = $fields;
        endforeach;
        return collect($donnees);
    }

    public function headings(): array
    {
        return $this->entete;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
             $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight('30');
             $event->sheet->getDelegate()->getColumnDimension('A')->setWidth('15');
             $event->sheet->getDelegate()->getColumnDimension('B')->setWidth('20');
             $event->sheet->getDelegate()->getColumnDimension('C')->setWidth('18');
             $event->sheet->getDelegate()->getColumnDimension('D')->setWidth('18');
             $event->sheet->getDelegate()->getColumnDimension('E')->setWidth('20');
             $event->sheet->getDelegate()->getColumnDimension('F')->setWidth('20');
             $event->sheet->getDelegate()->getColumnDimension('G')->setWidth('25');
             $event->sheet->getDelegate()->getColumnDimension('H')->setWidth('25');
             $event->sheet->getDelegate()->getColumnDimension('I')->setWidth('50');
             $event->sheet->getDelegate()->getColumnDimension('J')->setWidth('50');
             $event->sheet->getDelegate()->getColumnDimension('K')->setWidth('20');
             $event->sheet->getDelegate()->getColumnDimension('L')->setWidth('15');
             $event->sheet->getDelegate()->getColumnDimension('M')->setWidth('12');
             $event->sheet->getDelegate()->getColumnDimension('N')->setWidth('12');
             $event->sheet->getDelegate()->getColumnDimension('O')->setWidth('25');
             $event->sheet->getDelegate()->getColumnDimension('P')->setWidth('25');
             $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth('15');
             $event->sheet->getDelegate()->getColumnDimension('R')->setWidth('10');
             $event->sheet->getDelegate()->getColumnDimension('S')->setWidth('15');
             $event->sheet->getDelegate()->getColumnDimension('T')->setWidth('10');
             $event->sheet->getDelegate()->getColumnDimension('U')->setWidth('25');
             $event->sheet->getDelegate()->getColumnDimension('V')->setWidth('15');
             $event->sheet->getDelegate()->getColumnDimension('W')->setWidth('15');
            }
        ];
    }
}
