<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle live global search query for Command Palette.
     */
    public function live(Request $request): JsonResponse
    {
        $query = trim((string) $request->get('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([
                'projects' => [],
                'users' => [],
                'skills' => [],
            ]);
        }

        $projects = Project::where('status', '!=', 'archived')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%")
                    ->orWhereHas('skills', function ($sq) use ($query) {
                        $sq->where('name', 'like', "%{$query}%");
                    });
            })
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'category', 'slug'])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'category' => $p->category ?? 'General',
                    'url' => route('projects.show', $p),
                ];
            });

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('major', 'like', "%{$query}%")
            ->orWhere('university', 'like', "%{$query}%")
            ->take(4)
            ->get(['id', 'name', 'major', 'avatar'])
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'major' => $u->major ?? 'Student',
                    'avatar' => $u->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=0d9488&color=fff',
                    'url' => route('profile.show', $u),
                ];
            });

        $skills = Skill::where('name', 'like', "%{$query}%")
            ->take(4)
            ->get(['id', 'name', 'category'])
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'category' => $s->category ?? 'Tech',
                    'url' => route('projects.index', ['search' => $s->name]),
                ];
            });

        return response()->json([
            'projects' => $projects,
            'users' => $users,
            'skills' => $skills,
        ]);
    }
}
