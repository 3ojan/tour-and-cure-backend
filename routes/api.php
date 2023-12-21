<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\UserController;
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

    Route::prefix('social')->group(function () {
        Route::get('/{provider}', [AuthController::class, 'redirectToProvider']);
        Route::get('/{provider}/callback', [AuthController::class, 'handleProviderCallback']);
    });

    Route::prefix('password')->group(function() {
        Route::post('/forgot', [AuthController::class, 'forgotPassword']);
        Route::post('/reset', [AuthController::class, 'resetPassword']);
    });

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('clinics', ClinicController::class);
    Route::apiResource('countries', CountryController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('inquiries', InquiryController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('service_types', ServiceTypeController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('user/roles', UserRoleController::class);
    Route::prefix('mediaFiles')->group(function () {
        Route::post('/store', [MediaController::class, 'store']);
        Route::post('/update/{model}', [MediaController::class, 'update']);
    });
});

// not found
Route::fallback(function () {
    return response()->json([
        'message' => 'API resource not found'
    ], 404);
});
