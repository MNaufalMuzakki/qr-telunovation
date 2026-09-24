<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantAccessCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TenantRegistrationController extends Controller
{
    /**
     * Tampilkan Halaman Form Registrasi Tenant.
     */
    public function showForm()
    {
        return view('tenant.register');
    }

    /**
     * Proses Pendaftaran Tenant Baru dengan Kode Akses Kredensial dari Admin.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'confirmed', Password::defaults()],
            'tenant_name'  => ['required', 'string', 'max:255'],
            'booth_number' => ['required', 'string', 'max:50'],
            'access_code'  => ['required', 'string'],
        ], [
            'name.required'         => 'Nama PIC / Penanggung jawab wajib diisi.',
            'email.required'        => 'Alamat Email wajib diisi.',
            'email.unique'          => 'Alamat Email sudah terdaftar di sistem.',
            'password.required'     => 'Password wajib diisi.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'tenant_name.required'  => 'Nama Tenant / Booth wajib diisi.',
            'booth_number.required' => 'Nomor Booth wajib diisi.',
            'access_code.required'  => 'Kode Akses Kredensial wajib diisi.',
        ]);

        $inputCode = strtoupper(trim($request->access_code));

        // Master emergency code fallback for TelU event
        $isMasterCode = in_array($inputCode, ['TELU2026', 'BTP2026', 'TELU-BTP-2026']);

        // Cari kode di database
        $accessCodeModel = TenantAccessCode::where('code', $inputCode)->first();

        if (!$isMasterCode) {
            if (!$accessCodeModel) {
                return back()->withErrors([
                    'access_code' => 'Kode Akses Tenant tidak ditemukan. Sila periksa kembali atau minta kode ke Panitia Telkom University BTP.'
                ])->withInput();
            }

            if ($accessCodeModel->is_used) {
                return back()->withErrors([
                    'access_code' => 'Kode Akses ini sudah pernah digunakan oleh tenant lain. Sila minta kode akses baru dari Panitia Admin.'
                ])->withInput();
            }
        }

        // Eksekusi Pendaftaran dalam Database Transaction
        DB::transaction(function () use ($request, $accessCodeModel) {
            // 1. Buat User baru role tenant
            $user = User::create([
                'name'     => $request->name,
                'email'    => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'role'     => 'tenant',
            ]);

            // 2. Buat profil Tenant
            Tenant::create([
                'user_id'      => $user->id,
                'tenant_name'  => $request->tenant_name,
                'booth_number' => strtoupper(trim($request->booth_number)),
            ]);

            // 3. Tandai kode akses terpakai jika ditemukan
            if ($accessCodeModel) {
                $accessCodeModel->update([
                    'is_used'         => true,
                    'used_by_user_id' => $user->id,
                    'used_at'         => now(),
                ]);
            }

            // 4. Loginkan pengguna
            Auth::login($user);
        });

        return redirect()->route('tenant.dashboard')->with('success', 'Registrasi Tenant berhasil! Selamat datang di Telkom University Bandung Techno Park Exhibition.');
    }
}
