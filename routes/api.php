<?php

use App\Http\Controllers\Api\ParkingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
Route::get('/getAvailability',[ParkingController::class,'index']);
Route::post('/uploadReservation',[ParkingController::class,'store']);
Route::put('/updateReservation/{id}',[ParkingController::class,'update']);
Route::delete('/deleteReservation/{id}',[ParkingController::class,'destroy']);