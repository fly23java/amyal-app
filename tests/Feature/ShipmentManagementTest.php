<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shipment;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShipmentManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function user_can_view_shipments_list()
    {
        $this->actingAs($this->user);

        $response = $this->get('/shipments');

        $response->assertStatus(200);
        $response->assertViewIs('shipments.index');
    }

    /** @test */
    public function user_can_create_new_shipment()
    {
        $this->actingAs($this->user);

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
            'driver_id' => 1,
            'vehicle_id' => 1
        ];

        $response = $this->post('/shipments', $shipmentData);

        $response->assertRedirect('/shipments');
        $this->assertDatabaseHas('shipments', [
            'tracking_number' => 'SHIP001',
            'sender_name' => 'Sender Name'
        ]);
    }

    /** @test */
    public function user_can_view_shipment_details()
    {
        $this->actingAs($this->user);
        
        $shipment = Shipment::factory()->create();

        $response = $this->get("/shipments/{$shipment->id}");

        $response->assertStatus(200);
        $response->assertViewIs('shipments.show');
        $response->assertSee($shipment->tracking_number);
    }

    /** @test */
    public function user_can_update_shipment()
    {
        $this->actingAs($this->user);
        
        $shipment = Shipment::factory()->create();

        $updateData = [
            'sender_phone' => '9876543210',
            'weight' => 15.0
        ];

        $response = $this->put("/shipments/{$shipment->id}", $updateData);

        $response->assertRedirect("/shipments/{$shipment->id}");
        $this->assertDatabaseHas('shipments', [
            'id' => $shipment->id,
            'sender_phone' => '9876543210',
            'weight' => 15.0
        ]);
    }

    /** @test */
    public function user_can_delete_shipment()
    {
        $this->actingAs($this->user);
        
        $shipment = Shipment::factory()->create();

        $response = $this->delete("/shipments/{$shipment->id}");

        $response->assertRedirect('/shipments');
        $this->assertDatabaseMissing('shipments', ['id' => $shipment->id]);
    }

    /** @test */
    public function user_can_search_shipments()
    {
        $this->actingAs($this->user);
        
        Shipment::factory()->create(['tracking_number' => 'SHIP001']);
        Shipment::factory()->create(['tracking_number' => 'SHIP002']);

        $response = $this->get('/shipments?search=SHIP001');

        $response->assertStatus(200);
        $response->assertSee('SHIP001');
        $response->assertDontSee('SHIP002');
    }

    /** @test */
    public function user_can_filter_shipments_by_status()
    {
        $this->actingAs($this->user);
        
        $status1 = Status::factory()->create(['name' => 'Pending']);
        $status2 = Status::factory()->create(['name' => 'Delivered']);
        
        Shipment::factory()->create(['status_id' => $status1->id]);
        Shipment::factory()->create(['status_id' => $status2->id]);

        $response = $this->get('/shipments?status=' . $status1->id);

        $response->assertStatus(200);
        $response->assertSee('Pending');
    }

    /** @test */
    public function user_can_track_shipment()
    {
        $this->actingAs($this->user);
        
        $shipment = Shipment::factory()->create(['tracking_number' => 'SHIP001']);

        $response = $this->get('/track/SHIP001');

        $response->assertStatus(200);
        $response->assertSee($shipment->tracking_number);
    }

    /** @test */
    public function user_cannot_access_shipments_without_authentication()
    {
        $response = $this->get('/shipments');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_create_shipment_with_invalid_data()
    {
        $this->actingAs($this->user);

        $invalidData = [
            'tracking_number' => '',
            'sender_name' => ''
        ];

        $response = $this->post('/shipments', $invalidData);

        $response->assertSessionHasErrors(['tracking_number', 'sender_name']);
        $this->assertDatabaseMissing('shipments', $invalidData);
    }
}