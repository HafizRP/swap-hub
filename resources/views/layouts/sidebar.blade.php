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
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none text-dark gap-2">
            <div class="bg-primary rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px;">
                <svg class="text-white" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
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
    </ul>

    <!-- Go Premium Card (Hidden when minimized on desktop) -->
    <div class="p-3 mt-auto" x-show="window.innerWidth < 768 || sidebarExpanded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        <div class="card bg-primary text-white border-0 shadow-lg rounded-4 overflow-hidden position-relative">
            <div class="card-body p-3 position-relative z-1">
                <h6 class="fw-bold mb-1">Go Premium</h6>
                <p class="small opacity-75 mb-3" style="font-size: 11px; line-height: 1.4;">Get unlimited project applications and priority listing.</p>
                <button class="btn btn-light btn-sm w-100 fw-bold text-primary border-0 rounded-pill">Upgrade Now</button>
            </div>
            <!-- Decorative Circles -->
            <div class="position-absolute top-0 end-0 rounded-circle bg-white opacity-10" style="width: 80px; height: 80px; margin-top: -20px; margin-right: -20px;"></div>
            <div class="position-absolute bottom-0 start-0 rounded-circle bg-white opacity-10" style="width: 50px; height: 50px; margin-bottom: -10px; margin-left: -10px;"></div>
        </div>
    </div>
    
    <!-- Copyright -->
    <div class="px-4 py-3 text-center" x-show="window.innerWidth < 768 || sidebarExpanded">
        <small class="text-secondary opacity-50" style="font-size: 10px;">&copy; 2024 Swap Hub</small>
    </div>
</div>