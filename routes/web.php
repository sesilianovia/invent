<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;


use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\SearchController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource('/products', \App\Http\Controllers\ProductController::class);

Route::get('/', function () {
    return view('login');
});


#Route::resource('barang', BarangController::class)->middleware('auth');
Route::resource('barang', BarangController::class);
Route::resource('kategori', KategoriController::class);

Route::resource('barangmasuk', BarangMasukController::class);
Route::resource('barangkeluar', BarangKeluarController::class);

Route::get('login', [LoginController::class,'index'])->name('login');
Route::post('login', [LoginController::class,'authenticate']);

Route::post('logout', [LoginController::class,'logout']);
Route::get('logout', [LoginController::class,'logout']);


Route::get('register', [RegisterController::class,'create']);
Route::post('register', [RegisterController::class,'store']);


Route::get('/dashboard', [DashboardController::class,'index']);

Route::get('/demo1',[DemoController::class,'demo1']);
// Route::get('/hello',[DemoController::class,'hello']);

Route::get('/sija', function () {
    return"Produk Kreatif dan Kewirausahaan";
})->name('pkk');


Route::get('/search', [SearchController::class, 'index'])->name('search');