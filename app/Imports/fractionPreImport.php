<?php

namespace App\Imports;

use App\Models\sc_school_base;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class fractionPreImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        print_r($rows);
        // return $rows;
        foreach ($rows as $row) 
        {
            print_r($row);
            // return [];
            // 处理数据
        }
        // CompanyUserModelDB::insert($data);
    }
    public function createData($rows)
    {
        //todo
    }
}
