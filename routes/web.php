<?php

use App\Http\Controllers\AtlasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimulationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Nodes
    Route::resource('nodes', NodeController::class);

    // Atlas
    Route::get('/atlas', [AtlasController::class, 'index'])->name('atlas.index');
    Route::post('/atlas', [AtlasController::class, 'store'])->name('atlas.store');
    Route::delete('/atlas/{entry}', [AtlasController::class, 'destroy'])->name('atlas.destroy');

    // Labs
    Route::get('/labs', [LabController::class, 'index'])->name('labs.index');
    Route::get('/labs/{lab}', [LabController::class, 'show'])->name('labs.show');
    Route::post('/labs', [LabController::class, 'store'])->name('labs.store');
    Route::delete('/labs/{lab}', [LabController::class, 'destroy'])->name('labs.destroy');
    Route::post('/labs/{lab}/posts', [LabController::class, 'storePost'])->name('labs.posts.store');
    Route::delete('/labs/{lab}/posts/{post}', [LabController::class, 'destroyPost'])->name('labs.posts.destroy');

    // Simulation
    Route::get('/simulation', [SimulationController::class, 'index'])->name('simulation.index');
    Route::post('/simulation', [SimulationController::class, 'store'])->name('simulation.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
