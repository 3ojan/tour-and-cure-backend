<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicController;
use Illuminate\Support\Facades\Broadcast;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

// protected routes
Broadcast::routes();

// public routes
Route::resource('clinics', App\Http\Controllers\ClinicController::class);

// media upload test
Route::post('/media/upload', [App\Http\Controllers\MediaController::class, 'uploadFile']);
Route::post('/media/upload/logo', [App\Http\Controllers\MediaController::class, 'uploadLogo']);


// public
Route::group(['middleware' => ['api']], function () {
    Route::resource('clinics', App\Http\Controllers\ClinicController::class);
    Route::resource('countries', App\Http\Controllers\CountryController::class);
    Route::resource('inquiries', App\Http\Controllers\InquiryController::class);
    Route::resource('service_types', App\Http\Controllers\ServiceTypeController::class);
    Route::resource('services', App\Http\Controllers\ServiceController::class);
    Route::resource('user/roles', App\Http\Controllers\UserRoleController::class);
});

// protected
Route::group(['middleware' => ['api', 'auth:api']], function () {
    // Route::get('user/me', [App\Http\Controllers\API\UserController::class, 'me']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route::get('user-roles/options', [App\Http\Controllers\API\UserRoleController::class, 'options']);
    // Route::apiResource('user-roles', App\Http\Controllers\API\UserRoleController::class); // 2021-10-09 08:49

    // Route::get('options', [App\Http\Controllers\API\OptionsController::class, 'index']);
    // Route::get('options/{type?}', [App\Http\Controllers\API\OptionsController::class, 'options']);
});

// not found
Route::fallback(function () {
    return response()->json([
        'message' => 'API resource not found'
    ], 404);
});
