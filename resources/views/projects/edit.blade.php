@section('title', 'Edit Proyek - ' . $project->title)
<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-center">
            <div class="w-full max-w-3xl">
                <!-- Header -->
                <div class="mb-6">
                    <nav class="mb-2">
                        <ol class="flex items-center gap-2 text-xs font-bold text-stone-500 dark:text-stone-400">
                            <li>
                                <a href="{{ route('projects.show', $project) }}" class="no-underline text-stone-600 dark:text-stone-400 hover:text-teal-600 dark:hover:text-teal-400 transition-colors inline-flex items-center gap-1">
                                    <i class="bi bi-arrow-left"></i>
                                    <span>Kembali ke Detail Proyek</span>
                                </a>
                            </li>
                            <li>/</li>
                            <li class="text-teal-600 dark:text-teal-400">Edit</li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-black text-stone-900 dark:text-stone-100 tracking-tight mb-1">
                        Edit Detail Proyek
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-500 dark:text-stone-400 mb-0">
                        Perbarui konfigurasi, jadwal, atau integrasi repositori proyek Anda.
                    </p>
                </div>
 
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-teal-500 via-teal-600 to-emerald-500"></div>
                    <div class="p-4 md:p-6">
                        <form method="POST" action="{{ route('projects.update', $project) }}">
                            @csrf
                            @method('PUT')
 
                            <!-- Title -->
                            <div class="mb-5">
                                <label for="title" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">
                                    Judul Proyek <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}" required
                                    class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-sm text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
 
                            <!-- Description -->
                            <div class="mb-5">
                                <label for="description" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">
                                    Deskripsi Proyek <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="6" required
                                    class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-3 text-sm text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">{{ old('description', $project->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
 
                            <!-- GitHub Integration -->
                            <div class="mb-6 p-4 rounded-lg bg-stone-50 dark:bg-[#1a1917] border border-stone-200/80 dark:border-stone-800">
                                <div class="flex items-center gap-2.5 mb-4 text-stone-800 dark:text-stone-100">
                                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-700 flex items-center justify-center">
                                        <i class="bi bi-github text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-xs uppercase tracking-wider mb-0 text-stone-900 dark:text-stone-100">Integrasi Repositori GitHub</h3>
                                        <p class="text-[11px] text-stone-400 dark:text-stone-500 mb-0">Hubungkan repositori untuk pelacakan aktivitas tim</p>
                                    </div>
                                </div>
 
                                @if(count($repositories) > 0)
                                    <label for="github_repo_url" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase">Pilih Repositori</label>
                                    <select id="github_repo_url" name="github_repo_url"
                                        onchange="document.getElementById('github_repo_name').value = this.options[this.selectedIndex].text"
                                        class="w-full bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 mb-4 transition-all">
                                        @foreach($repositories as $repo)
                                            <option value="{{ $repo['html_url'] }}" {{ old('github_repo_url', $project->github_repo_url) == $repo['html_url'] ? 'selected' : '' }}>
                                                {{ $repo['full_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="github_repo_name" name="github_repo_name"
                                        value="{{ old('github_repo_name', $project->github_repo_name) }}">
 
                                    <!-- Webhook Status & Reconnect -->
                                    @if($project->github_repo_url)
                                        <div class="flex flex-col sm:flex-row justify-between sm:items-center bg-white dark:bg-[#141414] p-3.5 rounded-xl border border-stone-200 dark:border-stone-700 gap-3">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Status Webhook:</span>
                                                @php
                                                    $whColor = $project->github_webhook_status === 'active' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60';
                                                @endphp
                                                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border {{ $whColor }}">
                                                    {{ ucfirst($project->github_webhook_status ?? 'Not Setup') }}
                                                </span>
                                            </div>
                                            <button form="reconnectWebhookForm" type="submit"
                                                class="border border-teal-200 dark:border-teal-800 text-teal-600 dark:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-950/40 font-medium py-1.5 px-3 rounded-lg text-xs transition-colors shrink-0 flex items-center justify-center gap-1 cursor-pointer bg-transparent">
                                                <i class="bi bi-arrow-repeat"></i>
                                                <span>Sambungkan Ulang Webhook</span>
                                            </button>
                                        </div>
                                    @endif
 
                                @else
                                    <div class="bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-300 rounded-xl p-3 text-xs border border-amber-200 dark:border-amber-800/60 flex items-center justify-between">
                                        <span class="flex items-center gap-1.5">
                                            <i class="bi bi-exclamation-circle"></i>
                                            <span>Belum ada repositori GitHub yang terdeteksi.</span>
                                        </span>
                                        <a href="{{ route('profile.edit') }}" class="font-bold underline text-inherit">Hubungkan Akun GitHub</a>
                                    </div>
                                @endif
                                <x-input-error :messages="$errors->get('github_repo_url')" class="mt-2" />
                            </div>
 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <!-- Category -->
                                <div>
                                    <label for="category" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Kategori</label>
                                    <select id="category" name="category" required
                                        class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                                        <option value="Development" {{ old('category', $project->category) == 'Development' ? 'selected' : '' }}>Development</option>
                                        <option value="Design" {{ old('category', $project->category) == 'Design' ? 'selected' : '' }}>Design</option>
                                        <option value="Marketing" {{ old('category', $project->category) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="Research" {{ old('category', $project->category) == 'Research' ? 'selected' : '' }}>Research</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>
 
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Status Proyek</label>
                                    <select id="status" name="status" required
                                        class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                                        <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning</option>
                                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
 
                                <!-- Start Date -->
                                <div>
                                    <label for="start_date" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Tanggal Mulai</label>
                                    <input type="date" id="start_date" name="start_date"
                                        value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                                        class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>
 
                                <!-- End Date -->
                                <div>
                                    <label for="end_date" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Tanggal Berakhir</label>
                                    <input type="date" id="end_date" name="end_date"
                                        value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                                        class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                </div>
                            </div>
 
                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-6 border-t border-stone-100 dark:border-stone-800">
                                <a href="{{ route('projects.show', $project) }}"
                                    class="py-2.5 px-5 rounded-xl border border-stone-200 dark:border-stone-700 text-stone-600 dark:text-stone-400 hover:bg-stone-50 dark:hover:bg-stone-800 font-medium text-xs transition-colors no-underline">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="py-2.5 px-6 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-medium text-xs shadow-md shadow-teal-500/20 transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <!-- External Form for Webhook Reconnect -->
    <form id="reconnectWebhookForm" action="{{ route('projects.webhook.reconnect', $project) }}" method="POST" class="hidden">
        @csrf
    </form>
</x-app-layout>