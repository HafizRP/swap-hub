<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SuspendUserRequest;
use App\Http\Requests\Admin\ToggleRoleRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\AdminAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('role');

        // Search
        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('university', 'like', "%{$search}%")
                    ->orWhere('major', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role !== 'all') {
            $roleSlug = $request->role === 'user' ? 'student' : $request->role;
            $query->whereHas('role', function ($q) use ($roleSlug) {
                $q->where('slug', $roleSlug);
            });
        }

        // Sort with allowlist
        $allowedSorts = ['id', 'name', 'email', 'university', 'reputation_points', 'created_at', 'updated_at'];
        $allowedOrders = ['asc', 'desc'];

        $sortBy = in_array($request->get('sort'), $allowedSorts, true) ? $request->get('sort') : 'created_at';
        $sortOrder = in_array(strtolower((string) $request->get('order', '')), $allowedOrders, true) ? strtolower((string) $request->get('order')) : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['skills', 'ownedProjects', 'projects']);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $roleSlug = in_array($request->role, ['user', 'student'], true) ? 'student' : 'admin';
        $role = Role::where('slug', $roleSlug)->first();

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'reputation_points' => $request->reputation_points ?? $user->reputation_points,
        ];

        if ($role && (int) $user->id !== (int) Auth::id()) {
            $updateData['role_id'] = $role->id;
        }

        $user->update($updateData);

        app(AdminAuditService::class)->log(
            Auth::user(),
            'user.update',
            $user,
            ['fields' => array_keys($updateData)]
        );

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Prevent self-deletion
        if ((int) $user->id === (int) Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userId = $user->id;
        $user->delete();

        app(AdminAuditService::class)->log(
            Auth::user(),
            'user.delete',
            null,
            ['deleted_user_id' => $userId, 'email' => $user->email]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleRole(ToggleRoleRequest $request, User $user): RedirectResponse
    {
        // Prevent self-demotion
        if ((int) $user->id === (int) Auth::id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (! Hash::check($request->admin_password, $currentUser->password)) {
            \Log::warning('Security: Failed admin password verification during role toggle attempt', [
                'admin_id' => $currentUser->id,
                'target_user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            return back()->withErrors(['admin_password' => 'Incorrect password provided.']);
        }

        $isCurrentlyAdmin = $user->role && $user->role->slug === 'admin';
        $targetSlug = $isCurrentlyAdmin ? 'student' : 'admin';
        $targetRole = Role::where('slug', $targetSlug)->first();

        if ($targetRole) {
            $user->update([
                'role_id' => $targetRole->id,
            ]);

            app(AdminAuditService::class)->log(
                $currentUser,
                'user.toggle_role',
                $user,
                ['new_role' => $targetSlug]
            );
        }

        return back()->with('success', 'User role updated successfully.');
    }

    public function toggleSuspension(SuspendUserRequest $request, User $user, AdminAuditService $auditService): RedirectResponse
    {
        if ((int) $user->id === (int) Auth::id()) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        /** @var User $admin */
        $admin = Auth::user();

        if ($user->isSuspended()) {
            $user->update([
                'suspended_at' => null,
                'suspension_reason' => null,
            ]);
            $action = 'user.unsuspend';
            $message = 'Akun pengguna berhasil diaktifkan kembali.';
        } else {
            $reason = $request->input('reason') ?: 'Ditangguhkan oleh administrator.';
            $user->update([
                'suspended_at' => now(),
                'suspension_reason' => $reason,
            ]);
            $action = 'user.suspend';
            $message = 'Pengguna berhasil ditangguhkan.';
        }

        $auditService->log($admin, $action, $user, [
            'reason' => $user->suspension_reason,
        ]);

        return back()->with('success', $message);
    }
}
