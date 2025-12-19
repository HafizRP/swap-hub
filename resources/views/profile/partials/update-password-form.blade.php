<section>
    <header class="mb-4">
        <h4 class="fw-black text-white">
            {{ __('Encryption Protocol') }}
        </h4>

        <p class="small text-secondary fw-bold text-uppercase tracking-widest opacity-50">
            {{ __('Ensure your account is using a high-entropy password to maintain security.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-4">
            <label for="update_password_current_password" class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('Current Key') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner" 
                   autocomplete="current-password" style="background-color: rgba(255,255,255,0.05) !important;">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="mb-4">
            <label for="update_password_password" class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('New Cipher') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner" 
                   autocomplete="new-password" style="background-color: rgba(255,255,255,0.05) !important;">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="mb-5">
            <label for="update_password_password_confirmation" class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('Confirm Cipher') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner" 
                   autocomplete="new-password" style="background-color: rgba(255,255,255,0.05) !important;">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center gap-4 py-3 border-top border-white border-opacity-5">
            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg">
                {{ __('Rotate Keys') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="small text-success fw-black text-uppercase tracking-widest mb-0"
                >{{ __('Handshake Modified.') }}</p>
            @endif
        </div>
    </form>
</section>
