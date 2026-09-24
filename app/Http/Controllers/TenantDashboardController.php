<?php

namespace App\Http\Controllers;

use App\Exports\TenantLeadsExport;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class TenantDashboardController extends Controller
{
    /**
     * Halaman Dashboard Tenant: Ringkasan prospek & tabel leads.
     */
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;

        if (!$tenant) {
            return view('tenant.no_tenant_profile');
        }

        $search = $request->query('q');

        $visitsQuery = $tenant->visits()
            ->with('visitor')
            ->latest('scanned_at');

        if ($search) {
            $visitsQuery->whereHas('visitor', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $visits = $visitsQuery->paginate(15)->withQueryString();
        $totalLeads = $tenant->visits()->count();

        return view('tenant.dashboard', compact('tenant', 'visits', 'totalLeads', 'search'));
    }

    /**
     * Download daftar leads dalam format Excel (.xlsx) atau CSV (.csv).
     */
    public function export(Request $request)
    {
        $tenant = $request->user()->tenant;

        if (!$tenant) {
            abort(403, 'Akses ditolak. Profil tenant tidak ditemukan.');
        }

        $safeTenantName = Str::slug($tenant->tenant_name);
        $format = strtolower($request->query('format', 'xlsx'));

        // Format CSV Stream (Sangat handal & langsung bisa dibuka di Microsoft Excel tanpa library tambahan)
        if ($format === 'csv') {
            $fileName = 'leads_' . $safeTenantName . '_' . date('Ymd_His') . '.csv';

            $headers = [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $callback = function () use ($tenant) {
                $file = fopen('php://output', 'w');
                // Output UTF-8 BOM agar Microsoft Excel membaca spasi dan huruf dengan benar
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, ['No', 'Nama Pengunjung', 'Email', 'No. Telepon / WhatsApp', 'Waktu Scan']);

                $visits = $tenant->visits()->with('visitor')->latest('scanned_at')->get();
                $no = 1;

                foreach ($visits as $visit) {
                    fputcsv($file, [
                        $no++,
                        $visit->visitor->name ?? '-',
                        $visit->visitor->email ?? '-',
                        $visit->visitor->phone ?? '-',
                        $visit->scanned_at ? $visit->scanned_at->format('Y-m-d H:i:s') : '-',
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Standard Excel (.xlsx) via Maatwebsite Excel
        $fileName = 'leads_' . $safeTenantName . '_' . date('Ymd_His') . '.xlsx';
        
        try {
            return Excel::download(new TenantLeadsExport($tenant->id), $fileName);
        } catch (\Throwable $e) {
            // Automatic fallback to CSV if Excel library or zip extension is missing
            return redirect()->route('tenant.export', ['format' => 'csv']);
        }
    }
}
