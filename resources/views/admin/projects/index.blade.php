@section('title', 'Manajemen Proyek')
<x-app-layout>
    <div class="space-y-6">

        <!-- Top Header & Search -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Moderasi & Manajemen Proyek
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kelola status, arsip proyek bermasalah, dan tinjau repositori terhubung.
                </p>
            </div>

            <form method="GET" class="flex flex-wrap items-center gap-2.5">
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-48 sm:w-60 pl-8 pr-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs outline-none focus:ring-2 focus:ring-brand-500"
                           placeholder="Cari judul proyek...">
                </div>

                <select name="status" class="px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs outline-none focus:ring-2 focus:ring-brand-500"
                        onchange="this.form.submit()">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="planning" {{ request('status') === 'planning' ? 'selected' : '' }}>Planning</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </form>
        </div>

        <!-- Projects Table Card -->
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 uppercase font-extrabold text-[10px] bg-slate-50/50 dark:bg-slate-800/50">
                            <th class="py-3 px-4">Proyek</th>
                            <th class="py-3 px-4">Pemilik (Creator)</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Anggota</th>
                            <th class="py-3 px-4">Dibuat</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($projects as $project)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="max-w-xs space-y-0.5">
                                        <div class="font-bold text-slate-900 dark:text-white truncate">{{ $project->title }}</div>
                                        <p class="text-[11px] text-slate-400 truncate">{{ $project->description }}</p>
                                        @if($project->github_repo_url)
                                            <a href="{{ $project->github_repo_url }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-[10px] font-bold text-brand-600 hover:underline">
                                                <i class="bi bi-github"></i> Repository
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=6366f1&color=fff' }}"
                                             alt="{{ $project->owner->name }}" class="w-7 h-7 rounded-lg object-cover">
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 dark:text-white truncate">{{ $project->owner->name }}</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $project->owner->university ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $project->category ?? 'General' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $statusBadge = match($project->status) {
                                            'active' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300',
                                            'completed' => 'bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300',
                                            'planning' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300',
                                            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $statusBadge }}">
                                        {{ $project->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-700 dark:text-slate-300">
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-people-fill text-slate-400"></i>
                                        {{ $project->members->count() }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-400 text-[11px]">
                                    {{ $project->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.projects.show', $project) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-slate-100 dark:hover:bg-slate-800" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.projects.edit', $project) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-slate-100 dark:hover:bg-slate-800" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($project->status !== 'archived')
                                            <form method="POST" action="{{ route('admin.projects.archive', $project) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-slate-100 dark:hover:bg-slate-800" title="Arsipkan">
                                                    <i class="bi bi-archive"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                              onsubmit="return confirm('Hapus proyek {{ $project->title }} secara permanen?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-slate-100 dark:hover:bg-slate-800" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-slate-400">Tidak ada proyek yang sesuai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($projects->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $projects->links('pagination::tailwind') }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
