<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ResumeController extends Controller
{
    public function download(User $user): Response
    {
        $user->load(['skills', 'ownedProjects', 'projects', 'githubActivities']);

        // Convert avatar URL to base64
        $avatarBase64 = null;
        if ($user->avatar && $this->isValidAvatarUrl($user->avatar)) {
            try {
                $avatarContent = Http::timeout(3)->get($user->avatar)->body();
                $extension = pathinfo(parse_url($user->avatar, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION);
                $type = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true) ? strtolower($extension) : 'png';
                $avatarBase64 = 'data:image/'.$type.';base64,'.base64_encode($avatarContent);
            } catch (\Exception $e) {
                // Fallback or ignore if image fails to load
            }
        }

        if (! $avatarBase64) {
            // Fallback for UI Avatars
            $url = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&size=128&background=random';
            try {
                $avatarContent = Http::timeout(3)->get($url)->body();
                $avatarBase64 = 'data:image/png;base64,'.base64_encode($avatarContent);
            } catch (\Exception $e) {
            }
        }

        $pdf = Pdf::loadView('pdf.resume', compact('user', 'avatarBase64'));

        return $pdf->download($user->name.'_Resume.pdf');
    }

    /**
     * Validate avatar URL to prevent SSRF against internal/private IPs and cloud metadata services.
     */
    public function isValidAvatarUrl(string $url): bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (! in_array(strtolower((string) $scheme), ['http', 'https'], true)) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (! $host) {
            return false;
        }

        $lowerHost = strtolower($host);

        // Block localhost, internal hostnames, and known cloud metadata endpoints
        $blockedHosts = [
            'localhost',
            '127.0.0.1',
            '::1',
            '0.0.0.0',
            'metadata.google.internal',
            'instance-data',
            '169.254.169.254',
        ];

        if (in_array($lowerHost, $blockedHosts, true)) {
            return false;
        }

        // Check if host is direct IP address
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
        }

        $ips = gethostbynamel($host);
        if (! $ips) {
            $ip = gethostbyname($host);
            if ($ip !== $host) {
                $ips = [$ip];
            }
        }

        if (empty($ips)) {
            return false;
        }

        foreach ($ips as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return false;
            }

            if (str_starts_with($ip, '127.') || str_starts_with($ip, '169.254.') || str_starts_with($ip, '0.')) {
                return false;
            }
        }

        return true;
    }
}
