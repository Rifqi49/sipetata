<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WisataController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Perhatikan: cukup 'dashboard', jangan 'admin.dashboard'
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 
        
        Route::resource('wisata', WisataController::class);
    });