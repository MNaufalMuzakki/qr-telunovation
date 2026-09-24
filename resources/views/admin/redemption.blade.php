<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                    {{ __('Redemption Station (Meja Penukaran Hadiah)') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Validasi perolehan stempel digital dan serahkan merchandise kepada pengunjung.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs bg-white hover:bg-gray-50 text-gray-700 px-3.5 py-2 rounded-xl border border-gray-200 shadow-sm font-semibold transition">
                &larr; Dashboard Admin
            </a>
        </div>
    </x-slot>

    <!-- CDN Libraries: html5-qrcode, sweetalert2 -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-800 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-800 text-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Scan / Search Card -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Box Kamera Scanner Admin -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider text-center">Scan QR Pengunjung</h3>
                    <div id="adminReader" class="w-full rounded-2xl overflow-hidden bg-black aspect-square"></div>
                    <p class="text-[11px] text-gray-400 text-center">Arahkan kamera ke layar Digital Pass pengunjung.</p>
                </div>

                <!-- Input UUID Manual -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 mb-1">Cari Berdasarkan UUID</h3>
                        <p class="text-xs text-gray-500 mb-4">Jika kamera bermasalah, masukkan kode UUID pengunjung secara manual:</p>

                        <form method="GET" action="{{ route('admin.redemption') }}" class="space-y-3">
                            <input type="text" name="uuid" value="{{ $searchUuid ?? '' }}" required
                                placeholder="Contoh: 8a7c29e1-6d9b-4321-8f55-..."
                                class="w-full px-4 py-3 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-mono">
                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition">
                                Verifikasi Pengunjung
                            </button>
                        </form>
                    </div>

                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 text-xs text-amber-800 mt-6">
                        <strong>Aturan Hadiah:</strong> Pengunjung wajib mengumpulkan minimal <strong>5 Stempel</strong> dari tenant yang berbeda untuk berhak mengklaim reward.
                    </div>
                </div>
            </div>

            <!-- Hasil Pengecekan Pengunjung -->
            @if($searchUuid)
                @if($visitor)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-lg p-6 sm:p-8 space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-100 gap-4">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-500">Hasil Pengecekan</span>
                                <h3 class="text-2xl font-black text-gray-900">{{ $visitor->name }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $visitor->email }} &bull; {{ $visitor->phone }}</p>
                            </div>
                            <div>
                                @if($visitor->is_reward_claimed)
                                    <span class="px-4 py-2 bg-emerald-100 text-emerald-800 font-bold rounded-xl text-xs border border-emerald-200 inline-flex items-center gap-1.5">
                                        <span>✅</span> Hadiah Sudah Diklaim
                                    </span>
                                @elseif($visitor->visits_count >= 5)
                                    <span class="px-4 py-2 bg-amber-100 text-amber-800 font-bold rounded-xl text-xs border border-amber-200 inline-flex items-center gap-1.5 animate-pulse">
                                        <span>🎁</span> Berhak Dapat Hadiah!
                                    </span>
                                @else
                                    <span class="px-4 py-2 bg-gray-100 text-gray-600 font-bold rounded-xl text-xs border border-gray-200">
                                        Belum Memenuhi Syarat
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Progress Stempel -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-bold">
                                <span>Stempel Terkumpul</span>
                                <span class="text-indigo-600">{{ $visitor->visits_count }} / 5 Stempel</span>
                            </div>
                            <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-500 to-emerald-500 h-3 rounded-full" style="width: {{ min(100, ($visitor->visits_count / 5) * 100) }}%"></div>
                            </div>
                        </div>

                        <!-- Daftar Tenant yang Dikunjungi -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Riwayat Booth yang Dikunjungi:</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @forelse($visitor->visits as $v)
                                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between text-xs">
                                        <div>
                                            <span class="font-bold text-gray-900">{{ $v->tenant->tenant_name ?? 'Tenant' }}</span>
                                            <span class="text-indigo-600 font-semibold ml-1">({{ $v->tenant->booth_number ?? '-' }})</span>
                                        </div>
                                        <span class="text-gray-400 text-[10px]">{{ $v->scanned_at ? $v->scanned_at->format('H:i') : '' }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400">Belum ada kunjungan ke booth manapun.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Tombol Klaim Hadiah -->
                        <div class="pt-4 border-t border-gray-100 flex justify-end">
                            @if(!$visitor->is_reward_claimed && $visitor->visits_count >= 5)
                                <form action="{{ route('admin.redemption.claim') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengonfirmasi penyerahan hadiah untuk pengunjung ini?');">
                                    @csrf
                                    <input type="hidden" name="qr_code_id" value="{{ $visitor->qr_code_id }}">
                                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-lg shadow-emerald-500/20 text-sm transition flex items-center gap-2">
                                        <span>🎁</span>
                                        <span>Konfirmasi Serahkan Hadiah</span>
                                    </button>
                                </form>
                            @elseif($visitor->is_reward_claimed)
                                <button disabled class="px-6 py-3 bg-gray-100 text-gray-400 font-bold rounded-xl text-sm cursor-not-allowed">
                                    Hadiah Sudah Pernah Diserahkan
                                </button>
                            @else
                                <button disabled class="px-6 py-3 bg-gray-100 text-gray-400 font-bold rounded-xl text-sm cursor-not-allowed">
                                    Stempel Masih Kurang ({{ $visitor->visits_count }}/5)
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="p-6 bg-rose-50 border border-rose-100 rounded-3xl text-center text-rose-700 text-sm font-semibold">
                        Pengunjung dengan UUID "{{ $searchUuid }}" tidak ditemukan di sistem.
                    </div>
                @endif
            @endif

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const adminScanner = new Html5Qrcode("adminReader");
            adminScanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 220 },
                (decodedText) => {
                    adminScanner.stop().then(() => {
                        window.location.href = "{{ route('admin.redemption') }}?uuid=" + encodeURIComponent(decodedText.trim());
                    });
                }
            ).catch(err => {
                console.warn("Admin scanner camera not active:", err);
            });
        });
    </script>
</x-app-layout>
