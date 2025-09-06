<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Shipment;
use App\Models\Account;
use App\Models\City;
use App\Models\VehicleType;
use App\Models\Goods;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ShipmentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        // Create required reference data
        Account::factory()->create(['id' => 1]);
        City::factory()->create(['id' => 1]);
        City::factory()->create(['id' => 2]);
        VehicleType::factory()->create(['id' => 1]);
        Goods::factory()->create(['id' => 1]);
        Status::factory()->create(['id' => 1]);
    }

    /** @test */
    public function authenticated_user_can_view_shipments_index()
    {
        // Arrange
        $this->actingAs($this->user);
        Shipment::factory()->count(3)->create();

        // Act
        $response = $this->get(route('shipments.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('shipments.index');
        $response->assertViewHas('shipments');
    }

    /** @test */
    public function guest_cannot_view_shipments_index()
    {
        // Act
        $response = $this->get(route('shipments.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_view_create_shipment_form()
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->get(route('shipments.create'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('shipments.create');
    }

    /** @test */
    public function authenticated_user_can_create_shipment()
    {
        // Arrange
        $this->actingAs($this->user);
        
        $shipmentData = [
            'account_id' => 1,
            'loading_city_id' => 1,
            'unloading_city_id' => 2,
            'vehicle_type_id' => 1,
            'goods_id' => 1,
            'price' => 1000.00,
        ];

        // Act
        $response = $this->post(route('shipments.store'), $shipmentData);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('shipments', [
            'account_id' => 1,
            'price' => 1000.00,
            'status_id' => 1
        ]);
        $this->assertDatabaseHas('status_changes', [
            'status_id' => 1,
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function create_shipment_requires_valid_data()
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->post(route('shipments.store'), []);

        // Assert
        $response->assertSessionHasErrors([
            'account_id',
            'loading_city_id',
            'unloading_city_id',
            'vehicle_type_id',
            'goods_id',
            'price'
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_shipment_details()
    {
        // Arrange
        $this->actingAs($this->user);
        $shipment = Shipment::factory()->create();

        // Act
        $response = $this->get(route('shipments.show', $shipment));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('shipments.show');
        $response->assertViewHas('shipment');
    }

    /** @test */
    public function authenticated_user_can_view_edit_shipment_form()
    {
        // Arrange
        $this->actingAs($this->user);
        $shipment = Shipment::factory()->create();

        // Act
        $response = $this->get(route('shipments.edit', $shipment));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('shipments.edit');
        $response->assertViewHas('shipment');
    }

    /** @test */
    public function authenticated_user_can_update_shipment()
    {
        // Arrange
        $this->actingAs($this->user);
        $shipment = Shipment::factory()->create(['price' => 1000.00]);
        
        $updateData = [
            'account_id' => $shipment->account_id,
            'loading_city_id' => $shipment->loading_city_id,
            'unloading_city_id' => $shipment->unloading_city_id,
            'vehicle_type_id' => $shipment->vehicle_type_id,
            'goods_id' => $shipment->goods_id,
            'price' => 1500.00,
        ];

        // Act
        $response = $this->put(route('shipments.update', $shipment), $updateData);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('shipments', [
            'id' => $shipment->id,
            'price' => 1500.00
        ]);
    }

    /** @test */
    public function authenticated_user_can_delete_shipment()
    {
        // Arrange
        $this->actingAs($this->user);
        $shipment = Shipment::factory()->create();

        // Act
        $response = $this->delete(route('shipments.destroy', $shipment));

        // Assert
        $response->assertRedirect();
        $this->assertSoftDeleted('shipments', ['id' => $shipment->id]);
    }

    /** @test */
    public function shipment_index_can_be_filtered_by_status()
    {
        // Arrange
        $this->actingAs($this->user);
        Shipment::factory()->count(3)->create(['status_id' => 1]);
        Shipment::factory()->count(2)->create(['status_id' => 2]);

        // Act
        $response = $this->get(route('shipments.index', ['status_id' => 1]));

        // Assert
        $response->assertStatus(200);
        $shipments = $response->viewData('shipments');
        $this->assertCount(3, $shipments->items());
    }

    /** @test */
    public function shipment_index_can_be_filtered_by_account()
    {
        // Arrange
        $this->actingAs($this->user);
        Account::factory()->create(['id' => 2]);
        
        Shipment::factory()->count(4)->create(['account_id' => 1]);
        Shipment::factory()->count(2)->create(['account_id' => 2]);

        // Act
        $response = $this->get(route('shipments.index', ['account_id' => 1]));

        // Assert
        $response->assertStatus(200);
        $shipments = $response->viewData('shipments');
        $this->assertCount(4, $shipments->items());
    }

    /** @test */
    public function shipment_index_can_be_searched_by_serial_number()
    {
        // Arrange
        $this->actingAs($this->user);
        Shipment::factory()->create(['serial_number' => 'SEARCH-001']);
        Shipment::factory()->create(['serial_number' => 'SEARCH-002']);
        Shipment::factory()->create(['serial_number' => 'OTHER-001']);

        // Act
        $response = $this->get(route('shipments.index', ['serial_number' => 'SEARCH']));

        // Assert
        $response->assertStatus(200);
        $shipments = $response->viewData('shipments');
        $this->assertCount(2, $shipments->items());
    }

    /** @test */
    public function shipment_creation_generates_serial_number()
    {
        // Arrange
        $this->actingAs($this->user);
        
        $shipmentData = [
            'account_id' => 1,
            'loading_city_id' => 1,
            'unloading_city_id' => 2,
            'vehicle_type_id' => 1,
            'goods_id' => 1,
            'price' => 1000.00,
        ];

        // Act
        $response = $this->post(route('shipments.store'), $shipmentData);

        // Assert
        $shipment = Shipment::latest()->first();
        $this->assertNotNull($shipment->serial_number);
        $this->assertStringContainsString(now()->format('Ymd'), $shipment->serial_number);
    }

    /** @test */
    public function shipment_status_can_be_updated()
    {
        // Arrange
        $this->actingAs($this->user);
        Status::factory()->create(['id' => 2]);
        $shipment = Shipment::factory()->create(['status_id' => 1]);

        // Act
        $response = $this->patch(route('shipments.update-status', $shipment), [
            'status_id' => 2
        ]);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('shipments', [
            'id' => $shipment->id,
            'status_id' => 2
        ]);
        $this->assertDatabaseHas('status_changes', [
            'shipment_id' => $shipment->id,
            'status_id' => 2,
            'user_id' => $this->user->id
        ]);
    }
}