<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Tenant Exhibitor - Telkom University BTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-red-950 min-h-screen flex items-center justify-center p-4 selection:bg-cyan-500 selection:text-white">

    <div class="w-full max-w-lg bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden my-6">
        <!-- Background Glow Accent -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-red-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Back Button & Header -->
        <div class="mb-6 relative">
            <a href="{{ route('portal') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white mb-4 transition font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Portal Utama</span>
            </a>

            <div class="text-center">
                <span class="inline-block px-3.5 py-1 text-[11px] font-extrabold uppercase tracking-widest text-cyan-400 bg-cyan-500/10 border border-cyan-500/20 rounded-full mb-3">
                    Registrasi Exhibitor / Tenant
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Daftarkan Booth Anda</h1>
                <p class="text-slate-400 text-xs sm:text-sm mt-1.5">Masukkan detail booth dan <strong>Kode Akses Kredensial</strong> dari Panitia Admin TelU BTP.</p>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-5 p-3.5 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 text-xs sm:text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('tenant.register.submit') }}" method="POST" class="space-y-4 relative">
            @csrf

            <!-- Security Access Code Notice Box -->
            <div class="p-3.5 bg-cyan-950/40 border border-cyan-500/30 rounded-2xl flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center shrink-0 mt-0.5 font-bold text-sm">
                    🔑
                </div>
                <div>
                    <h4 class="text-xs font-bold text-cyan-300 uppercase tracking-wide">Butuh Kode Akses Kredensial</h4>
                    <p class="text-[11px] text-slate-300 mt-0.5 leading-relaxed">
                        Pendaftaran tenant dilindungi kode keamanan. Gunakan Kode Akses resmi dari Panitia BTP (Contoh: <code>BTP-A01</code>, <code>BTP2026</code>, atau kode yang diberikan Panitia).
                    </p>
                </div>
            </div>

            <!-- Input Kode Akses (Kredensial) -->
            <div>
                <label for="access_code" class="block text-xs font-bold text-amber-400 mb-1">
                    Kode Akses Kredensial Admin <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="access_code" name="access_code" value="{{ old('access_code') }}" required autofocus
                    placeholder="Contoh: BTP-A01 atau BTP2026"
                    class="w-full px-4 py-3 bg-slate-950 border @error('access_code') border-rose-500 @else border-amber-500/50 focus:border-amber-400 @enderror rounded-xl text-amber-300 font-mono font-bold tracking-wider placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition text-sm">
                @error('access_code')
                    <p class="text-rose-400 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-slate-800 my-4">

            <!-- Input Nama PIC -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap PIC Booth <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    placeholder="Contoh: Andi Pratama"
                    class="w-full px-4 py-3 bg-slate-800/80 border @error('name') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition text-sm">
                @error('name')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email Login <span class="text-rose-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    placeholder="booth@perusahaan.com"
                    class="w-full px-4 py-3 bg-slate-800/80 border @error('email') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition text-sm">
                @error('email')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Grid Nama Tenant & Nomor Booth -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="tenant_name" class="block text-xs font-semibold text-slate-300 mb-1">Nama Tenant / Brand <span class="text-rose-500">*</span></label>
                    <input type="text" id="tenant_name" name="tenant_name" value="{{ old('tenant_name') }}" required
                        placeholder="Contoh: Asus ROG Hub"
                        class="w-full px-4 py-3 bg-slate-800/80 border @error('tenant_name') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition text-sm">
                    @error('tenant_name')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="booth_number" class="block text-xs font-semibold text-slate-300 mb-1">Nomor Booth <span class="text-rose-500">*</span></label>
                    <input type="text" id="booth_number" name="booth_number" value="{{ old('booth_number') }}" required
                        placeholder="Contoh: A-05"
                        class="w-full px-4 py-3 bg-slate-800/80 border @error('booth_number') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition text-sm">
                    @error('booth_number')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password & Konfirmasi Password -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 mb-1">Password Login <span class="text-rose-500">*</span></label>
                    <input type="password" id="password" name="password" required
                        placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-3 bg-slate-800/80 border @error('password') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition text-sm">
                    @error('password')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 mb-1">Konfirmasi Password <span class="text-rose-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        placeholder="Ulangi password"
                        class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition text-sm">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 mt-2 bg-gradient-to-r from-cyan-600 via-blue-600 to-cyan-700 hover:from-cyan-500 hover:to-blue-500 text-white font-bold text-sm rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <span>Daftarkan Booth Tenant</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <!-- Footer Link -->
        <div class="mt-6 text-center text-xs text-slate-400">
            <span>Sudah memiliki akun booth? </span>
            <a href="{{ route('login') }}" class="text-cyan-400 hover:underline font-semibold">Login di Sini</a>
        </div>
    </div>

</body>
</html>
