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
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:visitors,email'],
            'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:9', 'max:16'],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'name.min'       => 'Nama minimal terdiri dari 3 karakter.',
            'name.regex'     => 'Nama hanya boleh mengandung huruf dan spasi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
            'email.unique'   => 'Email ini sudah terdaftar sebelumnya.',
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'phone.regex'    => 'Nomor telepon hanya boleh berisi angka (tanpa tanda +, strip, atau spasi).',
            'phone.min'      => 'Nomor telepon minimal 9 digit.',
            'phone.max'      => 'Nomor telepon maksimal 16 digit.',
        ]);

        $visitor = Visitor::create([
            'qr_code_id' => (string) Str::uuid(),
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
        ]);

        return redirect()->route('visitor.pass', ['uuid' => $visitor->qr_code_id])
            ->with('success', 'Registrasi berhasil! Tunjukkan Digital Pass ini ke setiap tenant yang Anda kunjungi.');
    }

    /**
     * Tampilkan Digital Pass Pengunjung (Mobile Wallet Pass style).
     */
    public function showPass(string $uuid)
    {
        $visitor = Visitor::where('qr_code_id', $uuid)
            ->withCount('visits')
            ->firstOrFail();

        $targetStamps = 5;
        $totalTenants = Tenant::count();

        // Generate QR code SVG secara inline (format SVG aman dan tajam di semua resolusi layar)
        $qrCodeSvg = QrCode::size(240)
            ->format('svg')
            ->margin(1)
            ->errorCorrection('H')
            ->generate($visitor->qr_code_id);

        return view('visitor.pass', compact('visitor', 'qrCodeSvg', 'targetStamps', 'totalTenants'));
    }
}
