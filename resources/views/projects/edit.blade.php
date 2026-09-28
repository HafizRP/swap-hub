@section('title', 'Edit Proyek - ' . $project->title)
<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-brand-600 mb-1">
                    <i class="bi bi-arrow-left"></i> Kembali ke Detail Proyek
                </a>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Edit Detail Proyek
                </h1>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300">
                Pengaturan Proyek
            </span>
        </div>

        <div class="p-6 sm:p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-6">
            <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Section: Informasi Utama -->
                <div class="space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                        1. Informasi Utama
                    </h3>

                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Judul Proyek <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select id="category" name="category" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                                <option value="Development" {{ old('category', $project->category) == 'Development' ? 'selected' : '' }}>Development</option>
                                <option value="Design" {{ old('category', $project->category) == 'Design' ? 'selected' : '' }}>Design</option>
                                <option value="Marketing" {{ old('category', $project->category) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Research" {{ old('category', $project->category) == 'Research' ? 'selected' : '' }}>Research</option>
                                <option value="Mobile" {{ old('category', $project->category) == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                                <option value="AI & Data" {{ old('category', $project->category) == 'AI & Data' ? 'selected' : '' }}>AI & Data</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-1" />
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Status Proyek <span class="text-rose-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                                <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning</option>
                                <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-1" />
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Deskripsi Proyek <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="5" required
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none leading-relaxed">{{ old('description', $project->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>
                </div>

                <!-- Section: GitHub Integration -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-600 dark:text-brand-400 flex items-center gap-2">
                        <i class="bi bi-github"></i>
                        <span>2. Integrasi GitHub Repository</span>
                    </h3>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/50 space-y-3">
                        @if(count($repositories) > 0)
                            <div>
                                <label for="github_repo_url" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pilih Repository</label>
                                <select id="github_repo_url" name="github_repo_url"
                                        onchange="document.getElementById('github_repo_name').value = this.options[this.selectedIndex].text"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-500 outline-none">
                                    <option value="">Pilih repository (Opsional)</option>
                                    @foreach($repositories as $repo)
                                        <option value="{{ $repo['html_url'] }}" {{ old('github_repo_url', $project->github_repo_url) == $repo['html_url'] ? 'selected' : '' }}>
                                            {{ $repo['full_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="github_repo_name" name="github_repo_name"
                                       value="{{ old('github_repo_name', $project->github_repo_name) }}">
                            </div>

                            @if($project->github_repo_url)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 gap-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Webhook:</span>
                                        @php
                                            $whStyle = $project->github_webhook_status === 'active'
                                                ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                                : 'bg-amber-500/10 text-amber-600 dark:text-amber-400';
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $whStyle }}">
                                            {{ ucfirst($project->github_webhook_status ?? 'Not Setup') }}
                                        </span>
                                    </div>
                                    <button form="reconnectWebhookForm" type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-50 hover:bg-brand-100 dark:bg-brand-950/40 text-brand-700 dark:text-brand-300 text-xs font-bold transition-colors">
                                        <i class="bi bi-arrow-repeat"></i>
                                        <span>Sinkronkan Ulang Webhook</span>
                                    </button>
                                </div>
                            @endif
                        @else
                            <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300 text-xs flex items-center justify-between">
                                <span>Akun GitHub belum terhubung atau tidak ada repository ditemukan.</span>
                                <a href="{{ route('profile.edit') }}" class="underline font-bold">Hubungkan GitHub</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                    <a href="{{ route('projects.show', $project) }}"
                       class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 text-slate-700 dark:text-slate-300 text-xs font-bold transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- External Form for Webhook Reconnect -->
    <form id="reconnectWebhookForm" action="{{ route('projects.webhook.reconnect', $project) }}" method="POST" class="hidden">
        @csrf
    </form>
</x-app-layout>
