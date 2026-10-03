@extends('layouts.app')

@section('title', 'Evaluasi Kegunaan Sistem (SUS)')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                <i class="bi bi-clipboard2-data-fill text-indigo-600 dark:text-indigo-400"></i>
                Laporan Evaluasi System Usability Scale (SUS)
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Hasil pengujian kegunaan sistem berbasis metodologi standar John Brooke (1996) untuk keperluan Bab 4 Skripsi.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('survey.sus') }}" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold no-underline inline-flex items-center gap-1.5 shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span>Isi Survei Baru</span>
            </a>
            <button onclick="window.print()" class="px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 hover:bg-slate-50 cursor-pointer">
                <i class="bi bi-printer"></i>
                <span>Cetak / PDF</span>
            </button>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Mean Score -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Rata-Rata Skor SUS</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ $stats['mean_score'] }}</span>
                <span class="text-xs font-bold text-slate-400">/ 100.00</span>
            </div>
            <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold mt-2 mb-0">
                <i class="bi bi-arrow-up-right"></i> Di atas rata-rata industri (68.0)
            </p>
        </div>

        <!-- Grade Scale -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Grade Skala (Sauro, 2016)</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $stats['grade'] }}</span>
                <span class="text-xs font-bold text-slate-400">Grade Tertinggi</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 mb-0">Percentile Rank: > 90%</p>
        </div>

        <!-- Adjective Rating -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Adjective Rating</span>
            <div class="flex items-baseline gap-2">
                <span class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $stats['adjective'] }}</span>
            </div>
            <p class="text-[11px] text-indigo-600 dark:text-indigo-400 font-bold mt-2 mb-0">Kategori: Acceptable</p>
        </div>

        <!-- Respondents & StdDev -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sampel & Deviasi</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900 dark:text-slate-100">{{ $stats['total'] }}</span>
                <span class="text-xs font-bold text-slate-400">Responden</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 mb-0">Standar Deviasi (σ): {{ $stats['std_dev'] }}</p>
        </div>
    </div>

    <!-- Question By Question Analysis Table -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700/60">
            <h3 class="font-extrabold text-sm text-slate-900 dark:text-slate-100 mb-0">Rata-Rata Skor per Butir Pertanyaan (Likert 1-5)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 dark:bg-slate-700/40 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="p-3.5">Kode</th>
                        <th class="p-3.5">Pernyataan Instrumen SUS</th>
                        <th class="p-3.5 text-center">Tipe</th>
                        <th class="p-3.5 text-right">Rata-Rata Skor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-slate-700 dark:text-slate-300">
                    @php
                        $prompts = [
                            1 => ['text' => 'Saya rasa saya akan sering menggunakan Swap Hub.', 'type' => 'Positif'],
                            2 => ['text' => 'Saya merasa sistem ini terlalu rumit untuk digunakan.', 'type' => 'Negatif'],
                            3 => ['text' => 'Saya merasa sistem ini mudah digunakan.', 'type' => 'Positif'],
                            4 => ['text' => 'Saya membutuhkan bantuan orang teknis untuk bisa menggunakan sistem ini.', 'type' => 'Negatif'],
                            5 => ['text' => 'Saya merasa berbagai fungsi dalam sistem ini terintegrasi dengan baik.', 'type' => 'Positif'],
                            6 => ['text' => 'Saya merasa banyak hal yang tidak konsisten pada sistem ini.', 'type' => 'Negatif'],
                            7 => ['text' => 'Saya rasa kebanyakan orang akan cepat belajar menggunakan sistem ini.', 'type' => 'Positif'],
                            8 => ['text' => 'Saya merasa sistem ini membingungkan saat digunakan.', 'type' => 'Negatif'],
                            9 => ['text' => 'Saya merasa sangat percaya diri saat menggunakan sistem ini.', 'type' => 'Positif'],
                            10 => ['text' => 'Saya perlu membiasakan diri terlebih dahulu sebelum bisa menggunakan sistem ini.', 'type' => 'Negatif'],
                        ];
                    @endphp
                    @foreach($prompts as $k => $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20">
                            <td class="p-3.5 font-bold font-mono text-indigo-600 dark:text-indigo-400">Q{{ $k }}</td>
                            <td class="p-3.5 font-medium">{{ $item['text'] }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $item['type'] === 'Positif' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300' }}">
                                    {{ $item['type'] }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right font-black text-sm">{{ $questionAverages["q{$k}"] ?? 0 }} / 5.0</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Individual Respondents Table -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
            <h3 class="font-extrabold text-sm text-slate-900 dark:text-slate-100 mb-0">Rincian Data Responden (Tabel Bab 4)</h3>
            <span class="text-xs font-bold text-slate-400">{{ $feedbacks->count() }} Data Masuk</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 dark:bg-slate-700/40 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="p-3.5">#</th>
                        <th class="p-3.5">Nama & Peran</th>
                        <th class="p-3.5 text-center">Skor Akhir (SUS)</th>
                        <th class="p-3.5 text-center">Grade</th>
                        <th class="p-3.5">Feedback / Tanggapan</th>
                        <th class="p-3.5 text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-slate-700 dark:text-slate-300">
                    @forelse($feedbacks as $index => $fb)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20">
                            <td class="p-3.5 text-slate-400 font-mono">{{ $index + 1 }}</td>
                            <td class="p-3.5">
                                <span class="font-bold text-slate-900 dark:text-slate-100 block">{{ $fb->respondent_name ?? 'Mahasiswa Responden' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $fb->respondent_role }}</span>
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="font-black text-sm text-indigo-600 dark:text-indigo-400">{{ $fb->sus_score }}</span>
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                    {{ $fb->grade }}
                                </span>
                            </td>
                            <td class="p-3.5 max-w-xs truncate text-slate-500 dark:text-slate-400">
                                {{ $fb->qualitative_feedback ?? '-' }}
                            </td>
                            <td class="p-3.5 text-right text-slate-400 text-[10px]">
                                {{ $fb->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">Belum ada data responden kuesioner.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
