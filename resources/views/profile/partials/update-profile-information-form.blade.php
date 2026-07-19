<section>
    <header class="mb-6">
        <h4 class="text-xl font-black text-slate-800 dark:text-slate-100">
            {{ __('Account Intelligence') }}
        </h4>
 
        <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1">
            {{ __("Manage your academic identity and contact credentials.") }}
        </p>
    </header>
 
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
 
    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')
 
        <div class="mb-5">
            <label for="name" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('Full Name') }}</label>
            <input id="name" name="name" type="text"
                class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
 
        <div class="mb-5">
            <label for="email" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('Academic Email') }}</label>
            <input id="email" name="email" type="email"
                class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
 
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-3 p-4 border border-amber-500/25 bg-amber-500/10 rounded-xl">
                    <p class="text-xs text-amber-700 dark:text-amber-400 font-bold mb-2">
                        {{ __('Your email address is unverified.') }}
                    </p>
                    <button form="send-verification"
                        class="text-[10px] text-amber-600 dark:text-amber-450 hover:underline uppercase tracking-wider font-black bg-transparent border-0 p-0">
                        {{ __('Resend Verification') }}
                    </button>
 
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-[10px] text-emerald-600 dark:text-emerald-450 font-bold uppercase tracking-wider">
                            {{ __('A new link has been dispatched.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
            <div class="col-span-1">
                <label for="university" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('University') }}</label>
                <input id="university" name="university" type="text"
                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                    value="{{ old('university', $user->university) }}">
                <x-input-error class="mt-2" :messages="$errors->get('university')" />
            </div>
 
            <div class="col-span-1">
                <label for="major" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('Course of Study') }}</label>
                <input id="major" name="major" type="text"
                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                    value="{{ old('major', $user->major) }}">
                <x-input-error class="mt-2" :messages="$errors->get('major')" />
            </div>
        </div>
 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
            <div class="col-span-1">
                <label for="graduation_year" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('Graduation Year') }}</label>
                <input id="graduation_year" name="graduation_year" type="number"
                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                    value="{{ old('graduation_year', $user->graduation_year) }}" min="2000" max="2100">
                <x-input-error class="mt-2" :messages="$errors->get('graduation_year')" />
            </div>
 
            <div class="col-span-1">
                <label for="phone" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('Pulse Line (Phone)') }}</label>
                <input id="phone" name="phone" type="text"
                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                    value="{{ old('phone', $user->phone) }}">
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
        </div>
 
        <div class="mb-5">
            <label for="github_username" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('GitHub Handle') }}</label>
            <div class="flex rounded-lg overflow-hidden border border-slate-200 dark:border-slate-600">
                <span class="inline-flex items-center px-3 bg-slate-100 dark:bg-slate-700 border-r border-slate-200 dark:border-slate-600 text-slate-400 text-sm">github.com/</span>
                <input id="github_username" name="github_username" type="text"
                    class="flex-1 w-full bg-slate-50 dark:bg-slate-700/50 border-0 px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors"
                    value="{{ old('github_username', $user->github_username) }}" placeholder="Octocat">
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('github_username')" />
        </div>
 
        <div class="mb-6">
            <label for="bio" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('The Pivot (Bio)') }}</label>
            <textarea id="bio" name="bio"
                class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors"
                rows="4">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>
 
        <div class="flex items-center gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-755 hover:bg-indigo-700 text-white font-black py-3 px-6 rounded-full text-xs shadow-lg transition-colors border-0">
                {{ __('Update Credentials') }}
            </button>
 
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] text-emerald-600 dark:text-emerald-450 font-black uppercase tracking-wider mb-0">
                    {{ __('Identity Synchronized.') }}
                </p>
            @endif
        </div>
    </form>
</section>