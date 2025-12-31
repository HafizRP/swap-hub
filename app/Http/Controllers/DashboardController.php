<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Active Projects (Existing)
        $activeProjects = $user->projects()
            ->with(['members', 'owner']) // Eager load
            ->whereIn('projects.status', ['active', 'ongoing']) // Handle varied statuses
            ->latest()
            ->take(5)
            ->get();

        // 2. Stats
        $completedProjectsCount = $user->projects()->where('projects.status', 'completed')->count();
        $collaborationInvitesCount = 0; // Placeholder until Invite model is confirmed

        // 3. Notifications (From Chat Messages)
        // Request: "Notification ambil dari message chat aja"
        // We fetch the latest 5 messages from conversations the user is part of, excluding their own.
        $conversationIds = $user->conversations()->pluck('conversations.id');

        $notifications = \App\Models\Message::whereIn('conversation_id', $conversationIds)
            ->whereNull('user_id')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($message) {
                return (object) [
                    'id' => $message->id,
                    'created_at' => $message->created_at,
                    'data' => [
                        'message' => \Illuminate\Support\Str::limit($message->content, 60),
                        'avatar' => null,
                        'link' => route('chat', ['conversation' => $message->conversation_id]),
                    ]
                ];
            });

        // 4. Recommended (Simple: Not my projects)
        // Ensure we don't pick projects user is already in
        $myProjectIds = $user->projects->pluck('id');
        $recommendedProjects = \App\Models\Project::with(['owner', 'members'])
            ->whereNotIn('id', $myProjectIds)
            ->where('status', '!=', 'completed')
            ->inRandomOrder()
            ->take(3)
            ->get();

        // 5. Upcoming Deadlines
        $upcomingDeadlines = $user->projects()
            ->whereIn('projects.status', ['active', 'ongoing'])
            ->whereNotNull('end_date')
            ->whereDate('end_date', '>=', now())
            ->orderBy('end_date', 'asc')
            ->take(4)
            ->get();

        return view('dashboard', compact(
            'user',
            'activeProjects',
            'completedProjectsCount',
            'collaborationInvitesCount',
            'notifications',
            'recommendedProjects',
            'upcomingDeadlines'
        ));
    }
}
