{{-- Desktop Sidebar — Vercel/Railway Style: Icon-only + Hover Expand --}}
<aside class="hidden md:flex craft-sidebar border-r border-stone-200/80 dark:border-stone-800/80 h-screen sticky top-0 flex-col z-30 transition-all duration-200 ease-out"
       @mouseenter="sidebarHovered = true"
       @mouseleave="sidebarHovered = false"
       :style="sidebarHovered ? 'width: 240px' : 'width: 60px'">

    <!-- Logo Area -->
    <div class="h-12 px-3 flex items-center border-b border-stone-200/60 dark:border-stone-800/60 shrink-0">
        <a href="{{ route('dashboard') }}" wire:navigate.hover
           class="flex items-center gap-2.5 no-underline overflow-hidden group">
            <x-application-logo class="w-7 h-7 rounded-lg shrink-0 group-hover:scale-105 transition-transform" />
            <span class="text-sm font-bold tracking-tight text-stone-900 dark:text-stone-100 whitespace-nowrap transition-opacity duration-200"
                  :class="sidebarHovered ? 'opacity-100' : 'opacity-0'">
                SwapHub
            </span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden p-2 space-y-6 custom-scrollbar">
        <!-- Workspace Group -->
        <div>
            <div class="px-2 mb-2 overflow-hidden transition-opacity duration-200"
                 :class="sidebarHovered ? 'opacity-100 h-auto' : 'opacity-0 h-0'">
                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500">
                    Workspace
                </span>
            </div>

            <div class="space-y-1">
                @foreach([
                    ['route' => 'dashboard', 'icon' => 'bi-columns-gap', 'label' => 'Dashboard'],
                    ['route' => 'projects.index', 'icon' => 'bi-compass', 'label' => 'Cari Proyek'],
                    ['route' => 'skills.swap.index', 'icon' => 'bi-arrow-left-right', 'label' => 'Skill Swap'],
                    ['route' => 'chat', 'icon' => 'bi-chat-dots', 'label' => 'Chat'],
                ] as $item)
                    @php
                        $isActive = request()->routeIs($item['route'] . '*');
                        $url = route($item['route'], $item['params'] ?? []);
                    @endphp
                    <a href="{{ $url }}" wire:navigate.hover
                       class="flex items-center rounded-lg transition-all duration-150 no-underline text-sm font-medium group relative {{ $isActive ? 'bg-teal-600 text-white' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800' }}"
                       :class="sidebarHovered ? 'px-3 py-2 gap-3' : 'justify-center py-2.5'"
                       :title="sidebarHovered ? '' : '{{ $item['label'] }}'">
                        <i class="bi {{ $item['icon'] }} text-base shrink-0"></i>
                        <span class="whitespace-nowrap transition-opacity duration-200"
                              :class="sidebarHovered ? 'opacity-100' : 'opacity-0 absolute'">
                            {{ $item['label'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Campus Group -->
        <div>
            <div class="px-2 mb-2 overflow-hidden transition-opacity duration-200"
                 :class="sidebarHovered ? 'opacity-100 h-auto' : 'opacity-0 h-0'">
                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500">
                    Campus
                </span>
            </div>

            <div class="space-y-1">
                @foreach([
                    ['route' => 'campus.study-desk', 'icon' => 'bi-camera-video', 'label' => 'Study Desk'],
                    ['route' => 'campus.code-reviews', 'icon' => 'bi-code-slash', 'label' => 'Code Review'],
                    ['route' => 'campus.credits', 'icon' => 'bi-coin', 'label' => 'Kredit Time-Bank'],
                    ['route' => 'campus.courses', 'icon' => 'bi-mortarboard', 'label' => 'Mata Kuliah'],
                ] as $cItem)
                    @php
                        $cActive = request()->routeIs($cItem['route'] . '*');
                        $cUrl = route($cItem['route']);
                    @endphp
                    <a href="{{ $cUrl }}" wire:navigate.hover
                       class="flex items-center rounded-lg transition-all duration-150 no-underline text-sm font-medium group relative {{ $cActive ? 'bg-teal-600 text-white' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800' }}"
                       :class="sidebarHovered ? 'px-3 py-2 gap-3' : 'justify-center py-2.5'"
                       :title="sidebarHovered ? '' : '{{ $cItem['label'] }}'">
                        <i class="bi {{ $cItem['icon'] }} text-base shrink-0"></i>
                        <span class="whitespace-nowrap transition-opacity duration-200"
                              :class="sidebarHovered ? 'opacity-100' : 'opacity-0 absolute'">
                            {{ $cItem['label'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Account Group -->
        <div>
            <div class="px-2 mb-2 overflow-hidden transition-opacity duration-200"
                 :class="sidebarHovered ? 'opacity-100 h-auto' : 'opacity-0 h-0'">
                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500">
                    Akun
                </span>
            </div>

            <div class="space-y-1">
                @php $profileActive = request()->routeIs('profile.show'); @endphp
                <a href="{{ route('profile.show', auth()->id()) }}" wire:navigate.hover
                   class="flex items-center rounded-lg transition-all duration-150 no-underline text-sm font-medium group relative {{ $profileActive ? 'bg-teal-600 text-white' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800' }}"
                   :class="sidebarHovered ? 'px-3 py-2 gap-3' : 'justify-center py-2.5'"
                   :title="sidebarHovered ? '' : 'Profil Saya'">
                    <i class="bi bi-person text-base shrink-0"></i>
                    <span class="whitespace-nowrap transition-opacity duration-200"
                          :class="sidebarHovered ? 'opacity-100' : 'opacity-0 absolute'">
                        Profil Saya
                    </span>
                </a>

                @php $settingsActive = request()->routeIs('profile.edit'); @endphp
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center rounded-lg transition-all duration-150 no-underline text-sm font-medium group relative {{ $settingsActive ? 'bg-teal-600 text-white' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800' }}"
                   :class="sidebarHovered ? 'px-3 py-2 gap-3' : 'justify-center py-2.5'"
                   :title="sidebarHovered ? '' : 'Pengaturan'">
                    <i class="bi bi-gear text-base shrink-0"></i>
                    <span class="whitespace-nowrap transition-opacity duration-200"
                          :class="sidebarHovered ? 'opacity-100' : 'opacity-0 absolute'">
                        Pengaturan
                    </span>
                </a>
            </div>
        </div>

        <!-- Admin Section -->
        @if(auth()->user()->isAdmin())
            <div>
                <div class="px-2 mb-2 overflow-hidden transition-opacity duration-200"
                     :class="sidebarHovered ? 'opacity-100 h-auto' : 'opacity-0 h-0'">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-500">
                        Admin
                    </span>
                </div>

                <div class="space-y-1">
                    @foreach([
                        ['route' => 'admin.dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Overview'],
                        ['route' => 'admin.users.index', 'icon' => 'bi-people', 'label' => 'Users'],
                        ['route' => 'admin.projects.index', 'icon' => 'bi-folder', 'label' => 'Projects'],
                        ['route' => 'admin.credits.index', 'icon' => 'bi-coin', 'label' => 'Credits'],
                        ['route' => 'admin.code-reviews.index', 'icon' => 'bi-code-slash', 'label' => 'Bounties'],
                        ['route' => 'admin.swaps.index', 'icon' => 'bi-arrow-left-right', 'label' => 'Swaps'],
                        ['route' => 'admin.skills.index', 'icon' => 'bi-tools', 'label' => 'Skills'],
                        ['route' => 'admin.courses.index', 'icon' => 'bi-mortarboard', 'label' => 'Courses'],
                        ['route' => 'admin.badges.index', 'icon' => 'bi-award', 'label' => 'Badges'],
                        ['route' => 'admin.audit-logs.index', 'icon' => 'bi-shield-check', 'label' => 'Audit Logs'],
                        ['route' => 'admin.usability.index', 'icon' => 'bi-clipboard2-data', 'label' => 'Evaluasi SUS'],
                        ['route' => 'admin.health.index', 'icon' => 'bi-heart-pulse', 'label' => 'Health'],
                    ] as $aItem)
                        @php $aActive = request()->routeIs($aItem['route'] . '*'); @endphp
                        <a href="{{ route($aItem['route']) }}"
                           class="flex items-center rounded-lg transition-all duration-150 no-underline text-sm font-medium group relative {{ $aActive ? 'bg-teal-600 text-white' : 'text-stone-600 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800' }}"
                           :class="sidebarHovered ? 'px-3 py-2 gap-3' : 'justify-center py-2.5'"
                           :title="sidebarHovered ? '' : '{{ $aItem['label'] }}'">
                            <i class="bi {{ $aItem['icon'] }} text-base shrink-0"></i>
                            <span class="whitespace-nowrap transition-opacity duration-200"
                                  :class="sidebarHovered ? 'opacity-100' : 'opacity-0 absolute'">
                                {{ $aItem['label'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </nav>

    <!-- Bottom: User Profile -->
    <div class="p-2 border-t border-stone-200/60 dark:border-stone-800/60 shrink-0">
        <a href="{{ route('profile.show', auth()->id()) }}"
           class="flex items-center rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors no-underline overflow-hidden"
           :class="sidebarHovered ? 'p-2 gap-2.5' : 'justify-center p-2'">
            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0d9488&color=fff' }}"
                 class="w-7 h-7 rounded-full object-cover shrink-0 ring-1 ring-stone-200 dark:ring-stone-700">
            <div class="min-w-0 overflow-hidden transition-opacity duration-200"
                 :class="sidebarHovered ? 'opacity-100' : 'opacity-0 absolute'">
                <p class="font-bold text-xs text-stone-900 dark:text-stone-100 truncate mb-0 leading-tight">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-[10px] text-stone-400 dark:text-stone-500 truncate mb-0">
                    {{ auth()->user()->university ?? 'Student' }}
                </p>
            </div>
        </a>
    </div>

</aside>
