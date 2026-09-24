<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes: Visitor Registration & Digital Pass
|--------------------------------------------------------------------------
*/
Route::get('/', [VisitorController::class, 'showRegistrationForm'])->name('visitor.register');
Route::post('/visitor/register', [VisitorController::class, 'register'])->name('visitor.register.submit');
Route::get('/pass/{uuid}', [VisitorController::class, 'showPass'])->name('visitor.pass');

/*
|--------------------------------------------------------------------------
| Authenticated Routes: Multi-Role Dashboard Redirector
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('tenant.dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Tenant Routes (Exhibitor / Booth)
    |--------------------------------------------------------------------------
    */
    Route::prefix('tenant')->name('tenant.')->group(function () {
        Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');
        Route::get('/export', [TenantDashboardController::class, 'export'])->name('export');
        
        // Halaman Web Scanner
        Route::view('/scanner', 'tenant.scanner')->name('scanner');
        
        // Endpoint penerima scan Axios (dengan rate limiting agar terhindar dari spam)
        Route::post('/scan/process', [ScanController::class, 'processScan'])
            ->middleware('throttle:60,1')
            ->name('scan.process');
    });

    /*
    |--------------------------------------------------------------------------
    | Super Admin Routes (Panitia Event)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/tenants', [AdminController::class, 'tenantsIndex'])->name('tenants.index');
        Route::post('/tenants', [AdminController::class, 'storeTenant'])->name('tenants.store');
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
