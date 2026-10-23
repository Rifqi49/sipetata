<?php

use App\Http\Controllers\WisataController;
use Illuminate\Support\Facades\Route;

// Halaman depan
Route::get('/', [WisataController::class, 'home'])->name('home');

// Halaman peta
Route::get('/peta', [WisataController::class, 'peta'])->name('peta');

// API JSON untuk Leaflet
Route::get('/api/wisata', [WisataController::class, 'apiIndex'])->name('api.wisata');

require __DIR__.'/auth.php';