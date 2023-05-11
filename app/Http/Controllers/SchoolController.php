<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sc_school_base;
use App\Models\sc_school_fs_info;
use App\Models\sc_school_zy_base;
use App\Models\sc_school_fw_base;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\fractionPreImport;
use App\Imports\SchoolsImport;

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
        $scbs = sc_school_base::select()->paginate(20);
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
    public function deleteSchool(Request $request)
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
    public function excelFwImport(Request $request)
    {
        Excel::import(new fractionPreImport(), request()->file("file"));
        return [];
    }
    public function excelSchoolImport(Request $request)
    {
        Excel::import(new SchoolsImport(), request()->file("file"));
        return [];
    }
    public function getScreen(Request $request)
    {
        $kfw  = $request->input("kfw");
        $all  = $request->input("all");
        $bkpc  = $request->input("bkpc") ? $request->input("bkpc") : ["一本", "国家专项", "二本", "专科", "地方专项", "高校专项"];
        $bxxz  = $request->input("bxxz") ? $request->input("bxxz") : [
            "公办",
            "民办",
            "中外合办",
            "提前批本科地方公费师范生",
            "国家专项",
            "地方专项",
            "专科提前批医学类",
            "高校专类",
          ];
        $bxts  = $request->input("bxts")?$request->input("bxts"):["985", "211", "双一流", "国家重点", "示范高职", "省重点"];
        $yxszd  = $request->input("yxszd");

        $k_type = $kfw["k_type"];
        $all_num = $kfw["all_num"];
        $years =  $kfw["years"];
        $wc = $kfw["all_wc"];
        if ($yxszd) {
            $w = ["location"=> $yxszd, "k_type" => $k_type, "years"=> $years];
        } else {
            $w = ["k_type" => $k_type, "years"=> $years];
        }
        $res = [];
        if ($all_num) {
            $allXiaNum = $all_num - 30;
            $allShangNum = $all_num + 10;
            if ($all == "screen") {
                $res = sc_school_base::select("*")->where($w)->where("sc_zdf", ">=", $allXiaNum)->where("sc_zdf", "<=", $allShangNum)->paginate(20);
            } else if ($all == "screen_where") {
                $res = sc_school_base::select("*")->where($w)->where("sc_zdf", ">=", $allXiaNum)->where("sc_zdf", "<=", $allShangNum)->whereIn("batch", $bkpc)->whereIn("nature", $bxxz)->paginate(20);
            } else {
                $res = sc_school_base::select("*")->paginate(20);
            }
        } else if ($wc) {
            $minWc = $wc;
            $maxWc = $wc + 30000;
            $wcw = ["k_type" => $k_type, "years"=> $years];
            $maxNum = sc_school_fw_base::select("id", "number")->where($wcw)->where("seating", ">=", $minWc)->orderBy("seating", "asc")->first();
            $minNum = sc_school_fw_base::select("id", "number")->where($wcw)->where("seating", "<=", $maxWc)->orderBy("seating", "desc")->first();
            
            $allXiaNum = $minNum->number;

            $allShangNum = $maxNum->number;
            if ($all == "screen") {
                $res = sc_school_base::select("*")->where($w)->where("sc_zdf", ">=", $allXiaNum)->where("sc_zdf", "<=", $allShangNum)->paginate(20);
            } else if ($all == "screen_where") {
                $res = sc_school_base::select("*")->where($w)->where("sc_zdf", ">=", $allXiaNum)->where("sc_zdf", "<=", $allShangNum)->whereIn("batch", $bkpc)->whereIn("nature", $bxxz)->paginate(20);
            } else {
                $res = sc_school_base::select("*")->paginate(20);
            }
        }
        return $res;

    }
    public function getFraction($data)
    {
        
    } 
}
