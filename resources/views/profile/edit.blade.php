@section('title', 'Profile Settings')
<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 fw-black text-white mb-0">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">
                <div
                    class="card border-0 shadow-sm rounded-4 bg-dark bg-opacity-25 border border-white border-opacity-10 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <div
                    class="card border-0 shadow-sm rounded-4 bg-dark bg-opacity-25 border border-white border-opacity-10 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="max-w-xl">
                            @include('profile.partials.update-skills-form')
                        </div>
                    </div>
                </div>

                <div
                    class="card border-0 shadow-sm rounded-4 bg-dark bg-opacity-25 border border-white border-opacity-10 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div
                    class="card border-0 shadow-sm rounded-4 bg-danger bg-opacity-10 border border-danger border-opacity-25 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>