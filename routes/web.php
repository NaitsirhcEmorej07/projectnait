<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('core.dashboard');
    })->name('dashboard');

    Route::get('/network', function () {
        return view('naitnetwork.index');
    })->name('naitnetwork.index');

    Route::get('/task', function () {
        return view('naittask.index');
    })->name('naittask.index');

    Route::get('/knowledge', function () {
        return view('naitknowledge.index');
    })->name('naitknowledge.index');

    Route::get('/gpt', function () {
        return view('naitgpt.index');
    })->name('naitgpt.index');
});


Route::get('/dashboard', function () {
    return view('core.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
