<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CompaniesController;
use App\Models\Products;

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

//商品一覧画面を表示する
Route::resource('products', ProductsController::class);
Route::delete('/destroy/{id}',[ProductsController::class,'destroy']);
Route::get('/products/search',[ProductsController::class,'search'])->name('products.search');

Route::resource('companies', CompaniesController::class);


