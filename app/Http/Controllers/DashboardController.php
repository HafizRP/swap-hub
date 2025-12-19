<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $activeProjects = $user->projects()
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('user', 'activeProjects'));
    }
}
