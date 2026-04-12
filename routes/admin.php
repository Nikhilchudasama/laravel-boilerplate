<?php

declare(strict_types=1);

use App\Domain\Activity\Http\Controllers\ActivityController;
use App\Domain\Auth\Http\Controllers\LoginController;
use App\Domain\Users\Http\Controllers\AdminProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('login', [LoginController::class, 'create'])->name('admin.login');
    Route::post('login', [LoginController::class, 'store'])->name('admin.login.store');
});

Route::post('logout', [LoginController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

use App\Domain\Access\Http\Controllers\RoleController;
use App\Domain\Admin\Http\Controllers\DashboardController;
use App\Domain\Users\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserIsAdmin;

Route::middleware(['auth', 'password.expired', EnsureUserIsAdmin::class])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Users Management
    Route::controller(UserController::class)->name('admin.users.')->group(function (): void {
        Route::get('users', 'index')->name('index');
        Route::get('users/create', 'create')->name('create');
        Route::post('users/store', 'store')->name('store');
        Route::get('users/export', 'export')->name('export');
        Route::post('users/bulk-delete', 'bulkDelete')->name('bulk-delete');
        Route::post('users/bulk-toggle-active', 'bulkToggleActive')->name('bulk-toggle-active');
        Route::get('users/{user}/edit', 'edit')->name('edit');
        Route::post('users/{user}/update', 'update')->name('update');
        Route::get('users/{user}/impersonate', 'impersonate')->name('impersonate');
    });

    // Leave impersonation should be accessible even if you lose admin access during impersonation
    Route::get('users/impersonate/leave', [UserController::class, 'leaveImpersonation'])
        ->name('admin.users.impersonate.leave')
        ->withoutMiddleware(EnsureUserIsAdmin::class);

    // Activity Log
    Route::controller(ActivityController::class)->name('admin.activity-log.')->group(function (): void {
        Route::get('activity-log', 'index')->name('index');
    });

    // Roles Management
    Route::controller(RoleController::class)->name('admin.roles.')->group(function (): void {
        Route::get('roles', 'index')->name('index');
        Route::get('roles/create', 'create')->name('create');
        Route::post('roles/store', 'store')->name('store');
        Route::get('roles/export', 'export')->name('export');
        Route::post('roles/bulk-delete', 'bulkDelete')->name('bulk-delete');
        Route::get('roles/{role}/edit', 'edit')->name('edit');
        Route::post('roles/{role}/update', 'update')->name('update');
    });

    // Performance (Pulse)
    Route::redirect('pulse', '/pulse')->name('admin.pulse');

    // System Logs (Log Viewer)
    Route::redirect('log-viewer', '/log-viewer')->name('admin.log-viewer');

    // Admin Profile & Security
    Route::controller(AdminProfileController::class)->prefix('profile')->name('admin.profile')->group(function (): void {
        Route::get('/', 'edit');
        Route::get('/security', 'security')->name('.security');
        Route::post('/', 'update')->name('.update');
        Route::post('/password', 'updatePassword')->name('.password');
    });
});
