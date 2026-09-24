<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengunjung - Exhibition 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 min-h-screen flex items-center justify-center p-4 selection:bg-indigo-500 selection:text-white">

    <div class="w-full max-w-md bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
        <!-- Background Glow Accent -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Header -->
        <div class="text-center mb-8 relative">
            <span class="inline-block px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 rounded-full mb-3">
                Digital Pass & Stamp System
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Daftar Pengunjung</h1>
            <p class="text-slate-400 text-sm mt-2">Dapatkan QR Code Digital Pass Anda dan kumpulkan stempel di setiap booth untuk hadiah menarik!</p>
        </div>

        @if(session('error'))
            <div class="mb-5 p-3.5 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('visitor.register.submit') }}" method="POST" class="space-y-4 relative">
            @csrf

            <!-- Input Nama -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                    placeholder="Contoh: Budi Santoso"
                    class="w-full px-4 py-3 bg-slate-800/80 border @error('name') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-sm">
                @error('name')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    placeholder="nama@email.com"
                    class="w-full px-4 py-3 bg-slate-800/80 border @error('email') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-sm">
                @error('email')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Phone -->
            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-300 mb-1.5">No. Telepon / WhatsApp (Hanya Angka)</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                    inputmode="numeric" pattern="[0-9]*"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    placeholder="081234567890"
                    class="w-full px-4 py-3 bg-slate-800/80 border @error('phone') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-sm font-mono">
                @error('phone')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition duration-200 text-sm mt-2 flex items-center justify-center gap-2">
                <span>Dapatkan Digital Pass</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>

        <!-- Footer / Link Login Tenant -->
        <div class="mt-8 pt-5 border-t border-slate-800 text-center text-xs text-slate-500">
            Penjaga Booth Tenant / Panitia? 
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold underline ml-1">
                Masuk di sini
            </a>
        </div>
    </div>

</body>
</html>
