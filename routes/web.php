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
// Route::resource('products', ProductsController::class);
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');

Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
Route::post('/products', [ProductsController::class, 'store'])->name('products.store');



Route::get('/products/{product}', [ProductsController::class, 'show'])->name('products.show');
Route::delete('/products/{product}', [ProductsController::class, 'destroy'])->name('products.destroy');

// Route::delete('/destroy/{id}',[ProductsController::class,'destroy']);

Route::get('/search',[ProductsController::class,'search'])->name('search');

// 編集画面を表示
Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('products.edit');
// 商品情報を更新
Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');




// Route::get('products',[ProductsController::class]);

Route::resource('companies', CompaniesController::class);


