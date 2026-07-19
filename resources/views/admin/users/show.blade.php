<x-app-layout>
    @section('title', 'User Details')
 
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Sidebar Profile -->
        <div class="lg:col-span-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-205 border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 text-center">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                        class="rounded-circle mb-4 shadow mx-auto" width="120" height="120">
                    <h4 class="font-bold text-slate-850 dark:text-slate-100 text-lg mb-1">{{ $user->name }}</h4>
                    <p class="text-xs text-slate-450 dark:text-slate-500 mb-4">{{ $user->email }}</p>
                    <span class="rounded-lg px-3 py-1.5 text-xs font-bold uppercase inline-flex items-center gap-1.5 {{ $user->role && $user->role->slug === 'admin' ? 'bg-red-500/10 text-red-500' : 'bg-indigo-500/10 text-indigo-650 dark:text-indigo-400' }}">
                        <i class="bi {{ $user->role && $user->role->slug === 'admin' ? 'bi-shield-fill' : 'bi-person-fill' }}"></i>
                        {{ $user->role->name ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>
 
        <!-- Info Details -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50">
                    <h5 class="font-bold text-slate-850 dark:text-slate-100 text-base mb-0 flex items-center gap-2">
                        <i class="bi bi-info-circle-fill text-indigo-650 dark:text-indigo-400"></i>User Information
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="col-span-1">
                            <label class="block text-slate-450 dark:text-slate-505 font-bold text-xs uppercase mb-1">University</label>
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">{{ $user->university ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-slate-455 dark:text-slate-505 font-bold text-xs uppercase mb-1">Major</label>
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">{{ $user->major ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-slate-455 dark:text-slate-505 font-bold text-xs uppercase mb-1">Phone</label>
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-slate-455 dark:text-slate-505 font-bold text-xs uppercase mb-1">Graduation Year</label>
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">{{ $user->graduation_year ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-slate-455 dark:text-slate-505 font-bold text-xs uppercase mb-1">Reputation Points</label>
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-200 flex items-center gap-1">
                                <i class="bi bi-star-fill text-amber-400"></i>{{ number_format($user->reputation_points) }}
                            </p>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-slate-455 dark:text-slate-505 font-bold text-xs uppercase mb-1">Joined</label>
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-slate-455 dark:text-slate-550 font-bold text-xs uppercase mb-1">Bio</label>
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">{{ $user->bio ?? 'No bio provided' }}</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-800/40 flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-250 font-bold py-2 px-5 rounded-full text-xs transition-colors no-underline flex items-center gap-1.5">
                        <i class="bi bi-arrow-left"></i>Back to List
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded-full text-xs transition-colors no-underline flex items-center gap-1.5 border-0 shadow">
                        <i class="bi bi-pencil-fill"></i>Edit User
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>