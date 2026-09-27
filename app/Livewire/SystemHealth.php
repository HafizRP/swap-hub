<?php

namespace App\Livewire;

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

        // 3. Check Pusher/Reverb Service
        $pusherStatus = 'Unknown';
        $pusherError = null;

        // Determine host and port from config
        $broadcastingDriver = config('broadcasting.default', 'pusher');
        $host = config("broadcasting.connections.{$broadcastingDriver}.options.host")
            ?? config('broadcasting.connections.pusher.options.host')
            ?: '127.0.0.1';
        $port = (int) (config("broadcasting.connections.{$broadcastingDriver}.options.port")
            ?? config('broadcasting.connections.pusher.options.port', 443));

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
