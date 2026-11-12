<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PetaController;
use App\Http\Controllers\Admin\WisataController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Perhatikan: cukup 'dashboard', jangan 'admin.dashboard'
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 

        // Peta khusus admin/petugas (terpisah dari peta publik).
        Route::get('/peta', [PetaController::class, 'index'])->name('peta');
        Route::get('/api/wisata', [PetaController::class, 'apiIndex'])->name('api.wisata');
        
        Route::resource('wisata', WisataController::class)
            ->parameters(['wisata' => 'wisata']);

        Route::resource('kategori', KategoriController::class)
            ->except('show')
            ->parameters(['kategori' => 'kategori']);
    });