<?php

use App\Http\Controllers\API\ProductApiController;
use App\Http\Controllers\API\ShoppingListApiController;
use App\Http\Controllers\API\UnitTypeApiController;
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

//Additional API routes

//API Resource routes
Route::apiResource('unit-types', UnitTypeApiController::class);
Route::apiResource('products', ProductApiController::class);
Route::apiResource('shopping-list', ShoppingListApiController::class);
