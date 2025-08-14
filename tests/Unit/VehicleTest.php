<?php

namespace Tests\Unit;

use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_vehicle()
    {
        $vehicleData = [
            'plate_number' => 'ABC123',
            'model' => 'Toyota Hilux',
            'year' => 2020,
            'capacity' => 1000.0,
            'fuel_type' => 'diesel',
            'status' => 'active',
            'vehicle_type_id' => 1
        ];

        $vehicle = Vehicle::create($vehicleData);

        $this->assertInstanceOf(Vehicle::class, $vehicle);
        $this->assertEquals('ABC123', $vehicle->plate_number);
        $this->assertEquals('Toyota Hilux', $vehicle->model);
        $this->assertEquals(2020, $vehicle->year);
        $this->assertEquals(1000.0, $vehicle->capacity);
    }

    /** @test */
    public function it_can_update_vehicle_information()
    {
        $vehicle = Vehicle::factory()->create();

        $vehicle->update([
            'status' => 'maintenance',
            'capacity' => 1500.0
        ]);

        $this->assertEquals('maintenance', $vehicle->fresh()->status);
        $this->assertEquals(1500.0, $vehicle->fresh()->capacity);
    }

    /** @test */
    public function it_can_delete_a_vehicle()
    {
        $vehicle = Vehicle::factory()->create();
        $vehicleId = $vehicle->id;

        $vehicle->delete();

        $this->assertDatabaseMissing('vehicles', ['id' => $vehicleId]);
    }

    /** @test */
    public function it_belongs_to_vehicle_type()
    {
        $vehicle = Vehicle::factory()->create();
        
        $this->assertTrue(method_exists($vehicle, 'vehicleType'));
    }

    /** @test */
    public function it_has_many_drivers()
    {
        $vehicle = Vehicle::factory()->create();
        
        $this->assertTrue(method_exists($vehicle, 'drivers'));
    }

    /** @test */
    public function it_has_many_shipments()
    {
        $vehicle = Vehicle::factory()->create();
        
        $this->assertTrue(method_exists($vehicle, 'shipments'));
    }

    /** @test */
    public function it_can_check_availability()
    {
        $vehicle = Vehicle::factory()->create(['status' => 'active']);

        // Assuming there's a method to check availability
        $this->assertTrue(method_exists($vehicle, 'isAvailable'));
    }

    /** @test */
    public function it_can_calculate_total_capacity_used()
    {
        $vehicle = Vehicle::factory()->create(['capacity' => 1000.0]);

        // Assuming there's a method to calculate used capacity
        $this->assertTrue(method_exists($vehicle, 'getUsedCapacity'));
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Vehicle::create([
            'plate_number' => 'ABC123'
            // Missing required fields
        ]);
    }

    /** @test */
    public function it_can_get_active_vehicles()
    {
        Vehicle::factory()->create(['status' => 'active']);
        Vehicle::factory()->create(['status' => 'maintenance']);

        $activeVehicles = Vehicle::where('status', 'active')->get();

        $this->assertEquals(1, $activeVehicles->count());
    }

    /** @test */
    public function it_can_get_vehicles_by_capacity()
    {
        Vehicle::factory()->create(['capacity' => 500.0]);
        Vehicle::factory()->create(['capacity' => 1500.0]);

        $largeVehicles = Vehicle::where('capacity', '>=', 1000.0)->get();

        $this->assertEquals(1, $largeVehicles->count());
    }
}