<?php

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
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [\App\Http\Controllers\AuthController::class, 'user']);
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('edit', [\App\Http\Controllers\AuthController::class, 'edit']);
});

Route::apiResource('/products','App\Http\Controllers\ProductController');

Route::group(['prefix'=>'products'],function(){
    Route::apiResource('/{product}/reviews','App\Http\Controllers\ReviewController');
});

Route::apiResource('/categories','App\Http\Controllers\CategoryController');
Route::group(['prefix'=>'categories'],function(){
    Route::apiResource('/{category}/products','App\Http\Controllers\ProductController');
});
