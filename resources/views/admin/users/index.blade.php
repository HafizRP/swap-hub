@section('title', 'User Management')
 
<x-app-layout>
    <div x-data="{ showRoleModal: false, formAction: '', actionName: '', userDisplayName: '' }">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700/50 bg-white dark:bg-slate-800">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h5 class="font-bold text-slate-850 dark:text-slate-100 text-lg mb-1 flex items-center gap-2">
                            <i class="bi bi-people-fill text-indigo-650 dark:text-indigo-400"></i>All Users
                        </h5>
                        <small class="text-slate-450 dark:text-slate-500">Manage and monitor all registered users</small>
                    </div>
                    <div>
                        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center bg-slate-50 dark:bg-slate-750 border border-slate-200 dark:border-slate-700 rounded-full overflow-hidden w-[250px] shadow-sm">
                                <span class="pl-4 pr-2 text-slate-405 text-slate-400">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="bg-transparent border-0 w-full outline-none text-slate-850 dark:text-slate-100 placeholder-slate-400 py-2 px-1 text-xs" 
                                    placeholder="Search users...">
                            </div>
                            <select name="role" class="bg-slate-550 bg-slate-50 dark:bg-slate-750 border border-slate-200 dark:border-slate-700 rounded-full px-4 py-2 text-xs text-slate-700 dark:text-slate-250 focus:outline-none" onchange="this.form.submit()">
                                <option value="all">All Roles</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse align-middle text-nowrap">
                        <thead class="bg-slate-50 dark:bg-slate-700/30">
                            <tr>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-455 uppercase border-0">User</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-455 uppercase border-0">Contact</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-455 uppercase border-0">University</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-455 uppercase border-0">Role</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-455 uppercase border-0">Reputation</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-455 uppercase border-0">Joined</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 dark:text-slate-455 uppercase border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-755/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                                class="rounded-circle shadow-sm shrink-0" width="48" height="48">
                                            <div class="min-w-0">
                                                <div class="font-bold text-sm text-slate-850 dark:text-slate-100 truncate">{{ $user->name }}</div>
                                                <small class="text-slate-450 dark:text-slate-400 text-xs truncate block mt-0.5">{{ $user->major ?? 'No major' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-800 dark:text-slate-200">{{ $user->email }}</div>
                                        @if($user->phone)
                                            <small class="text-slate-400 dark:text-slate-505 text-xs block mt-0.5">
                                                <i class="bi bi-telephone mr-1"></i>{{ $user->phone }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 block truncate max-w-[200px]">{{ $user->university ?? 'N/A' }}</span>
                                        <small class="text-slate-400 dark:text-slate-500 text-[10px] block mt-0.5">{{ $user->student_id ?? '' }}</small>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $user->role && $user->role->slug === 'admin' ? 'bg-red-500/10 text-red-500' : 'bg-indigo-500/10 text-indigo-650 dark:text-indigo-400' }}">
                                            {{ $user->role->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1 text-xs font-semibold text-slate-800 dark:text-slate-200">
                                            <i class="bi bi-star-fill text-amber-400"></i>
                                            <span>{{ $user->reputation_points }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-slate-800 dark:text-slate-200">{{ $user->created_at->format('M d, Y') }}</div>
                                        <small class="text-slate-400 dark:text-slate-500 text-[10px] block mt-0.5">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
                                            <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-slate-500 hover:text-indigo-605 border-r border-slate-200 dark:border-slate-700 inline-block" title="View Details">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-slate-500 hover:text-sky-500 border-r border-slate-200 dark:border-slate-700 inline-block" title="Edit User">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                @php
                                                    $isUserAdmin = $user->role?->slug === 'admin';
                                                    $toggleLabel = $isUserAdmin ? 'Demote' : 'Promote';
                                                @endphp
                                                <button type="button"
                                                    @click="formAction = '{{ route('admin.users.toggle-role', $user) }}'; actionName = '{{ $toggleLabel }}'; userDisplayName = '{{ addslashes($user->name) }}'; showRoleModal = true"
                                                    class="p-2 text-slate-500 hover:text-amber-500 border-r border-slate-200 dark:border-slate-700 bg-transparent border-0"
                                                    title="{{ $isUserAdmin ? 'Demote to User' : 'Promote to Admin' }}">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
 
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?')"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-500 hover:text-red-500 bg-transparent border-0" title="Delete User">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="p-2 text-slate-400 bg-slate-50 dark:bg-slate-750 cursor-not-allowed border-0" disabled title="Cannot modify yourself">
                                                    <i class="bi bi-lock-fill"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10">
                                        <i class="bi bi-inbox text-4xl block mb-2 text-slate-400 opacity-50"></i>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">No users found matching your criteria</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-800/40">
                    {{ $users->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
 
        <!-- Role Change Confirmation Modal (Alpine.js) -->
        <div x-show="showRoleModal" class="fixed inset-0 z-50 flex items-center justify-center p-3 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition>
            <div class="bg-white dark:bg-slate-800 border border-slate-202 border-slate-200 dark:border-slate-700 rounded-2xl max-w-md w-full shadow-2xl overflow-hidden">
                <div class="p-5 flex justify-between items-center border-b border-slate-100 dark:border-slate-700/50">
                    <h5 class="font-bold text-slate-850 dark:text-slate-100 text-base">Confirm Action</h5>
                    <button type="button" @click="showRoleModal = false" class="text-slate-400 hover:text-slate-655 dark:hover:text-slate-300 bg-transparent border-0 p-0 outline-none">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form :action="formAction" method="POST" class="p-5">
                    @csrf
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-bold mb-4 leading-normal uppercase">
                        Are you sure you want to <span class="text-indigo-650" x-text="actionName.toLowerCase()"></span> user <span class="text-slate-800 dark:text-slate-100" x-text="userDisplayName"></span>?
                        <br>Please enter your password to confirm this action.
                    </p>
                    <div class="mb-5">
                        <label class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">Your Password</label>
                        <input type="password" name="admin_password" 
                               class="w-full bg-slate-50 dark:bg-slate-700/55 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-850 dark:text-slate-100 focus:outline-none focus:border-indigo-500" 
                               required autofocus placeholder="Confirm admin password...">
                        @error('admin_password')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                        <button type="button" @click="showRoleModal = false" class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-755 dark:text-slate-250 font-bold py-2 px-5 rounded-full text-xs transition-colors border-0">Cancel</button>
                        <button type="submit" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded-full text-xs transition-colors border-0 shadow">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>