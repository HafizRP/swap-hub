<style>
    @media (max-width: 767.98px) {
        .sidebar-responsive {
            position: fixed !important;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1051; /* Above overlay */
            width: 280px !important;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-responsive.mobile-open {
            transform: translateX(0);
        }
    }
    @media (min-width: 768px) {
        .sidebar-responsive {
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 1040;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    }
    .nav-link.active {
        background-color: var(--bs-primary) !important;
        color: white !important;
    }
    .nav-link.active i {
        color: white !important;
    }
</style>

<div class="d-flex flex-column flex-shrink-0 bg-white border-end sidebar-responsive" 
     :class="{ 'mobile-open': sidebarOpenMobile }"
     :style="(window.innerWidth >= 768) ? (sidebarExpanded ? 'width: 260px' : 'width: 80px') : ''"
     style="overflow-x: hidden;">
    
    <!-- Logo & Toggle -->
    <div class="d-flex align-items-center p-4 mb-2" :class="sidebarExpanded ? 'justify-content-between' : 'justify-content-center flex-column gap-3'">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none text-dark gap-2"
           x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
           @theme-changed.window="darkMode = localStorage.getItem('theme') === 'dark'">
            <img :src="darkMode ? '{{ asset('icon-dark.png') }}' : '{{ asset('icon.png') }}'" 
                 alt="Swap Hub" 
                 style="width: 32px; height: 32px;"
                 class="object-fit-contain flex-shrink-0">
            <span class="fs-5 fw-bold text-nowrap" x-show="window.innerWidth < 768 || sidebarExpanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Swap Hub</span>
        </a>
        
        <!-- Desktop Toggle -->
        <button @click="toggleSidebar()" class="btn btn-sm btn-icon text-secondary p-0 border-0 d-none d-md-block" :title="sidebarExpanded ? 'Minimize' : 'Expand'">
            <i class="bi" :class="sidebarExpanded ? 'bi-chevron-left' : 'bi-list fs-5'"></i>
        </button>

        <!-- Mobile Close -->
        <button @click="sidebarOpenMobile = false" class="btn btn-sm btn-icon text-secondary p-0 border-0 d-md-none">
            <i class="bi bi-x-lg fs-5"></i>
        </button>
    </div>
    
    <!-- User Info -->
    <div class="px-3 mb-4 transition-all">
        <div class="d-flex align-items-center" :class="(window.innerWidth < 768 || sidebarExpanded) ? 'gap-3 p-2 bg-light rounded-3' : 'justify-content-center'">
            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random' }}" 
                 class="rounded-circle flex-shrink-0" width="40" height="40" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ auth()->user()->name }}">
            
            <div style="line-height: 1.2; min-width: 0;" x-show="window.innerWidth < 768 || sidebarExpanded" x-transition>
                <h6 class="fw-bold mb-0 text-dark small text-truncate">{{ auth()->user()->name }}</h6>
                <small class="text-secondary d-block text-truncate" style="font-size: 11px;">{{ auth()->user()->university ?? 'Student' }}</small>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <ul class="nav nav-pills flex-column mb-auto px-3 gap-2">
        @foreach([
            ['route' => 'dashboard', 'icon' => 'bi-grid-fill', 'label' => 'Dashboard'],
            ['route' => 'projects.index', 'icon' => 'bi-search', 'label' => 'Cari Proyek'],
            ['route' => 'profile.show', 'icon' => 'bi-person-fill', 'label' => 'Profil Saya', 'params' => auth()->id()],
            ['route' => 'chat', 'icon' => 'bi-chat-dots-fill', 'label' => 'Workspace'],
        ] as $item)
            @php
                $isActive = request()->routeIs($item['route'] . '*');
                $url = $item['route'] === '#' ? '#' : route($item['route'], $item['params'] ?? []);
            @endphp
            <li class="nav-item">
                <a href="{{ $url }}" 
                   class="nav-link d-flex align-items-center text-nowrap {{ $isActive ? 'active' : 'text-secondary' }}" 
                   :class="(window.innerWidth < 768 || sidebarExpanded) ? 'gap-3 px-3' : 'justify-content-center px-0 py-2'"
                   :title="(!sidebarExpanded && window.innerWidth >= 768) ? '{{ $item['label'] }}' : ''"
                   @if(!$isActive) style="color: #64748b;" @endif>
                    <i class="bi {{ $item['icon'] }} fs-5 flex-shrink-0"></i>
                    <span x-show="window.innerWidth < 768 || sidebarExpanded" x-transition>{{ $item['label'] }}</span>
                </a>
            </li>
        @endforeach

        <li class="mt-2">
             <div class="border-top my-2 mx-2"></div>
        </li>

        <li class="nav-item">
            <a href="{{ route('profile.edit') }}" 
               class="nav-link d-flex align-items-center text-nowrap {{ request()->routeIs('profile.edit') ? 'active' : 'text-secondary' }}"
               :class="(window.innerWidth < 768 || sidebarExpanded) ? 'gap-3 px-3' : 'justify-content-center px-0 py-2'"
               :title="(!sidebarExpanded && window.innerWidth >= 768) ? 'Pengaturan' : ''">
                <i class="bi bi-gear-fill fs-5 flex-shrink-0"></i>
                <span x-show="window.innerWidth < 768 || sidebarExpanded" x-transition>Pengaturan</span>
            </a>
        </li>

        @if(auth()->user()->isAdmin())
            <li class="mt-2">
                <div class="border-top my-2 mx-2"></div>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-link d-flex align-items-center text-nowrap {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-secondary' }}"
                   :class="(window.innerWidth < 768 || sidebarExpanded) ? 'gap-3 px-3' : 'justify-content-center px-0 py-2'"
                   :title="(!sidebarExpanded && window.innerWidth >= 768) ? 'Admin Dashboard' : ''">
                    <i class="bi bi-speedometer2 fs-5 flex-shrink-0"></i>
                    <span x-show="window.innerWidth < 768 || sidebarExpanded" x-transition>Admin Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" 
                   class="nav-link d-flex align-items-center text-nowrap {{ request()->routeIs('admin.users.*') ? 'active' : 'text-secondary' }}"
                   :class="(window.innerWidth < 768 || sidebarExpanded) ? 'gap-3 px-3' : 'justify-content-center px-0 py-2'"
                   :title="(!sidebarExpanded && window.innerWidth >= 768) ? 'User Management' : ''">
                    <i class="bi bi-people-fill fs-5 flex-shrink-0"></i>
                    <span x-show="window.innerWidth < 768 || sidebarExpanded" x-transition>User Management</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.projects.index') }}" 
                   class="nav-link d-flex align-items-center text-nowrap {{ request()->routeIs('admin.projects.*') ? 'active' : 'text-secondary' }}"
                   :class="(window.innerWidth < 768 || sidebarExpanded) ? 'gap-3 px-3' : 'justify-content-center px-0 py-2'"
                   :title="(!sidebarExpanded && window.innerWidth >= 768) ? 'Project Management' : ''">
                    <i class="bi bi-folder-fill fs-5 flex-shrink-0"></i>
                    <span x-show="window.innerWidth < 768 || sidebarExpanded" x-transition>Project Management</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.health.index') }}" 
                   class="nav-link d-flex align-items-center text-nowrap {{ request()->routeIs('admin.health.*') ? 'active' : 'text-secondary' }}"
                   :class="(window.innerWidth < 768 || sidebarExpanded) ? 'gap-3 px-3' : 'justify-content-center px-0 py-2'"
                   :title="(!sidebarExpanded && window.innerWidth >= 768) ? 'System Health' : ''">
                    <i class="bi bi-heart-pulse-fill fs-5 flex-shrink-0"></i>
                    <span x-show="window.innerWidth < 768 || sidebarExpanded" x-transition>System Health</span>
                </a>
            </li>
        @endif
    </ul>
    
    <!-- Copyright -->
    <div class="px-4 py-3 text-center" x-show="window.innerWidth < 768 || sidebarExpanded">
        <small class="text-secondary opacity-50" style="font-size: 10px;">&copy; 2024 Swap Hub</small>
    </div>
</div>