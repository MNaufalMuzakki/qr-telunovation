<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ $tenant->tenant_name }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Nomor Booth: <span class="font-bold text-red-700 px-2 py-0.5 bg-red-50 rounded-md border border-red-200">{{ $tenant->booth_number }}</span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('tenant.scanner') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-md shadow-cyan-600/20 text-xs sm:text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buka Scanner Kamera
                </a>
                
                <!-- Dropdown / Group Export Excel & CSV -->
                <div class="inline-flex rounded-xl shadow-sm border border-emerald-600/30 overflow-hidden bg-emerald-700 text-white">
                    <a href="{{ route('tenant.export', ['format' => 'xlsx']) }}" title="Download file format Excel .xlsx" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-emerald-700 hover:bg-emerald-800 font-bold text-xs transition border-r border-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Export Excel (.xlsx)</span>
                    </a>
                    <a href="{{ route('tenant.export', ['format' => 'csv']) }}" title="Download file format CSV .csv" class="inline-flex items-center px-2.5 py-2.5 bg-emerald-800 hover:bg-emerald-900 font-extrabold text-[11px] transition">
                        .CSV
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Stats Card -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Total Lead Visitors</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalLeads) }}</h3>
                        <p class="text-xs text-emerald-600 font-semibold mt-1">Real-time sync dari scanner</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-700 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Nomor Booth</p>
                        <h3 class="text-3xl font-extrabold text-red-700 mt-1">{{ $tenant->booth_number }}</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Telkom University Exhibition</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center text-cyan-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-slate-900 to-red-950 p-6 rounded-3xl text-white shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-extrabold text-rose-300 uppercase tracking-wider">Aksi Cepat Scanner</p>
                        <h4 class="text-base font-bold text-white mt-1">Scan dari Berbagai HP</h4>
                        <a href="{{ route('tenant.scanner') }}" class="inline-block text-xs text-cyan-400 hover:text-cyan-300 font-semibold underline mt-1">Buka Web Scanner Kamera &rarr;</a>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Leads Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Daftar Leads Pengunjung</h3>
                        <p class="text-xs text-slate-500">Semua pengunjung yang telah berhasil di-scan oleh tim booth Anda.</p>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('tenant.dashboard') }}" class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, no HP..."
                            class="px-3.5 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none w-48 sm:w-64">
                        <button type="submit" class="px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition">
                            Cari
                        </button>
                        @if($search)
                            <a href="{{ route('tenant.dashboard') }}" class="text-xs text-slate-400 hover:text-slate-600 underline">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Nama Pengunjung</th>
                                <th class="px-6 py-4">Kontak (Email & WhatsApp)</th>
                                <th class="px-6 py-4">Waktu Scan</th>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                        Belum ada pengunjung yang di-scan. Buka Web Scanner untuk mulai memindai QR pengunjung!
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
