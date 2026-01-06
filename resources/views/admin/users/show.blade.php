<x-app-layout>
    @section('title', 'User Details')

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                        class="rounded-circle mb-3" width="120" height="120">
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-secondary mb-3">{{ $user->email }}</p>
                    <span
                        class="badge {{ $user->role && $user->role->slug === 'admin' ? 'bg-danger' : 'bg-secondary' }} px-3 py-2">
                        <i
                            class="bi {{ $user->role && $user->role->slug === 'admin' ? 'bi-shield-fill' : 'bi-person-fill' }} me-1"></i>
                        {{ $user->role->name ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle-fill me-2 text-primary"></i>User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-secondary small">University</label>
                            <p class="fw-semibold">{{ $user->university ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary small">Major</label>
                            <p class="fw-semibold">{{ $user->major ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary small">Phone</label>
                            <p class="fw-semibold">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary small">Graduation Year</label>
                            <p class="fw-semibold">{{ $user->graduation_year ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary small">Reputation Points</label>
                            <p class="fw-semibold"><i
                                    class="bi bi-star-fill text-warning me-1"></i>{{ number_format($user->reputation_points) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary small">Joined</label>
                            <p class="fw-semibold">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-secondary small">Bio</label>
                            <p class="fw-semibold">{{ $user->bio ?? 'No bio provided' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to List
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-fill me-1"></i>Edit User
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>