<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScanController extends Controller
{
    /**
     * Memproses QR Code UUID yang dikirim kamera tenant via Axios.
     */
    public function processScan(Request $request): JsonResponse
    {
        $request->validate([
            'qr_code_id' => ['required', 'string', 'uuid'],
        ], [
            'qr_code_id.required' => 'Data QR Code tidak boleh kosong.',
            'qr_code_id.uuid'     => 'Format QR Code tidak valid.',
        ]);

        $user = $request->user();
        $tenant = $user->tenant;

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak terhubung dengan profil Tenant/Booth manapun.',
            ], 403);
        }

        $qrCodeId = $request->input('qr_code_id');

        // 1. Validasi keberadaan UUID Visitor
        $visitor = Visitor::where('qr_code_id', $qrCodeId)->first();

        if (!$visitor) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code pengunjung tidak dikenali atau tidak terdaftar.',
            ], 404);
        }

        // 2. Pre-check: Cek apakah tenant ini sudah pernah scan pengunjung ini
        $alreadyScanned = Visit::where('tenant_id', $tenant->id)
            ->where('visitor_id', $visitor->id)
            ->exists();

        if ($alreadyScanned) {
            return response()->json([
                'success' => false,
                'status_code' => 'ALREADY_SCANNED',
                'message' => "Pengunjung \"{$visitor->name}\" sudah pernah Anda scan sebelumnya!",
                'data' => [
                    'visitor_name' => $visitor->name,
                ]
            ], 409); // 409 Conflict
        }

        // 3. Eksekusi Atomic Insert dengan Database Transaction & Catch QueryException
        try {
            DB::beginTransaction();

            $visit = Visit::create([
                'tenant_id'  => $tenant->id,
                'visitor_id' => $visitor->id,
                'scanned_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil mencatat prospek dari {$visitor->name}!",
                'data' => [
                    'visitor_name'  => $visitor->name,
                    'visitor_email' => $visitor->email,
                    'visitor_phone' => $visitor->phone,
                    'scanned_at'    => $visit->scanned_at->format('H:i:s, d M Y'),
                ],
            ], 200);

        } catch (QueryException $e) {
            DB::rollBack();

            // Tangkap MySQL Error Code 1062 (Duplicate entry) jika terjadi race condition bersamaan
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false,
                    'status_code' => 'ALREADY_SCANNED',
                    'message' => "Pengunjung \"{$visitor->name}\" baru saja di-scan!",
                ], 409);
            }

            Log::error('Scan Processing Database Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat data ke database: ' . $e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Scan Processing Unexpected Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses scan.',
            ], 500);
        }
    }
}
