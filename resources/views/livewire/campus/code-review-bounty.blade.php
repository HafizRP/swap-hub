<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{ showCreateModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-[#141414] p-5 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase tracking-wider">Campus Bounties</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300">
                    {{ $user->credits }} Credits Available
                </span>
            </div>
            <h1 class="text-xl font-bold text-stone-900 dark:text-stone-100 mt-1 mb-0">Code Review Bounty Board</h1>
            <p class="text-stone-500 dark:text-stone-400 text-xs mt-1 mb-0">Minta review kode dari rekan mahasiswa atau dapatkan kredit dengan mereview pull request mereka.</p>
        </div>
        <button @click="showCreateModal = true" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg text-xs transition-colors shadow-sm border-0 cursor-pointer flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
            <i class="bi bi-plus-lg text-xs"></i> Buat Permintaan Review
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <!-- Requests Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- List of Requests -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">Permintaan Review Aktif</h2>

            @forelse($openRequests as $req)
                <div class="bg-white dark:bg-[#141414] p-5 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 space-y-3" wire:key="request-{{ $req->id }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $req->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($req->user->name) . '&background=0d9488&color=fff' }}" class="w-9 h-9 rounded-full object-cover">
                            <div>
                                <h3 class="font-bold text-stone-900 dark:text-stone-100 text-sm mb-0">{{ $req->title }}</h3>
                                <p class="text-[11px] text-stone-400 mb-0">{{ $req->user->name }} &bull; {{ $req->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                            {{ $req->bounty_credits }} CR Bounty
                        </span>
                    </div>

                    @if($req->description)
                        <p class="text-xs text-stone-600 dark:text-stone-300 mb-0">{{ $req->description }}</p>
                    @endif

                    <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-stone-100 dark:border-stone-800 text-xs">
                        @if($req->repository_url)
                            <a href="{{ $req->repository_url }}" target="_blank" class="text-stone-600 dark:text-stone-400 hover:text-teal-600 no-underline flex items-center gap-1 font-medium">
                                <i class="bi bi-github"></i> Repository
                            </a>
                        @endif
                        @if($req->pr_url)
                            <span class="text-stone-300 dark:text-stone-700">&bull;</span>
                            <a href="{{ $req->pr_url }}" target="_blank" class="text-stone-600 dark:text-stone-400 hover:text-teal-600 no-underline flex items-center gap-1 font-medium">
                                <i class="bi bi-git"></i> Pull Request
                            </a>
                        @endif
                        <span class="text-stone-300 dark:text-stone-700">&bull;</span>
                        <span class="text-[11px] font-semibold uppercase text-stone-400">{{ $req->status }}</span>
                    </div>

                    <!-- Submissions section -->
                    @if($req->submissions->isNotEmpty())
                        <div class="mt-3 pt-3 border-t border-stone-100 dark:border-stone-800 space-y-2">
                            <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Submisi Feedback ({{ $req->submissions->count() }}):</span>
                            @foreach($req->submissions as $sub)
                                <div class="p-3 bg-stone-50 dark:bg-[#1a1917] rounded-lg space-y-1.5" wire:key="sub-{{ $sub->id }}">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-stone-800 dark:text-stone-200">{{ $sub->reviewer->name }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded {{ $sub->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-200 text-stone-700 dark:bg-stone-700 dark:text-stone-300' }}">
                                                {{ $sub->status }}
                                            </span>
                                            @if($req->user_id === auth()->id() && $sub->status === 'pending' && $req->status !== 'completed')
                                                <button wire:click="acceptSubmission({{ $sub->id }})" class="px-2 py-0.5 bg-emerald-600 text-white rounded text-[11px] font-semibold border-0 cursor-pointer hover:bg-emerald-700">
                                                    Terima & Beri Bounty
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-xs text-stone-600 dark:text-stone-400 mb-0 whitespace-pre-line">{{ $sub->feedback }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($req->user_id !== auth()->id() && $req->status !== 'completed')
                        <div class="pt-2 flex justify-end">
                            <button wire:click="selectRequest({{ $req->id }})" class="px-3 py-1.5 bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 hover:bg-teal-100 rounded-lg text-xs font-medium border-0 cursor-pointer transition-colors">
                                <i class="bi bi-chat-square-code"></i> Berikan Review Feedback
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white dark:bg-[#141414] p-8 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 text-center">
                    <i class="bi bi-code-slash text-4xl text-stone-300 dark:text-stone-600 mb-2 block"></i>
                    <p class="text-stone-600 dark:text-stone-400 text-sm font-semibold mb-1">Belum ada permintaan review aktif.</p>
                    <small class="text-stone-400 dark:text-stone-500 text-xs">Jadilah yang pertama meminta review atau bagikan pull request Anda!</small>
                </div>
            @endforelse

            <div>
                {{ $openRequests->links() }}
            </div>
        </div>

        <!-- Feedback Submission Sidebar -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-[#141414] p-5 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 space-y-4">
                <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">Kirim Review Feedback</h2>
                @if($activeRequestId)
                    <form wire:submit.prevent="submitFeedback" class="space-y-3">
                        <p class="text-xs text-stone-500 mb-0">Mereview request #{{ $activeRequestId }}</p>
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Catatan & Masukan Review</label>
                            <textarea wire:model="feedback" rows="6" placeholder="Tuliskan analisis arsitektur, bug yang ditemukan, atau saran performa..." class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"></textarea>
                            @error('feedback') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" wire:click="$set('activeRequestId', null)" class="px-3 py-1.5 text-xs text-stone-500 hover:text-stone-700 border-0 bg-transparent cursor-pointer">Batal</button>
                            <button type="submit" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-medium border-0 cursor-pointer shadow-sm">Kirim Review</button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-6 text-stone-400">
                        <i class="bi bi-arrow-left-circle text-2xl block mb-1"></i>
                        <p class="text-xs mb-0">Pilih salah satu permintaan review di samping untuk menulis feedback dan mendapatkan bounty kredit.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Request Modal -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141414] w-full max-w-lg p-5 rounded-xl shadow-xl space-y-4 border border-stone-200 dark:border-stone-800">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-stone-900 dark:text-stone-100 mb-0">Permintaan Code Review Baru</h3>
                <button @click="showCreateModal = false" class="text-stone-400 hover:text-stone-600 border-0 bg-transparent cursor-pointer"><i class="bi bi-x-lg"></i></button>
            </div>
            <form wire:submit.prevent="createRequest" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Judul Review</label>
                    <input type="text" wire:model="title" placeholder="mis. Review Arsitektur Repository Auth Microservice" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                    @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Deskripsi & Area Fokus</label>
                    <textarea wire:model="description" rows="3" placeholder="Jelaskan bagian kode yang membutuhkan perhatian..." class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"></textarea>
                    @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">URL Repository</label>
                        <input type="url" wire:model="repositoryUrl" placeholder="https://github.com/..." class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                        @error('repositoryUrl') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">URL Pull Request (Opsional)</label>
                        <input type="url" wire:model="pullRequestUrl" placeholder="https://github.com/.../pull/1" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                        @error('pullRequestUrl') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Bounty Kredit (Saldo Anda: {{ $user->credits }})</label>
                    <input type="number" min="0" max="{{ $user->credits }}" wire:model="bountyCredits" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                    @error('bountyCredits') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-stone-100 dark:border-stone-800">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-xs text-stone-500 hover:text-stone-700 border-0 bg-transparent cursor-pointer">Batal</button>
                    <button type="submit" @click="showCreateModal = false" class="px-4 py-2 text-xs bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium border-0 cursor-pointer shadow-sm">Publikasikan Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>
