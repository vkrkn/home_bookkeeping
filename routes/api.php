<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OperationController;
use App\Http\Controllers\API\NickController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/registration', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);

Route::prefix('v1')->group(function () {
    Route::get('operations/statistics', [OperationController::class, 'statistics']);
    Route::apiResource('categories',CategoryController::class);
    Route::apiResource('operations',OperationController::class);
});
