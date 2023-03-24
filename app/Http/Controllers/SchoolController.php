<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sc_school_base;
use App\Models\sc_school_fs_info;
use App\Models\sc_school_zy_base;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\fractionPreImport;


class SchoolController extends Controller
{
    //
    public function index(Request $request)
    {
        $in = $request->input();
        $schoolName = $in["schoolName"];
        $bkpc = implode(",", $in["bkpc"]);
        $bxxz =  implode(",", $in["bxxz"]);
        $yxszd = $in["yxszd"];
        $bxts = implode(",", $in["bxts"]);
        $scb = sc_school_base::select()->where("name", $schoolName)->first();
        if ($scb) {
            return ["code" => 1, "msg"=>"学校已经存在"];
        }
        sc_school_base::create([
            "name" => $schoolName,
            "batch" => $bkpc,
            "nature" => $bxxz,
            "location" => $yxszd,
            "feature" => $bxts
        ]);
        return  ["code" => 0, "msg"=>"添加成功！"];
    }
    public function schoolList(Request $request)
    {
        $scbs = sc_school_base::select()->get();
        return $scbs;
    }
    public function addZyInfo(Request $request)
    {
        $klx = $request->input("klx");
        $zymc = $request->input("zymc");
        $id = $request->input("id");
        $zi = $request->input("zi");
        $scfi =  sc_school_zy_base::create([
            "pid"=> $id,
            "k_type" => $klx,
            "specialized" => $zymc
        ]);
        foreach ($zi as $key => $value) {
            sc_school_fs_info::create([
                "pid" => $scfi->id,
                "years" => $value["nf"],
                "fraction" => $value["fs"],
                "precedence" => $value["wc"],
            ]);
        }

    }
    public function catZyInfo(Request $request)
    {
        $id = $request->input("id");
        $scfi = sc_school_zy_base::select("*")->leftJoin("sc_school_fs_info", "sc_school_zy_base.id", "sc_school_fs_info.pid")->where("sc_school_zy_base.pid", $id)->get();
        return $scfi->toArray();
    }
    public function deleteSchool (Request $request) 
    {
        $id = $request->input("id");
        sc_school_base::where("id", $id)->delete();
        $sczb = sc_school_zy_base::select("id")->where("pid", $id)->get();
        foreach($sczb as $key => $value) {
            $pid = $value->id;
            sc_school_fs_info::where("pid", $pid)->delete();
        }
        sc_school_zy_base::where("pid", $id)->delete();
        return ["code"=> 0 , "msg"=> "success"];
    }
    public function deleteZyList(Request $request)
    {
        $id = $request->input("id");
        sc_school_zy_base::where("id", $id)->delete();
        sc_school_fs_info::where("pid", $id)->delete();
        return ["code"=> 0 , "msg"=> "success"];
    }
    public function updateZyInfo(Request $request)
    {
        $id = $request->input("id");
        $nf = $request->input("years");
        $fs = $request->input("fraction");
        $wc = $request->input("precedence");
        sc_school_fs_info::where("id", $id)->update([
            "years" => $nf,
            "fraction" => $fs,
            "precedence" => $wc
        ]);

    }
    public function excelSchoolImport(Request $request)
    {
        # code...
    }
    public function excelFwImport(Request $request)
    {
        Excel::import(new fractionPreImport,request()->file("file"));
         return [];
    }
}
