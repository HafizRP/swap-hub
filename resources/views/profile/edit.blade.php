@section('title', 'Profile Settings')
<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-center">
            <div class="w-full max-w-3xl flex flex-col gap-6">
 
                <!-- Section 1: Profile Information -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
 
                <!-- Section 2: Skills -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="max-w-xl">
                            @include('profile.partials.update-skills-form')
                        </div>
                    </div>
                </div>
 
                <!-- Section 3: Password -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
 
                <!-- Section 4: Danger Zone -->
                <div class="bg-red-500/10 rounded-2xl shadow-sm border border-red-500/25 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>