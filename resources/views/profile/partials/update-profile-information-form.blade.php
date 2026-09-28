<section>
    <header class="mb-6">
        <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
            <i class="bi bi-person-badge-fill text-brand-600"></i>
            <span>Informasi Pribadi & Akademik</span>
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Perbarui data diri, universitas, jurusan, dan kontak Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input id="name" name="name" type="text"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none"
                       value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                    Email Kampus / Utama <span class="text-rose-500">*</span>
                </label>
                <input id="email" name="email" type="email"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none"
                       value="{{ old('email', $user->email) }}" required autocomplete="username">
                <x-input-error class="mt-1" :messages="$errors->get('email')" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 text-xs">
                <p class="text-amber-800 dark:text-amber-300 font-bold mb-1">
                    Alamat email Anda belum diverifikasi.
                </p>
                <button form="send-verification" class="text-brand-600 dark:text-brand-400 underline font-bold">
                    Kirim ulang email verifikasi
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-emerald-600 dark:text-emerald-400 font-bold">
                        Tautan verifikasi baru telah dikirim ke alamat email Anda.
                    </p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="university" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Universitas / Perguruan Tinggi</label>
                <input id="university" name="university" type="text"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none"
                       value="{{ old('university', $user->university) }}" placeholder="Contoh: Universitas Indonesia">
                <x-input-error class="mt-1" :messages="$errors->get('university')" />
            </div>

            <div>
                <label for="major" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jurusan / Program Studi</label>
                <input id="major" name="major" type="text"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none"
                       value="{{ old('major', $user->major) }}" placeholder="Contoh: Ilmu Komputer">
                <x-input-error class="mt-1" :messages="$errors->get('major')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="graduation_year" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tahun Angkatan / Kelulusan</label>
                <input id="graduation_year" name="graduation_year" type="number"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none"
                       value="{{ old('graduation_year', $user->graduation_year) }}" min="2000" max="2100">
                <x-input-error class="mt-1" :messages="$errors->get('graduation_year')" />
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp / Kontak</label>
                <input id="phone" name="phone" type="text"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none"
                       value="{{ old('phone', $user->phone) }}" placeholder="+62 812-xxxx-xxxx">
                <x-input-error class="mt-1" :messages="$errors->get('phone')" />
            </div>
        </div>

        <div>
            <label for="github_username" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Username GitHub</label>
            <div class="flex rounded-xl overflow-hidden border border-slate-300 dark:border-slate-700">
                <span class="inline-flex items-center px-3 bg-slate-100 dark:bg-slate-800 border-r border-slate-300 dark:border-slate-700 text-slate-500 text-xs font-bold">
                    github.com/
                </span>
                <input id="github_username" name="github_username" type="text"
                       class="flex-1 px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm outline-none"
                       value="{{ old('github_username', $user->github_username) }}" placeholder="username">
            </div>
            <x-input-error class="mt-1" :messages="$errors->get('github_username')" />
        </div>

        <div>
            <label for="bio" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Bio / Ringkasan Diri</label>
            <textarea id="bio" name="bio" rows="4"
                      class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none leading-relaxed"
                      placeholder="Ceritakan minat, keahlian, dan tujuan Anda...">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-1" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-3 pt-4">
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                Simpan Profil
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                   class="text-xs text-emerald-600 dark:text-emerald-400 font-bold">
                    Profil berhasil diperbarui!
                </p>
            @endif
        </div>
    </form>
</section>
