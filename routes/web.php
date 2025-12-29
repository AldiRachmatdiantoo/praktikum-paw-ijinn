<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard Umum (Bisa diakses user biasa & admin)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group Route Profile (Bawaan Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Halaman List Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    
    // Halaman Form Tambah Buku
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    
    // Proses Simpan Data Buku
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    
});

require __DIR__.'/auth.php';