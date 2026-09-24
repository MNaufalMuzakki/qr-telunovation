<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ __('Web Scanner Booth') }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Booth: <span class="font-bold text-indigo-600">{{ auth()->user()->tenant->booth_number ?? '-' }}</span> &bull; {{ auth()->user()->tenant->tenant_name ?? '-' }}
                </p>
            </div>
            <a href="{{ route('tenant.dashboard') }}" class="text-xs sm:text-sm bg-white hover:bg-gray-50 text-gray-700 px-3.5 py-2 rounded-xl border border-gray-200 shadow-sm font-semibold transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Dashboard
            </a>
        </div>
    </x-slot>

    <!-- CDN Libraries: html5-qrcode, axios, sweetalert2 -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 max-w-lg mx-auto px-4 sm:px-6">
        <!-- Card Scanner Utama -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header Petunjuk Mobile -->
            <div class="p-4 bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 text-white text-center relative">
                <p class="text-[10px] uppercase tracking-wider font-extrabold text-indigo-200">Kamera Scanner Aktif</p>
                <h3 class="text-base sm:text-lg font-extrabold mt-0.5">Arahkan ke Digital Pass Pengunjung</h3>
            </div>

            <!-- Viewport Kamera Scanner -->
            <div class="p-4 relative bg-slate-950">
                <div id="reader" class="w-full rounded-2xl overflow-hidden bg-black aspect-square shadow-inner"></div>

                <!-- Overlay Loading State (Mitigasi Spam & Sinyal Lemah) -->
                <div id="scanOverlay" class="hidden absolute inset-0 bg-slate-950/85 flex flex-col items-center justify-center text-white z-20 backdrop-blur-sm p-6 text-center">
                    <svg class="animate-spin h-10 w-10 text-indigo-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <p class="font-bold text-base text-white" id="overlayMessage">Memverifikasi QR Code...</p>
                    <p class="text-xs text-slate-400 mt-1">Mohon tunggu, jangan tutup layar ini.</p>
                </div>
            </div>

            <!-- Panel Status & Kontrol -->
            <div class="p-5 bg-white space-y-4">
                <div id="statusIndicator" class="flex items-center justify-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 py-2.5 px-4 rounded-xl border border-emerald-100">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Kamera siap memindai QR Code pengunjung</span>
                </div>

                <!-- Tombol Resume Scan (jika kamera dijeda setelah scan) -->
                <button id="btnResumeScan" onclick="resumeScanning()" class="hidden w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition flex items-center justify-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Lanjut Scan Pengunjung Berikutnya</span>
                </button>

                <!-- Input Manual UUID (Mode Pengujian / Fallback jika tanpa kamera) -->
                <div class="pt-3 border-t border-gray-100">
                    <details class="group text-xs">
                        <summary class="cursor-pointer font-semibold text-gray-500 hover:text-indigo-600 transition flex items-center justify-between py-1">
                            <span>🛠️ Mode Testing / Input Manual UUID</span>
                            <span class="group-open:rotate-180 transition-transform">▼</span>
                        </summary>
                        <div class="pt-3 space-y-2">
                            <p class="text-[11px] text-gray-400">Masukkan kode UUID pengunjung jika kamera tidak tersedia atau sedang testing di 1 browser:</p>
                            <div class="flex gap-2">
                                <input type="text" id="manualUuidInput" placeholder="Tempelkan UUID pengunjung..." 
                                    class="flex-1 px-3 py-2 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-mono">
                                <button type="button" onclick="submitManualUuid()" 
                                    class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                                    Simulasikan Scan
                                </button>
                            </div>
                        </div>
                    </details>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p class="text-xs text-gray-500">
                Membutuhkan koneksi HTTPS dan izin kamera aktif pada browser HP Anda.
            </p>
        </div>
    </div>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

        let html5QrCode = null;
        let isProcessing = false;

        // Audio Beep Synthesizer via Web Audio API (Bekerja offline & instan)
        let audioCtx = null;
        function getAudioContext() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            return audioCtx;
        }

        function playFeedbackSound(isSuccess = true) {
            try {
                const ctx = getAudioContext();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);

                if (isSuccess) {
                    // Dua nada ceria (Success Beep)
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    osc.frequency.setValueAtTime(1200, ctx.currentTime + 0.1);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.25);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.25);
                } else {
                    // Nada rendah (Error Buzz)
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(220, ctx.currentTime);
                    gain.gain.setValueAtTime(0.35, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.35);
                }
            } catch (e) {
                console.warn('Audio playback not supported:', e);
            }
        }

        function toggleLoading(show, message = 'Memverifikasi data...') {
            const overlay = document.getElementById('scanOverlay');
            const msgEl = document.getElementById('overlayMessage');
            if (show) {
                msgEl.innerText = message;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            } else {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }
        }

        function onScanSuccess(decodedText) {
            if (isProcessing) return;

            isProcessing = true;
            toggleLoading(true, 'Menyimpan data kunjungan...');

            // Pause kamera sementara agar tidak trigger frame berikutnya
            if (html5QrCode && html5QrCode.isScanning) {
                try {
                    html5QrCode.pause();
                } catch(e) {}
            }

            axios.post('{{ route('tenant.scan.process') }}', {
                qr_code_id: decodedText.trim()
            })
            .then(response => {
                playFeedbackSound(true);
                toggleLoading(false);

                const data = response.data.data;
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil di-Scan!',
                    text: response.data.message,
                    html: `
                        <div class="text-left bg-gray-50 p-4 rounded-xl text-xs sm:text-sm space-y-1.5 border border-gray-100 mt-2">
                            <div><strong class="text-gray-600">Nama:</strong> <span class="font-bold text-gray-900">${data.visitor_name}</span></div>
                            <div><strong class="text-gray-600">Email:</strong> <span class="text-gray-800">${data.visitor_email}</span></div>
                            <div><strong class="text-gray-600">No. WhatsApp:</strong> <span class="text-gray-800">${data.visitor_phone}</span></div>
                            <div class="text-[11px] text-gray-400 pt-1 border-t border-gray-200 mt-2">Waktu Scan: ${data.scanned_at}</div>
                        </div>
                    `,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonText: 'Lanjut Scan',
                    confirmButtonColor: '#4F46E5',
                }).then(() => {
                    resumeScanning();
                });
            })
            .catch(error => {
                playFeedbackSound(false);
                toggleLoading(false);

                let errorTitle = 'Gagal Memproses Scan';
                let errorMessage = 'Terjadi kesalahan sistem.';

                if (error.response) {
                    if (error.response.status === 409) {
                        errorTitle = 'Sudah Pernah Di-Scan!';
                        errorMessage = error.response.data.message || 'Pengunjung ini sudah terdata di booth Anda.';
                    } else if (error.response.status === 404) {
                        errorTitle = 'QR Code Tidak Dikenali';
                        errorMessage = error.response.data.message || 'QR Code ini bukan Digital Pass yang valid.';
                    } else if (error.response.data && error.response.data.message) {
                        errorMessage = error.response.data.message;
                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: errorTitle,
                    text: errorMessage,
                    confirmButtonColor: '#4F46E5',
                    confirmButtonText: 'Scan Ulang'
                }).then(() => {
                    resumeScanning();
                });
            });
        }

        function resumeScanning() {
            isProcessing = false;
            document.getElementById('btnResumeScan').classList.add('hidden');
            if (html5QrCode) {
                try {
                    html5QrCode.resume();
                } catch (e) {
                    console.warn('Resume error:', e);
                }
            }
        }

        function submitManualUuid() {
            const input = document.getElementById('manualUuidInput');
            const uuid = input.value.trim();
            if (!uuid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'UUID Kosong',
                    text: 'Silakan masukkan atau tempelkan UUID pengunjung terlebih dahulu.',
                    confirmButtonColor: '#4F46E5',
                });
                return;
            }
            onScanSuccess(uuid);
        }

        document.addEventListener('DOMContentLoaded', () => {
            html5QrCode = new Html5Qrcode("reader");

            const config = {
                fps: 15,
                qrbox: { width: 240, height: 240 },
                aspectRatio: 1.0,
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                }
            };

            // Menggunakan kamera belakang pada perangkat smartphone
            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess
            ).catch(err => {
                console.error("Camera init error:", err);
                const indicator = document.getElementById('statusIndicator');
                indicator.className = 'flex items-center justify-center gap-2 text-xs font-semibold text-rose-700 bg-rose-50 py-2.5 px-4 rounded-xl border border-rose-100 text-center';
                indicator.innerText = "Akses kamera tidak diizinkan atau kamera tidak tersedia.";
            });
        });
    </script>
</x-app-layout>
