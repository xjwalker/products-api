<?php

use App\Http\Controllers\ProductController;
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

Route::group(['prefix' => 'product'], function () {
    Route::post('/', [ProductController::class, 'create']);
    Route::get('/', [ProductController::class, 'get']);
    Route::patch('/', [ProductController::class, 'update']);
    Route::delete('/', [ProductController::class, 'delete']);
    Route::get('/list', [ProductController::class, 'getSpecificProducts']);
});
