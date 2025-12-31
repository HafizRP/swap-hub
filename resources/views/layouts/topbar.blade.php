<nav class="navbar navbar-expand bg-white border-bottom py-2 sticky-top px-4" style="height: 74px;">
    <div class="container-fluid p-0">
        <!-- Mobile Toggle -->
        <button @click="toggleSidebar()" class="btn btn-icon btn-light me-3 d-md-none text-secondary border-0">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Search -->
        <div class="d-none d-md-block flex-grow-1 me-4" style="max-width: 400px;">
            <div class="input-group bg-light rounded-pill border border-light">
                <span class="input-group-text bg-transparent border-0 text-secondary ps-3"><i
                        class="bi bi-search"></i></span>
                <input type="text" class="form-control bg-transparent border-0 shadow-none"
                    placeholder="Search for projects or students..." style="font-size: 0.9rem;">
            </div>
        </div>

        <!-- Right Side -->
        <ul class="navbar-nav ms-auto align-items-center gap-1">
            <li class="nav-item me-2">
                <button @click="toggleTheme()" class="btn btn-icon btn-light rounded-circle text-secondary border-0">
                    <i class="bi" :class="darkMode ? 'bi-sun-fill' : 'bi-moon-stars-fill'"></i>
                </button>
            </li>
            <li class="nav-item me-2">
                <a class="btn btn-icon btn-light rounded-circle text-secondary position-relative" href="#">
                    <i class="bi bi-bell-fill"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </a>
            </li>
            <li class="nav-item me-3">
                <a class="btn btn-icon btn-light rounded-circle text-secondary" href="{{ route('chat') }}">
                    <i class="bi bi-chat-left-text-fill"></i>
                </a>
            </li>

            <li class="nav-item border-start ps-3 ms-1">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-md-block" style="line-height: 1.2;">
                        <span class="d-block fw-bold text-dark"
                            style="font-size: 0.9rem;">{{ auth()->user()->name }}</span>
                        <span class="d-block text-secondary"
                            style="font-size: 0.75rem;">{{ auth()->user()->major ?? 'Computer Science' }}</span>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                width="40" height="40" class="rounded-circle border border-2 border-white shadow-sm">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 mt-2">
                            <li>
                                <h6 class="dropdown-header">Manage Account</h6>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('profile.show', auth()->id()) }}"><i class="bi bi-person"></i>
                                    Profile</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('profile.edit') }}"><i class="bi bi-gear"></i> Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item text-danger d-flex align-items-center gap-2"><i
                                            class="bi bi-box-arrow-right"></i> Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>