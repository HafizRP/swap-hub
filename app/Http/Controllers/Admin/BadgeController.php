<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignBadgeRequest;
use App\Http\Requests\Admin\StoreBadgeRequest;
use App\Http\Requests\Admin\UpdateBadgeRequest;
use App\Models\Badge;
use App\Models\User;
use App\Services\AdminAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BadgeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Badge::withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $badges = $query->latest()->paginate(20)->withQueryString();
        $users = User::orderBy('name')->select('id', 'name', 'email')->get();

        return view('admin.badges.index', compact('badges', 'users'));
    }

    public function store(StoreBadgeRequest $request, AdminAuditService $auditService): RedirectResponse
    {
        $badge = Badge::create($request->validated());

        /** @var User $admin */
        $admin = Auth::user();
        $auditService->log($admin, 'badge.create', $badge, ['name' => $badge->name]);

        return back()->with('success', 'Badge penghargaan baru berhasil dibuat.');
    }

    public function update(UpdateBadgeRequest $request, Badge $badge, AdminAuditService $auditService): RedirectResponse
    {
        $badge->update($request->validated());

        /** @var User $admin */
        $admin = Auth::user();
        $auditService->log($admin, 'badge.update', $badge, ['name' => $badge->name]);

        return back()->with('success', 'Badge penghargaan berhasil diperbarui.');
    }

    public function destroy(Badge $badge, AdminAuditService $auditService): RedirectResponse
    {
        $name = $badge->name;
        $badgeId = $badge->id;
        $badge->delete();

        /** @var User $admin */
        $admin = Auth::user();
        $auditService->log($admin, 'badge.delete', null, ['badge_id' => $badgeId, 'name' => $name]);

        return back()->with('success', 'Badge berhasil dihapus.');
    }

    public function assign(AssignBadgeRequest $request, AdminAuditService $auditService): RedirectResponse
    {
        $validated = $request->validated();
        $user = User::findOrFail($validated['user_id']);
        $badge = Badge::findOrFail($validated['badge_id']);

        if (! $user->badges()->where('badge_id', $badge->id)->exists()) {
            $user->badges()->attach($badge->id, ['awarded_at' => now()]);
        }

        /** @var User $admin */
        $admin = Auth::user();
        $auditService->log($admin, 'badge.assign', $badge, [
            'target_user_id' => $user->id,
            'target_user_name' => $user->name,
        ]);

        return back()->with('success', "Badge {$badge->name} berhasil disematkan ke {$user->name}.");
    }
}
