<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                    {{ $tenant->tenant_name }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Booth: <span class="font-bold text-indigo-600 px-2 py-0.5 bg-indigo-50 rounded-md border border-indigo-100">{{ $tenant->booth_number }}</span>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('tenant.scanner') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-500/20 text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buka Scanner Kamera
                </a>
                <a href="{{ route('tenant.export') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md shadow-emerald-500/20 text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Excel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Stats Card -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Leads Dikumpulkan</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 mt-1">{{ number_format($totalLeads) }}</h3>
                        <p class="text-xs text-emerald-600 font-semibold mt-1">Data kontak pengunjung terverifikasi</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Booth Number</p>
                        <h3 class="text-3xl font-extrabold text-indigo-600 mt-1">{{ $tenant->booth_number }}</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">Nomor lokasi pameran</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-900 to-slate-900 p-6 rounded-2xl text-white shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-indigo-200 uppercase tracking-wider">Quick Action</p>
                        <h4 class="text-lg font-bold text-white mt-1">Scan di Ponsel Anda</h4>
                        <a href="{{ route('tenant.scanner') }}" class="inline-block text-xs text-indigo-300 font-semibold underline mt-1">Buka kamera scanner &rarr;</a>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Leads Table Card -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <!-- Search & Header Bar -->
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Daftar Pengunjung Terdata</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Semua data prospek yang telah berhasil di-scan oleh tim booth Anda.</p>
                    </div>

                    <form method="GET" action="{{ route('tenant.dashboard') }}" class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama, email, no telp..."
                            class="px-4 py-2 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none w-64">
                        <button type="submit" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                            Cari
                        </button>
                        @if($search)
                            <a href="{{ route('tenant.dashboard') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                        @endif
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50/75 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Nama Pengunjung</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">No. WhatsApp / HP</th>
                                <th class="px-6 py-4">Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($visits as $index => $visit)
                                <tr class="hover:bg-indigo-50/30 transition">
                                    <td class="px-6 py-4 font-mono text-xs text-gray-400">
                                        {{ $visits->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900">
                                        {{ $visit->visitor->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $visit->visitor->email ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $visit->visitor->phone) }}" target="_blank" class="text-indigo-600 hover:underline inline-flex items-center gap-1 font-sans">
                                            <span>{{ $visit->visitor->phone ?? '-' }}</span>
                                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $visit->scanned_at ? $visit->scanned_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                                        Belum ada pengunjung yang di-scan. Mulai scan pengunjung di booth Anda sekarang!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($visits->hasPages())
                    <div class="p-6 border-t border-gray-100 bg-gray-50/50">
                        {{ $visits->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
