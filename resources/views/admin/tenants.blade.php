<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                {{ __('Manajemen Akun Booth & Tenant') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-xs bg-white hover:bg-gray-50 text-gray-700 px-3.5 py-2 rounded-xl border border-gray-200 shadow-sm font-semibold transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-800 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Tambah Tenant Baru -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Daftarkan Akun Tenant Baru</h3>
                <p class="text-xs text-gray-500 mb-6">Buatkan akun login bagi penjaga booth agar mereka dapat menggunakan Web Scanner.</p>

                <form action="{{ route('admin.tenants.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Perusahaan / Tenant</label>
                        <input type="text" name="tenant_name" required placeholder="PT Inovasi Teknologi" value="{{ old('tenant_name') }}"
                            class="w-full px-3.5 py-2.5 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('tenant_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Booth</label>
                        <input type="text" name="booth_number" required placeholder="Contoh: A-12" value="{{ old('booth_number') }}"
                            class="w-full px-3.5 py-2.5 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('booth_number') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Penjaga / PIC</label>
                        <input type="text" name="name" required placeholder="Nama PIC" value="{{ old('name') }}"
                            class="w-full px-3.5 py-2.5 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email Login Tenant</label>
                        <input type="email" name="email" required placeholder="booth.a12@event.com" value="{{ old('email') }}"
                            class="w-full px-3.5 py-2.5 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('email') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required placeholder="Password Login"
                            class="w-full px-3.5 py-2.5 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('password') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2 lg:col-span-1 flex items-end">
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-500/20 transition">
                            + Daftarkan Tenant
                        </button>
                    </div>
                </form>
            </div>

            <!-- List Tenant Terdaftar -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Tenant Terdaftar</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Booth</th>
                                <th class="px-6 py-4">Nama Tenant</th>
                                <th class="px-6 py-4">Email Login</th>
                                <th class="px-6 py-4 text-center">Leads Terkumpul</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($tenants as $t)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-indigo-600">
                                        {{ $t->booth_number }}
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-gray-900">
                                        {{ $t->tenant_name }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono">
                                        {{ $t->user->email ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900">
                                        {{ $t->visits_count }} Leads
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-xs">
                                        Belum ada tenant yang didaftarkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tenants->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $tenants->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
