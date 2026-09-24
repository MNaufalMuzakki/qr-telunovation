<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExhibitionSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_registration_and_pass_generation(): void
    {
        $response = $this->post(route('visitor.register.submit'), [
            'name'  => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '08123456789',
        ]);

        $visitor = Visitor::where('email', 'john@example.com')->first();
        $this->assertNotNull($visitor);
        $this->assertNotEmpty($visitor->qr_code_id);

        $response->assertRedirect(route('visitor.pass', ['uuid' => $visitor->qr_code_id]));

        // Cek halaman digital pass
        $passResponse = $this->get(route('visitor.pass', ['uuid' => $visitor->qr_code_id]));
        $passResponse->assertStatus(200);
        $passResponse->assertSee('John Doe');
        $passResponse->assertSee('Progres Stempel Digital');
    }

    public function test_tenant_can_scan_visitor_and_prevents_duplicate_scan(): void
    {
        $tenantUser = User::factory()->create(['role' => 'tenant']);
        $tenant = Tenant::create([
            'user_id'      => $tenantUser->id,
            'tenant_name'  => 'Alpha Tech',
            'booth_number' => 'A-01',
        ]);

        $visitor = Visitor::create([
            'qr_code_id' => (string) \Illuminate\Support\Str::uuid(),
            'name'       => 'Jane Doe',
            'email'      => 'jane@example.com',
            'phone'      => '08987654321',
        ]);

        // Scan pertama: Berhasil
        $response = $this->actingAs($tenantUser)->postJson(route('tenant.scan.process'), [
            'qr_code_id' => $visitor->qr_code_id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('visits', [
            'tenant_id'  => $tenant->id,
            'visitor_id' => $visitor->id,
        ]);

        // Scan kedua: Ditolak (Anti-Double Scan)
        $duplicateResponse = $this->actingAs($tenantUser)->postJson(route('tenant.scan.process'), [
            'qr_code_id' => $visitor->qr_code_id,
        ]);

        $duplicateResponse->assertStatus(409);
        $duplicateResponse->assertJson(['success' => false, 'status_code' => 'ALREADY_SCANNED']);
    }
}
