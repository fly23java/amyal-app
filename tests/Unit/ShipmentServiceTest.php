<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ShipmentService;
use App\Models\Shipment;
use App\Models\StatusChange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;

class ShipmentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $shipmentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shipmentService = new ShipmentService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_store_a_shipment_successfully()
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);

        $data = [
            'account_id' => 1,
            'loading_city_id' => 1,
            'unloading_city_id' => 2,
            'vehicle_type_id' => 1,
            'goods_id' => 1,
            'price' => 1000.00
        ];

        // Act
        $result = $this->shipmentService->store($data);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('shipmentId', $result);
        $this->assertArrayHasKey('serial_number', $result);
        
        $this->assertDatabaseHas('shipments', [
            'account_id' => $data['account_id'],
            'price' => $data['price'],
            'status_id' => 1
        ]);

        $this->assertDatabaseHas('status_changes', [
            'shipment_id' => $result['shipmentId'],
            'status_id' => 1,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function it_generates_unique_serial_numbers()
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);

        $data = [
            'account_id' => 1,
            'loading_city_id' => 1,
            'unloading_city_id' => 2,
            'vehicle_type_id' => 1,
            'goods_id' => 1,
            'price' => 1000.00
        ];

        // Act
        $result1 = $this->shipmentService->store($data);
        $result2 = $this->shipmentService->store($data);

        // Assert
        $this->assertNotEquals($result1['serial_number'], $result2['serial_number']);
    }

    /** @test */
    public function it_returns_error_when_store_fails()
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);

        // Mock Shipment to throw exception
        $this->mock(Shipment::class, function ($mock) {
            $mock->shouldReceive('create')->andThrow(new \Exception('Database error'));
        });

        $data = [
            'account_id' => 1,
            'loading_city_id' => 1,
            'unloading_city_id' => 2,
            'vehicle_type_id' => 1,
            'goods_id' => 1,
            'price' => 1000.00
        ];

        // Act
        $result = $this->shipmentService->store($data);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error_message', $result);
    }

    /** @test */
    public function it_can_get_serial_number_attribute()
    {
        // Act
        $serialNumber = $this->shipmentService->getSerialNumberAttribute();

        // Assert
        $this->assertIsString($serialNumber);
        $this->assertEquals(4, strlen($serialNumber));
        $this->assertMatchesRegularExpression('/^\d{4}$/', $serialNumber);
    }

    /** @test */
    public function it_increments_serial_number_for_same_day()
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);

        // Create a shipment first
        $shipment = Shipment::factory()->create([
            'serial_number' => '1-1-2-' . now()->format('Ymd') . '0001',
            'created_at' => now()
        ]);

        // Act
        $newSerialNumber = $this->shipmentService->getSerialNumberAttribute();

        // Assert
        $this->assertEquals('0002', $newSerialNumber);
    }

    /** @test */
    public function it_resets_serial_number_for_new_day()
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);

        // Create a shipment for yesterday
        $shipment = Shipment::factory()->create([
            'serial_number' => '1-1-2-' . now()->subDay()->format('Ymd') . '0005',
            'created_at' => now()->subDay()
        ]);

        // Act
        $newSerialNumber = $this->shipmentService->getSerialNumberAttribute();

        // Assert
        $this->assertEquals('0001', $newSerialNumber);
    }

    /** @test */
    public function it_can_prepare_shipment_data()
    {
        // Arrange
        $shipment = Shipment::factory()->create([
            'serial_number' => 'TEST-001',
            'price' => 1500.00
        ]);

        // Act
        $data = $this->shipmentService->prepareShipmentData($shipment->id);

        // Assert
        $this->assertIsArray($data);
        $this->assertEquals($shipment->id, $data['id']);
        $this->assertEquals('TEST-001', $data['serial_number']);
        $this->assertEquals(1500.00, $data['price']);
        $this->assertArrayHasKey('account_name', $data);
        $this->assertArrayHasKey('loading_city', $data);
        $this->assertArrayHasKey('unloading_city', $data);
    }
}