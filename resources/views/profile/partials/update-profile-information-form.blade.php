<section>
    <header class="mb-4">
        <h4 class="fw-black text-white">
            {{ __('Account Intelligence') }}
        </h4>

        <p class="small text-secondary fw-bold text-uppercase tracking-widest opacity-50">
            {{ __("Manage your academic identity and contact credentials.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-4">
            <label for="name"
                class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('Full Name') }}</label>
            <input id="name" name="name" type="text"
                class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                style="background-color: rgba(255,255,255,0.05) !important;">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-4">
            <label for="email"
                class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('Academic Email') }}</label>
            <input id="email" name="email" type="email"
                class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner"
                value="{{ old('email', $user->email) }}" required autocomplete="username"
                style="background-color: rgba(255,255,255,0.05) !important;">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-3 p-3 border border-warning border-opacity-25 rounded-4"
                    style="background-color: rgba(255, 193, 7, 0.1) !important;">
                    <p class="small text-warning fw-bold mb-2">
                        {{ __('Your email address is unverified.') }}
                    </p>
                    <button form="send-verification"
                        class="btn btn-link btn-sm text-warning p-0 fw-black text-decoration-none text-uppercase tracking-widest"
                        style="font-size: 10px;">
                        {{ __('Resend Verification') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 small text-success fw-bold text-uppercase tracking-widest" style="font-size: 10px;">
                            {{ __('A new link has been dispatched.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label for="university"
                    class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('University') }}</label>
                <input id="university" name="university" type="text"
                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner"
                    value="{{ old('university', $user->university) }}"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                <x-input-error class="mt-2" :messages="$errors->get('university')" />
            </div>

            <div class="col-md-6">
                <label for="major"
                    class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('Course of Study') }}</label>
                <input id="major" name="major" type="text"
                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner"
                    value="{{ old('major', $user->major) }}"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                <x-input-error class="mt-2" :messages="$errors->get('major')" />
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label for="graduation_year"
                    class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('Graduation Year') }}</label>
                <input id="graduation_year" name="graduation_year" type="number"
                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner"
                    value="{{ old('graduation_year', $user->graduation_year) }}" min="2000" max="2100"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                <x-input-error class="mt-2" :messages="$errors->get('graduation_year')" />
            </div>

            <div class="col-md-6">
                <label for="phone"
                    class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('Pulse Line (Phone)') }}</label>
                <input id="phone" name="phone" type="text"
                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner"
                    value="{{ old('phone', $user->phone) }}"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
        </div>

        <div class="mb-4">
            <label for="github_username"
                class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('GitHub Handle') }}</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-0 text-secondary rounded-start-4"
                    style="background-color: rgba(255,255,255,0.05) !important;">github.com/</span>
                <input id="github_username" name="github_username" type="text"
                    class="form-control bg-dark border-0 rounded-end-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner"
                    value="{{ old('github_username', $user->github_username) }}" placeholder="Octocat"
                    style="background-color: rgba(255,255,255,0.05) !important;">
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('github_username')" />
        </div>

        <div class="mb-5">
            <label for="bio"
                class="form-label small fw-black text-secondary text-uppercase tracking-widest">{{ __('The Pivot (Bio)') }}</label>
            <textarea id="bio" name="bio"
                class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner"
                rows="4"
                style="background-color: rgba(255,255,255,0.05) !important;">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="d-flex align-items-center gap-4 py-3 border-top border-white border-opacity-5">
            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg">
                {{ __('Update Credentials') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="small text-success fw-black text-uppercase tracking-widest mb-0">
                    {{ __('Identity Synchronized.') }}</p>
            @endif
        </div>
    </form>
</section>