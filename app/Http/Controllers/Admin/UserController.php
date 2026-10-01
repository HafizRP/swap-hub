<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('university', 'like', "%{$search}%");
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

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:user,student,admin'],
            'reputation_points' => ['nullable', 'integer', 'min:0'],
        ]);

        $roleSlug = in_array($validated['role'], ['user', 'student'], true) ? 'student' : 'admin';
        $role = Role::where('slug', $roleSlug)->first();

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'reputation_points' => $validated['reputation_points'] ?? $user->reputation_points,
        ];

        if ($role && (int) $user->id !== (int) Auth::id()) {
            $updateData['role_id'] = $role->id;
        }

        $user->update($updateData);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Prevent self-deletion
        if ((int) $user->id === (int) Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleRole(Request $request, User $user): RedirectResponse
    {
        // Prevent self-demotion
        if ((int) $user->id === (int) Auth::id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        // Validate password
        $request->validate([
            'admin_password' => 'required|string',
        ]);

        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (! Hash::check($request->admin_password, $currentUser->password)) {
            return back()->withErrors(['admin_password' => 'Incorrect password provided.']);
        }

        $isCurrentlyAdmin = $user->role && $user->role->slug === 'admin';
        $targetSlug = $isCurrentlyAdmin ? 'student' : 'admin';
        $targetRole = Role::where('slug', $targetSlug)->first();

        if ($targetRole) {
            $user->update([
                'role_id' => $targetRole->id,
            ]);
        }

        return back()->with('success', 'User role updated successfully.');
    }
}
