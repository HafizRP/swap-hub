@extends('layouts.app')

@section('title', 'Kuesioner Uji Kegunaan Sistem (SUS)')

@section('content')
<div class="max-w-4xl mx-auto py-4">
    <!-- Header -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 p-6 md:p-8 shadow-sm mb-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl">
                <i class="bi bi-clipboard2-check-fill"></i>
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100">Kuesioner System Usability Scale (SUS)</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Evaluasi akademik tingkat kegunaan dan penerimaan sistem Swap Hub (John Brooke, 1996)</p>
            </div>
        </div>
        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-0 bg-slate-50 dark:bg-slate-700/40 p-3.5 rounded-xl border border-slate-200/60 dark:border-slate-700/60">
            <strong>Petunjuk Pengisian:</strong> Berikan tanggapan Anda secara objektif berdasarkan pengalaman nyata menggunakan fitur-fitur Swap Hub. Skala penilaian: <strong>1 = Sangat Tidak Setuju</strong> hingga <strong>5 = Sangat Setuju</strong>.
        </p>
    </div>

    <!-- Questionnaire Form -->
    <form action="{{ route('survey.sus.store') }}" method="POST">
        @csrf

        <!-- Respondent Role -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 p-6 shadow-sm mb-6">
            <h3 class="font-extrabold text-sm text-slate-900 dark:text-slate-100 mb-4">Profil Responden</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">Nama Responden (Opsional)</label>
                    <input type="text" name="respondent_name" value="{{ old('respondent_name', auth()->user()->name) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-xs text-slate-800 dark:text-slate-100 outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1.5">Peran / Jurusan Mahasiswa <span class="text-rose-500">*</span></label>
                    <input type="text" name="respondent_role" required value="{{ old('respondent_role', auth()->user()->major ?? 'Mahasiswa Informatika') }}" placeholder="Contoh: Mahasiswa Teknik Informatika" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-xs text-slate-800 dark:text-slate-100 outline-none focus:border-indigo-500">
                </div>
            </div>
        </div>

        <!-- 10 Questions -->
        <div class="space-y-4 mb-6">
            @foreach($questions as $num => $question)
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-5 shadow-sm">
                    <div class="flex items-start gap-3 mb-3">
                        <span class="w-6 h-6 rounded-lg bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
                            {{ $num }}
                        </span>
                        <p class="text-xs md:text-sm font-bold text-slate-800 dark:text-slate-200 mb-0 leading-relaxed">
                            {{ $question }}
                        </p>
                    </div>

                    <!-- Likert Radio Scale 1 - 5 -->
                    <div class="grid grid-cols-5 gap-2 pt-2 border-t border-slate-100 dark:border-slate-700/50 text-center">
                        @for($val = 1; $val <= 5; $val++)
                            <label class="cursor-pointer group flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                                <input type="radio" name="q{{ $num }}" value="{{ $val }}" required {{ old("q{$num}", 4) == $val ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 accent-indigo-600 cursor-pointer">
                                <span class="text-xs font-extrabold text-slate-700 dark:text-slate-300">{{ $val }}</span>
                                <span class="text-[10px] text-slate-400 hidden sm:block">
                                    @if($val === 1) Sangat Tidak Setuju
                                    @elseif($val === 5) Sangat Setuju
                                    @endif
                                </span>
                            </label>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Feedback & Submit -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 p-6 shadow-sm mb-6">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                Saran atau Masukan Tambahan untuk Pengembangan Platform:
            </label>
            <textarea name="qualitative_feedback" rows="3" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-xs text-slate-800 dark:text-slate-100 outline-none focus:border-indigo-500 placeholder-slate-400" placeholder="Tuliskan pengalaman atau kendala yang Anda temui..."></textarea>
            
            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/25 transition-all cursor-pointer">
                    Kirim Evaluasi SUS
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
