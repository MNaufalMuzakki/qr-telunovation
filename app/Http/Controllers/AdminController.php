<?php

namespace App\Http\Controllers;

use App\Exports\TenantLeadsExport;
use App\Models\Tenant;
use App\Models\TenantAccessCode;
use App\Models\User;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    /**
     * Dashboard Analitik Real-Time & Leaderboard Tenant.
     */
    public function dashboard()
    {
        $totalVisitors = Visitor::count();
        $totalScans = Visit::count();
        $activeVisitors = Visitor::has('visits')->count();
        $totalRewardsClaimed = Visitor::where('is_reward_claimed', true)->count();
        $percentageActive = $totalVisitors > 0 ? round(($activeVisitors / $totalVisitors) * 100, 1) : 0;

        // Leaderboard Tenant dengan perolehan scan terbanyak
        $leaderboard = Tenant::withCount('visits')
            ->orderByDesc('visits_count')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalVisitors',
            'totalScans',
            'activeVisitors',
            'percentageActive',
            'totalRewardsClaimed',
            'leaderboard'
        ));
    }

    /**
     * Halaman Manajemen Tenant (Daftar Tenant & Manajemen Kode Akses).
     */
    public function tenantsIndex()
    {
        $tenants = Tenant::with('user')->withCount('visits')->latest()->paginate(10);
        $accessCodes = TenantAccessCode::with('usedBy')->latest()->get();

        return view('admin.tenants', compact('tenants', 'accessCodes'));
    }

    /**
     * Lihat Dashboard Leads milik Tenant Spesifik (Super Admin View).
     */
    public function tenantLeads(Request $request, $tenantId)
    {
        $tenant = Tenant::with('user')->findOrFail($tenantId);
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

        return view('admin.tenant_leads', compact('tenant', 'visits', 'totalLeads', 'search'));
    }

    /**
     * Hapus Tenant dan Akun Penggunanya.
     */
    public function deleteTenant($id)
    {
        $tenant = Tenant::findOrFail($id);
        $user = $tenant->user;

        DB::transaction(function () use ($tenant, $user) {
            $tenant->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.tenants.index')->with('success', 'Akun Tenant berhasil dihapus!');
    }

    /**
     * Kelola Seluruh Data Pengunjung (CRUD Visitor oleh Admin).
     */
    public function visitorsIndex(Request $request)
    {
        $search = $request->query('q');

        $visitorsQuery = Visitor::with(['visits.tenant'])
            ->withCount('visits')
            ->latest();

        if ($search) {
            $visitorsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $visitors = $visitorsQuery->paginate(15)->withQueryString();

        return view('admin.visitors', compact('visitors', 'search'));
    }

    /**
     * Update Data Pengunjung oleh Admin.
     */
    public function updateVisitor(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:visitors,email,' . $id],
            'phone' => ['required', 'string', 'max:50'],
        ]);

        $visitor->update([
            'name'  => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => trim($validated['phone']),
        ]);

        return back()->with('success', "Data pengunjung {$visitor->name} berhasil diperbarui!");
    }

    /**
     * Hapus Pengunjung beserta Seluruh Data Scan/Stempelnya.
     */
    public function deleteVisitor($id)
    {
        $visitor = Visitor::findOrFail($id);
        $name = $visitor->name;
        $visitor->delete();

        return back()->with('success', "Data pengunjung \"{$name}\" berhasil dihapus!");
    }

    /**
     * Hapus Satu Record Scan / Visit.
     */
    public function deleteVisit($id)
    {
        $visit = Visit::findOrFail($id);
        $visit->delete();

        return back()->with('success', 'Record scan pengunjung berhasil dihapus!');
    }

    /**
     * Buat akun tenant baru secara manual oleh Admin.
     */
    public function storeTenant(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', Password::defaults()],
            'tenant_name'  => ['required', 'string', 'max:255'],
            'booth_number' => ['required', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'tenant',
            ]);

            Tenant::create([
                'user_id'      => $user->id,
                'tenant_name'  => $validated['tenant_name'],
                'booth_number' => $validated['booth_number'],
            ]);
        });

        return back()->with('success', 'Akun Tenant dan data Booth berhasil didaftarkan oleh Admin!');
    }

    /**
     * Buat Kode Akses Kredensial Tenant Baru.
     */
    public function storeTenantCode(Request $request)
    {
        $request->validate([
            'code'         => ['nullable', 'string', 'max:50', 'unique:tenant_access_codes,code'],
            'booth_number' => ['nullable', 'string', 'max:50'],
            'notes'        => ['nullable', 'string', 'max:255'],
        ], [
            'code.unique' => 'Kode Akses ini sudah pernah dibuat sebelumnya.',
        ]);

        $code = $request->code ? strtoupper(trim($request->code)) : 'BTP-' . strtoupper(Str::random(6));

        TenantAccessCode::create([
            'code'         => $code,
            'booth_number' => $request->booth_number ? strtoupper(trim($request->booth_number)) : null,
            'notes'        => $request->notes,
        ]);

        return back()->with('success', "Kode Akses Tenant [{$code}] berhasil dibuat!");
    }

    /**
     * Hapus Kode Akses Tenant.
     */
    public function deleteTenantCode($id)
    {
        $accessCode = TenantAccessCode::findOrFail($id);
        $accessCode->delete();

        return back()->with('success', 'Kode Akses Tenant berhasil dihapus!');
    }

    /**
     * Halaman Redemption Station (Meja Validasi Hadiah).
     */
    public function redemptionStation(Request $request)
    {
        $searchUuid = $request->query('uuid');
        $visitor = null;

        if ($searchUuid) {
            $visitor = Visitor::with(['visits.tenant'])
                ->where('qr_code_id', $searchUuid)
                ->first();
        }

        $minStampsRequired = 3;

        return view('admin.redemption', compact('visitor', 'searchUuid', 'minStampsRequired'));
    }

    /**
     * Eksekusi klaim reward untuk pengunjung.
     */
    public function claimReward(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
        ]);

        $visitor = Visitor::withCount('visits')->findOrFail($request->visitor_id);

        if ($visitor->is_reward_claimed) {
            return back()->with('error', 'Hadiah untuk pengunjung ini sudah pernah diklaim sebelumnya!');
        }

        if ($visitor->visits_count < 3) {
            return back()->with('error', 'Jumlah stempel pengunjung belum mencukupi (minimal 3 stempel)!');
        }

        $visitor->update(['is_reward_claimed' => true]);

        return back()->with('success', "Selamat! Hadiah berhasil diklaim untuk pengunjung {$visitor->name}.");
    }
}
