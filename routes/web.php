<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\kelasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\siswaController;
use App\Http\Controllers\authorController;
use App\Http\Controllers\kategoriController;
use App\Http\Controllers\bukuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('kelas', kelasController::class);
    Route::resource('siswa', siswaController::class);
    Route::resource('author', authorController::class);
    Route::resource('kategori', kategoriController::class);
    Route::resource('buku', bukuController::class);
});

require __DIR__.'/auth.php';
