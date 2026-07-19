<section>
    <header class="mb-6">
        <h4 class="text-xl font-black text-slate-800 dark:text-slate-100">
            {{ __('Encryption Protocol') }}
        </h4>
 
        <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1">
            {{ __('Ensure your account is using a high-entropy password to maintain security.') }}
        </p>
    </header>
 
    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')
 
        <div class="mb-5">
            <label for="update_password_current_password" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('Current Key') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" 
                   class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors" 
                   autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>
 
        <div class="mb-5">
            <label for="update_password_password" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('New Cipher') }}</label>
            <input id="update_password_password" name="password" type="password" 
                   class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors" 
                   autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>
 
        <div class="mb-6">
            <label for="update_password_password_confirmation" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('Confirm Cipher') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                   class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors" 
                   autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>
 
        <div class="flex items-center gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-6 rounded-full text-xs shadow-lg transition-colors border-0">
                {{ __('Rotate Keys') }}
            </button>
 
            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] text-emerald-600 dark:text-emerald-450 font-black uppercase tracking-wider mb-0"
                >{{ __('Handshake Modified.') }}</p>
            @endif
        </div>
    </form>
</section>
