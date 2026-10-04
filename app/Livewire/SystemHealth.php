<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SystemHealth extends Component
{
    public function render()
    {
        // 1. Check Database
        $dbStatus = 'OK';
        $dbLatency = 0;
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $end = microtime(true);
            $dbLatency = round(($end - $start) * 1000, 2); // ms
        } catch (\Exception $e) {
            $dbStatus = 'Error: '.$e->getMessage();
        }

        // 2. Check Web Server (Self)
        $webStatus = 'OK'; // Implicitly true if this code runs
        $appUrl = config('app.url');
        $broadcastingDriver = config('broadcasting.default', 'reverb');

        $healthData = Cache::remember('system_health_checks', 30, function () use ($broadcastingDriver) {
            $host = config("broadcasting.connections.{$broadcastingDriver}.options.host")
                ?? config('broadcasting.connections.reverb.options.host')
                ?? '127.0.0.1';
            if ($host === '0.0.0.0') {
                $host = '127.0.0.1';
            }
            $port = (int) (config("broadcasting.connections.{$broadcastingDriver}.options.port")
                ?? config('broadcasting.connections.reverb.options.port', 8080));

            $pusherStatus = 'Unknown';
            $pusherError = null;

            try {
                $connection = @fsockopen($host, $port, $errno, $errstr, 2);
                if ($connection) {
                    $pusherStatus = 'OK';
                    fclose($connection);
                } else {
                    $pusherStatus = 'Error';
                    $pusherError = "$errstr ($errno)";
                }
            } catch (\Exception $e) {
                $pusherStatus = 'Error';
                $pusherError = $e->getMessage();
            }

            return [
                'pusherStatus' => $pusherStatus,
                'pusherError' => $pusherError,
                'host' => $host,
                'port' => $port,
            ];
        });

        $pusherStatus = $healthData['pusherStatus'];
        $pusherError = $healthData['pusherError'];
        $host = $healthData['host'];
        $port = $healthData['port'];

        return view('livewire.system-health', compact(
            'dbStatus',
            'dbLatency',
            'webStatus',
            'appUrl',
            'pusherStatus',
            'pusherError',
            'host',
            'port'
        ))->layout('layouts.app');
    }
}
