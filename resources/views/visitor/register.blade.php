<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengunjung - Telkom University BTP Exhibition</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-red-950 min-h-screen flex items-center justify-center p-4 selection:bg-red-600 selection:text-white">

    <div class="w-full max-w-md bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden my-6">
        <!-- Background Glow Accent -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-red-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-amber-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Back Button & Header -->
        <div class="mb-6 relative">
            <a href="{{ route('portal') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white mb-4 transition font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Portal Utama</span>
            </a>

            <div class="text-center">
                <span class="inline-block px-3.5 py-1 text-[11px] font-extrabold uppercase tracking-widest text-rose-400 bg-red-500/10 border border-red-500/20 rounded-full mb-3">
                    Bandung Techno Park • Digital Pass
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Registrasi Pengunjung</h1>
                <p class="text-slate-400 text-xs sm:text-sm mt-1.5">Dapatkan QR Code Pass Digital Anda dan kumpulkan stempel di setiap booth untuk klaim hadiah!</p>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-5 p-3.5 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 text-xs sm:text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('visitor.register.submit') }}" method="POST" class="space-y-4 relative">
            @csrf

            <!-- Input Nama -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                    placeholder="Contoh: Budi Santoso"
                    class="w-full px-4 py-3 bg-slate-800/80 border @error('name') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500 transition text-sm">
                @error('name')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    placeholder="nama@email.com"
                    class="w-full px-4 py-3 bg-slate-800/80 border @error('email') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500 transition text-sm">
                @error('email')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Phone -->
            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-300 mb-1.5">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                    placeholder="081234567890"
                    class="w-full px-4 py-3 bg-slate-800/80 border @error('phone') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500 transition text-sm">
                @error('phone')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3.5 px-4 mt-2 bg-gradient-to-r from-red-700 via-rose-600 to-red-800 hover:from-red-600 hover:to-rose-700 text-white font-bold text-sm rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <span>Dapatkan Digital Pass QR</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <!-- Footer Link -->
        <div class="mt-6 text-center text-xs text-slate-400 border-t border-slate-800/80 pt-4">
            <span>Penjaga Booth / Tenant? </span>
            <a href="{{ route('tenant.register') }}" class="text-cyan-400 hover:underline font-semibold">Daftar Tenant dengan Kode</a>
        </div>
    </div>

</body>
</html>
