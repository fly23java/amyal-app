<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DriverManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_view_drivers_list()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/drivers');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_new_driver()
    {
        $this->actingAs($this->admin);

        $driverData = [
            'name' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'license_number' => 'DL123456',
            'license_expiry' => '2025-12-31',
            'vehicle_id' => 1,
            'status' => 'active',
            'address' => 'Driver Address',
            'emergency_contact' => '9876543210'
        ];

        $response = $this->post('/drivers', $driverData);

        $this->assertDatabaseHas('drivers', [
            'name' => 'John Doe',
            'license_number' => 'DL123456',
            'status' => 'active'
        ]);
    }

    /** @test */
    public function admin_can_view_driver_details()
    {
        $this->actingAs($this->admin);
        
        $driver = Driver::factory()->create();

        $response = $this->get("/drivers/{$driver->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_update_driver()
    {
        $this->actingAs($this->admin);
        
        $driver = Driver::factory()->create();

        $updateData = [
            'phone' => '9876543210',
            'status' => 'inactive'
        ];

        $response = $this->put("/drivers/{$driver->id}", $updateData);

        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'phone' => '9876543210',
            'status' => 'inactive'
        ]);
    }

    /** @test */
    public function admin_can_delete_driver()
    {
        $this->actingAs($this->admin);
        
        $driver = Driver::factory()->create();

        $response = $this->delete("/drivers/{$driver->id}");

        $this->assertDatabaseMissing('drivers', ['id' => $driver->id]);
    }

    /** @test */
    public function admin_can_search_drivers()
    {
        $this->actingAs($this->admin);
        
        Driver::factory()->create(['name' => 'John Doe']);
        Driver::factory()->create(['name' => 'Jane Smith']);

        $response = $this->get('/drivers?search=John');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_filter_drivers_by_status()
    {
        $this->actingAs($this->admin);
        
        Driver::factory()->create(['status' => 'active']);
        Driver::factory()->create(['status' => 'inactive']);

        $response = $this->get('/drivers?status=active');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_assign_vehicle_to_driver()
    {
        $this->actingAs($this->admin);
        
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create();

        $updateData = ['vehicle_id' => $vehicle->id];

        $response = $this->put("/drivers/{$driver->id}", $updateData);

        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'vehicle_id' => $vehicle->id
        ]);
    }

    /** @test */
    public function non_admin_cannot_access_driver_management()
    {
        $regularUser = User::factory()->create(['role' => 'user']);
        $this->actingAs($regularUser);

        $response = $this->get('/drivers');

        $response->assertStatus(403);
    }

    /** @test */
    public function cannot_create_driver_with_invalid_data()
    {
        $this->actingAs($this->admin);

        $invalidData = [
            'name' => '',
            'license_number' => '',
            'phone' => 'invalid-phone'
        ];

        $response = $this->post('/drivers', $invalidData);

        $this->assertDatabaseMissing('drivers', $invalidData);
    }
}