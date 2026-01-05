<x-app-layout>
@section('title', 'Edit User')

<section>
    <header class="mb-4">
        <h4 class="fw-black">
            <i class="bi bi-pencil-fill me-2 text-primary"></i>Edit User Profile
        </h4>
        <p class="small text-secondary fw-bold text-uppercase tracking-widest opacity-50">
            Manage user account information and permissions
        </p>
    </header>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="form-label small fw-black text-secondary text-uppercase tracking-widest">Full Name</label>
            <input id="name" name="name" type="text"
                class="form-control bg-dark border-0 rounded-4 px-4 py-3 focus:ring-2 focus:ring-primary shadow-inner"
                value="{{ old('name', $user->name) }}" required autofocus
                style="background-color: rgba(255,255,255,0.05) !important;">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-4">
            <label for="email" class="form-label small fw-black text-secondary text-uppercase tracking-widest">Email Address</label>
            <input id="email" name="email" type="email"
                class="form-control bg-dark border-0 rounded-4 px-4 py-3 focus:ring-2 focus:ring-primary shadow-inner"
                value="{{ old('email', $user->email) }}" required
                style="background-color: rgba(255,255,255,0.05) !important;">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label for="university" class="form-label small fw-black text-secondary text-uppercase tracking-widest">University</label>
                <input id="university" name="university" type="text"
                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 focus:ring-2 focus:ring-primary shadow-inner"
                    value="{{ old('university', $user->university) }}"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                <x-input-error class="mt-2" :messages="$errors->get('university')" />
            </div>

            <div class="col-md-6">
                <label for="major" class="form-label small fw-black text-secondary text-uppercase tracking-widest">Course of Study</label>
                <input id="major" name="major" type="text"
                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 focus:ring-2 focus:ring-primary shadow-inner"
                    value="{{ old('major', $user->major) }}"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                <x-input-error class="mt-2" :messages="$errors->get('major')" />
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label for="graduation_year" class="form-label small fw-black text-secondary text-uppercase tracking-widest">Graduation Year</label>
                <input id="graduation_year" name="graduation_year" type="number"
                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 focus:ring-2 focus:ring-primary shadow-inner"
                    value="{{ old('graduation_year', $user->graduation_year) }}" min="2000" max="2100"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                <x-input-error class="mt-2" :messages="$errors->get('graduation_year')" />
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label small fw-black text-secondary text-uppercase tracking-widest">Phone Number</label>
                <input id="phone" name="phone" type="text"
                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 focus:ring-2 focus:ring-primary shadow-inner"
                    value="{{ old('phone', $user->phone) }}"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label for="role" class="form-label small fw-black text-secondary text-uppercase tracking-widest">User Role</label>
                <select id="role" name="role" class="form-select bg-dark border-0 rounded-4 px-4 py-3 focus:ring-2 focus:ring-primary shadow-inner"
                    style="background-color: rgba(255,255,255,0.05) !important;"
                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @if($user->id === auth()->id())
                    <small class="text-warning d-block mt-2">⚠️ You cannot change your own role</small>
                @endif
                <x-input-error class="mt-2" :messages="$errors->get('role')" />
            </div>

            <div class="col-md-6">
                <label for="reputation_points" class="form-label small fw-black text-secondary text-uppercase tracking-widest">Reputation Points</label>
                <input id="reputation_points" name="reputation_points" type="number"
                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 focus:ring-2 focus:ring-primary shadow-inner"
                    value="{{ old('reputation_points', $user->reputation_points) }}"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                <x-input-error class="mt-2" :messages="$errors->get('reputation_points')" />
            </div>
        </div>

        <div class="mb-5">
            <label for="bio" class="form-label small fw-black text-secondary text-uppercase tracking-widest">User Bio</label>
            <textarea id="bio" name="bio"
                class="form-control bg-dark border-0 rounded-4 px-4 py-3 focus:ring-2 focus:ring-primary shadow-inner"
                rows="4"
                style="background-color: rgba(255,255,255,0.05) !important;">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="d-flex align-items-center gap-4 py-3 border-top border-white border-opacity-5">
            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg">
                <i class="bi bi-check-circle-fill me-2"></i>Update User
            </button>
            
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary rounded-pill px-5 py-3 fw-black">
                <i class="bi bi-x-circle me-2"></i>Cancel
            </a>

            @if (session('status') === 'user-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="small text-success fw-black text-uppercase tracking-widest mb-0">
                    User Updated Successfully.
                </p>
            @endif
        </div>
    </form>
</section>
</x-app-layout>