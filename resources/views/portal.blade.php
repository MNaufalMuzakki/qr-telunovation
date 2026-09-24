<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telkom University Bandung Techno Park - Exhibition 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-telu-maroon { background-color: #990000; }
        .text-telu-maroon { color: #990000; }
        .border-telu-maroon { border-color: #990000; }
        .glow-maroon { box-shadow: 0 0 35px -5px rgba(153, 0, 0, 0.4); }
        .glow-cyan { box-shadow: 0 0 35px -5px rgba(6, 182, 212, 0.35); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-red-950 min-h-screen text-slate-100 flex flex-col justify-between selection:bg-red-700 selection:text-white">

    <!-- Subtle Grid Background Overlay -->
    <div class="fixed inset-0 bg-[linear-gradient(to_right,#1e293b15_1px,transparent_1px),linear-gradient(to_bottom,#1e293b15_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>

    <!-- Navigation Header -->
    <header class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-red-700 via-rose-600 to-amber-500 flex items-center justify-center font-extrabold text-white shadow-lg glow-maroon">
                TelU
            </div>
            <div>
                <h1 class="text-base sm:text-lg font-bold text-white tracking-tight leading-none">TELUNOVATION 2026</h1>
                <p class="text-[11px] font-semibold text-rose-400 tracking-wider uppercase mt-0.5">Bandung Techno Park • Telkom University</p>
            </div>
        </div>

        <div>
            <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-semibold text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-800 border border-slate-700 rounded-xl transition flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                <span>Login Tenant / Admin</span>
            </a>
        </div>
    </header>

    <!-- Main Hero Container -->
    <main class="relative z-10 w-full max-w-5xl mx-auto px-4 py-8 sm:py-12 flex flex-col items-center my-auto">

        <!-- Top Announcement Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-950/70 border border-red-800/50 text-rose-300 text-xs font-semibold mb-6 shadow-inner">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Exhibition & Innovation Fair 2026 is Live!</span>
        </div>

        <!-- Hero Title -->
        <div class="text-center max-w-3xl mb-12">
            <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight">
                Selamat Datang di Portal <br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-500 via-rose-400 to-amber-300">
                    Telkom University Exhibition
                </span>
            </h2>
            <p class="text-slate-400 text-sm sm:text-base mt-4 max-w-2xl mx-auto font-normal leading-relaxed">
                Sistem Digital Pass & Stamp Collection Bandung Techno Park. Silakan pilih peranan Anda di bawah ini untuk memulai.
            </p>
        </div>

        <!-- Choice Cards Grid: Pengunjung vs Tenant -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 w-full max-w-4xl">

            <!-- Card Pintu 1: Pengunjung -->
            <div class="group relative bg-slate-900/80 backdrop-blur-xl border border-slate-800 hover:border-red-600/60 rounded-3xl p-6 sm:p-8 transition-all duration-300 hover:-translate-y-1 shadow-2xl overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 right-0 w-32 h-32 bg-red-600/10 rounded-full blur-2xl group-hover:bg-red-600/20 transition"></div>
                
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-rose-400 bg-red-500/10 border border-red-500/20 rounded-full">
                            Pintu Pengunjung
                        </span>
                        <div class="w-12 h-12 rounded-2xl bg-red-950 border border-red-800/60 flex items-center justify-center text-rose-400 group-hover:scale-110 transition shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-extrabold text-white mb-2">Saya Pengunjung Event</h3>
                    <p class="text-slate-400 text-xs sm:text-sm leading-relaxed mb-6">
                        Dapatkan <strong>QR Code Digital Pass</strong> secara instan, kumpulkan stempel digital saat berkunjung ke setiap booth, dan klaim hadiah menarik di Station Panitia!
                    </p>

                    <ul class="space-y-2 mb-8 text-xs text-slate-300">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Gratis tanpa perlu membuat password</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Langsung dapet e-Pass QR Code di HP</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Kumpulkan min. 3 stempel untuk Merchandise</span>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('visitor.register') }}" class="w-full py-3.5 px-4 bg-gradient-to-r from-red-700 via-rose-600 to-red-800 hover:from-red-600 hover:to-rose-700 text-white font-bold text-sm rounded-xl transition shadow-lg glow-maroon flex items-center justify-center gap-2 group-hover:gap-3">
                    <span>Masuk / Daftar Pengunjung</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Card Pintu 2: Tenant / Exhibitor -->
            <div class="group relative bg-slate-900/80 backdrop-blur-xl border border-slate-800 hover:border-cyan-600/60 rounded-3xl p-6 sm:p-8 transition-all duration-300 hover:-translate-y-1 shadow-2xl overflow-hidden flex flex-col justify-between">
                <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-600/10 rounded-full blur-2xl group-hover:bg-cyan-600/20 transition"></div>
                
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-cyan-400 bg-cyan-500/10 border border-cyan-500/20 rounded-full">
                            Pintu Exhibitor / Tenant
                        </span>
                        <div class="w-12 h-12 rounded-2xl bg-slate-950 border border-cyan-800/60 flex items-center justify-center text-cyan-400 group-hover:scale-110 transition shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0V11m0 10V11m0 0h-2m2 0h2"/></svg>
                        </div>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-extrabold text-white mb-2">Saya Penjaga Booth / Tenant</h3>
                    <p class="text-slate-400 text-xs sm:text-sm leading-relaxed mb-6">
                        Khusus untuk tim booth exhibition. Gunakan <strong>Kode Akses / Kredensial</strong> dari Panitia Admin TelU BTP untuk mendaftarkan akun booth Anda.
                    </p>

                    <ul class="space-y-2 mb-8 text-xs text-slate-300">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Memerlukan <strong>Kode Akses Kredensial</strong> dari Admin</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Akses Kamera Scanner QR Lead Retrieval</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Export Rekap Data Pengunjung ke Excel</span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-2.5">
                    <a href="{{ route('tenant.register') }}" class="w-full py-3.5 px-4 bg-gradient-to-r from-cyan-600 to-blue-700 hover:from-cyan-500 hover:to-blue-600 text-white font-bold text-sm rounded-xl transition shadow-lg glow-cyan flex items-center justify-center gap-2 group-hover:gap-3">
                        <span>Daftar Tenant (Butuh Kode)</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    
                    <a href="{{ route('login') }}" class="w-full py-2.5 px-4 bg-slate-800/90 hover:bg-slate-800 border border-slate-700 text-slate-300 hover:text-white font-semibold text-xs rounded-xl transition flex items-center justify-center gap-2">
                        <span>Sudah Punya Akun? Login di Sini</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Quick Stats Footer Banner -->
        <div class="mt-12 flex flex-wrap items-center justify-center gap-6 sm:gap-12 text-slate-400 text-xs sm:text-sm font-medium border-t border-slate-800/80 pt-8 w-full">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                <span>Terhubung ke {{ $totalTenants ?? 0 }} Booth Exhibitor</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                <span>{{ $totalVisitors ?? 0 }} Pengunjung Terdaftar</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span>Real-Time Digital Stamp System</span>
            </div>
        </div>
    </main>

    <!-- Footer Copyright -->
    <footer class="relative z-10 w-full py-6 text-center text-xs text-slate-500 border-t border-slate-900 bg-slate-950/60 backdrop-blur-md">
        <p>© 2026 Bandung Techno Park - Telkom University. All rights reserved.</p>
    </footer>

</body>
</html>
