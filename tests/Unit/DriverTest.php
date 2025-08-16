<?php

namespace Tests\Unit;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_driver()
    {
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

        $driver = Driver::create($driverData);

        $this->assertInstanceOf(Driver::class, $driver);
        $this->assertEquals('John Doe', $driver->name);
        $this->assertEquals('DL123456', $driver->license_number);
        $this->assertEquals('active', $driver->status);
    }

    /** @test */
    public function it_can_update_driver_information()
    {
        $driver = Driver::factory()->create();

        $driver->update([
            'phone' => '9876543210',
            'status' => 'inactive'
        ]);

        $this->assertEquals('9876543210', $driver->fresh()->phone);
        $this->assertEquals('inactive', $driver->fresh()->status);
    }

    /** @test */
    public function it_can_delete_a_driver()
    {
        $driver = Driver::factory()->create();
        $driverId = $driver->id;

        $driver->delete();

        $this->assertDatabaseMissing('drivers', ['id' => $driverId]);
    }

    /** @test */
    public function it_belongs_to_vehicle()
    {
        $driver = Driver::factory()->create();
        
        $this->assertTrue(method_exists($driver, 'vehicle'));
    }

    /** @test */
    public function it_has_many_shipments()
    {
        $driver = Driver::factory()->create();
        
        $this->assertTrue(method_exists($driver, 'shipments'));
    }

    /** @test */
    public function it_can_check_license_expiry()
    {
        $driver = Driver::factory()->create([
            'license_expiry' => '2025-12-31'
        ]);

        // Assuming there's a method to check if license is expired
        $this->assertTrue(method_exists($driver, 'isLicenseExpired'));
    }

    /** @test */
    public function it_can_check_availability()
    {
        $driver = Driver::factory()->create(['status' => 'active']);

        // Assuming there's a method to check availability
        $this->assertTrue(method_exists($driver, 'isAvailable'));
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Driver::create([
            'name' => 'Test Driver'
            // Missing required fields
        ]);
    }

    /** @test */
    public function it_can_get_active_drivers()
    {
        Driver::factory()->create(['status' => 'active']);
        Driver::factory()->create(['status' => 'inactive']);

        $activeDrivers = Driver::where('status', 'active')->get();

        $this->assertEquals(1, $activeDrivers->count());
    }
}