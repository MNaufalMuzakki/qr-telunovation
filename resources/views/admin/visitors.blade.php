<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Manajemen Master Data Pengunjung') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">Super Admin Control Panel: Kelola data pengunjung, edit info, dan audit riwayat stempel.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2 rounded-xl shadow-sm font-semibold transition">
                &larr; Dashboard Admin
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{ editModalOpen: false, editId: null, editName: '', editEmail: '', editPhone: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-800 text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Main Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Daftar Pengunjung Terdaftar</h3>
                        <p class="text-xs text-slate-500">Total {{ number_format($visitors->total()) }} pengunjung terdaftar di sistem.</p>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.visitors.index') }}" class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, whatsapp..."
                            class="px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none w-56 sm:w-72">
                        <button type="submit" class="px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition">
                            Cari
                        </button>
                        @if($search)
                            <a href="{{ route('admin.visitors.index') }}" class="text-xs text-slate-400 hover:text-slate-600 underline">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Nama Pengunjung</th>
                                <th class="px-6 py-4">Email & WhatsApp</th>
                                <th class="px-6 py-4">Stempel Dikumpulkan</th>
                                <th class="px-6 py-4">Status Reward</th>
                                <th class="px-6 py-4 text-right">Aksi Super Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($visitors as $index => $visitor)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                        {{ $visitors->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-slate-900">
                                        {{ $visitor->name }}
                                        <div class="text-[10px] text-slate-400 font-mono font-normal">UUID: {{ substr($visitor->qr_code_id, 0, 8) }}...</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-semibold text-slate-800">{{ $visitor->email }}</div>
                                        <div class="text-xs text-slate-500 font-mono">{{ $visitor->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-xs font-extrabold rounded-md bg-slate-100 text-slate-800 border border-slate-200">
                                            {{ $visitor->visits_count }} Booth
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($visitor->is_reward_claimed)
                                            <span class="px-2 py-0.5 text-[10px] font-extrabold text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200">
                                                HADIAH TERKLAIM
                                            </span>
                                        @elseif($visitor->visits_count >= 3)
                                            <span class="px-2 py-0.5 text-[10px] font-extrabold text-amber-700 bg-amber-100 rounded-full border border-amber-200">
                                                SIAP KLAIM
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] font-semibold text-slate-500 bg-slate-100 rounded-full">
                                                PROSESS
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button @click="editModalOpen = true; editId = {{ $visitor->id }}; editName = '{{ addslashes($visitor->name) }}'; editEmail = '{{ addslashes($visitor->email) }}'; editPhone = '{{ addslashes($visitor->phone) }}';"
                                            class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold hover:underline">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.visitors.delete', $visitor->id) }}" method="POST" onsubmit="return confirm('Hapus permanen pengunjung ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-600 hover:text-rose-900 font-semibold hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                        Belum ada data pengunjung terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($visitors->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $visitors->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- MODAL EDIT PENGUNJUNG -->
        <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm" x-cloak>
            <div @click.away="editModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-100">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">Edit Data Pengunjung</h3>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
                </div>

                <form :action="`/admin/visitors/${editId}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="editName" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="editEmail" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" x-model="editPhone" required
                            class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-700 hover:bg-red-800 text-white text-xs font-bold rounded-xl shadow-md transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
