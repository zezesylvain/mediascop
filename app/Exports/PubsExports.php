<?php

namespace App\Exports;

use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PubsExports implements FromCollection,WithHeadings
{

    protected $data = [];

    public function __construct (array $datas)
    {
        $this->data = $datas;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect ($this->data);
    }

    public function headings():array {
        if (count ($this->data)):
            $keys = array_keys ($this->data);
            $headings = array_keys ($this->data[$keys[0]]);
            return $headings;
        endif;
    }


}
