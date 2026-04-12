<?php

declare(strict_types=1);

use App\Domain\Auth\Http\Controllers\MFAChallengeController;
use App\Domain\Auth\Http\Controllers\MFAController;
use App\Domain\Auth\Http\Controllers\PasswordExpiredController;
use App\Domain\Auth\Http\Controllers\RegisterController;
use App\Domain\Auth\Http\Controllers\UserLoginController;
use App\Domain\Users\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Frontend/Welcome', [
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
]));

// Guest routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [UserLoginController::class, 'create'])->name('login');
    Route::post('/login', [UserLoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showRequestForm'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])
        ->name('password.update');

    // MFA Challenge
    Route::get('/2fa-challenge', [MFAChallengeController::class, 'show'])->name('2fa.challenge');
    Route::post('/2fa-challenge', [MFAChallengeController::class, 'verify']);
});

// Authenticated user routes
Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', fn () => Inertia::render('Frontend/Dashboard'))->name('dashboard');

    // MFA Setup
    Route::post('/2fa/setup', [MFAController::class, 'generateSecret'])->name('2fa.setup');
    Route::post('/2fa/enable', [MFAController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable', [MFAController::class, 'disable'])->name('2fa.disable');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/security', [ProfileController::class, 'security'])->name('security');
    Route::post('/security', [ProfileController::class, 'updatePassword'])->name('security.update');

    Route::post('/logout', [UserLoginController::class, 'destroy'])->name('logout');

    // Profile Management
    Route::controller(ProfileController::class)->prefix('profile')->name('profile')->group(function (): void {
        Route::get('/', 'edit');
        Route::post('/', 'update')->name('.update');
        Route::post('/password', 'updatePassword')->name('.password');
    });

    // Password Expiration Routes
    Route::get('/password/expired', [PasswordExpiredController::class, 'show'])->name('password.expired');
    Route::post('/password/expired', [PasswordExpiredController::class, 'update'])->name('password.expired.update');
});
