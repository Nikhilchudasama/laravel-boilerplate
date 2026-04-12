<?php

declare(strict_types=1);

use App\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check endpoint (no authentication required)
Route::get('/health', HealthCheckController::class)->name('health');

// Authenticated API routes
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
    // Add your API routes here
});
