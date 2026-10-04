<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4" x-data="{ showTransferModal: false }">
    <!-- Header & Balance -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2 bg-gradient-to-r from-teal-700 via-teal-800 to-stone-900 p-5 rounded-xl text-white shadow-card flex flex-col justify-between">
            <div>
                <span class="text-xs uppercase tracking-wider font-semibold opacity-80">Time-Bank Credit Balance</span>
                <h1 class="text-4xl font-extrabold mt-1">{{ number_format($user->credits) }} <span class="text-xl font-medium">Credits</span></h1>
                <p class="text-xs text-teal-100 mt-2 max-w-lg">
                    Gunakan kredit untuk meminta code review bounty, atau dapatkan kredit dari kontribusi skill swap & bantuan sesama mahasiswa.
                </p>
            </div>
            <div class="mt-6 flex gap-3">
                <button @click="showTransferModal = true" class="px-4 py-2 bg-white text-teal-800 font-bold rounded-lg text-xs hover:bg-teal-50 transition-colors shadow-sm border-0 cursor-pointer flex items-center gap-1.5">
                    <i class="bi bi-send text-xs"></i> Transfer Kredit
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 flex flex-col justify-center space-y-3">
            <h3 class="font-bold text-stone-900 dark:text-stone-100 text-sm mb-0">Informasi Time-Bank</h3>
            <p class="text-xs text-stone-500 dark:text-stone-400 mb-0">
                1 Credit setara dengan 1 jam kontribusi atau bantuan teknis peer-to-peer.
            </p>
            <div class="pt-2 border-t border-stone-100 dark:border-stone-800 flex justify-between text-xs font-semibold">
                <span class="text-stone-500">Status Akun:</span>
                <span class="text-emerald-600 dark:text-emerald-400">Aktif</span>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <!-- Transaction History -->
    <div class="bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800">
        <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-4">Riwayat Transaksi Kredit</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-stone-200 dark:border-stone-800 text-[11px] font-bold uppercase text-stone-400">
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4">Deskripsi</th>
                        <th class="py-3 px-4 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-stone-800 text-xs">
                    @forelse($transactions as $trx)
                        <tr wire:key="trx-{{ $trx->id }}">
                            <td class="py-3.5 px-4 text-stone-500">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase {{ $trx->amount > 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' }}">
                                    {{ $trx->type }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-stone-800 dark:text-stone-200">{{ $trx->description ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-right font-bold {{ $trx->amount > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $trx->amount > 0 ? '+' : '' }}{{ $trx->amount }} CR
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-stone-400 text-xs">Belum ada riwayat transaksi kredit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Transfer Modal -->
    <div x-show="showTransferModal" x-cloak class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141414] w-full max-w-md p-4 rounded-xl shadow-xl space-y-4 border border-stone-200 dark:border-stone-800">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-stone-900 dark:text-stone-100 mb-0">Transfer Kredit Time-Bank</h3>
                <button @click="showTransferModal = false" class="text-stone-400 hover:text-stone-600 border-0 bg-transparent cursor-pointer"><i class="bi bi-x-lg"></i></button>
            </div>
            <form wire:submit.prevent="transferCredits" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Email Penerima</label>
                    <input type="email" wire:model="recipientEmail" placeholder="mahasiswa@campus.ac.id" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                    @error('recipientEmail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Jumlah Kredit (Maks: {{ $user->credits }})</label>
                    <input type="number" min="1" max="{{ $user->credits }}" wire:model="transferAmount" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                    @error('transferAmount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Catatan Transfer</label>
                    <input type="text" wire:model="transferDescription" placeholder="mis. Bantuan tugas koding" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-stone-100 dark:border-stone-800">
                    <button type="button" @click="showTransferModal = false" class="px-4 py-2 text-xs text-stone-500 hover:text-stone-700 border-0 bg-transparent cursor-pointer">Batal</button>
                    <button type="submit" @click="showTransferModal = false" class="px-4 py-2 text-xs bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium border-0 cursor-pointer shadow-sm">Kirim Kredit</button>
                </div>
            </form>
        </div>
    </div>
</div>
