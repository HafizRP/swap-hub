<x-app-layout>
@section('title', 'Edit User')
 
<div class="container mx-auto py-6">
    <div class="flex justify-center">
        <div class="w-full max-w-3xl">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="h-1.5 bg-gradient-to-right bg-gradient-to-r from-indigo-500 to-indigo-400"></div>
                <div class="p-6 md:p-8">
                    <header class="mb-6">
                        <h4 class="text-xl font-black text-slate-850 dark:text-slate-100 flex items-center gap-2">
                            <i class="bi bi-pencil-fill text-indigo-650 dark:text-indigo-400"></i>Edit User Profile
                        </h4>
                        <p class="text-xs text-slate-450 dark:text-slate-500 font-bold uppercase tracking-wider mt-1">
                            Manage user account information and permissions
                        </p>
                    </header>
 
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-4">
                        @csrf
                        @method('PUT')
 
                        <!-- Full Name -->
                        <div class="mb-5">
                            <label for="name" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Full Name</label>
                            <input id="name" name="name" type="text"
                                class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-850 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors"
                                value="{{ old('name', $user->name) }}" required autofocus>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
 
                        <!-- Email Address -->
                        <div class="mb-5">
                            <label for="email" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Email Address</label>
                            <input id="email" name="email" type="email"
                                class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-850 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors"
                                value="{{ old('email', $user->email) }}" required>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>
 
                        <!-- University and Major -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                            <div class="col-span-1">
                                <label for="university" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">University</label>
                                <input id="university" name="university" type="text"
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-850 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                                    value="{{ old('university', $user->university) }}">
                                <x-input-error class="mt-2" :messages="$errors->get('university')" />
                            </div>
 
                            <div class="col-span-1">
                                <label for="major" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Course of Study</label>
                                <input id="major" name="major" type="text"
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-850 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                                    value="{{ old('major', $user->major) }}">
                                <x-input-error class="mt-2" :messages="$errors->get('major')" />
                            </div>
                        </div>
 
                        <!-- Graduation Year and Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                            <div class="col-span-1">
                                <label for="graduation_year" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Graduation Year</label>
                                <input id="graduation_year" name="graduation_year" type="number"
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-850 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                                    value="{{ old('graduation_year', $user->graduation_year) }}" min="2000" max="2100">
                                <x-input-error class="mt-2" :messages="$errors->get('graduation_year')" />
                            </div>
 
                            <div class="col-span-1">
                                <label for="phone" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Phone Number</label>
                                <input id="phone" name="phone" type="text"
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-850 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                                    value="{{ old('phone', $user->phone) }}">
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>
                        </div>
 
                        <!-- Role and Reputation -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                            <div class="col-span-1">
                                <label for="role" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">User Role</label>
                                <select id="role" name="role" 
                                    class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-3 text-sm text-slate-850 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
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
                                <label for="reputation_points" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Reputation Points</label>
                                <input id="reputation_points" name="reputation_points" type="number"
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-850 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                                    value="{{ old('reputation_points', $user->reputation_points) }}">
                                <x-input-error class="mt-2" :messages="$errors->get('reputation_points')" />
                            </div>
                        </div>
 
                        <!-- Bio -->
                        <div class="mb-6">
                            <label for="bio" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">User Bio</label>
                            <textarea id="bio" name="bio"
                                class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-855 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                                rows="4">{{ old('bio', $user->bio) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                        </div>
 
                        <!-- Actions -->
                        <div class="flex gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                            <a href="{{ route('admin.users.show', $user) }}" class="w-1/2 block text-center border border-slate-250 dark:border-slate-700 text-slate-700 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-700/50 font-bold py-3 px-4 rounded-xl text-xs transition-colors no-underline">
                                <i class="bi bi-x-circle mr-1"></i>Cancel
                            </a>
                            <button type="submit" class="w-1/2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl text-xs shadow border-0 transition-colors">
                                <i class="bi bi-check-circle-fill mr-1"></i>Update User
                            </button>
                        </div>
 
                        @if (session('status') === 'user-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                class="text-xs text-emerald-600 dark:text-emerald-450 font-bold uppercase tracking-wider mt-4 text-center">
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