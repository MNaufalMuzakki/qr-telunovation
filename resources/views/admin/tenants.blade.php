<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Manajemen Akun & Kode Akses Tenant') }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola akun exhibitor dan buat Kode Akses Kredensial pendaftaran booth.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2 rounded-xl shadow-sm font-semibold transition flex items-center gap-1.5">
                <span>&larr; Dashboard Admin</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-800 text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- SECTION 1: MANAJEMEN KODE AKSES KREDENSIAL TENANT -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <span class="px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-amber-700 bg-amber-100 border border-amber-300/60 rounded-full mb-2 inline-block">
                            🔑 Fitur Kredensial Keamanan
                        </span>
                        <h3 class="text-lg font-bold text-slate-900">Buat Kode Akses Tenant Baru</h3>
                        <p class="text-xs text-slate-500 mt-1">Berikan kode ini kepada pihak booth / tenant agar mereka bisa mendaftar mandiri di form pendaftaran tenant.</p>
                    </div>
                </div>

                <form action="{{ route('admin.tenant_codes.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200/60">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Akses (Kosongkan utk Auto-Generate)</label>
                        <input type="text" name="code" placeholder="Contoh: BTP-A05" value="{{ old('code') }}"
                            class="w-full px-3.5 py-2.5 text-xs font-mono font-bold uppercase border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                        @error('code') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Booth (Opsional)</label>
                        <input type="text" name="booth_number" placeholder="Contoh: A-05" value="{{ old('booth_number') }}"
                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan / Perusahaan (Opsional)</label>
                        <input type="text" name="notes" placeholder="Contoh: Utk Tim Asus ROG" value="{{ old('notes') }}"
                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full py-2.5 bg-red-700 hover:bg-red-800 text-white font-bold rounded-xl text-xs shadow-md shadow-red-700/20 transition flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Buat Kode Akses</span>
                        </button>
                    </div>
                </form>

                <!-- Tabel Daftar Kode Akses -->
                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Kode Akses</th>
                                <th class="px-4 py-3">Target Booth</th>
                                <th class="px-4 py-3">Catatan</th>
                                <th class="px-4 py-3">Status Kode</th>
                                <th class="px-4 py-3">Pengguna</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($accessCodes as $code)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 font-mono font-bold text-red-700 text-sm">
                                        {{ $code->code }}
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-800">
                                        {{ $code->booth_number ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-500">
                                        {{ $code->notes ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($code->is_used)
                                            <span class="px-2.5 py-1 text-[10px] font-extrabold text-rose-700 bg-rose-100 rounded-full border border-rose-200">
                                                TERPAKAI
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-[10px] font-extrabold text-emerald-700 bg-emerald-100 rounded-full border border-emerald-200">
                                                AKTIF (SIAP PAKAI)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        @if($code->usedBy)
                                            <div class="font-bold text-slate-900">{{ $code->usedBy->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">{{ $code->usedBy->email }}</div>
                                        @else
                                            <span class="text-slate-400 italic">Belum terpakai</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <form action="{{ route('admin.tenant_codes.delete', $code->id) }}" method="POST" onsubmit="return confirm('Hapus kode akses ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-400 italic">
                                        Belum ada kode akses yang dibuat. Gunakan form di atas untuk membuat kode akses baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 2: REGISTRASI TENANT DIRECT OLEH ADMIN -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-1">Daftarkan Akun Tenant Secara Langsung (Direct)</h3>
                <p class="text-xs text-slate-500 mb-6">Jika Panitia ingin mendaftarkan akun tenant secara manual tanpa perlu tenant mengisi form sendiri.</p>

                <form action="{{ route('admin.tenants.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Perusahaan / Tenant</label>
                        <input type="text" name="tenant_name" required placeholder="Contoh: PT Inovasi Telkom" value="{{ old('tenant_name') }}"
                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                        @error('tenant_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Booth</label>
                        <input type="text" name="booth_number" required placeholder="Contoh: A-12" value="{{ old('booth_number') }}"
                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                        @error('booth_number') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Penjaga / PIC</label>
                        <input type="text" name="name" required placeholder="Nama PIC Booth" value="{{ old('name') }}"
                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                        @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email Login Tenant</label>
                        <input type="email" name="email" required placeholder="booth.a12@event.com" value="{{ old('email') }}"
                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                        @error('email') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required placeholder="Password Login"
                            class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:outline-none">
                        @error('password') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2 lg:col-span-1 flex items-end">
                        <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs shadow-md transition">
                            + Daftarkan Tenant Direct
                        </button>
                    </div>
                </form>
            </div>

            <!-- SECTION 3: DAFTAR TENANT TERDAFTAR -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Daftar Tenant Terdaftar</h3>
                        <p class="text-xs text-slate-500">Semua tenant yang aktif di Telkom University Bandung Techno Park Exhibition.</p>
                    </div>
                    <span class="px-3 py-1 bg-red-100 text-red-800 font-extrabold text-xs rounded-full">
                        Total {{ $tenants->total() }} Tenant
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Booth</th>
                                <th class="px-6 py-4">Nama Tenant</th>
                                <th class="px-6 py-4">Email Login</th>
                                <th class="px-6 py-4 text-center">Total Leads Scanned</th>
                                <th class="px-6 py-4 text-right">Aksi Super Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($tenants as $t)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-bold text-red-700">
                                        {{ $t->booth_number }}
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-slate-900">
                                        {{ $t->tenant_name }}
                                        <div class="text-xs font-normal text-slate-400">PIC: {{ $t->user->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono">
                                        {{ $t->user->email ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 bg-slate-100 text-slate-800 font-black rounded-lg text-xs">
                                            {{ $t->visits_count }} Visitor
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('admin.tenants.leads', $t->id) }}" class="inline-block px-3 py-1 bg-cyan-50 hover:bg-cyan-100 text-cyan-700 border border-cyan-200 rounded-lg text-xs font-bold transition">
                                            Lihat Leads &rarr;
                                        </a>
                                        <form action="{{ route('admin.tenants.delete', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tenant ini beserta akun loginsnya?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">
                                        Belum ada tenant yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tenants->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $tenants->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
