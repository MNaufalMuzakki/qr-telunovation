<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Tenant & Admin - Telkom University BTP</title>
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
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Back Button & Header -->
        <div class="mb-6 relative">
            <a href="{{ route('portal') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white mb-4 transition font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Portal Utama</span>
            </a>

            <div class="text-center">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-gradient-to-tr from-red-700 via-rose-600 to-amber-500 flex items-center justify-center font-extrabold text-white text-lg shadow-lg mb-3">
                    TelU
                </div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight">Login Tenant & Admin</h1>
                <p class="text-slate-400 text-xs sm:text-sm mt-1">Masuk untuk mengakses Web Scanner Booth atau Dashboard Admin.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-300 text-xs font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4 relative">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email Registered</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="admin@event.com atau booth@event.com"
                    class="w-full px-4 py-3 bg-slate-800/80 border @error('email') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500 transition text-sm">
                @error('email')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-300 mb-1.5">Password</label>
                <input type="password" id="password" name="password" required
                    placeholder="••••••••"
                    class="w-full px-4 py-3 bg-slate-800/80 border @error('password') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500 transition text-sm">
                @error('password')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-300">
                    <input id="remember_me" type="checkbox" class="rounded bg-slate-800 border-slate-700 text-red-600 focus:ring-red-500 focus:ring-offset-slate-900" name="remember">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 mt-2 bg-gradient-to-r from-red-700 via-rose-600 to-red-800 hover:from-red-600 hover:to-rose-700 text-white font-bold text-sm rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <span>Masuk ke Dashboard</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <!-- Footer Links -->
        <div class="mt-6 text-center text-xs text-slate-400 border-t border-slate-800/80 pt-4 space-y-1.5">
            <div>
                <span>Belum punya akun Booth Tenant? </span>
                <a href="{{ route('tenant.register') }}" class="text-cyan-400 hover:underline font-semibold">Daftar Tenant dengan Kode Akses</a>
            </div>
            <div>
                <span>Atau masuk sebagai pengunjung? </span>
                <a href="{{ route('visitor.register') }}" class="text-rose-400 hover:underline font-semibold">Daftar e-Pass Pengunjung</a>
            </div>
        </div>
    </div>

</body>
</html>
