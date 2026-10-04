<section>
    <header class="mb-6">
        <h3 class="text-base font-black text-stone-900 dark:text-stone-100 flex items-center gap-2 mb-0">
            <i class="bi bi-person-badge text-teal-600 dark:text-teal-400"></i>
            <span>Informasi Profil & Akademik</span>
        </h3>
        <p class="text-xs text-stone-500 dark:text-stone-400 mt-1 mb-0">
            Kelola identitas mahasiswa, program studi, dan informasi kontak Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <!-- Full Name -->
        <div>
            <label for="name" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input id="name" name="name" type="text"
                class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">
                Alamat Email <span class="text-red-500">*</span>
            </label>
            <input id="email" name="email" type="email"
                class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-3 p-3.5 border border-amber-200 dark:border-amber-800/60 bg-amber-50 dark:bg-amber-950/30 rounded-xl">
                    <p class="text-xs text-amber-700 dark:text-amber-300 font-semibold mb-2">
                        Alamat email Anda belum diverifikasi.
                    </p>
                    <button form="send-verification"
                        class="text-xs text-amber-800 dark:text-amber-200 hover:underline font-bold bg-transparent border-0 p-0 cursor-pointer">
                        Kirim Ulang Email Verifikasi
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs text-emerald-600 dark:text-emerald-400 font-bold mb-0">
                            Tautan verifikasi baru telah dikirim ke email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- University & Major -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="university" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Universitas / Kampus</label>
                <input id="university" name="university" type="text"
                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                    value="{{ old('university', $user->university) }}" placeholder="Contoh: Universitas Indonesia">
                <x-input-error class="mt-1.5" :messages="$errors->get('university')" />
            </div>

            <div>
                <label for="major" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Program Studi / Jurusan</label>
                <input id="major" name="major" type="text"
                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                    value="{{ old('major', $user->major) }}" placeholder="Contoh: Teknik Informatika">
                <x-input-error class="mt-1.5" :messages="$errors->get('major')" />
            </div>
        </div>

        <!-- Graduation Year & Phone -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="graduation_year" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Tahun Kelulusan</label>
                <input id="graduation_year" name="graduation_year" type="number"
                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                    value="{{ old('graduation_year', $user->graduation_year) }}" min="2000" max="2100" placeholder="2026">
                <x-input-error class="mt-1.5" :messages="$errors->get('graduation_year')" />
            </div>

            <div>
                <label for="phone" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Nomor Telepon / WA</label>
                <input id="phone" name="phone" type="text"
                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                    value="{{ old('phone', $user->phone) }}" placeholder="+62 812-xxxx-xxxx">
                <x-input-error class="mt-1.5" :messages="$errors->get('phone')" />
            </div>
        </div>

        <!-- GitHub Handle -->
        <div>
            <label for="github_username" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Username GitHub</label>
            <div class="flex rounded-xl overflow-hidden border border-stone-200 dark:border-stone-700 focus-within:ring-2 focus-within:ring-teal-500/20 focus-within:border-teal-600 transition-all">
                <span class="inline-flex items-center px-3.5 bg-stone-100 dark:bg-stone-800 text-stone-400 text-xs font-mono">github.com/</span>
                <input id="github_username" name="github_username" type="text"
                    class="flex-1 w-full bg-stone-50 dark:bg-[#1a1917] border-0 px-3.5 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none"
                    value="{{ old('github_username', $user->github_username) }}" placeholder="octocat">
            </div>
            <x-input-error class="mt-1.5" :messages="$errors->get('github_username')" />
        </div>

        <!-- Bio -->
        <div>
            <label for="bio" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Biografi Singkat</label>
            <textarea id="bio" name="bio"
                class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-3 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                rows="3" placeholder="Ceritakan latar belakang, ketertarikan teknologi, atau tujuan kolaborasi Anda...">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-1.5" :messages="$errors->get('bio')" />
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-4 pt-4 border-t border-stone-100 dark:border-stone-800">
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2.5 px-5 rounded-lg text-xs shadow-sm transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs text-emerald-600 dark:text-emerald-400 font-bold mb-0">
                    <i class="bi bi-check-circle-fill mr-1"></i>
                    Profil berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>
