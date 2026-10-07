@section('title', 'Pengaturan Profil')
<x-app-layout>
    <div class="w-full">
        <!-- Header -->
        <div class="mb-6">
            <div class="mb-2">
                <a href="{{ route('profile.show', auth()->user()) }}" 
                   class="text-xs font-bold text-stone-500 dark:text-stone-400 hover:text-teal-600 dark:hover:text-teal-400 no-underline inline-flex items-center gap-1.5 transition-colors">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Halaman Profil</span>
                </a>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-stone-900 dark:text-stone-100 tracking-tight mb-1">
                Pengaturan Akun & Profil
            </h1>
            <p class="text-xs sm:text-sm text-stone-500 dark:text-stone-400 mb-0">
                Kelola informasi akun Anda, keahlian teknis, dan preferensi keamanan.
            </p>
        </div>

        <div class="flex justify-center">
            <div class="w-full max-w-3xl flex flex-col gap-4">
 
                <!-- Section 1: Profile Information -->
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                    <div class="p-4">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
 
                <!-- Section 2: Skills Matrix -->
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                    <div class="p-4">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-skills-form')
                        </div>
                    </div>
                </div>
 
                <!-- Section 3: Password / Security -->
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                    <div class="p-4">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
 
                <!-- Section 4: Danger Zone -->
                <div class="bg-red-50/50 dark:bg-red-950/20 rounded-xl shadow-card border border-red-200/80 dark:border-red-900/40 overflow-hidden">
                    <div class="p-4">
                        <div class="max-w-2xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>