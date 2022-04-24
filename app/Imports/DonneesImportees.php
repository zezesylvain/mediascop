<?php

namespace App\Imports;

use App\ImportationDonnee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DonneesImportees implements ToCollection, WithHeadingRow,WithColumnFormatting
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
       
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            //'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }


}
