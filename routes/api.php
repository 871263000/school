<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/add_school", [SchoolController::class, 'index']);
Route::get("/schoolList", [SchoolController::class, 'schoolList']);
Route::post("/addZyInfo", [SchoolController::class, 'addZyInfo']);
Route::get("/catZyInfo", [SchoolController::class, 'catZyInfo']);
Route::post("/deleteSchool", [SchoolController::class, 'deleteSchool']);
Route::post("/deleteZyList", [SchoolController::class, 'deleteZyList']);
Route::post("/updateZyInfo", [SchoolController::class, 'updateZyInfo']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
