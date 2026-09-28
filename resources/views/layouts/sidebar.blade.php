{{-- Desktop sidebar — hidden on mobile via .sidebar-desktop CSS class --}}
<aside class="sidebar-desktop bg-white dark:bg-slate-900 border-r border-slate-200/80 dark:border-slate-800 h-screen sticky top-0 overflow-hidden transition-[width] duration-300 ease-in-out z-30"
       :style="sidebarExpanded ? 'width:260px' : 'width:76px'">

    <!-- Logo & Collapse/Expand toggle -->
    <div class="flex items-center p-4 mb-2 shrink-0 min-h-[64px] border-b border-slate-100 dark:border-slate-800/80 justify-between">
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2.5 no-underline text-slate-900 dark:text-white flex-1 min-w-0 overflow-hidden group">
            <img src="{{ asset('icon.png') }}"
                 alt="Swap Hub"
                 class="w-8 h-8 rounded-lg shrink-0 object-contain shadow-sm group-hover:scale-105 transition-transform">
            <span class="text-base font-extrabold tracking-tight whitespace-nowrap overflow-hidden transition-all duration-200"
                  :style="sidebarExpanded ? 'opacity:1;max-width:150px' : 'opacity:0;max-width:0'">
                Swap<span class="text-brand-500">Hub</span>
            </span>
        </a>

        <button @click="toggleSidebar()"
                class="shrink-0 p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                :title="sidebarExpanded ? 'Ciutkan Sidebar' : 'Lebarkan Sidebar'">
            <i class="bi text-sm transition-transform duration-300"
               :class="sidebarExpanded ? 'bi-chevron-left' : 'bi-list'"></i>
        </button>
    </div>

    <!-- User Profile Strip -->
    <div class="px-3 mb-3 shrink-0">
        <div class="flex items-center rounded-xl transition-all duration-200 overflow-hidden border border-slate-100 dark:border-slate-800/60"
             :class="sidebarExpanded ? 'gap-3 p-2.5 bg-slate-50 dark:bg-slate-800/60' : 'justify-center p-1.5 bg-transparent'">
            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff' }}"
                 class="rounded-lg shrink-0 object-cover w-9 h-9 shadow-sm"
                 :title="sidebarExpanded ? '' : '{{ auth()->user()->name }}'">
            <div class="min-w-0 overflow-hidden transition-all duration-200"
                 :style="sidebarExpanded ? 'opacity:1;max-width:160px' : 'opacity:0;max-width:0'">
                <p class="font-bold text-slate-900 dark:text-white text-xs truncate mb-0">{{ auth()->user()->name }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <p class="text-slate-500 dark:text-slate-400 text-[11px] truncate mb-0">{{ auth()->user()->university ?? 'Student' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col flex-1 px-3 gap-1 overflow-y-auto overflow-x-hidden pb-4 custom-scrollbar">

        <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2.5 pt-2 pb-1"
             :class="sidebarExpanded ? 'block' : 'hidden'">
            Menu Utama
        </div>

        @foreach([
            ['route' => 'dashboard',      'icon' => 'bi-grid-fill',       'label' => 'Dashboard'],
            ['route' => 'projects.index', 'icon' => 'bi-compass-fill',    'label' => 'Cari Proyek'],
            ['route' => 'profile.show',   'icon' => 'bi-person-badge-fill','label' => 'Profil & Portofolio', 'params' => auth()->id()],
            ['route' => 'chat',           'icon' => 'bi-chat-dots-fill',   'label' => 'Workspace & Chat'],
        ] as $item)
            @php
                $isActive = request()->routeIs($item['route'] . '*');
                $url = route($item['route'], $item['params'] ?? []);
            @endphp
            <a href="{{ $url }}"
               class="flex items-center py-2.5 rounded-xl transition-all text-xs font-semibold shrink-0 {{ $isActive ? 'bg-brand-600 text-white shadow-sm shadow-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}"
               :class="sidebarExpanded ? 'gap-3 px-3' : 'justify-center px-0'"
               :title="sidebarExpanded ? '' : '{{ $item['label'] }}'">
                <i class="bi {{ $item['icon'] }} text-base shrink-0"></i>
                <span class="whitespace-nowrap overflow-hidden transition-all duration-200"
                      :style="sidebarExpanded ? 'opacity:1;max-width:180px' : 'opacity:0;max-width:0'">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach

        <div class="border-t border-slate-100 dark:border-slate-800 my-2 mx-1 shrink-0"></div>

        <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2.5 pt-1 pb-1"
             :class="sidebarExpanded ? 'block' : 'hidden'">
            Pengaturan
        </div>

        @php $settingsActive = request()->routeIs('profile.edit'); @endphp
        <a href="{{ route('profile.edit') }}"
           class="flex items-center py-2.5 rounded-xl transition-all text-xs font-semibold shrink-0 {{ $settingsActive ? 'bg-brand-600 text-white shadow-sm shadow-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}"
           :class="sidebarExpanded ? 'gap-3 px-3' : 'justify-center px-0'"
           :title="sidebarExpanded ? '' : 'Pengaturan Akun'">
            <i class="bi bi-gear-fill text-base shrink-0"></i>
            <span class="whitespace-nowrap overflow-hidden transition-all duration-200"
                  :style="sidebarExpanded ? 'opacity:1;max-width:180px' : 'opacity:0;max-width:0'">
                Pengaturan Akun
            </span>
        </a>

        @if(auth()->user()->isAdmin())
            <div class="border-t border-slate-100 dark:border-slate-800 my-2 mx-1 shrink-0"></div>

            <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2.5 pt-1 pb-1"
                 :class="sidebarExpanded ? 'block' : 'hidden'">
                Admin Panel
            </div>

            @foreach([
                ['route' => 'admin.dashboard',      'icon' => 'bi-speedometer2',    'label' => 'Admin Overview'],
                ['route' => 'admin.users.index',    'icon' => 'bi-people-fill',     'label' => 'Kelola User'],
                ['route' => 'admin.projects.index', 'icon' => 'bi-folder-fill',     'label' => 'Kelola Proyek'],
                ['route' => 'admin.health.index',   'icon' => 'bi-heart-pulse-fill', 'label' => 'System Health'],
            ] as $aItem)
                @php $aActive = request()->routeIs($aItem['route'] . '*'); @endphp
                <a href="{{ route($aItem['route']) }}"
                   class="flex items-center py-2.5 rounded-xl transition-all text-xs font-semibold shrink-0 {{ $aActive ? 'bg-brand-600 text-white shadow-sm shadow-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}"
                   :class="sidebarExpanded ? 'gap-3 px-3' : 'justify-center px-0'"
                   :title="sidebarExpanded ? '' : '{{ $aItem['label'] }}'">
                    <i class="bi {{ $aItem['icon'] }} text-base shrink-0"></i>
                    <span class="whitespace-nowrap overflow-hidden transition-all duration-200"
                          :style="sidebarExpanded ? 'opacity:1;max-width:180px' : 'opacity:0;max-width:0'">
                        {{ $aItem['label'] }}
                    </span>
                </a>
            @endforeach
        @endif
    </nav>
</aside>
