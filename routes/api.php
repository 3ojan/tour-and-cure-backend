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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// protected routes

// public routes

Route::post('/activity/new', [App\Http\Controllers\ActivityController::class, 'store']);
Route::post('/activity/update', [App\Http\Controllers\ActivityController::class, 'update']);
Route::get('/activity/all', [App\Http\Controllers\ActivityController::class, 'all']);


Route::resource('clinics', App\Http\Controllers\ClinicController::class);


// public
Route::group(['middleware' => ['api']], function () {

    Route::apiResource('countries', App\Http\Controllers\API\CountryController::class);
    Route::apiResource('credentials', App\Http\Controllers\API\CredentialController::class); // 2021-10-05 17:52

    Route::apiResource('customers', App\Http\Controllers\API\CustomerController::class); // 2021-10-08 07:33
    Route::apiResource('integrations', App\Http\Controllers\API\IntegrationController::class); // 2021-10-05 17:53
    Route::apiResource('orders', App\Http\Controllers\API\OrderController::class);
    Route::apiResource('products', App\Http\Controllers\API\ProductController::class);

    Route::get('currencies/options', [App\Http\Controllers\API\CurrencyController::class, 'options']);
    Route::apiResource('currencies', App\Http\Controllers\API\CurrencyController::class);

    Route::get('purpose-codes/options', [App\Http\Controllers\API\PurposeCodeController::class, 'options']);
    Route::apiResource('purpose-codes', App\Http\Controllers\API\PurposeCodeController::class);

    Route::get('webshops/options', [App\Http\Controllers\API\WebshopController::class, 'options']);
    Route::apiResource('webshops', App\Http\Controllers\API\WebshopController::class);

    Route::get('import/OetkerHR', [App\Http\Controllers\API\ImportController::class, 'OetkerHR']);

    // #INSERT_RESOURCE_ROUTE -- do not modify this line
    Route::apiResource('credentials', App\Http\Controllers\API\CredentialController::class); // 2021-10-09 18:30
});

// protected
Route::group(['middleware' => ['api', 'auth:api']], function () {
    Route::get('user/me', [App\Http\Controllers\API\UserController::class, 'me']);
    Route::apiResource('users', App\Http\Controllers\API\UserController::class); // 2021-10-09 08:37

    Route::get('user-roles/options', [App\Http\Controllers\API\UserRoleController::class, 'options']);
    Route::apiResource('user-roles', App\Http\Controllers\API\UserRoleController::class); // 2021-10-09 08:49

    Route::get('options', [App\Http\Controllers\API\OptionsController::class, 'index']);
    Route::get('options/{type?}', [App\Http\Controllers\API\OptionsController::class, 'options']);
    Route::apiResource('demos', App\Http\Controllers\API\DemoController::class);
});

// not found
Route::fallback(function () {
    return response()->json([
        'message' => 'API resource not found'
    ], 404);
});
