@section('title', 'Pengaturan Profil')
<x-app-layout>
    <div class="container mx-auto py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="mb-2">
                <a href="{{ route('profile.show', auth()->user()) }}" 
                   class="text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 no-underline inline-flex items-center gap-1.5 transition-colors">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Halaman Profil</span>
                </a>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-slate-100 tracking-tight mb-1">
                Pengaturan Akun & Profil
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-0">
                Kelola informasi akun Anda, keahlian teknis, dan preferensi keamanan.
            </p>
        </div>

        <div class="flex justify-center">
            <div class="w-full max-w-3xl flex flex-col gap-6">
 
                <!-- Section 1: Profile Information -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-card border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
 
                <!-- Section 2: Skills Matrix -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-card border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-skills-form')
                        </div>
                    </div>
                </div>
 
                <!-- Section 3: Password / Security -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-card border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
 
                <!-- Section 4: Danger Zone -->
                <div class="bg-red-50/50 dark:bg-red-950/20 rounded-3xl shadow-card border border-red-200/80 dark:border-red-900/40 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="max-w-2xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>