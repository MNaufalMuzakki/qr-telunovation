<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Super Admin
        User::create([
            'name'     => 'Super Admin Panitia',
            'email'    => 'admin@event.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // 2. Akun Tenant Booth A-01
        $tenantUser1 = User::create([
            'name'     => 'PIC Samsung',
            'email'    => 'samsung@event.com',
            'password' => Hash::make('password'),
            'role'     => 'tenant',
        ]);
        Tenant::create([
            'user_id'      => $tenantUser1->id,
            'tenant_name'  => 'Samsung Innovation Hub',
            'booth_number' => 'A-01',
        ]);

        // 3. Akun Tenant Booth B-02
        $tenantUser2 = User::create([
            'name'     => 'PIC Telkom',
            'email'    => 'telkom@event.com',
            'password' => Hash::make('password'),
            'role'     => 'tenant',
        ]);
        Tenant::create([
            'user_id'      => $tenantUser2->id,
            'tenant_name'  => 'Telkom Digital Solution',
            'booth_number' => 'B-02',
        ]);

        // 4. Akun Tenant Booth C-03
        $tenantUser3 = User::create([
            'name'     => 'PIC Google Cloud',
            'email'    => 'google@event.com',
            'password' => Hash::make('password'),
            'role'     => 'tenant',
        ]);
        Tenant::create([
            'user_id'      => $tenantUser3->id,
            'tenant_name'  => 'Google Cloud Arena',
            'booth_number' => 'C-03',
        ]);

        // 5. Visitor Dummy untuk demonstrasi awal
        Visitor::create([
            'qr_code_id'        => (string) Str::uuid(),
            'name'              => 'Ahmad Fauzi',
            'email'             => 'fauzi@example.com',
            'phone'             => '081234567890',
            'is_reward_claimed' => false,
        ]);
    }
}
