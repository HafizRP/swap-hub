<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <div class="bg-primary rounded-3 p-1 d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px;">
                <svg class="text-white" style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <span class="fw-black tracking-tighter">SWAP HUB</span>
        </a>

        <!-- Hamburger -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold text-white' : '' }}" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('projects.*') ? 'active fw-bold text-white' : '' }}" href="{{ route('projects.index') }}">
                        Projects
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('skill-swaps.*') ? 'active fw-bold text-white' : '' }}" href="{{ route('skill-swaps.index') }}">
                        Marketplace
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('chat.*') ? 'active fw-bold text-white' : '' }}" href="{{ route('chat.index') }}">
                        Chat
                    </a>
                </li>
            </ul>

            <!-- Settings Dropdown -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background-color: rgba(255, 255, 255, 0.05) !important;" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4f46e5&color=fff' }}" class="rounded-circle" width="24" height="24">
                        <span class="small fw-bold">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 py-2" style="background-color: #1e293b;" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item py-2 px-4" href="{{ route('profile.show', Auth::user()) }}">
                                <span class="small">Public Portfolio</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-4" href="{{ route('profile.edit') }}">
                                <span class="small">Settings</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider opacity-10"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 px-4 text-danger">
                                    <span class="small fw-bold">Log Out</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
