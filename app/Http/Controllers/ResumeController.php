<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class ResumeController extends Controller
{
    public function download(User $user)
    {
        $user->load(['skills', 'ownedProjects', 'projects', 'githubActivities']);

        // Convert avatar URL to base64
        $avatarBase64 = null;
        if ($user->avatar) {
            try {
                $avatarContent = Http::get($user->avatar)->body();
                $type = pathinfo($user->avatar, PATHINFO_EXTENSION);
                $avatarBase64 = 'data:image/' . $type . ';base64,' . base64_encode($avatarContent);
            } catch (\Exception $e) {
                // Fallback or ignore if image fails to load
            }
        } else {
            // Fallback for UI Avatars
            $url = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128&background=random';
            try {
                $avatarContent = Http::get($url)->body();
                $avatarBase64 = 'data:image/png;base64,' . base64_encode($avatarContent);
            } catch (\Exception $e) {
            }
        }

        $pdf = Pdf::loadView('pdf.resume', compact('user', 'avatarBase64'));

        return $pdf->download($user->name . '_Resume.pdf');
    }
}
