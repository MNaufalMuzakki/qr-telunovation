<?php

namespace App\Http\Controllers;

use App\Exports\TenantLeadsExport;
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
     * Download daftar leads dalam format Excel/XLSX.
     */
    public function export(Request $request)
    {
        $tenant = $request->user()->tenant;

        if (!$tenant) {
            abort(403, 'Akses ditolak. Profil tenant tidak ditemukan.');
        }

        $safeTenantName = Str::slug($tenant->tenant_name);
        $fileName = 'leads_' . $safeTenantName . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new TenantLeadsExport($tenant->id), $fileName);
    }
}
