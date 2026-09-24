<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\TenantAccessCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_registration_fails_without_valid_code(): void
    {
        $response = $this->post(route('tenant.register.submit'), [
            'name'                  => 'PIC Booth Baru',
            'email'                 => 'pic@boothbaru.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'tenant_name'           => 'Booth Inovasi',
            'booth_number'          => 'A-10',
            'access_code'           => 'INVALID-CODE',
        ]);

        $response->assertSessionHasErrors('access_code');
        $this->assertDatabaseMissing('users', ['email' => 'pic@boothbaru.com']);
    }

    public function test_tenant_registration_succeeds_with_valid_access_code(): void
    {
        $code = TenantAccessCode::create([
            'code'         => 'BTP-TEST-99',
            'booth_number' => 'A-10',
            'is_used'      => false,
        ]);

        $response = $this->post(route('tenant.register.submit'), [
            'name'                  => 'PIC Booth Baru',
            'email'                 => 'pic@boothbaru.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'tenant_name'           => 'Booth Inovasi',
            'booth_number'          => 'A-10',
            'access_code'           => 'BTP-TEST-99',
        ]);

        $response->assertRedirect(route('tenant.dashboard'));

        // Cek user terdaftar dengan role tenant
        $user = User::where('email', 'pic@boothbaru.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('tenant', $user->role);

        // Cek tenant terhubung
        $tenant = Tenant::where('user_id', $user->id)->first();
        $this->assertNotNull($tenant);
        $this->assertEquals('Booth Inovasi', $tenant->tenant_name);
        $this->assertEquals('A-10', $tenant->booth_number);

        // Cek kode ditandai terpakai
        $code->refresh();
        $this->assertTrue($code->is_used);
        $this->assertEquals($user->id, $code->used_by_user_id);
    }
}
