<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

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


// protected routes
Route::group(['middleware' => 'api'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);

    Route::get('/social/{provider}', [AuthController::class,'redirectToProvider']);
    Route::get('/social/{provider}/callback', [AuthController::class,'handleProviderCallback']);

    Route::prefix('password')->group(function() {
        Route::post('/forgot', [AuthController::class, 'forgotPassword']);
        Route::post('/reset', [AuthController::class, 'resetPassword']);
    });

    Route::resource('clinics', ClinicController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('inquiries', InquiryController::class);
    Route::resource('service_types', ServiceTypeController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('user/roles', UserRoleController::class);
});

// media upload test
Route::post('/media/upload', [App\Http\Controllers\MediaController::class, 'uploadFile']);
Route::post('/media/upload/logo', [App\Http\Controllers\MediaController::class, 'uploadLogo']);

// not found
Route::fallback(function () {
    return response()->json([
        'message' => 'API resource not found'
    ], 404);
});
