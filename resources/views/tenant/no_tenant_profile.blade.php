<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Booth Belum Dihubungkan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 text-center space-y-4">
                <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto text-2xl">
                    ⚠️
                </div>
                <h3 class="text-xl font-bold text-gray-900">Akun Anda Belum Memiliki Profil Booth</h3>
                <p class="text-sm text-gray-600">
                    Akun ini terdaftar sebagai panitia/admin atau belum dihubungkan ke nomor booth tertentu oleh panitia acara.
                </p>
                <div class="pt-4">
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
                        Buka Dashboard Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
