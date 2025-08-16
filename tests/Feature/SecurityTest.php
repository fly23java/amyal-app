<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'user']);
    }

    /** @test */
    public function user_cannot_access_admin_panel()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_other_user_profile()
    {
        $this->actingAs($this->regularUser);
        $otherUser = User::factory()->create();

        $response = $this->get("/users/{$otherUser->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_update_other_user_profile()
    {
        $this->actingAs($this->regularUser);
        $otherUser = User::factory()->create();

        $response = $this->put("/users/{$otherUser->id}", [
            'name' => 'Hacked Name'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_delete_other_user()
    {
        $this->actingAs($this->regularUser);
        $otherUser = User::factory()->create();

        $response = $this->delete("/users/{$otherUser->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_shipment_management_without_auth()
    {
        $response = $this->get('/shipments');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_create_shipment_without_auth()
    {
        $response = $this->post('/shipments', []);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_update_shipment_without_auth()
    {
        $shipment = Shipment::factory()->create();

        $response = $this->put("/shipments/{$shipment->id}", []);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_delete_shipment_without_auth()
    {
        $shipment = Shipment::factory()->create();

        $response = $this->delete("/shipments/{$shipment->id}");

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_access_driver_management_without_auth()
    {
        $response = $this->get('/drivers');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_access_reports_without_auth()
    {
        $response = $this->get('/reports');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_access_settings_without_auth()
    {
        $response = $this->get('/settings');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_access_profile_without_auth()
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_access_dashboard_without_auth()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_access_admin_users_without_admin_role()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin/users');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_admin_shipments_without_admin_role()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin/shipments');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_admin_drivers_without_admin_role()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin/drivers');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_admin_reports_without_admin_role()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin/reports');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_admin_settings_without_admin_role()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin/settings');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_admin_logs_without_admin_role()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin/logs');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_admin_backup_without_admin_role()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin/backup');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_admin_restore_without_admin_role()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin/restore');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_admin_export_without_admin_role()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin/export');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_access_admin_import_without_admin_role()
    {
        $this->actingAs($this->regularUser);

        $response = $this->get('/admin/import');

        $response->assertStatus(403);
    }
}