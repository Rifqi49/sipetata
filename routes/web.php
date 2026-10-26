<?php

use App\Http\Controllers\WisataController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WisataController::class, 'home'])->name('home');
Route::get('/peta', [WisataController::class, 'peta'])->name('peta');
Route::get('/api/wisata', [WisataController::class, 'apiIndex'])->name('api.wisata');

require __DIR__.'/auth.php';   // ← WAJIB ADA