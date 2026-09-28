@section('title', $user->name . ' - Profil')
<x-app-layout>
    <div class="container mx-auto py-6">
        
        <!-- ROW 1: HEADER CARD -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-card border border-slate-200/80 dark:border-slate-700/80 mb-6 overflow-hidden relative">
            <div class="h-24 bg-gradient-to-r from-indigo-500 via-indigo-600 to-emerald-500 opacity-90"></div>
            <div class="px-6 pb-6 md:px-8 md:pb-8 pt-0 relative">
                <div class="flex flex-col md:flex-row items-center md:items-end justify-between gap-6 -mt-12">
                    
                    <!-- Avatar & Info -->
                    <div class="flex flex-col md:flex-row items-center md:items-end gap-5 text-center md:text-left">
                        <div class="relative shrink-0">
                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128&background=6366f1&color=fff' }}" 
                                 class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border-4 border-white dark:border-slate-800 object-cover shadow-lg" 
                                 alt="{{ $user->name }}">
                            <div class="absolute bottom-1 right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white dark:border-slate-800" title="Online"></div>
                        </div>
                        
                        <div class="mb-1">
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-1">
                                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-slate-100 tracking-tight mb-0">
                                    {{ $user->name }}
                                </h1>
                                <span class="bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 text-[10px] font-bold px-2 py-0.5 rounded-md border border-indigo-200/60 dark:border-indigo-800/60">
                                    {{ $user->role->name ?? 'Mahasiswa' }}
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-1 font-medium">
                                {{ $user->university ?? 'Universitas' }} <span class="mx-1">•</span> {{ $user->major ?? 'Program Studi' }}
                            </p>
                            <div class="flex items-center justify-center md:justify-start gap-3 text-xs text-slate-400 dark:text-slate-500">
                                <span class="flex items-center gap-1">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $user->location ?? 'Indonesia' }}
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold">
                                    <i class="bi bi-star-fill text-[11px]"></i>
                                    {{ number_format($user->reputation_points) }} CP
                                </span>
                            </div>
                        </div>
                    </div>
 
                    <!-- Actions -->
                    <div class="flex flex-wrap items-center gap-2.5 justify-center">
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}" 
                               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-sm flex items-center gap-2 text-xs transition-all duration-150 active:scale-[0.98] no-underline">
                                <i class="bi bi-pencil-square text-xs"></i>
                                <span>Edit Profil</span>
                            </a>
                            <a href="{{ route('profile.resume', $user) }}" 
                               class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 font-bold py-2.5 px-4 rounded-xl flex items-center gap-2 text-xs transition-colors no-underline shadow-sm">
                                <i class="bi bi-file-earmark-pdf text-xs"></i>
                                <span>Unduh Resume</span>
                            </a>
                        @else
                            <form action="{{ route('chat.direct', $user) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm flex items-center gap-2 text-xs transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer">
                                    <i class="bi bi-chat-dots-fill text-xs"></i>
                                    <span>Kirim Pesan</span>
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>
 
        <!-- ROW 2: SPLIT CONTENT -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            
            <!-- ABOUT ME -->
            <div class="lg:col-span-7">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-card border border-slate-200/80 dark:border-slate-700/80 h-full flex flex-col p-6 md:p-8">
                    <h3 class="font-extrabold text-base text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                        <i class="bi bi-person-lines-fill text-indigo-600 dark:text-indigo-400"></i>
                        <span>Tentang Saya</span>
                    </h3>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-xs sm:text-sm mb-6 flex-grow whitespace-pre-line">
                        {{ $user->bio ?? "Mahasiswa {$user->major} yang antusias dalam kolaborasi proyek dan pengembangan software tim. Senang mempelajari teknologi baru dan berkontribusi pada solusi inovatif." }}
                    </p>
 
                    <!-- Stats Grid -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60 mt-auto">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-700/50">
                                <span class="uppercase text-slate-400 dark:text-slate-500 text-[10px] font-bold tracking-wider block mb-0.5">Status</span>
                                <p class="font-bold text-xs text-slate-800 dark:text-slate-200 mb-0">Mahasiswa</p>
                            </div>
                            <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-700/50">
                                <span class="uppercase text-slate-400 dark:text-slate-500 text-[10px] font-bold tracking-wider block mb-0.5">Proyek</span>
                                <p class="font-bold text-xs text-slate-800 dark:text-slate-200 mb-0">{{ $user->projects->count() }} Selesai</p>
                            </div>
                            <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-700/50">
                                <span class="uppercase text-slate-400 dark:text-slate-500 text-[10px] font-bold tracking-wider block mb-0.5">Reputasi</span>
                                <p class="font-bold text-xs text-emerald-600 dark:text-emerald-400 mb-0">{{ $user->reputation_points }} CP</p>
                            </div>
                            <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-700/50">
                                <span class="uppercase text-slate-400 dark:text-slate-500 text-[10px] font-bold tracking-wider block mb-0.5">Bahasa</span>
                                <p class="font-bold text-xs text-slate-800 dark:text-slate-200 mb-0">ID, English</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- SKILLS & EXPERTISE -->
            <div class="lg:col-span-5">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-card border border-slate-200/80 dark:border-slate-700/80 h-full p-6 md:p-8 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                            <i class="bi bi-lightning-charge-fill text-amber-500"></i>
                            <span>Keahlian & Kompetensi</span>
                        </h3>
 
                        <!-- Skill Tags -->
                        <div class="flex flex-wrap gap-1.5 mb-6">
                            @forelse($user->skills as $skill)
                                <span class="bg-slate-100 dark:bg-slate-700/60 text-slate-700 dark:text-slate-200 rounded-xl px-3 py-1 font-semibold border border-slate-200 dark:border-slate-600 text-xs">
                                    {{ $skill->name }}
                                </span>
                            @empty
                                <span class="text-slate-400 dark:text-slate-500 text-xs italic">Belum ada keahlian khusus yang didaftarkan.</span>
                            @endforelse
                        </div>
 
                        <!-- Skill Proficiency Bars -->
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block mb-3">Tingkat Kemahiran</span>
                        <div class="flex flex-col gap-3">
                            @php
                                $displayedSkills = $user->skills->take(3); // Show top 3 for bars
                            @endphp
                            @foreach($displayedSkills as $skill)
                                @php
                                    $levelMap = ['beginner' => 30, 'intermediate' => 60, 'advanced' => 85, 'expert' => 98];
                                    $percent = $levelMap[$skill->pivot->proficiency_level] ?? 50;
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $skill->name }}</span>
                                        <span class="text-[10px] text-indigo-600 dark:text-indigo-400 font-bold uppercase">{{ $skill->pivot->proficiency_level }} ({{ $percent }}%)</span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-300" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                            @if($user->skills->isEmpty())
                                <div class="text-center p-4 bg-slate-50 dark:bg-slate-700/30 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                                    <p class="text-slate-400 text-xs mb-0">Tambahkan skill di profil untuk melihat visualisasi kompetensi.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- ROW 3: GITHUB ACTIVITY -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-card border border-slate-200/80 dark:border-slate-700/80 mb-6 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                            <i class="bi bi-github text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-sm text-slate-900 dark:text-slate-100 mb-0 flex items-center gap-2">
                                <span>Aktivitas GitHub Publik</span>
                                <span class="rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 px-2 py-0.5 text-[9px] font-bold">Terhubung</span>
                            </h3>
                            <p class="text-slate-400 dark:text-slate-500 text-xs mb-0">Kontribusi commit mahasiswa terverifikasi</p>
                        </div>
                    </div>
                </div>
 
                <!-- Contribution Graph Simulation -->
                <div class="flex flex-wrap gap-1 px-1 overflow-x-auto pb-2">
                    @for($w = 0; $w < 36; $w++) 
                         <div class="flex flex-col gap-1">
                             @for($d = 0; $d < 5; $d++)
                                 @php 
                                     $active = rand(0, 10) > 6;
                                     $opacity = $active ? rand(40, 95) : 15;
                                 @endphp
                                 <div class="w-3 h-3 rounded-[3px]" style="background-color: rgba(99, 102, 241, {{ $opacity / 100 }});"></div>
                             @endfor
                         </div>
                    @endfor
                </div>
            </div>
        </div>
 
        <!-- ROW 4: PROJECT HISTORY -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-card border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
             <div class="p-6 md:p-8 flex justify-between items-center pb-4">
                 <h3 class="font-extrabold text-base text-slate-900 dark:text-slate-100 mb-0 flex items-center gap-2">
                    <i class="bi bi-kanban text-indigo-600 dark:text-indigo-400"></i>
                    <span>Riwayat Kolaborasi Proyek</span>
                 </h3>
                 <a href="{{ route('projects.index') }}" class="text-indigo-600 dark:text-indigo-400 text-xs font-bold no-underline hover:underline">
                    Jelajahi Proyek Lain →
                 </a>
             </div>
             <div class="border-t border-slate-100 dark:border-slate-700/60 overflow-x-auto">
                 <table class="w-full text-nowrap text-left">
                      <thead class="bg-slate-50 dark:bg-slate-700/30 border-b border-slate-100 dark:border-slate-700/60">
                          <tr>
                              <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Nama Proyek</th>
                              <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Peran</th>
                              <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                              <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Aksi</th>
                          </tr>
                      </thead>
                      <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse($user->projects->take(5) as $project)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                            <i class="bi bi-folder-fill"></i>
                                        </div>
                                        <span class="font-bold text-xs text-slate-900 dark:text-slate-100">{{ $project->title }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                     <span class="bg-slate-100 dark:bg-slate-700/60 text-slate-700 dark:text-slate-300 rounded-full text-[10px] px-2.5 py-0.5 font-bold uppercase border border-slate-200 dark:border-slate-600">{{ $project->pivot->role ?? 'Member' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                     <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                         <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                         {{ ucfirst($project->status ?? 'Active') }}
                                     </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                     <a href="{{ route('projects.show', $project) }}" class="border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs font-bold py-1 px-3 rounded-lg no-underline transition-colors shadow-sm">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-slate-400 dark:text-slate-500 text-xs">
                                    Belum ada riwayat proyek yang tercatat pada profil ini.
                                </td>
                            </tr>
                        @endforelse
                      </tbody>
                 </table>
             </div>
        </div>
 
    </div>
</x-app-layout>