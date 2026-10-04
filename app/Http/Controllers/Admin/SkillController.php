<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSkillRequest;
use App\Http\Requests\Admin\UpdateSkillRequest;
use App\Models\Skill;
use App\Models\User;
use App\Services\AdminAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(Request $request): View
    {
        $query = Skill::withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%");
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $skills = $query->orderBy('name')->paginate(20)->withQueryString();
        $categories = Skill::whereNotNull('category')->distinct()->pluck('category');

        return view('admin.skills.index', compact('skills', 'categories'));
    }

    public function store(StoreSkillRequest $request, AdminAuditService $auditService): RedirectResponse
    {
        $skill = Skill::create($request->validated());

        /** @var User $admin */
        $admin = Auth::user();
        $auditService->log($admin, 'skill.create', $skill, ['name' => $skill->name]);

        return back()->with('success', 'Keahlian baru berhasil ditambahkan.');
    }

    public function update(UpdateSkillRequest $request, Skill $skill, AdminAuditService $auditService): RedirectResponse
    {
        $skill->update($request->validated());

        /** @var User $admin */
        $admin = Auth::user();
        $auditService->log($admin, 'skill.update', $skill, ['name' => $skill->name]);

        return back()->with('success', 'Keahlian berhasil diperbarui.');
    }

    public function destroy(Skill $skill, AdminAuditService $auditService): RedirectResponse
    {
        $name = $skill->name;
        $skillId = $skill->id;
        $skill->delete();

        /** @var User $admin */
        $admin = Auth::user();
        $auditService->log($admin, 'skill.delete', null, ['skill_id' => $skillId, 'name' => $name]);

        return back()->with('success', 'Keahlian berhasil dihapus.');
    }
}
