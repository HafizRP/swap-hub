@section('title', 'User Management')

<x-app-layout>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-4">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-1 fw-bold">
                        <i class="bi bi-people-fill me-2 text-primary"></i>All Users
                    </h5>
                    <small class="text-secondary">Manage and monitor all registered users</small>
                </div>
                <div class="col-auto">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control border-0 bg-light" placeholder="Search users...">
                        </div>
                        <select name="role" class="form-select border-0 bg-light" style="width: 150px;"
                            onchange="this.form.submit()">
                            <option value="all">All Roles</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">User</th>
                            <th class="py-3">Contact</th>
                            <th class="py-3">University</th>
                            <th class="py-3">Role</th>
                            <th class="py-3">Reputation</th>
                            <th class="py-3">Joined</th>
                            <th class="pe-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                            class="rounded-circle me-3" width="48" height="48">
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            <small class="text-secondary">{{ $user->major ?? 'No major' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <small class="text-secondary"><i
                                                class="bi bi-telephone me-1"></i>{{ $user->phone }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-block">{{ $user->university ?? 'N/A' }}</span>
                                    <small class="text-secondary">{{ $user->student_id ?? '' }}</small>
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $user->role && $user->role->slug === 'admin' ? 'bg-danger' : 'bg-primary' }} rounded-pill px-3">
                                        {{ $user->role->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning me-1"></i>
                                        <span class="fw-bold">{{ $user->reputation_points }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">{{ $user->created_at->format('M d, Y') }}</div>
                                    <small class="text-secondary">{{ $user->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary"
                                            title="View Details">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-info"
                                            title="Edit User">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button type="button"
                                                class="btn btn-outline-{{ $user->role === 'admin' ? 'warning' : 'success' }}"
                                                onclick="confirmRoleChange('{{ route('admin.users.toggle-role', $user) }}', '{{ $user->role === 'admin' ? 'Demote' : 'Promote' }}', '{{ $user->name }}')"
                                                title="{{ $user->role === 'admin' ? 'Demote to User' : 'Promote to Admin' }}">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>

                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?')"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete User">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-outline-secondary" disabled title="Cannot modify yourself">
                                                <i class="bi bi-lock-fill"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/search-result-not-found-2130361-1800925.png"
                                        alt="No users found" width="150" class="mb-3 opacity-75">
                                    <p class="text-secondary">No users found matching your criteria</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Role Change Confirmation Modal -->
    <div class="modal fade" id="roleChangeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form id="roleChangeForm" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Confirm Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <p class="text-secondary mb-3">
                            Are you sure you want to <strong id="actionText"></strong> user <strong
                                id="userName"></strong>?
                            <br>Please enter your password to confirm this action.
                        </p>
                        <div class="form-group">
                            <label class="form-label small text-uppercase text-secondary fw-bold">Your Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key"></i></span>
                                <input type="password" name="admin_password" class="form-control border-start-0 ps-0"
                                    required autofocus>
                            </div>
                            @error('admin_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmRoleChange(url, action, name) {
            document.getElementById('roleChangeForm').action = url;
            document.getElementById('actionText').textContent = action.toLowerCase();
            document.getElementById('userName').textContent = name;

            var modal = new bootstrap.Modal(document.getElementById('roleChangeModal'));
            modal.show();
        }
    </script>
</x-app-layout>