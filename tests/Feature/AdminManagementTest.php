<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Visit;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_tenant_leads_inspection(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tenantUser = User::factory()->create(['role' => 'tenant']);
        $tenant = Tenant::create([
            'user_id'      => $tenantUser->id,
            'tenant_name'  => 'Inspect Tenant',
            'booth_number' => 'Z-99',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.tenants.leads', $tenant->id));
        $response->assertStatus(200);
        $response->assertSee('Inspect Tenant');
    }

    public function test_admin_can_manage_visitors_crud(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $visitor = Visitor::create([
            'qr_code_id' => (string) \Illuminate\Support\Str::uuid(),
            'name'       => 'Test Visitor CRUD',
            'email'      => 'crudvisitor@example.com',
            'phone'      => '081299988877',
        ]);

        // Cek index pengunjung
        $indexResponse = $this->actingAs($admin)->get(route('admin.visitors.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Test Visitor CRUD');

        // Update data pengunjung
        $updateResponse = $this->actingAs($admin)->put(route('admin.visitors.update', $visitor->id), [
            'name'  => 'Test Visitor Updated',
            'email' => 'crudvisitor@example.com',
            'phone' => '081299988877',
        ]);
        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('visitors', ['name' => 'Test Visitor Updated']);

        // Hapus data pengunjung
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.visitors.delete', $visitor->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('visitors', ['id' => $visitor->id]);
    }
}
