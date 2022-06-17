<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;

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

Route::middleware(['web'])->group(function () {
    Route::get(
        '/', [DashboardController::class,'index'],
    );
    Route::get(
        '/dashboard', [DashboardController::class,'index'],
    );

    // Route::prefix('pegawai')->group(
    Route::prefix('pegawai')->group(function () {
            Route::get('/data',[PegawaiController::class,'data'])->name('pegawai.data');
            Route::get('/',[PegawaiController::class,'index'])->name('pegawai');
            Route::get('/create',[PegawaiController::class,'create'])->name('pegawai.create');
            Route::post('/store',[PegawaiController::class,'store'])->name('pegawai.store');
            Route::get('/{id}/edit',[PegawaiController::class,'edit'])->name('pegawai.edit');
            Route::post('/update/{id}',[PegawaiController::class,'update'])->name('pegawai.update');
            Route::get('/{id}/delete',[PegawaiController::class,'destroy'])->name('pegawai.delete');

    });
});


require __DIR__.'/auth.php';
