@section('title', 'Credit Ledger Management')

<x-app-layout>
    <div class="space-y-6">
        <!-- Header & Action Modal -->
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-lg mb-1 flex items-center gap-2">
                        <i class="bi bi-coin text-amber-500"></i>Credit Ledger & Transactions
                    </h5>
                    <p class="text-stone-400 dark:text-stone-500 text-xs mb-0">Audit riwayat kredit global dan penyesuaian saldo manual mahasiswa</p>
                </div>
                <div>
                    <button type="button" onclick="document.getElementById('adjustModal').classList.remove('hidden')"
                        class="bg-amber-500 hover:bg-amber-600 text-stone-900 font-bold py-2 px-4 rounded-lg text-xs transition-colors flex items-center gap-2 shadow-sm border-0 cursor-pointer">
                        <i class="bi bi-plus-slash-minus"></i>Sesuaikan Saldo Kredit
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <form action="{{ route('admin.credits.index') }}" method="GET" class="mt-6 flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg overflow-hidden w-[260px] shadow-sm">
                    <span class="pl-3 pr-2 text-stone-400"><i class="bi bi-search text-xs"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-1.5 px-1 text-xs" 
                        placeholder="Cari user / alasan...">
                </div>
                <select name="type" class="bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-1.5 text-xs text-stone-700 dark:text-stone-300 focus:outline-none" onchange="this.form.submit()">
                    <option value="all">Semua Tipe</option>
                    <option value="award" {{ request('type') == 'award' ? 'selected' : '' }}>Award (+)</option>
                    <option value="spend" {{ request('type') == 'spend' ? 'selected' : '' }}>Spend (-)</option>
                    <option value="transfer_in" {{ request('type') == 'transfer_in' ? 'selected' : '' }}>Transfer In</option>
                    <option value="transfer_out" {{ request('type') == 'transfer_out' ? 'selected' : '' }}>Transfer Out</option>
                </select>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-stone-50 dark:bg-stone-900/60 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">ID</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">User</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Nominal</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Tipe</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Alasan / Referensi</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40">
                                <td class="px-6 py-3.5 text-xs text-stone-400">#{{ $trx->id }}</td>
                                <td class="px-6 py-3.5">
                                    <div class="text-xs font-bold text-stone-900 dark:text-stone-100">{{ $trx->user?->name ?? 'User Deleted' }}</div>
                                    <div class="text-[11px] text-stone-400">{{ $trx->user?->email }}</div>
                                </td>
                                <td class="px-6 py-3.5 font-bold text-xs {{ $trx->amount >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500' }}">
                                    {{ $trx->amount > 0 ? '+'.$trx->amount : $trx->amount }}
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="rounded px-2 py-0.5 text-[10px] font-bold uppercase {{ $trx->amount >= 0 ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-500' }}">
                                        {{ $trx->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-700 dark:text-stone-300 max-w-[300px] truncate" title="{{ $trx->reason }}">
                                    {{ $trx->reason }}
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-400">
                                    {{ $trx->created_at->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-xs text-stone-400">Belum ada transaksi kredit ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="p-4 border-t border-stone-100 dark:border-stone-800">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Adjust Credit -->
    <div id="adjustModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-stone-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141414] rounded-xl max-w-md w-full border border-stone-200 dark:border-stone-800 shadow-xl overflow-hidden">
            <form action="{{ route('admin.credits.adjust') }}" method="POST">
                @csrf
                <div class="p-5 border-b border-stone-100 dark:border-stone-800">
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm">Penyesuaian Kredit Manual</h5>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Target Mahasiswa</label>
                        <select name="user_id" required class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} (Saldo: {{ $u->credits }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Jumlah Kredit (+ / -)</label>
                        <input type="number" name="amount" required placeholder="Contoh: 50 atau -20" class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Alasan Penyesuaian</label>
                        <textarea name="reason" required rows="3" placeholder="Alasan koreksi saldo..." class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100"></textarea>
                    </div>
                </div>
                <div class="p-4 border-t border-stone-100 dark:border-stone-800 flex justify-end gap-2 bg-stone-50 dark:bg-[#1a1917]">
                    <button type="button" onclick="document.getElementById('adjustModal').classList.add('hidden')" class="px-4 py-1.5 rounded-lg text-xs font-medium text-stone-600 dark:text-stone-400 hover:bg-stone-200 dark:hover:bg-stone-800">Batal</button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-bold bg-amber-500 hover:bg-amber-600 text-stone-900 shadow-sm border-0">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
