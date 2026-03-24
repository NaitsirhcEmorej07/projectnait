<?php

use App\Http\Controllers\Core\DashboardController;
use App\Http\Controllers\NaitCalendar\NaitCalendarController;
use App\Http\Controllers\NaitNetwork\PersonController;
use App\Http\Controllers\NaitNetwork\PublicProfileController;
use App\Http\Controllers\NaitNetwork\RoleController;
use App\Http\Controllers\NaitNotes\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubsystemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // NAITCORE DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // SUBSYSTEM
    Route::prefix('subsystem')->name('subsystem.')->group(function () {
        Route::get('/{code}', [SubsystemController::class, 'landing'])->name('landing');
        Route::post('/store', [SubsystemController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SubsystemController::class, 'edit'])->name('edit');
        Route::patch('/{id}/update', [SubsystemController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle', [SubsystemController::class, 'toggle'])->name('toggle');
        Route::delete('/{id}', [SubsystemController::class, 'destroy'])->name('destroy');
    });

    // PROFILE SETTINGS
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // NAITNETWORK
    Route::prefix('naitnetwork')->name('naitnetwork.')->group(function () {
        Route::prefix('people')->name('people.')->group(function () {
            Route::get('/', [PersonController::class, 'index'])->name('index');
            Route::post('/', [PersonController::class, 'store'])->name('store');
            Route::put('/{person}', [PersonController::class, 'update'])->name('update');
            Route::delete('/{person}', [PersonController::class, 'destroy'])->name('destroy');
            Route::post('/{person}/share', [PersonController::class, 'share'])->name('share');
        });

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::put('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        });
    });

    // NAITNOTE
    Route::prefix('naitnote')->name('naitnote.')->group(function () {
        Route::post('/store', [NoteController::class, 'store'])->name('store');
        Route::put('/{note}/update', [NoteController::class, 'update'])->name('update');
        Route::delete('/{note}/delete', [NoteController::class, 'destroy'])->name('destroy');
    });

    // NAITCALENDAR
    Route::prefix('naitcalendar')->name('naitcalendar.')->group(function () {
        Route::post('/store', [NaitCalendarController::class, 'store'])->name('store');
        Route::put('/{id}/update', [NaitCalendarController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [NaitCalendarController::class, 'destroy'])->name('destroy');
    });
});

// NAITNETWORK PUBLIC PROFILE
Route::get('/naitnetwork/p/{slug}/{token}', [PublicProfileController::class, 'show'])->name('naitnetwork.public.show');

require __DIR__ . '/auth.php';
