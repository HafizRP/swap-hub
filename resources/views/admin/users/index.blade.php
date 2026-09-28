@section('title', 'Manajemen Pengguna')
<x-app-layout>
    <div x-data="{ showRoleModal: false, formAction: '', actionName: '', userDisplayName: '' }" class="space-y-6">

        <!-- Top Header & Search -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Manajemen Pengguna
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Kelola peran akun mahasiswa, hak akses administrator, dan moderasi profil.
                </p>
            </div>

            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-48 sm:w-60 pl-8 pr-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs outline-none focus:ring-2 focus:ring-brand-500"
                           placeholder="Cari nama / email...">
                </div>

                <select name="role" class="px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs outline-none focus:ring-2 focus:ring-brand-500"
                        onchange="this.form.submit()">
                    <option value="all">Semua Peran</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </form>
        </div>

        <!-- Users Table Card -->
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 uppercase font-extrabold text-[10px] bg-slate-50/50 dark:bg-slate-800/50">
                            <th class="py-3 px-4">Pengguna</th>
                            <th class="py-3 px-4">Email / Kontak</th>
                            <th class="py-3 px-4">Kampus & Jurusan</th>
                            <th class="py-3 px-4">Peran</th>
                            <th class="py-3 px-4">Reputasi</th>
                            <th class="py-3 px-4">Terdaftar</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff' }}"
                                             alt="{{ $user->name }}" class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 dark:text-white truncate">{{ $user->name }}</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $user->github_username ? '@' . $user->github_username : '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-400">
                                    <div class="font-medium">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-[10px] text-slate-400">{{ $user->phone }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-400">
                                    <div class="font-semibold text-slate-900 dark:text-white truncate max-w-[160px]">{{ $user->university ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[160px]">{{ $user->major ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->role && $user->role->slug === 'admin' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300' : 'bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300' }}">
                                        {{ $user->role->name ?? 'Student' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-bold text-amber-500">
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-star-fill text-xs"></i>
                                        {{ $user->reputation_points }} CP
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-400 text-[11px]">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.users.show', $user) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-slate-100 dark:hover:bg-slate-800" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-slate-100 dark:hover:bg-slate-800" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            @php
                                                $isUserAdmin = $user->role?->slug === 'admin';
                                                $toggleLabel = $isUserAdmin ? 'Demote ke User' : 'Promote ke Admin';
                                            @endphp
                                            <button type="button"
                                                    @click="formAction = '{{ route('admin.users.toggle-role', $user) }}'; actionName = '{{ $toggleLabel }}'; userDisplayName = '{{ addslashes($user->name) }}'; showRoleModal = true"
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                                                    title="{{ $toggleLabel }}">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-slate-100 dark:hover:bg-slate-800" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-slate-400">Tidak ada data pengguna yang sesuai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $users->links('pagination::tailwind') }}
                </div>
            @endif
        </div>

        <!-- Role Change Confirmation Modal -->
        <div x-show="showRoleModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-md w-full shadow-2xl p-6 space-y-4"
                 @click.outside="showRoleModal = false">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-black text-slate-900 dark:text-white text-base">Konfirmasi Ubah Peran</h3>
                    <button type="button" @click="showRoleModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form :action="formAction" method="POST" class="space-y-4">
                    @csrf
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Anda akan melakukan <span class="font-bold text-brand-600" x-text="actionName"></span> untuk pengguna <span class="font-bold text-slate-900 dark:text-white" x-text="userDisplayName"></span>. Masukkan kata sandi akun admin Anda untuk mengonfirmasi tindakan ini.
                    </p>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Admin</label>
                        <input type="password" name="admin_password" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs outline-none focus:ring-2 focus:ring-brand-500"
                               placeholder="Masukkan kata sandi...">
                    </div>
                    <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="showRoleModal = false"
                                class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
