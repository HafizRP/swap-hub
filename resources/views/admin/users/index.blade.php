@section('title', 'User Management')
 
<x-app-layout>
    <div x-data="{ showRoleModal: false, formAction: '', actionName: '', userDisplayName: '' }">
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
            <div class="p-4 border-b border-stone-100 dark:border-stone-800 bg-white dark:bg-[#141414]">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h5 class="font-bold text-stone-900 dark:text-stone-100 text-base mb-0 flex items-center gap-2">
                            <i class="bi bi-people-fill text-teal-600 dark:text-teal-400"></i>All Users
                        </h5>
                        <small class="text-stone-400 dark:text-stone-500 text-xs">Manage and monitor all registered users</small>
                    </div>
                    <div>
                        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg overflow-hidden w-[250px] shadow-sm">
                                <span class="pl-3 pr-2 text-stone-400">
                                    <i class="bi bi-search text-xs"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-1.5 px-1 text-xs" 
                                    placeholder="Search users...">
                            </div>
                            <select name="role" class="bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-1.5 text-xs text-stone-700 dark:text-stone-300 focus:outline-none" onchange="this.form.submit()">
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
                        <thead class="bg-stone-50 dark:bg-stone-900/60 border-b border-stone-200 dark:border-stone-800">
                            <tr>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">User</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Contact</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">University</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Role</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Reputation</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Joined</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                            @forelse($users as $user)
                                <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40 transition-colors" wire:key="admin-user-{{ $user->id }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d9488&color=fff' }}"
                                                class="rounded-full shadow-sm shrink-0 w-10 h-10 object-cover">
                                            <div class="min-w-0">
                                                <div class="font-bold text-xs text-stone-900 dark:text-stone-100 truncate">{{ $user->name }}</div>
                                                <small class="text-stone-400 dark:text-stone-500 text-[11px] truncate block mt-0.5">{{ $user->major ?? 'No major' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-stone-800 dark:text-stone-200">{{ $user->email }}</div>
                                        @if($user->phone)
                                            <small class="text-stone-400 dark:text-stone-500 text-[11px] block mt-0.5">
                                                <i class="bi bi-telephone mr-1"></i>{{ $user->phone }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-semibold text-stone-800 dark:text-stone-200 block truncate max-w-[200px]">{{ $user->university ?? 'N/A' }}</span>
                                        <small class="text-stone-400 dark:text-stone-500 text-[10px] block mt-0.5">{{ $user->student_id ?? '' }}</small>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1 items-start">
                                            <span class="rounded px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $user->role && $user->role->slug === 'admin' ? 'bg-red-500/10 text-red-500' : 'bg-teal-500/10 text-teal-700 dark:text-teal-400' }}">
                                                {{ $user->role->name ?? 'N/A' }}
                                            </span>
                                            @if($user->isSuspended())
                                                <span class="rounded px-2 py-0.5 text-[9px] font-bold uppercase bg-amber-500/10 text-amber-600 dark:text-amber-400" title="{{ $user->suspension_reason }}">
                                                    <i class="bi bi-slash-circle mr-0.5"></i>Suspended
                                                </span>
                                            @endif
                                        </div>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1 text-xs font-semibold text-stone-800 dark:text-stone-200">
                                            <i class="bi bi-star-fill text-amber-400"></i>
                                            <span>{{ $user->reputation_points }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-stone-800 dark:text-stone-200">{{ $user->created_at->format('M d, Y') }}</div>
                                        <small class="text-stone-400 dark:text-stone-500 text-[10px] block mt-0.5">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex rounded-lg overflow-hidden border border-stone-200 dark:border-stone-700 shadow-sm bg-white dark:bg-[#141414]">
                                            <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-stone-500 hover:text-teal-600 border-r border-stone-200 dark:border-stone-700 inline-block" title="View Details">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-stone-500 hover:text-sky-500 border-r border-stone-200 dark:border-stone-700 inline-block" title="Edit User">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                @php
                                                    $isUserAdmin = $user->role?->slug === 'admin';
                                                    $toggleLabel = $isUserAdmin ? 'Demote' : 'Promote';
                                                @endphp
                                                <button type="button"
                                                    @click="formAction = '{{ route('admin.users.toggle-role', $user) }}'; actionName = '{{ $toggleLabel }}'; userDisplayName = '{{ addslashes($user->name) }}'; showRoleModal = true"
                                                    class="p-2 text-stone-500 hover:text-amber-500 border-r border-stone-200 dark:border-stone-700 bg-transparent border-0 cursor-pointer"
                                                    title="{{ $isUserAdmin ? 'Demote to User' : 'Promote to Admin' }}">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>

                                                <form method="POST" action="{{ route('admin.users.toggle-suspension', $user) }}" class="inline-block" onsubmit="return confirm('{{ $user->isSuspended() ? 'Aktifkan kembali akun ini?' : 'Tangguhkan akun ini?' }}')">
                                                    @csrf
                                                    <button type="submit" class="p-2 text-stone-500 {{ $user->isSuspended() ? 'hover:text-emerald-500' : 'hover:text-amber-500' }} border-r border-stone-200 dark:border-stone-700 bg-transparent border-0 cursor-pointer" title="{{ $user->isSuspended() ? 'Unsuspend User' : 'Suspend User' }}">
                                                        <i class="bi {{ $user->isSuspended() ? 'bi-check-circle-fill text-amber-500' : 'bi-slash-circle' }}"></i>
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?')"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-stone-500 hover:text-red-500 bg-transparent border-0 cursor-pointer" title="Delete User">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="p-2 text-stone-400 bg-stone-50 dark:bg-stone-800 cursor-not-allowed border-0" disabled title="Cannot modify yourself">
                                                    <i class="bi bi-lock-fill"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10">
                                        <i class="bi bi-inbox text-4xl block mb-2 text-stone-400 opacity-50"></i>
                                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-1">No users found matching your criteria</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
                <div class="p-4 border-t border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-[#141414]">
                    {{ $users->links('pagination::tailwind') }}
                </div>
            @endif
        </div>

        <!-- Role Change Confirmation Modal (Alpine.js) -->
        <div x-show="showRoleModal" class="fixed inset-0 z-50 flex items-center justify-center p-3 bg-stone-900/60 backdrop-blur-sm" style="display: none;" x-transition>
            <div class="bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-800 rounded-xl max-w-md w-full shadow-2xl overflow-hidden">
                <div class="p-4 flex justify-between items-center border-b border-stone-100 dark:border-stone-800">
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm mb-0">Confirm Action</h5>
                    <button type="button" @click="showRoleModal = false" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 bg-transparent border-0 p-0 outline-none cursor-pointer">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form :action="formAction" method="POST" class="p-4">
                    @csrf
                    <p class="text-xs text-stone-500 dark:text-stone-400 font-bold mb-4 leading-normal uppercase">
                        Are you sure you want to <span class="text-teal-600 dark:text-teal-400" x-text="actionName.toLowerCase()"></span> user <span class="text-stone-900 dark:text-stone-100" x-text="userDisplayName"></span>?
                        <br>Please enter your password to confirm this action.
                    </p>
                    <div class="mb-5">
                        <label class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase">Your Password</label>
                        <input type="password" name="admin_password" 
                               class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" 
                               required autofocus placeholder="Confirm admin password...">
                        @error('admin_password')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-2.5 pt-3 border-t border-stone-100 dark:border-stone-800">
                        <button type="button" @click="showRoleModal = false" class="bg-stone-100 hover:bg-stone-200 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-300 font-medium py-2 px-4 rounded-lg text-xs transition-colors border-0 cursor-pointer">Cancel</button>
                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-lg text-xs transition-colors border-0 shadow-sm cursor-pointer">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
