<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WisataController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('wisata', WisataController::class);
    });