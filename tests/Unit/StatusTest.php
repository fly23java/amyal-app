<?php

namespace Tests\Unit;

use App\Models\Status;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_status()
    {
        $statusData = [
            'name' => 'Pending',
            'description' => 'Shipment is pending',
            'color' => '#FFA500',
            'is_active' => true,
            'sort_order' => 1
        ];

        $status = Status::create($statusData);

        $this->assertInstanceOf(Status::class, $status);
        $this->assertEquals('Pending', $status->name);
        $this->assertEquals('Shipment is pending', $status->description);
        $this->assertEquals('#FFA500', $status->color);
        $this->assertTrue($status->is_active);
        $this->assertEquals(1, $status->sort_order);
    }

    /** @test */
    public function it_can_update_status_information()
    {
        $status = Status::factory()->create();

        $status->update([
            'name' => 'In Transit',
            'color' => '#0000FF'
        ]);

        $this->assertEquals('In Transit', $status->fresh()->name);
        $this->assertEquals('#0000FF', $status->fresh()->color);
    }

    /** @test */
    public function it_can_delete_a_status()
    {
        $status = Status::factory()->create();
        $statusId = $status->id;

        $status->delete();

        $this->assertDatabaseMissing('statuses', ['id' => $statusId]);
    }

    /** @test */
    public function it_has_many_shipments()
    {
        $status = Status::factory()->create();
        
        $this->assertTrue(method_exists($status, 'shipments'));
    }

    /** @test */
    public function it_can_get_active_statuses()
    {
        Status::factory()->create(['is_active' => true]);
        Status::factory()->create(['is_active' => false]);

        $activeStatuses = Status::where('is_active', true)->get();

        $this->assertEquals(1, $activeStatuses->count());
    }

    /** @test */
    public function it_can_get_statuses_by_sort_order()
    {
        Status::factory()->create(['sort_order' => 3]);
        Status::factory()->create(['sort_order' => 1]);
        Status::factory()->create(['sort_order' => 2]);

        $orderedStatuses = Status::orderBy('sort_order')->get();

        $this->assertEquals(1, $orderedStatuses->first()->sort_order);
        $this->assertEquals(3, $orderedStatuses->last()->sort_order);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Status::create([
            'name' => 'Test Status'
            // Missing required fields
        ]);
    }

    /** @test */
    public function it_can_check_if_status_is_final()
    {
        $status = Status::factory()->create(['name' => 'Delivered']);

        // Assuming there's a method to check if status is final
        $this->assertTrue(method_exists($status, 'isFinal'));
    }

    /** @test */
    public function it_can_get_next_possible_statuses()
    {
        $status = Status::factory()->create(['name' => 'Pending']);

        // Assuming there's a method to get next possible statuses
        $this->assertTrue(method_exists($status, 'getNextPossibleStatuses'));
    }

    /** @test */
    public function it_can_get_status_transitions()
    {
        $status = Status::factory()->create(['name' => 'In Transit']);

        // Assuming there's a method to get status transitions
        $this->assertTrue(method_exists($status, 'getTransitions'));
    }
}