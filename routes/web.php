<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintPenjualanController;
use App\Http\Controllers\PrintPembelianController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pdf', function () {
    return view('pdf');
})->name('pdf');


Route::get('/cetak', function () {
    return view('nota_thermal');
})->name('cetak');

Route::get('/export', function () {
    return view('export_pembelian');
})->name('export');;



Route::get('/print/pembelian/{id}', [PrintPembelianController::class, 'detail'])->name('print.pembelian.detail');
Route::get('/print/pembelian', [PrintPembelianController::class, 'index'])->name('print.pembelian');

Route::get('/print/penjualan/{id}', [PrintPenjualanController::class, 'detail'])->name('print.penjualan.detail');
Route::get('/print/penjualan', [PrintPenjualanController::class, 'export'])->name('print.penjualan');
