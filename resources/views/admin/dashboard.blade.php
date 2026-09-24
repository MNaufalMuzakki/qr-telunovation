<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                    {{ __('Admin Event Dashboard & Real-Time Analytics') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Pantau arus pengunjung, aktivitas scan booth, dan penukaran hadiah secara real-time.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.tenants.index') }}" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 font-bold rounded-xl border border-gray-200 text-xs shadow-sm transition">
                    Kelola Tenant
                </a>
                <a href="{{ route('admin.redemption') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-500/20 transition">
                    Redemption Station
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pengunjung</p>
                    <h3 class="text-3xl font-black text-gray-900 mt-2">{{ number_format($totalVisitors) }}</h3>
                    <p class="text-xs text-indigo-600 font-semibold mt-1">Terdaftar dalam sistem</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Interaksi Scan</p>
                    <h3 class="text-3xl font-black text-indigo-600 mt-2">{{ number_format($totalScans) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Stempel digital tercatat</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tingkat Keaktifan</p>
                    <h3 class="text-3xl font-black text-emerald-600 mt-2">{{ $percentageActive }}%</h3>
                    <p class="text-xs text-emerald-700 mt-1">{{ number_format($activeVisitors) }} pengunjung sudah keliling</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Hadiah Diklaim</p>
                    <h3 class="text-3xl font-black text-purple-600 mt-2">{{ number_format($totalRewardsClaimed) }}</h3>
                    <p class="text-xs text-purple-700 mt-1">Di Redemption Station</p>
                </div>
            </div>

            <!-- Leaderboard Tenant -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">Leaderboard Booth Teramai (Top 10)</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Peringkat tenant berdasarkan jumlah scan pengunjung terbanyak.</p>
                    </div>
                    <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-lg flex items-center gap-1.5">
                        <span>🏆</span> Real-Time Rank
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Peringkat</th>
                                <th class="px-6 py-4">Nama Tenant</th>
                                <th class="px-6 py-4">Nomor Booth</th>
                                <th class="px-6 py-4 text-right">Total Leads / Scan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($leaderboard as $index => $tenant)
                                <tr class="hover:bg-indigo-50/20 transition">
                                    <td class="px-6 py-4 font-bold text-sm">
                                        @if($index == 0)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-white font-extrabold shadow-sm">1</span>
                                        @elseif($index == 1)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-300 text-slate-800 font-extrabold shadow-sm">2</span>
                                        @elseif($index == 2)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white font-extrabold shadow-sm">3</span>
                                        @else
                                            <span class="text-gray-400 font-mono ml-2.5">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-gray-900">
                                        {{ $tenant->tenant_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-md font-bold text-xs">
                                            {{ $tenant->booth_number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-indigo-600 text-base">
                                        {{ number_format($tenant->visits_count) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">
                                        Belum ada aktivitas scan tenant yang terekam.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
