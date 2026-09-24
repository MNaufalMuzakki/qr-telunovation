<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase bg-red-100 text-red-800 rounded-md">
                        Super Admin Mode
                    </span>
                    <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                        Dashboard Leads: {{ $tenant->tenant_name }}
                    </h2>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Nomor Booth: <span class="font-bold text-red-700 px-2 py-0.5 bg-red-50 rounded-md border border-red-200">{{ $tenant->booth_number }}</span>
                    &bull; PIC: <span class="font-semibold text-slate-700">{{ $tenant->user->name ?? '-' }}</span> ({{ $tenant->user->email ?? '-' }})
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.tenants.index') }}" class="px-3.5 py-2 text-xs font-semibold bg-white border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition">
                    &larr; Kembali ke Daftar Tenant
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-800 text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Stats Card -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Total Leads Booth Ini</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalLeads) }}</h3>
                        <p class="text-xs text-emerald-600 font-semibold mt-1">Pengunjung ter-scan</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-700 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Nomor Booth</p>
                        <h3 class="text-3xl font-extrabold text-red-700 mt-1">{{ $tenant->booth_number }}</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Status: Aktif Exhibition</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center text-cyan-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-slate-900 to-red-950 p-6 rounded-3xl text-white shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-extrabold text-rose-300 uppercase tracking-wider">Kontrol Super Admin</p>
                        <h4 class="text-sm font-bold text-white mt-1">Audit Data Leads</h4>
                        <p class="text-[11px] text-slate-300 mt-0.5">Admin berhak memantau & mengedit record scan ini.</p>
                    </div>
                </div>
            </div>

            <!-- Leads Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Daftar Visitor Scanned oleh {{ $tenant->tenant_name }}</h3>
                        <p class="text-xs text-slate-500">Super Admin memiliki akses penuh untuk menghapus atau meninjau scan.</p>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.tenants.leads', $tenant->id) }}" class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, no HP..."
                            class="px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none w-48 sm:w-64">
                        <button type="submit" class="px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition">
                            Cari
                        </button>
                        @if($search)
                            <a href="{{ route('admin.tenants.leads', $tenant->id) }}" class="text-xs text-slate-400 hover:text-slate-600 underline">Reset</a>
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
                                <th class="px-6 py-4">Waktu Scan</th>
                                <th class="px-6 py-4 text-right">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($visits as $index => $visit)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-xs text-slate-400 font-mono">
                                        {{ $visits->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-slate-900">
                                        {{ $visit->visitor->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-semibold text-slate-800">{{ $visit->visitor->email ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 font-mono">{{ $visit->visitor->phone ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono text-slate-500">
                                        {{ $visit->scanned_at ? $visit->scanned_at->format('d M Y, H:i:s') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.visits.delete', $visit->id) }}" method="POST" onsubmit="return confirm('Hapus record scan ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold hover:underline">
                                                Hapus Scan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                        Belum ada data scan pengunjung di tenant ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($visits->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $visits->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
