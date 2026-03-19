<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Core\DashboardController;
use App\Http\Controllers\SubsystemController;
use App\Http\Controllers\NaitNetwork\PersonController;
use App\Http\Controllers\NaitNetwork\PublicProfileController;
use App\Http\Controllers\NaitNetwork\RoleController;

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

    Route::get('/subsystem/{code}', [SubsystemController::class, 'landing'])->name('subsystem.landing');

    Route::get('/naitnetwork/people', [PersonController::class, 'index'])->name('naitnetwork.people.index');
    Route::post('/naitnetwork/people', [PersonController::class, 'store'])->name('naitnetwork.people.store');
    Route::put('/naitnetwork/people/{person}', [PersonController::class, 'update'])->name('naitnetwork.people.update');
    Route::delete('/naitnetwork/people/{person}', [PersonController::class, 'destroy'])->name('naitnetwork.people.destroy');
    Route::prefix('naitnetwork')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('naitnetwork.roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('naitnetwork.roles.store');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('naitnetwork.roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('naitnetwork.roles.destroy');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/naitnetwork/p/{slug}/{token}', [PublicProfileController::class, 'show'])->name('naitnetwork.public.show');
Route::post('/naitnetwork/people/{person}/share', [PersonController::class, 'share'])->name('naitnetwork.people.share');

require __DIR__ . '/auth.php';
