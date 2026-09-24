<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Pass - {{ $visitor->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 min-h-screen flex items-center justify-center p-4 selection:bg-indigo-500 selection:text-white">

    <div class="w-full max-w-sm bg-slate-900/95 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 text-white shadow-2xl relative overflow-hidden">
        <!-- Accent Glow -->
        <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Header Card -->
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <span class="text-[10px] tracking-widest font-extrabold uppercase text-indigo-400">Official Pass</span>
                <h1 class="text-lg font-black tracking-tight text-white">TELUNOVATION 2026</h1>
            </div>
            @if($visitor->is_reward_claimed)
                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Reward Claimed
                </span>
            @else
                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                    Visitor Pass
                </span>
            @endif
        </div>

        @if(session('success'))
            <div class="mt-4 p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-300 text-xs text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- QR Code Box (Centerpiece) -->
        <div class="my-5 bg-white rounded-2xl p-5 flex flex-col items-center justify-center shadow-lg">
            <div class="w-full flex justify-center py-2">
                {!! $qrCodeSvg !!}
            </div>
            <p class="text-[11px] text-slate-500 font-mono mt-2 tracking-widest uppercase">
                UUID: {{ substr($visitor->qr_code_id, 0, 8) }}...{{ substr($visitor->qr_code_id, -6) }}
            </p>
        </div>

        <!-- Info Pengunjung -->
        <div class="text-center space-y-1 mb-5">
            <h2 class="text-xl font-extrabold text-white tracking-tight">{{ $visitor->name }}</h2>
            <p class="text-xs text-slate-400 font-medium">{{ $visitor->email }} &bull; {{ $visitor->phone }}</p>
        </div>

        <!-- Stamp Progress Gamification -->
        <div class="bg-slate-800/80 rounded-2xl p-4 border border-slate-700/50 space-y-3">
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                    <span>🎖️</span> Progres Stempel Digital
                </span>
                <span class="font-extrabold text-indigo-400 text-sm">
                    {{ $visitor->visits_count }} / {{ $targetStamps }}
                </span>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-slate-700/70 h-3 rounded-full overflow-hidden p-0.5">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full rounded-full transition-all duration-700 shadow-sm"
                     style="width: {{ min(100, ($visitor->visits_count / $targetStamps) * 100) }}%"></div>
            </div>

            <!-- Pesan Reward -->
            <div class="text-center">
                @if($visitor->is_reward_claimed)
                    <p class="text-xs font-semibold text-emerald-400">
                        🎉 Anda telah berhasil menukarkan merchandise di Redemption Station!
                    </p>
                @elseif($visitor->visits_count >= $targetStamps)
                    <p class="text-xs font-bold text-amber-300 animate-pulse">
                        🎁 Syarat terpenuhi! Tunjukkan QR ini ke meja Redemption Station untuk mengambil hadiah.
                    </p>
                @else
                    <p class="text-xs text-slate-400">
                        Kunjungi <span class="font-bold text-indigo-300">{{ $targetStamps - $visitor->visits_count }} booth lagi</span> untuk mendapatkan hadiah!
                    </p>
                @endif
            </div>
        </div>

        <!-- Instruksi & Action Buttons -->
        <div class="mt-5 space-y-2">
            <button onclick="window.location.reload()"
                class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-semibold border border-slate-700 transition flex items-center justify-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Perbarui Status Stempel
            </button>
            <p class="text-[11px] text-center text-slate-500 leading-relaxed pt-1">
                Tunjukkan layar QR ini kepada penjaga booth tenant untuk di-scan.
            </p>
        </div>
    </div>

</body>
</html>
