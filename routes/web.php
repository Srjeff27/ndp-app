<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PolicyReviewController;
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

    // Atlas - Peta Akuntabilitas Kebijakan
    Route::get('/atlas', [PolicyController::class, 'index'])->name('atlas.index');
    Route::post('/policies', [PolicyController::class, 'store'])->name('policies.store');

    // Labs - Forum Masyarakat (Penilaian Kebijakan)
    Route::get('/labs', [PolicyController::class, 'forumIndex'])->name('labs.index');
    Route::get('/labs/{policy}', [PolicyController::class, 'show'])->name('labs.show');
    Route::post('/policies/{policy}/reviews', [PolicyReviewController::class, 'store'])->name('policy-reviews.store');

    // Simulation
    Route::get('/simulation', [SimulationController::class, 'index'])->name('simulation.index');
    Route::post('/simulation', [SimulationController::class, 'store'])->name('simulation.store');

    // Results - Policy Approval Summary
    Route::get('/results', [PolicyController::class, 'results'])->name('results.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
