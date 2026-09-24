<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes: Portal, Registrasi Pengunjung & Registrasi Tenant
|--------------------------------------------------------------------------
*/
// Portal Utama Pilihan Peran (Telkom University Bandung Techno Park)
Route::get('/', [PortalController::class, 'index'])->name('portal');

// Pintu Pengunjung
Route::get('/visitor/register', [VisitorController::class, 'showRegistrationForm'])->name('visitor.register');
Route::post('/visitor/register', [VisitorController::class, 'register'])->name('visitor.register.submit');
Route::get('/pass/{uuid}', [VisitorController::class, 'showPass'])->name('visitor.pass');

// Pintu Tenant (Dengan Kode Akses Kredensial Admin)
Route::get('/tenant/register', [TenantRegistrationController::class, 'showForm'])->name('tenant.register');
Route::post('/tenant/register', [TenantRegistrationController::class, 'register'])->name('tenant.register.submit');

/*
|--------------------------------------------------------------------------
| Authenticated Routes: Multi-Role Dashboard Redirector & Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Redirector otomatis berdasarkan Role
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('tenant.dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Tenant Routes (DILINDUNGI MIDDLEWARE TENANT)
    |--------------------------------------------------------------------------
    */
    Route::prefix('tenant')->name('tenant.')->middleware(['tenant'])->group(function () {
        Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');
        Route::get('/export', [TenantDashboardController::class, 'export'])->name('export');
        
        // Halaman Web Scanner
        Route::view('/scanner', 'tenant.scanner')->name('scanner');
        
        // Endpoint penerima scan Axios
        Route::post('/scan/process', [ScanController::class, 'processScan'])
            ->middleware('throttle:60,1')
            ->name('scan.process');
    });

    /*
    |--------------------------------------------------------------------------
    | Super Admin Routes (DILINDUNGI MIDDLEWARE SUPER ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Manajemen Tenant & Kode Akses
        Route::get('/tenants', [AdminController::class, 'tenantsIndex'])->name('tenants.index');
        Route::post('/tenants', [AdminController::class, 'storeTenant'])->name('tenants.store');
        Route::post('/tenant-codes', [AdminController::class, 'storeTenantCode'])->name('tenant_codes.store');
        Route::delete('/tenant-codes/{id}', [AdminController::class, 'deleteTenantCode'])->name('tenant_codes.delete');

        // Redemption Station
        Route::get('/redemption', [AdminController::class, 'redemptionStation'])->name('redemption');
        Route::post('/redemption/claim', [AdminController::class, 'claimReward'])->name('redemption.claim');
    });

    /*
    |--------------------------------------------------------------------------
    | User Profile Settings
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
