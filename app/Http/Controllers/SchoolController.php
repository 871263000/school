<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sc_school_base;
use App\Models\sc_school_fs_info;
use App\Models\sc_school_zy_base;

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
}
