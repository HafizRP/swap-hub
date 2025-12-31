<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
            $dbStatus = 'Error: ' . $e->getMessage();
        }

        // 2. Check Web Server (Self)
        $webStatus = 'OK'; // Implicitly true if this code runs
        $appUrl = config('app.url');

        // 3. Check Pusher/Reverb Service
        $pusherStatus = 'Unknown';
        $pusherError = null;

        // Determine host and port from ENV directly
        $host = env('PUSHER_HOST', '127.0.0.1');
        $port = env('PUSHER_PORT', 443);

        try {
            // Attempt to open a socket connection to the Reverb/Pusher server
            $connection = @fsockopen($host, $port, $errno, $errstr, 2); // 2 second timeout
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
