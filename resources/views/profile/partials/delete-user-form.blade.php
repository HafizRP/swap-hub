<section>
    <header class="mb-4">
        <h4 class="fw-black text-danger">
            {{ __('Terminal Phase (Delete Account)') }}
        </h4>

        <p class="small text-secondary fw-bold text-uppercase tracking-widest opacity-50">
            {{ __('Once your account is purged, all resources and data will be permanently erased from the matrix.') }}
        </p>
    </header>

    <button class="btn btn-outline-danger rounded-pill px-4 py-2 fw-black small text-uppercase tracking-widest"
            data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        {{ __('Terminate Identity') }}
    </button>

    <!-- Modal -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border border-white border-opacity-10 rounded-5 shadow-2xl">
                <form method="post" action="{{ route('profile.destroy') }}" class="p-4 p-md-5">
                    @csrf
                    @method('delete')

                    <h4 class="fw-black text-white mb-3" id="confirmUserDeletionLabel">
                        {{ __('Confirm Identity Deletion?') }}
                    </h4>

                    <p class="small text-secondary fw-bold mb-4 opacity-75">
                        {{ __('This action is irreversible. Please enter your authentication key (password) to finalize the purging of your student profile from the ecosystem.') }}
                    </p>

                    <div class="mb-5">
                        <label for="password" class="form-label small fw-black text-secondary text-uppercase tracking-widest opacity-50">{{ __('Access Key (Password)') }}</label>
                        <input id="password" name="password" type="password" class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-danger shadow-inner" 
                               placeholder="{{ __('Enter password...') }}" style="background-color: rgba(255,255,255,0.05) !important;">
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="button" class="btn btn-link text-secondary fw-black text-decoration-none text-uppercase tracking-widest small opacity-50 hover-white" data-bs-dismiss="modal">
                            {{ __('Abort') }}
                        </button>

                        <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-black text-uppercase tracking-widest small">
                            {{ __('Purge Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    .rounded-5 { border-radius: 2rem !important; }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
    .hover-white:hover { color: white !important; opacity: 1 !important; }
</style>
