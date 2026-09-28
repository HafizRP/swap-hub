@section('title', 'Pengaturan Profil')
<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <a href="{{ route('profile.show', auth()->user()) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-600 mb-1">
                    <i class="bi bi-arrow-left"></i> Lihat Profil Publik
                </a>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Pengaturan Akun & Profil
                </h1>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300">
                Data Pribadi
            </span>
        </div>

        <div class="space-y-6">
            <!-- Section 1: Profile Information -->
            <div class="p-6 sm:p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Section 2: Skills Management -->
            <div class="p-6 sm:p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
                @include('profile.partials.update-skills-form')
            </div>

            <!-- Section 3: Password -->
            <div class="p-6 sm:p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            <!-- Section 4: Danger Zone -->
            <div class="p-6 sm:p-8 rounded-2xl bg-rose-50/50 dark:bg-rose-950/20 border border-rose-200/80 dark:border-rose-900/50 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</x-app-layout>
