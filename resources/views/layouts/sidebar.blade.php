{{-- Desktop sidebar — hidden on mobile via .sidebar-desktop CSS class --}}
<aside class="sidebar-desktop bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 h-screen sticky top-0 overflow-hidden transition-[width] duration-300 ease-in-out"
       :style="sidebarExpanded ? 'width:260px' : 'width:72px'">

    <!-- Logo & Collapse/Expand toggle -->
    <div class="flex items-center p-4 mb-2 shrink-0 min-h-[64px]">
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 no-underline text-slate-800 dark:text-slate-100 flex-1 min-w-0 overflow-hidden">
            <img src="{{ asset('icon.png') }}"
                 alt="Swap Hub"
                 class="w-8 h-8 rounded shrink-0 object-contain">
            <span class="text-base font-bold whitespace-nowrap overflow-hidden transition-all duration-200"
                  :style="sidebarExpanded ? 'opacity:1;max-width:150px' : 'opacity:0;max-width:0'">
                Swap Hub
            </span>
        </a>

        <button @click="toggleSidebar()"
                class="shrink-0 p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 border-0 bg-transparent rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors"
                :title="sidebarExpanded ? 'Minimize' : 'Expand'">
            <i class="bi text-base transition-transform duration-300"
               :class="sidebarExpanded ? 'bi-chevron-left' : 'bi-list'"></i>
        </button>
    </div>

    <!-- User Info -->
    <div class="px-3 mb-4 shrink-0">
        <div class="flex items-center rounded-xl transition-all duration-200 overflow-hidden"
             :class="sidebarExpanded ? 'gap-3 p-2 bg-slate-100 dark:bg-slate-700/50' : 'justify-center p-1'">
            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random' }}"
                 class="rounded-full shrink-0 object-cover"
                 style="width:36px;height:36px;"
                 :title="sidebarExpanded ? '' : '{{ auth()->user()->name }}'">
            <div class="min-w-0 overflow-hidden transition-all duration-200"
                 :style="sidebarExpanded ? 'opacity:1;max-width:180px' : 'opacity:0;max-width:0'">
                <p class="font-bold mb-0 text-slate-800 dark:text-slate-100 text-sm truncate">{{ auth()->user()->name }}</p>
                <p class="text-slate-500 dark:text-slate-400 text-[11px] truncate mb-0">{{ auth()->user()->university ?? 'Student' }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col flex-1 px-3 gap-0.5 overflow-y-auto overflow-x-hidden pb-2"
         style="scrollbar-width:none;">

        @foreach([
            ['route' => 'dashboard',        'icon' => 'bi-grid-fill',         'label' => 'Dashboard'],
            ['route' => 'projects.index',   'icon' => 'bi-compass-fill',      'label' => 'Cari Proyek'],
            ['route' => 'skills.swap.index','icon' => 'bi-arrow-left-right',  'label' => 'Skill Swap'],
            ['route' => 'profile.show',     'icon' => 'bi-person-badge-fill', 'label' => 'Profil Saya', 'params' => auth()->id()],
            ['route' => 'chat',             'icon' => 'bi-chat-dots-fill',     'label' => 'Workspace & Chat'],
        ] as $item)
            @php
                $isActive = request()->routeIs($item['route'] . '*');
                $url = route($item['route'], $item['params'] ?? []);
            @endphp
            <a href="{{ $url }}"
               class="flex items-center py-2.5 rounded-xl transition-all duration-150 no-underline text-sm font-semibold shrink-0 {{ $isActive ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}"
               :class="sidebarExpanded ? 'gap-3 px-3.5' : 'justify-center px-0'"
               :title="sidebarExpanded ? '' : '{{ $item['label'] }}'">
                <i class="bi {{ $item['icon'] }} text-base shrink-0"></i>
                <span class="whitespace-nowrap overflow-hidden transition-all duration-200"
                      :style="sidebarExpanded ? 'opacity:1;max-width:180px' : 'opacity:0;max-width:0'">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach

        <div class="border-t border-slate-200 dark:border-slate-700 my-2 mx-1 shrink-0"></div>

        @php $settingsActive = request()->routeIs('profile.edit'); @endphp
        <a href="{{ route('profile.edit') }}"
           class="flex items-center py-2.5 rounded-xl transition-all duration-150 no-underline text-sm font-semibold shrink-0 {{ $settingsActive ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}"
           :class="sidebarExpanded ? 'gap-3 px-3.5' : 'justify-center px-0'"
           :title="sidebarExpanded ? '' : 'Pengaturan'">
            <i class="bi bi-gear-fill text-base shrink-0"></i>
            <span class="whitespace-nowrap overflow-hidden transition-all duration-200"
                  :style="sidebarExpanded ? 'opacity:1;max-width:180px' : 'opacity:0;max-width:0'">
                Pengaturan
            </span>
        </a>

        @if(auth()->user()->isAdmin())
            <div class="border-t border-slate-200 dark:border-slate-700 my-2 mx-1 shrink-0"></div>
            <div class="px-3.5 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap overflow-hidden"
                 :style="sidebarExpanded ? 'opacity:1;max-width:180px' : 'opacity:0;max-width:0'">
                Admin Panel
            </div>
            @foreach([
                ['route' => 'admin.dashboard',      'icon' => 'bi-speedometer2',   'label' => 'Admin Dashboard'],
                ['route' => 'admin.users.index',    'icon' => 'bi-people-fill',    'label' => 'User Management'],
                ['route' => 'admin.projects.index', 'icon' => 'bi-folder-fill',    'label' => 'Project Management'],
                ['route' => 'admin.health.index',   'icon' => 'bi-heart-pulse-fill','label' => 'System Health'],
            ] as $aItem)
                @php $aActive = request()->routeIs($aItem['route'] . '*'); @endphp
                <a href="{{ route($aItem['route']) }}"
                   class="flex items-center py-2 rounded-xl transition-all duration-150 no-underline text-xs font-semibold shrink-0 {{ $aActive ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}"
                   :class="sidebarExpanded ? 'gap-3 px-3.5' : 'justify-center px-0'"
                   :title="sidebarExpanded ? '' : '{{ $aItem['label'] }}'">
                    <i class="bi {{ $aItem['icon'] }} text-sm shrink-0"></i>
                    <span class="whitespace-nowrap overflow-hidden transition-all duration-200"
                          :style="sidebarExpanded ? 'opacity:1;max-width:180px' : 'opacity:0;max-width:0'">
                        {{ $aItem['label'] }}
                    </span>
                </a>
            @endforeach
        @endif
    </nav>

    <!-- Footer -->
    <div class="px-4 py-3 shrink-0 border-t border-slate-200 dark:border-slate-700 overflow-hidden transition-all duration-200"
         :style="sidebarExpanded ? 'opacity:1' : 'opacity:0;height:0;padding:0;border:none'">
        <p class="text-slate-400 dark:text-slate-500 text-[10px] text-center mb-0">&copy; 2024 Swap Hub</p>
    </div>
</aside>