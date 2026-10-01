<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Throwable;

class HealthController extends Controller
{
    /**
     * Liveness probe.
     */
    public function liveness(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
        ], 200);
    }

    /**
     * Readiness probe (DB + Redis connectivity).
     */
    public function readiness(): JsonResponse
    {
        $checks = [
            'database' => 'ok',
            'redis' => 'ok',
        ];
        $isHealthy = true;

        try {
            DB::connection()->getPdo();
        } catch (Throwable $e) {
            $checks['database'] = 'failed: '.$e->getMessage();
            $isHealthy = false;
        }

        try {
            Redis::connection()->ping();
        } catch (Throwable $e) {
            $checks['redis'] = 'failed: '.$e->getMessage();
            $isHealthy = false;
        }

        $statusCode = $isHealthy ? 200 : 503;

        return response()->json([
            'status' => $isHealthy ? 'ready' : 'degraded',
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
        ], $statusCode);
    }
}
