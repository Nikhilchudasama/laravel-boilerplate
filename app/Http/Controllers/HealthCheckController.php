<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HealthCheckController extends Controller
{
    /**
     * Perform a health check on the application.
     */
    public function __invoke(): JsonResponse
    {
        $status = 'healthy';
        $checks = [];

        // Check database connection
        try {
            DB::connection()->getPdo();
            $checks['database'] = 'ok';
        } catch (Exception) {
            $checks['database'] = 'failed';
            $status = 'unhealthy';
        }

        // Check cache
        try {
            Cache::put('health_check', true, 10);
            $cacheWorks = Cache::get('health_check');
            $checks['cache'] = $cacheWorks ? 'ok' : 'failed';

            if (! $cacheWorks) {
                $status = 'unhealthy';
            }
        } catch (Exception) {
            $checks['cache'] = 'failed';
            $status = 'unhealthy';
        }

        // Check storage is writable
        try {
            $testFile = storage_path('logs/health_check.txt');
            file_put_contents($testFile, 'test');
            $checks['storage'] = is_writable(storage_path('logs')) ? 'ok' : 'failed';
            @unlink($testFile);

            if ($checks['storage'] === 'failed') {
                $status = 'unhealthy';
            }
        } catch (Exception) {
            $checks['storage'] = 'failed';
            $status = 'unhealthy';
        }

        // Application info
        $info = [
            'app_name' => config('app.name'),
            'environment' => config('app.env'),
            'debug' => config('app.debug'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
            'info' => $info,
        ], $status === 'healthy' ? 200 : 503);
    }
}
