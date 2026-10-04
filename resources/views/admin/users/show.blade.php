<x-app-layout>
    @section('title', 'User Details')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <!-- Sidebar Profile -->
        <div class="lg:col-span-4">
            <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                <div class="p-4 text-center">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d9488&color=fff' }}"
                        class="rounded-full mb-4 shadow-sm mx-auto w-24 h-24 object-cover">
                    <h4 class="font-bold text-stone-900 dark:text-stone-100 text-base mb-1">{{ $user->name }}</h4>
                    <p class="text-xs text-stone-400 dark:text-stone-500 mb-4">{{ $user->email }}</p>
                    <span class="rounded-lg px-3 py-1.5 text-xs font-bold uppercase inline-flex items-center gap-1.5 {{ $user->role && $user->role->slug === 'admin' ? 'bg-red-500/10 text-red-500' : 'bg-teal-500/10 text-teal-700 dark:text-teal-400' }}">
                        <i class="bi {{ $user->role && $user->role->slug === 'admin' ? 'bi-shield-fill' : 'bi-person-fill' }}"></i>
                        {{ $user->role->name ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Details -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-100 dark:border-stone-800">
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm mb-0 flex items-center gap-2">
                        <i class="bi bi-info-circle-fill text-teal-600 dark:text-teal-400"></i>User Information
                    </h5>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs uppercase mb-1">University</label>
                            <p class="font-semibold text-xs text-stone-800 dark:text-stone-200">{{ $user->university ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs uppercase mb-1">Major</label>
                            <p class="font-semibold text-xs text-stone-800 dark:text-stone-200">{{ $user->major ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs uppercase mb-1">Phone</label>
                            <p class="font-semibold text-xs text-stone-800 dark:text-stone-200">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs uppercase mb-1">Graduation Year</label>
                            <p class="font-semibold text-xs text-stone-800 dark:text-stone-200">{{ $user->graduation_year ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs uppercase mb-1">Reputation Points</label>
                            <p class="font-semibold text-xs text-stone-800 dark:text-stone-200 flex items-center gap-1">
                                <i class="bi bi-star-fill text-amber-400"></i>{{ number_format($user->reputation_points) }}
                            </p>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs uppercase mb-1">Joined</label>
                            <p class="font-semibold text-xs text-stone-800 dark:text-stone-200">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs uppercase mb-1">Bio</label>
                            <p class="font-semibold text-xs text-stone-800 dark:text-stone-200">{{ $user->bio ?? 'No bio provided' }}</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-[#141414] flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="bg-stone-100 hover:bg-stone-200 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-300 font-medium py-1.5 px-4 rounded-lg text-xs transition-colors no-underline flex items-center gap-1.5">
                        <i class="bi bi-arrow-left"></i>Back to List
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-1.5 px-4 rounded-lg text-xs transition-colors no-underline flex items-center gap-1.5 border-0 shadow-sm">
                        <i class="bi bi-pencil-fill"></i>Edit User
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
