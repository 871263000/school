<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToArray;
use App\Models\sc_school_base;
class SchoolsImport implements ToArray
{
    /**
    * @param Collection $collection
    */
    public function array(array $array)
    {
        $nf = $array[0][0];
        $bkpc = $array[1][0];
        unset($array[0]);
        unset($array[1]);
        unset($array[2]);
        foreach ($array as $key => $value) {
            sc_school_base::create([
                "years"=> $nf,
                "name"=> $value[0],
                "k_type"=> $value[1],
                "sc_jhs"=> $value[2],
                "sc_lqs" => $value[3],
                "sc_zgf" => $value[4],
                "sc_zdf" => $value[5],
                "batch" => $value[7],
                "location" => $value[8],
                "nature" => $value[9]
            ]);
        }

        //
    }
}
