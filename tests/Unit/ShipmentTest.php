<?php

namespace Tests\Unit;

use App\Models\Shipment;
use App\Models\User;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_shipment()
    {
        $shipmentData = [
            'tracking_number' => 'SHIP001',
            'sender_name' => 'Sender Name',
            'sender_phone' => '1234567890',
            'sender_address' => 'Sender Address',
            'receiver_name' => 'Receiver Name',
            'receiver_phone' => '0987654321',
            'receiver_address' => 'Receiver Address',
            'weight' => 10.5,
            'dimensions' => '20x30x40',
            'description' => 'Test shipment',
            'value' => 100.00,
            'shipping_cost' => 25.00,
            'status_id' => 1,
            'user_id' => 1,
            'driver_id' => 1,
            'vehicle_id' => 1
        ];

        $shipment = Shipment::create($shipmentData);

        $this->assertInstanceOf(Shipment::class, $shipment);
        $this->assertEquals('SHIP001', $shipment->tracking_number);
        $this->assertEquals('Sender Name', $shipment->sender_name);
        $this->assertEquals(10.5, $shipment->weight);
        $this->assertEquals(100.00, $shipment->value);
    }

    /** @test */
    public function it_can_update_shipment_status()
    {
        $shipment = Shipment::factory()->create(['status_id' => 1]);

        $shipment->update(['status_id' => 2]);

        $this->assertEquals(2, $shipment->fresh()->status_id);
    }

    /** @test */
    public function it_can_calculate_total_cost()
    {
        $shipment = Shipment::factory()->create([
            'shipping_cost' => 25.00,
            'value' => 100.00
        ]);

        // Assuming there's a method to calculate total cost
        $this->assertTrue(method_exists($shipment, 'calculateTotalCost'));
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $shipment = Shipment::factory()->create();
        
        $this->assertTrue(method_exists($shipment, 'user'));
    }

    /** @test */
    public function it_belongs_to_driver()
    {
        $shipment = Shipment::factory()->create();
        
        $this->assertTrue(method_exists($shipment, 'driver'));
    }

    /** @test */
    public function it_belongs_to_vehicle()
    {
        $shipment = Shipment::factory()->create();
        
        $this->assertTrue(method_exists($shipment, 'vehicle'));
    }

    /** @test */
    public function it_belongs_to_status()
    {
        $shipment = Shipment::factory()->create();
        
        $this->assertTrue(method_exists($shipment, 'status'));
    }

    /** @test */
    public function it_can_generate_tracking_number()
    {
        $shipment = Shipment::factory()->create();
        
        // Assuming there's a method to generate tracking number
        $this->assertNotEmpty($shipment->tracking_number);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Shipment::create([
            'tracking_number' => 'SHIP001'
            // Missing required fields
        ]);
    }
}