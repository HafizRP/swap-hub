<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{ showCreateModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-[#141414] p-5 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800">
        <div>
            <span class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase tracking-wider">Virtual Campus</span>
            <h1 class="text-xl font-bold text-stone-900 dark:text-stone-100 mt-1 mb-0">Study Desk & Peer Rooms</h1>
            <p class="text-stone-500 dark:text-stone-400 text-xs mt-1 mb-0">Sesi belajar bersama, mentoring, dan diskusi tugas kelompok dengan integrasi video call instan.</p>
        </div>
        <button @click="showCreateModal = true" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg text-xs transition-colors shadow-sm border-0 cursor-pointer flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
            <i class="bi bi-camera-video text-xs"></i> Buat Sesi Belajar
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    @error('session')
        <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 rounded-xl text-sm font-medium">
            {{ $message }}
        </div>
    @enderror

    <!-- Active Sessions Grid -->
    <div class="space-y-4">
        <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">Sesi Belajar Aktif & Mendatang</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($activeSessions as $session)
                @php
                    $isHost = $session->host_id === auth()->id();
                    $isParticipant = $session->participants->contains(auth()->id());
                @endphp
                <div class="bg-white dark:bg-[#141414] p-5 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 flex flex-col justify-between" wire:key="session-{{ $session->id }}">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $session->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-teal-50 text-teal-600 border border-teal-200 dark:bg-teal-950/40 dark:text-teal-400' }}">
                                {{ $session->status }}
                            </span>
                            <span class="text-[11px] text-stone-400 flex items-center gap-1">
                                <i class="bi bi-clock"></i>
                                {{ $session->starts_at ? $session->starts_at->format('d M, H:i') : 'Flexible' }}
                            </span>
                        </div>

                        <div>
                            <h3 class="font-bold text-stone-900 dark:text-stone-100 text-sm mb-1">{{ $session->title }}</h3>
                            @if($session->description)
                                <p class="text-xs text-stone-500 dark:text-stone-400 mb-0 line-clamp-2">{{ $session->description }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-stone-100 dark:border-stone-800">
                            <img src="{{ $session->host->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($session->host->name) . '&background=0d9488&color=fff' }}" class="w-6 h-6 rounded-full object-cover">
                            <span class="text-xs text-stone-600 dark:text-stone-300 font-medium truncate">Host: {{ $session->host->name }}</span>
                        </div>

                        <!-- Participants count -->
                        <div class="text-[11px] text-stone-400 flex items-center gap-1">
                            <i class="bi bi-people"></i>
                            <span>{{ $session->participants->count() }} Peserta terdaftar</span>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-stone-100 dark:border-stone-800 flex items-center justify-between gap-2">
                        @if($session->meeting_url && ($isParticipant || $isHost))
                            <a href="{{ $session->meeting_url }}" target="_blank" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-semibold no-underline transition-colors flex items-center gap-1.5 shadow-sm">
                                <i class="bi bi-box-arrow-up-right"></i> Masuk Room
                            </a>
                        @endif

                        <div class="flex items-center gap-2 ml-auto">
                            @if(!$isParticipant && !$isHost)
                                <button wire:click="joinSession({{ $session->id }})" class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-200 rounded-lg text-xs font-medium border-0 cursor-pointer transition-colors">
                                    Gabung
                                </button>
                            @elseif($isParticipant && !$isHost)
                                <button wire:click="leaveSession({{ $session->id }})" class="px-2.5 py-1.5 text-xs text-stone-400 hover:text-red-500 border-0 bg-transparent cursor-pointer">
                                    Keluar
                                </button>
                            @endif

                            @if($isHost && $session->status !== 'completed')
                                <button wire:click="completeSession({{ $session->id }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-medium border-0 cursor-pointer shadow-sm">
                                    Selesai & Beri Kredit
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white dark:bg-[#141414] p-8 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 text-center">
                    <i class="bi bi-calendar-event text-4xl text-stone-300 dark:text-stone-600 mb-2 block"></i>
                    <p class="text-stone-600 dark:text-stone-400 text-sm font-semibold mb-1">Belum ada sesi belajar aktif.</p>
                    <small class="text-stone-400 dark:text-stone-500 text-xs">Buat sesi belajar baru untuk berdiskusi dengan sesama mahasiswa.</small>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Session Modal -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141414] w-full max-w-lg p-5 rounded-xl shadow-xl space-y-4 border border-stone-200 dark:border-stone-800">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-stone-900 dark:text-stone-100 mb-0">Jadwalkan Sesi Belajar</h3>
                <button @click="showCreateModal = false" class="text-stone-400 hover:text-stone-600 border-0 bg-transparent cursor-pointer"><i class="bi bi-x-lg"></i></button>
            </div>
            <form wire:submit.prevent="createSession" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Judul Sesi</label>
                    <input type="text" wire:model="title" placeholder="mis. Persiapan Ujian Struktur Data" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                    @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Deskripsi & Agenda</label>
                    <textarea wire:model="description" rows="3" placeholder="Topik apa saja yang akan dibahas..." class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"></textarea>
                    @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Waktu Mulai</label>
                        <input type="datetime-local" wire:model="startsAt" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                        @error('startsAt') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Durasi (Menit)</label>
                        <input type="number" min="15" max="360" wire:model="durationMinutes" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                        @error('durationMinutes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-stone-100 dark:border-stone-800">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-xs text-stone-500 hover:text-stone-700 border-0 bg-transparent cursor-pointer">Batal</button>
                    <button type="submit" @click="showCreateModal = false" class="px-4 py-2 text-xs bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium border-0 cursor-pointer shadow-sm">Jadwalkan Sesi</button>
                </div>
            </form>
        </div>
    </div>
</div>
