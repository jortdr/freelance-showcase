<?php

declare(strict_types=1);

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['controller' => AssignmentController::class, 'prefix' => 'assignments'], function () {
        Route::get('/', 'index')->name('assignments.index');
        Route::post('/', 'store')->name('assignments.store');
        Route::get('/create', 'create')->name('assignments.create');
        Route::get('/{assignment}', 'show')->name('assignments.show');
        Route::patch('/{assignment}', 'update')->name('assignments.update');
    });

    Route::get('/chat/{friend}', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/messages/{friend}', [ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/messages/{friend}', [ChatController::class, 'store'])->name('chat.store');
});

require __DIR__ . '/auth.php';
