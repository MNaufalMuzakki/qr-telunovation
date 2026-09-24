<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Visitor;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    /**
     * Halaman Portal Pintu Masuk Exhibition Telkom University Bandung Techno Park.
     * Pengunjung & Tenant memilih opsi pendaftaran/akses di sini.
     */
    public function index()
    {
        $totalTenants = Tenant::count();
        $totalVisitors = Visitor::count();

        return view('portal', compact('totalTenants', 'totalVisitors'));
    }
}
