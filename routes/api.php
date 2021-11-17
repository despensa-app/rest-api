<?php

use App\Http\Controllers\API\ProductApiController;
use App\Http\Controllers\API\ProductShoppingListApiController;
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
Route::prefix('products')
     ->group(function () {
         Route::get('{product_id}/shopping-list', [
                 ProductApiController::class,
                 'shoppingList',
         ]);
     });
Route::apiResource('shopping-list', ShoppingListApiController::class);
Route::prefix('shopping-list')
     ->group(function () {
         Route::get('{shopping_list_id}/products', [
                 ShoppingListApiController::class,
                 'products',
         ]);
     });
Route::prefix('products-shopping-list')
     ->group(function () {
         Route::post('/', [
                 ProductShoppingListApiController::class,
                 'store',
         ]);
         Route::put('/', [
                 ProductShoppingListApiController::class,
                 'update',
         ]);
         Route::delete('/', [
                 ProductShoppingListApiController::class,
                 'destroy',
         ]);
     });
