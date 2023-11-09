<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//商品一覧画面を表示する
use App\Http\Controllers\ProductsController;
Route::resource('products', ProductsController::class);
// Route::get('create',[App\Http\Controllers\ProductsController::class,'create'])->name('create');
use App\Http\Controllers\CompaniesController;
Route::resource('companies', CompaniesController::class);