<x-app-layout>
@section('title', 'Edit User')

<div class="container mx-auto py-6">
    <div class="flex justify-center">
        <div class="w-full max-w-3xl">
            <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                <div class="h-1.5 bg-gradient-to-r from-teal-500 to-teal-400"></div>
                <div class="p-4 md:p-6">
                    <header class="mb-6">
                        <h4 class="text-base font-black text-stone-900 dark:text-stone-100 flex items-center gap-2 mb-0">
                            <i class="bi bi-pencil-fill text-teal-600 dark:text-teal-400"></i>Edit User Profile
                        </h4>
                        <p class="text-xs text-stone-500 dark:text-stone-400 font-bold uppercase tracking-wider mt-1 mb-0">
                            Manage user account information and permissions
                        </p>
                    </header>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-4">
                        @csrf
                        @method('PUT')

                        <!-- Full Name -->
                        <div class="mb-5">
                            <label for="name" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Full Name</label>
                            <input id="name" name="name" type="text"
                                class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                value="{{ old('name', $user->name) }}" required autofocus>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email Address -->
                        <div class="mb-5">
                            <label for="email" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Email Address</label>
                            <input id="email" name="email" type="email"
                                class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                value="{{ old('email', $user->email) }}" required>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- University and Major -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                            <div class="col-span-1">
                                <label for="university" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">University</label>
                                <input id="university" name="university" type="text"
                                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                    value="{{ old('university', $user->university) }}">
                                <x-input-error class="mt-2" :messages="$errors->get('university')" />
                            </div>

                            <div class="col-span-1">
                                <label for="major" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Course of Study</label>
                                <input id="major" name="major" type="text"
                                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                    value="{{ old('major', $user->major) }}">
                                <x-input-error class="mt-2" :messages="$errors->get('major')" />
                            </div>
                        </div>

                        <!-- Graduation Year and Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                            <div class="col-span-1">
                                <label for="graduation_year" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Graduation Year</label>
                                <input id="graduation_year" name="graduation_year" type="number"
                                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                    value="{{ old('graduation_year', $user->graduation_year) }}" min="2000" max="2100">
                                <x-input-error class="mt-2" :messages="$errors->get('graduation_year')" />
                            </div>

                            <div class="col-span-1">
                                <label for="phone" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Phone Number</label>
                                <input id="phone" name="phone" type="text"
                                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                    value="{{ old('phone', $user->phone) }}">
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>
                        </div>

                        <!-- Role and Reputation -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                            <div class="col-span-1">
                                <label for="role" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">User Role</label>
                                <select id="role" name="role" 
                                    class="w-full bg-white dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="user" {{ $user->role?->slug === 'student' || $user->role?->slug === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user->role?->slug === 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                                @if($user->id === auth()->id())
                                    <small class="text-amber-500 text-xs mt-2 block">⚠️ You cannot change your own role</small>
                                @endif
                                <x-input-error class="mt-2" :messages="$errors->get('role')" />
                            </div>

                            <div class="col-span-1">
                                <label for="reputation_points" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Reputation Points</label>
                                <input id="reputation_points" name="reputation_points" type="number"
                                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                    value="{{ old('reputation_points', $user->reputation_points) }}">
                                <x-input-error class="mt-2" :messages="$errors->get('reputation_points')" />
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="mb-6">
                            <label for="bio" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">User Bio</label>
                            <textarea id="bio" name="bio"
                                class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-3 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                rows="4">{{ old('bio', $user->bio) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-4 pt-6 border-t border-stone-200 dark:border-stone-800">
                            <a href="{{ route('admin.users.show', $user) }}" class="w-1/2 block text-center border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-800 font-medium py-2 px-3 rounded-lg text-xs transition-colors no-underline">
                                <i class="bi bi-x-circle mr-1"></i>Cancel
                            </a>
                            <button type="submit" class="w-1/2 bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-3 rounded-lg text-xs shadow border-0 transition-colors cursor-pointer">
                                <i class="bi bi-check-circle-fill mr-1"></i>Update User
                            </button>
                        </div>

                        @if (session('status') === 'user-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                class="text-xs text-emerald-600 dark:text-emerald-400 font-bold uppercase tracking-wider mt-4 text-center">
                                User Updated Successfully.
                            </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
