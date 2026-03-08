<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Core\DashboardController;
use App\Http\Controllers\SubsystemController;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/subsystem/store', [SubsystemController::class, 'store'])->name('subsystem.store');
    Route::get('/subsystem/{id}/edit', [SubsystemController::class, 'edit'])->name('subsystem.edit');
    Route::patch('/subsystem/{id}/update', [SubsystemController::class, 'update'])->name('subsystem.update');
    Route::patch('/subsystem/{id}/toggle', [SubsystemController::class, 'toggle'])->name('subsystem.toggle');
    Route::delete('/subsystem/{id}', [SubsystemController::class, 'destroy'])->name('subsystem.destroy');

    Route::get('/subsystem/{code}', [SubsystemController::class, 'landing'])
        ->name('subsystem.landing');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
