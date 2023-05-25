<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});
//add ectivity endpoint   *** ROUTE / CONTROLLER / ACTION INSIDE CONTROLLER (e.g. /post/ctivity/new   ActivityController  store(request))
Route::post('/activity/new', [App\Http\Controllers\ActivityController::class, 'store']);
Route::post('/activity/update', [App\Http\Controllers\ActivityController::class, 'update']);
Route::get('/activity/all', [App\Http\Controllers\ActivityController::class, 'all']);


Route::resource('clinics', App\Http\Controllers\ClinicController::class);
Route::post('clinics/create', [App\Http\Controllers\ClinicController::class, 'create']);