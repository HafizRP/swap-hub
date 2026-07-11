<section x-data="{ showConfirmModal: false }">
    <header class="mb-6">
        <h4 class="text-xl font-black text-red-650 dark:text-red-500">
            {{ __('Terminal Phase (Delete Account)') }}
        </h4>
 
        <p class="text-xs text-red-700/80 dark:text-red-400/80 font-bold uppercase tracking-wider mt-1">
            {{ __('Once your account is purged, all resources and data will be permanently erased from the matrix.') }}
        </p>
    </header>
 
    <button @click="showConfirmModal = true" class="border border-red-600 text-red-600 hover:bg-red-500 hover:text-white font-black py-2.5 px-4 rounded-full text-xs transition-all uppercase tracking-wider">
        {{ __('Terminate Identity') }}
    </button>
 
    <!-- Modal (Alpine.js) -->
    <div x-show="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-slate-900/60 p-4" style="display: none;" x-transition>
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-md w-full shadow-2xl p-6" @click.outside="showConfirmModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-700/50 mb-4">
                <h4 class="font-black text-slate-800 dark:text-slate-100 text-lg">
                    {{ __('Confirm Identity Deletion?') }}
                </h4>
                <button @click="showConfirmModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>
            
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
 
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold mb-4 leading-normal uppercase">
                    {{ __('This action is irreversible. Please enter your authentication key (password) to finalize the purging of your student profile.') }}
                </p>
 
                <div class="mb-5">
                    <label for="password" class="block text-slate-500 dark:text-slate-400 text-xs font-bold mb-2 uppercase">{{ __('Access Key (Password)') }}</label>
                    <input id="password" name="password" type="password" 
                           class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-red-500 transition-colors" 
                           placeholder="{{ __('Enter password...') }}">
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>
 
                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                    <button type="button" @click="showConfirmModal = false" class="text-slate-400 hover:text-slate-655 text-xs font-black uppercase tracking-wider bg-transparent border-0 py-2.5 px-4 rounded-lg">
                        {{ __('Abort') }}
                    </button>
 
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-black py-2.5 px-4 rounded-full text-xs shadow transition-colors border-0 uppercase tracking-wider">
                        {{ __('Purge Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
