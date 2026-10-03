@section('title', 'Pertukaran Skill')
<x-app-layout>
    <div class="py-2 space-y-6">
        
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 border border-indigo-900/50 shadow-xl">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute inset-0 bg-subtle-grid opacity-30 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 text-xs font-bold mb-3">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Peer-to-Peer Learning</span>
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight mb-2">
                        Pertukaran Skill Mahasiswa
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-xl mb-0">
                        Tukarkan keahlianmu dengan mahasiswa lain. Ajarkan apa yang kamu kuasai dan pelajari skill baru yang kamu butuhkan.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-xs font-bold text-slate-300 bg-white/10 px-3.5 py-2 rounded-xl border border-white/15 backdrop-blur-sm tabular-nums">
                        {{ $requests->total() }} Permintaan Aktif
                    </span>
                    <a href="{{ route('skills.swap.create') }}" 
                        class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-600/30 flex items-center gap-2 text-xs no-underline transition-all duration-150 active:scale-[0.98]">
                        <i class="bi bi-plus-lg text-sm"></i>
                        <span>Ajukan Swap Skill</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-base text-emerald-500"></i>
                <span>
                    @if(session('status') === 'skill-swap-created')
                        Permintaan pertukaran skill berhasil dipublikasikan!
                    @elseif(session('status') === 'skill-swap-accepted')
                        Anda berhasil menerima tawaran pertukaran skill ini!
                    @elseif(session('status') === 'skill-swap-completed')
                        Pertukaran skill selesai! Poin reputasi telah ditransfer.
                    @elseif(session('status') === 'skill-swap-cancelled')
                        Permintaan pertukaran skill telah dibatalkan.
                    @else
                        {{ session('status') }}
                    @endif
                </span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs font-semibold flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-base text-rose-500"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filter Bar -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 p-4 shadow-sm flex flex-wrap gap-3 items-center justify-between">
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('skills.swap.index') }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors no-underline {{ !request('status') && !request('filter') ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                    Semua
                </a>
                <a href="{{ route('skills.swap.index', ['status' => 'pending']) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                    Menunggu (Pending)
                </a>
                <a href="{{ route('skills.swap.index', ['status' => 'accepted']) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request('status') === 'accepted' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                    Berjalan (Accepted)
                </a>
                <a href="{{ route('skills.swap.index', ['status' => 'completed']) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request('status') === 'completed' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                    Selesai
                </a>
                <a href="{{ route('skills.swap.index', ['filter' => 'my']) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request('filter') === 'my' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100' }}">
                    <i class="bi bi-person mr-1"></i> Swap Saya
                </a>
            </div>

            @if($skills->isNotEmpty())
            <form action="{{ route('skills.swap.index') }}" method="GET" class="flex items-center gap-2">
                <select name="skill_id" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-1.5 text-xs text-slate-800 dark:text-slate-100 focus:outline-none">
                    <option value="">Semua Kategori Skill</option>
                    @foreach($skills as $skill)
                        <option value="{{ $skill->id }}" {{ (string) request('skill_id') === (string) $skill->id ? 'selected' : '' }}>
                            {{ $skill->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            @endif
        </div>

        <!-- Requests Grid -->
        @if($requests->isEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/60 p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-2xl">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 mb-1">Belum Ada Permintaan Swap</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-4">
                    Jadilah yang pertama menawarkan barter keahlian dengan sesama mahasiswa di Swap Hub!
                </p>
                <a href="{{ route('skills.swap.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold no-underline hover:bg-indigo-500">
                    <i class="bi bi-plus-lg"></i>
                    <span>Mulai Swap Pertama</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($requests as $requestItem)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 p-5 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                        <div>
                            <!-- User header -->
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <img src="{{ $requestItem->requester->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($requestItem->requester->name) }}"
                                         alt="{{ $requestItem->requester->name }}"
                                         class="w-9 h-9 rounded-full object-cover shrink-0 border border-slate-200 dark:border-slate-700">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate mb-0">
                                            {{ $requestItem->requester->name }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 mb-0">
                                            {{ $requestItem->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                @php
                                    $statusColor = match($requestItem->status) {
                                        'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300',
                                        'accepted' => 'bg-blue-100 text-blue-800 dark:bg-blue-950/50 dark:text-blue-300',
                                        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300',
                                        default => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                    {{ $requestItem->status }}
                                </span>
                            </div>

                            <!-- Skills Swap Exchange Box -->
                            <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-3 mb-4 border border-slate-100 dark:border-slate-700/50">
                                <div class="flex items-center justify-between gap-2 text-xs">
                                    <div class="flex-1 min-w-0">
                                        <span class="block text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-1">
                                            Menawarkan
                                        </span>
                                        <span class="inline-block px-2.5 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-950/50 text-emerald-900 dark:text-emerald-200 font-bold truncate max-w-full">
                                            {{ $requestItem->offeredSkill->name ?? 'Skill' }}
                                        </span>
                                    </div>
                                    <div class="text-slate-400 shrink-0 px-1">
                                        <i class="bi bi-arrow-right text-base"></i>
                                    </div>
                                    <div class="flex-1 min-w-0 text-right">
                                        <span class="block text-[10px] font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1">
                                            Mencari
                                        </span>
                                        <span class="inline-block px-2.5 py-1 rounded-lg bg-indigo-100 dark:bg-indigo-950/50 text-indigo-900 dark:text-indigo-200 font-bold truncate max-w-full">
                                            {{ $requestItem->requestedSkill->name ?? 'Skill' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-3 mb-4">
                                {{ $requestItem->description }}
                            </p>

                            @if(isset($requestItem->compatibility) && $requestItem->requester_id !== auth()->id())
                                <div class="mb-3 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500 dark:text-slate-400">
                                        <i class="bi bi-stars text-indigo-500"></i>
                                        <span>Kecocokan Barter:</span>
                                    </div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $requestItem->compatibility['badge_class'] }}">
                                        {{ $requestItem->compatibility['status'] }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Footer & Actions -->
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60">
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                    <i class="bi bi-trophy text-amber-500"></i>
                                    <span>{{ $requestItem->points_offered }} Poin Reputasi</span>
                                </span>

                                @if($requestItem->provider)
                                    <span class="text-[10px] text-slate-400">
                                        Partner: <strong class="text-slate-700 dark:text-slate-200">{{ $requestItem->provider->name }}</strong>
                                    </span>
                                @endif
                            </div>

                            <!-- Dynamic Buttons -->
                            <div class="flex items-center gap-2">
                                @if($requestItem->status === 'pending')
                                    @if((int) $requestItem->requester_id !== (int) auth()->id())
                                        <form action="{{ route('skills.swap.accept', $requestItem) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                                Ambil Tawaran
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('skills.swap.cancel', $requestItem) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Batalkan permintaan ini?')" class="w-full py-2 bg-slate-100 dark:bg-slate-700 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl text-xs font-bold transition-all">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                @elseif(in_array($requestItem->status, ['accepted', 'in_progress']) && ((int) $requestItem->requester_id === (int) auth()->id() || (int) $requestItem->provider_id === (int) auth()->id()))
                                    <form action="{{ route('skills.swap.complete', $requestItem) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Tandai swap telah selesai? Poin akan ditransfer ke partner.')" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                            Tandai Selesai
                                        </button>
                                    </form>
                                    <form action="{{ route('skills.swap.cancel', $requestItem) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Batalkan swap ini?')" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-rose-600 rounded-xl text-xs font-bold hover:bg-rose-50">
                                            Batal
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
