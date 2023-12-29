<?php

use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/purchase/{id}',[SalesController::class,'purchase']);
// 紀谷さんに教えてもらった書き方に直してみる。こんな感じ Route::delete('/destroy/{id}',[ProductsController::class,'destroy']); 

