<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
     * Halaman Manajemen Tenant (Daftar & Form Tambah Tenant).
     */
    public function tenantsIndex()
    {
        $tenants = Tenant::with('user')->withCount('visits')->latest()->paginate(10);
        return view('admin.tenants', compact('tenants'));
    }

    /**
     * Buat akun tenant baru beserta relasi ke booth.
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

        return back()->with('success', 'Akun Tenant dan data Booth berhasil didaftarkan!');
    }

    /**
     * Halaman Redemption Station (Meja Validasi Hadiah).
     */
    public function redemptionStation(Request $request)
    {
        $searchUuid = $request->query('uuid');
        $visitor = null;

        if ($searchUuid) {
            $visitor = Visitor::where('qr_code_id', trim($searchUuid))
                ->with(['visits.tenant'])
                ->withCount('visits')
                ->first();
        }

        return view('admin.redemption', compact('visitor', 'searchUuid'));
    }

    /**
     * Eksekusi klaim hadiah bagi pengunjung yang memenuhi syarat stempel.
     */
    public function claimReward(Request $request)
    {
        $request->validate([
            'qr_code_id' => ['required', 'uuid'],
        ]);

        $visitor = Visitor::where('qr_code_id', $request->input('qr_code_id'))
            ->withCount('visits')
            ->firstOrFail();

        if ($visitor->is_reward_claimed) {
            return back()->with('error', 'Hadiah untuk pengunjung ini sudah pernah diklaim sebelumnya!');
        }

        // Minimal 5 stempel untuk klaim
        if ($visitor->visits_count < 5) {
            return back()->with('error', "Stempel belum mencukupi. Pengunjung baru mengumpulkan {$visitor->visits_count} dari minimal 5 stempel.");
        }

        $visitor->update(['is_reward_claimed' => true]);

        return back()->with('success', "Selamat! Hadiah berhasil diserahkan kepada {$visitor->name}. Status hadiah telah ditandai.");
    }
}
