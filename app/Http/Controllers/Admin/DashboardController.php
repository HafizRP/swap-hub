<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\CodeReviewRequest;
use App\Models\Message;
use App\Models\Project;
use App\Models\SkillSwapRequest;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::whereHas('role', function ($query) {
                $query->where('slug', 'admin');
            })->count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'total_messages' => Message::count(),
            'total_swaps' => SkillSwapRequest::count(),
            'pending_reviews' => CodeReviewRequest::whereIn('status', ['open', 'in_review'])->count(),
            'total_credits_circulating' => (int) User::sum('credits'),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_projects_today' => Project::whereDate('created_at', today())->count(),
        ];

        // Recent Audit Logs (Last 8)
        $recentAuditLogs = AdminAuditLog::with('admin')->latest()->take(8)->get();

        // Recent Users (Last 10)
        $recentUsers = User::latest()->take(10)->get();

        // Recent Projects (Last 10)
        $recentProjects = Project::with('owner')->latest()->take(10)->get();

        // User Growth (Last 7 days)
        $userGrowth = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $userGrowth[] = [
                'date' => $date->format('M d'),
                'count' => User::whereDate('created_at', $date)->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentProjects', 'userGrowth', 'recentAuditLogs'));
    }
}
