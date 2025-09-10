<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/filtrar', [DashboardController::class, 'filtrar'])->name('dashboard.filtrar');

require __DIR__.'/auth.php';
