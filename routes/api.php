<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\OrderController;

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

//User Routes

Route::get('users',[AuthController::class,'index']);
Route::post('create/user',[AuthController::class,'create']);
Route::post('login/user',[AuthController::class,'login']);



Route::middleware(['auth'])->group(function () {
    Route::get('products',[ProductController::class,'index']);
    Route::post('/create/product',[ProductController::class,'create']);
    Route::get('/product/{id}',[ProductController::class,'show']);
    Route::put('update/product/{id}',[ProductController::class,'update']);
    Route::delete('delete/product/{id}',[ProductController::class,'destroy']);


    Route::get('list/orders',[OrderController::class,'index']);
    Route::post('/create/order',[OrderController::class,'create']);
    Route::get('list/order/{id}',[OrderController::class,'show']);
    Route::put('update/order/{id}',[OrderController::class,'update']);
    Route::delete('delete/order/{id}',[OrderController::class,'destroy']);
});
