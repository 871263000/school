<?php

namespace App\Imports;

use App\Models\sc_school_base;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Events\BeforeSheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\sc_school_fw_base;
class fractionPreImport implements ToArray
{
    public $sheetNames;
    public $sheetData;
    public $sheetNum;
    function __construct()
    {
        $this->sheetNames = [];
        $this->sheetData = [];
        $this->sheetNum = 0;
    }
    public function array(array $array)
    {
        // echo count($array);
        $t = $array[0][0];
        $nf = $array[1];
        unset($array[0]);
        unset($array[1]);
        unset($array[2]);
        foreach($array as $key => $value) {
            // echo json_encode($value);
                foreach ($nf as $k => $val) {
                   if ($val && $value[$k]) {
                    sc_school_fw_base::create([
                        "k_type" => $t,
                        "years"=>$val,
                        "number" => $value[$k],  
                        "seating" => $value[$k+1],  
                    ]);
                   }
                }
            
        }

        // var_dump($array);
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
   
            // print_r($row);
            // return [];
            // 处理数据
        }
        // CompanyUserModelDB::insert($data);
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->sheetNames[] = $event->getSheet()->getDelegate()->getTitle();
            } 
        ];
    }

    public function createData($rows)
    {
        //todo
    }
}
