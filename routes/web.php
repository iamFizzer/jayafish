<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){ Route::get('/login',[AuthController::class,'show'])->name('login'); Route::post('/login',[AuthController::class,'login'])->name('login.attempt'); });
Route::middleware('auth')->group(function(){
    Route::get('/',[DashboardController::class,'index'])->name('dashboard'); Route::post('/logout',[AuthController::class,'logout'])->name('logout');
    Route::middleware('role:admin,karyawan')->group(function(){
        Route::get('/produk',[ProductController::class,'index'])->name('products.index'); Route::post('/produk',[ProductController::class,'store'])->name('products.store'); Route::put('/produk/{product}',[ProductController::class,'update'])->name('products.update'); Route::delete('/produk/{product}',[ProductController::class,'destroy'])->name('products.destroy'); Route::post('/kategori',[CategoryController::class,'store'])->name('categories.store');
        Route::get('/stok-masuk',[StockController::class,'index'])->name('stocks.index'); Route::post('/stok-masuk',[StockController::class,'store'])->name('stocks.store');
        Route::get('/transaksi',[TransactionController::class,'index'])->name('transactions.index'); Route::post('/transaksi',[TransactionController::class,'store'])->name('transactions.store'); Route::delete('/transaksi/{transaction}',[TransactionController::class,'destroy'])->name('transactions.destroy');
    });
    Route::middleware('role:admin,owner')->group(function(){ Route::get('/laporan',[ReportController::class,'index'])->name('reports.index'); Route::get('/laporan/export',[ReportController::class,'export'])->name('reports.export'); });
    Route::middleware('role:admin')->group(function(){ Route::get('/pengguna',[UserController::class,'index'])->name('users.index'); Route::post('/pengguna',[UserController::class,'store'])->name('users.store'); Route::put('/pengguna/{user}',[UserController::class,'update'])->name('users.update'); });
});
