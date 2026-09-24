<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VisitorController extends Controller
{
    /**
     * Tampilkan form registrasi pengunjung.
     */
    public function showRegistrationForm()
    {
        return view('visitor.register');
    }

    /**
     * Proses pendaftaran pengunjung & generate UUID untuk QR Code.
     * Jika email / nomor HP sudah pernah terdaftar, sistem akan otomatis mengembalikan Pass yang sudah ada
     * sehingga data stempel tidak akan hilang/duplikat.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:9', 'max:16'],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'name.min'       => 'Nama minimal terdiri dari 3 karakter.',
            'name.regex'     => 'Nama hanya boleh mengandung huruf dan spasi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'phone.regex'    => 'Nomor telepon hanya boleh berisi angka (tanpa spasi/strip).',
            'phone.min'      => 'Nomor telepon minimal 9 digit.',
            'phone.max'      => 'Nomor telepon maksimal 16 digit.',
        ]);

        $cleanEmail = strtolower(trim($validated['email']));
        $cleanPhone = trim($validated['phone']);

        // Check if visitor already exists by email OR phone number
        $existingVisitor = Visitor::where('email', $cleanEmail)
            ->orWhere('phone', $cleanPhone)
            ->first();

        if ($existingVisitor) {
            return redirect()->route('visitor.pass', ['uuid' => $existingVisitor->qr_code_id])
                ->with('info', 'Selamat datang kembali, ' . $existingVisitor->name . '! Ini adalah Digital Pass Anda.');
        }

        // Create new unique visitor
        $visitor = Visitor::create([
            'qr_code_id' => (string) Str::uuid(),
            'name'       => trim($validated['name']),
            'email'      => $cleanEmail,
            'phone'      => $cleanPhone,
        ]);

        return redirect()->route('visitor.pass', ['uuid' => $visitor->qr_code_id])
            ->with('success', 'Registrasi berhasil! Tunjukkan Digital Pass ini ke setiap tenant yang Anda kunjungi.');
    }

    /**
     * Tampilkan Digital Pass Pengunjung beserta riwayat stempel booth yang telah dikunjungi.
     */
    public function showPass(string $uuid)
    {
        $visitor = Visitor::where('qr_code_id', $uuid)
            ->with(['visits' => function ($query) {
                $query->with('tenant')->latest('scanned_at');
            }])
            ->withCount('visits')
            ->firstOrFail();

        $qrCodeSvg = QrCode::size(240)
            ->color(15, 23, 42)
            ->generate($visitor->qr_code_id);

        $targetStamps = 3;

        return view('visitor.pass', compact('visitor', 'qrCodeSvg', 'targetStamps'));
    }
}
