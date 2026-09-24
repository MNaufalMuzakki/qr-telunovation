<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Pass - {{ $visitor->name }} - Telkom University BTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-red-950 min-h-screen flex items-center justify-center p-4 selection:bg-red-600 selection:text-white">

    <div class="w-full max-w-sm bg-slate-900/95 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 text-white shadow-2xl relative overflow-hidden my-6">
        <!-- Background Glow Accent -->
        <div class="absolute -top-20 -right-20 w-40 h-40 bg-red-600/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-amber-500/20 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Header Card -->
        <div class="flex items-center justify-between border-b border-slate-800 pb-4 relative">
            <div>
                <span class="text-[10px] tracking-widest font-extrabold uppercase text-rose-400">Digital Visitor Pass</span>
                <h1 class="text-base font-black tracking-tight text-white">TELUNOVATION 2026</h1>
            </div>
            @if($visitor->is_reward_claimed)
                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center gap-1 shadow-sm">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Reward Claimed
                </span>
            @else
                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30">
                    Active Pass
                </span>
            @endif
        </div>

        @if(session('success'))
            <div class="mt-4 p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-300 text-xs text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="mt-4 p-3 bg-cyan-500/10 border border-cyan-500/30 rounded-xl text-cyan-300 text-xs text-center font-semibold">
                {{ session('info') }}
            </div>
        @endif

        <!-- QR Code Box (Centerpiece) -->
        <div class="my-5 bg-white rounded-2xl p-5 flex flex-col items-center justify-center shadow-lg relative">
            <div class="w-full flex justify-center py-2">
                {!! $qrCodeSvg !!}
            </div>
            <p class="text-[10px] text-slate-500 font-mono mt-2 tracking-widest uppercase font-bold">
                ID: {{ substr($visitor->qr_code_id, 0, 8) }}...{{ substr($visitor->qr_code_id, -6) }}
            </p>
        </div>

        <!-- Info Pengunjung -->
        <div class="text-center space-y-1 mb-5">
            <h2 class="text-lg font-extrabold text-white tracking-tight">{{ $visitor->name }}</h2>
            <p class="text-xs text-slate-400 font-medium">{{ $visitor->email }} &bull; {{ $visitor->phone }}</p>
        </div>

        <!-- Stamp Progress Gamification -->
        <div class="bg-slate-800/80 rounded-2xl p-4 border border-slate-700/50 space-y-3">
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-300 font-bold flex items-center gap-1.5">
                    <span>🎖️</span> Stempel Digital Dikumpulkan
                </span>
                <span class="font-extrabold text-amber-400 text-sm">
                    {{ $visitor->visits_count }} / {{ $targetStamps }}
                </span>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-slate-700/70 h-3 rounded-full overflow-hidden p-0.5">
                <div class="bg-gradient-to-r from-red-600 via-rose-500 to-amber-400 h-full rounded-full transition-all duration-700 shadow-sm"
                     style="width: {{ min(100, ($visitor->visits_count / $targetStamps) * 100) }}%"></div>
            </div>

            <!-- Pesan Status Reward -->
            <div class="text-center pt-1">
                @if($visitor->is_reward_claimed)
                    <p class="text-xs font-bold text-emerald-400">
                        🎉 Selamat! Hadiah Anda sudah ditukarkan di Redemption Station.
                    </p>
                @elseif($visitor->visits_count >= $targetStamps)
                    <p class="text-xs font-extrabold text-amber-300 animate-pulse">
                        🎁 Target Stempel Tercapai! Tunjukkan QR ini ke Station Panitia untuk klaim hadiah!
                    </p>
                @else
                    <p class="text-xs text-slate-400">
                        Kunjungi <span class="font-bold text-rose-300">{{ $targetStamps - $visitor->visits_count }} booth lagi</span> untuk mendapatkan hadiah!
                    </p>
                @endif
            </div>
        </div>

        <!-- RIWAYAT BOOTH YANG SUDAH DIKUNJUNGI (STAMP LIST) -->
        <div class="mt-5 space-y-2">
            <h3 class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center justify-between">
                <span>Daftar Booth Dikunjungi</span>
                <span class="text-[10px] text-slate-500 font-normal">Real-Time Sync</span>
            </h3>

            <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                @forelse($visitor->visits as $visit)
                    <div class="p-3 bg-slate-800/90 rounded-xl border border-slate-700/60 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center font-bold text-xs shrink-0">
                                ✓
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-white leading-tight">
                                    {{ $visit->tenant->tenant_name ?? 'Booth Exhibition' }}
                                </h4>
                                <span class="text-[10px] text-rose-400 font-semibold">
                                    Booth {{ $visit->tenant->booth_number ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono">
                            {{ $visit->scanned_at ? $visit->scanned_at->format('H:i WIB') : '-' }}
                        </span>
                    </div>
                @empty
                    <div class="p-4 bg-slate-800/40 rounded-xl border border-dashed border-slate-700/60 text-center text-xs text-slate-400 italic">
                        Belum ada booth yang di-scan. Kunjungi booth exhibitor untuk mengumpulkan stempel!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-5 space-y-2">
            <button onclick="window.location.reload()"
                class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold border border-slate-700 transition flex items-center justify-center gap-2 shadow-sm">
                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span>Refresh Status Stempel</span>
            </button>

            <a href="{{ route('portal') }}"
                class="w-full py-2 px-4 bg-transparent hover:bg-slate-800/50 text-slate-400 hover:text-slate-200 rounded-xl text-[11px] font-semibold transition flex items-center justify-center gap-1.5">
                <span>&larr; Kembali ke Portal Utama</span>
            </a>
        </div>
    </div>

</body>
</html>
