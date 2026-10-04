<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Models\Project;
use App\Services\AdminAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::with(['owner', 'members']);

        // Search
        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhereHas('owner', function ($oq) use ($search) {
                        $oq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Sort with allowlist
        $allowedSorts = ['id', 'title', 'status', 'category', 'created_at', 'updated_at'];
        $allowedOrders = ['asc', 'desc'];

        $sortBy = in_array($request->get('sort'), $allowedSorts, true) ? $request->get('sort') : 'created_at';
        $sortOrder = in_array(strtolower((string) $request->get('order', '')), $allowedOrders, true) ? strtolower((string) $request->get('order')) : 'desc';

        $query->orderBy($sortBy, $sortOrder);

        $projects = $query->paginate(20)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function show(Project $project): View
    {
        $project->load(['owner', 'members', 'tasks', 'githubActivities']);

        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        app(AdminAuditService::class)->log(
            Auth::user(),
            'project.update',
            $project,
            ['fields' => array_keys($request->validated())]
        );

        return redirect()->route('admin.projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $projectId = $project->id;
        $title = $project->title;
        $project->delete();

        app(AdminAuditService::class)->log(
            Auth::user(),
            'project.delete',
            null,
            ['project_id' => $projectId, 'title' => $title]
        );

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function archive(Project $project): RedirectResponse
    {
        $project->update(['status' => 'archived']);

        app(AdminAuditService::class)->log(
            Auth::user(),
            'project.archive',
            $project,
            ['title' => $project->title]
        );

        return back()->with('success', 'Project archived successfully.');
    }
}
