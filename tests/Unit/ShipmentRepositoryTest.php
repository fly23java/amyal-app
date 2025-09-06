<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Shipment;
use App\Models\Account;
use App\Models\Status;
use App\Models\User;
use App\Repositories\Eloquent\ShipmentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ShipmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ShipmentRepository(new Shipment());
    }

    /** @test */
    public function it_can_get_all_shipments()
    {
        // Arrange
        Shipment::factory()->count(5)->create();

        // Act
        $shipments = $this->repository->all();

        // Assert
        $this->assertInstanceOf(Collection::class, $shipments);
        $this->assertCount(5, $shipments);
    }

    /** @test */
    public function it_can_find_shipment_by_id()
    {
        // Arrange
        $shipment = Shipment::factory()->create();

        // Act
        $found = $this->repository->find($shipment->id);

        // Assert
        $this->assertInstanceOf(Shipment::class, $found);
        $this->assertEquals($shipment->id, $found->id);
    }

    /** @test */
    public function it_returns_null_when_shipment_not_found()
    {
        // Act
        $found = $this->repository->find(999);

        // Assert
        $this->assertNull($found);
    }

    /** @test */
    public function it_can_create_shipment()
    {
        // Arrange
        $data = [
            'account_id' => 1,
            'loading_city_id' => 1,
            'unloading_city_id' => 2,
            'vehicle_type_id' => 1,
            'goods_id' => 1,
            'price' => 1000.00,
            'status_id' => 1,
            'serial_number' => 'TEST-001'
        ];

        // Act
        $shipment = $this->repository->create($data);

        // Assert
        $this->assertInstanceOf(Shipment::class, $shipment);
        $this->assertEquals($data['price'], $shipment->price);
        $this->assertEquals($data['serial_number'], $shipment->serial_number);
        $this->assertDatabaseHas('shipments', $data);
    }

    /** @test */
    public function it_can_update_shipment()
    {
        // Arrange
        $shipment = Shipment::factory()->create(['price' => 1000.00]);
        $updateData = ['price' => 1500.00];

        // Act
        $updated = $this->repository->update($shipment, $updateData);

        // Assert
        $this->assertTrue($updated);
        $this->assertEquals(1500.00, $shipment->fresh()->price);
    }

    /** @test */
    public function it_can_delete_shipment()
    {
        // Arrange
        $shipment = Shipment::factory()->create();

        // Act
        $deleted = $this->repository->delete($shipment);

        // Assert
        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('shipments', ['id' => $shipment->id]);
    }

    /** @test */
    public function it_can_paginate_shipments()
    {
        // Arrange
        Shipment::factory()->count(25)->create();

        // Act
        $paginated = $this->repository->paginate(10);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginated);
        $this->assertEquals(10, $paginated->perPage());
        $this->assertEquals(25, $paginated->total());
    }

    /** @test */
    public function it_can_count_shipments()
    {
        // Arrange
        Shipment::factory()->count(7)->create();

        // Act
        $count = $this->repository->count();

        // Assert
        $this->assertEquals(7, $count);
    }

    /** @test */
    public function it_can_count_shipments_with_criteria()
    {
        // Arrange
        Shipment::factory()->count(3)->create(['status_id' => 1]);
        Shipment::factory()->count(2)->create(['status_id' => 2]);

        // Act
        $count = $this->repository->count(['status_id' => 1]);

        // Assert
        $this->assertEquals(3, $count);
    }

    /** @test */
    public function it_can_check_if_shipment_exists()
    {
        // Arrange
        $shipment = Shipment::factory()->create(['serial_number' => 'UNIQUE-001']);

        // Act
        $exists = $this->repository->exists(['serial_number' => 'UNIQUE-001']);
        $notExists = $this->repository->exists(['serial_number' => 'NOT-FOUND']);

        // Assert
        $this->assertTrue($exists);
        $this->assertFalse($notExists);
    }

    /** @test */
    public function it_can_find_shipments_by_criteria()
    {
        // Arrange
        Shipment::factory()->count(3)->create(['status_id' => 1]);
        Shipment::factory()->count(2)->create(['status_id' => 2]);

        // Act
        $shipments = $this->repository->findBy(['status_id' => 1]);

        // Assert
        $this->assertCount(3, $shipments);
        foreach ($shipments as $shipment) {
            $this->assertEquals(1, $shipment->status_id);
        }
    }

    /** @test */
    public function it_can_find_one_shipment_by_criteria()
    {
        // Arrange
        $shipment = Shipment::factory()->create(['serial_number' => 'FIND-ME']);

        // Act
        $found = $this->repository->findOneBy(['serial_number' => 'FIND-ME']);

        // Assert
        $this->assertInstanceOf(Shipment::class, $found);
        $this->assertEquals('FIND-ME', $found->serial_number);
    }

    /** @test */
    public function it_can_get_shipments_by_status()
    {
        // Arrange
        Shipment::factory()->count(3)->create(['status_id' => 1]);
        Shipment::factory()->count(2)->create(['status_id' => 2]);

        // Act
        $shipments = $this->repository->getByStatus(1);

        // Assert
        $this->assertInstanceOf(Collection::class, $shipments);
        $this->assertCount(3, $shipments);
    }

    /** @test */
    public function it_can_get_shipments_by_account()
    {
        // Arrange
        Shipment::factory()->count(4)->create(['account_id' => 1]);
        Shipment::factory()->count(2)->create(['account_id' => 2]);

        // Act
        $shipments = $this->repository->getByAccount(1);

        // Assert
        $this->assertInstanceOf(Collection::class, $shipments);
        $this->assertCount(4, $shipments);
    }

    /** @test */
    public function it_can_get_recent_shipments()
    {
        // Arrange
        Shipment::factory()->count(15)->create();

        // Act
        $recent = $this->repository->getRecent(5);

        // Assert
        $this->assertInstanceOf(Collection::class, $recent);
        $this->assertCount(5, $recent);
    }

    /** @test */
    public function it_can_search_shipments()
    {
        // Arrange
        $shipment1 = Shipment::factory()->create(['serial_number' => 'SEARCH-001']);
        $shipment2 = Shipment::factory()->create(['serial_number' => 'SEARCH-002']);
        $shipment3 = Shipment::factory()->create(['serial_number' => 'OTHER-001']);

        // Act
        $results = $this->repository->search('SEARCH');

        // Assert
        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(2, $results);
    }

    /** @test */
    public function it_can_get_shipments_by_date_range()
    {
        // Arrange
        $startDate = now()->subDays(5)->toDateString();
        $endDate = now()->toDateString();
        
        Shipment::factory()->create(['created_at' => now()->subDays(3)]);
        Shipment::factory()->create(['created_at' => now()->subDays(1)]);
        Shipment::factory()->create(['created_at' => now()->subDays(10)]); // Outside range

        // Act
        $shipments = $this->repository->getByDateRange($startDate, $endDate);

        // Assert
        $this->assertInstanceOf(Collection::class, $shipments);
        $this->assertCount(2, $shipments);
    }

    /** @test */
    public function it_can_get_dashboard_stats()
    {
        // Arrange
        Shipment::factory()->count(10)->create(['created_at' => now()]);
        Shipment::factory()->count(5)->create(['status_id' => 1]);
        Shipment::factory()->count(3)->create(['status_id' => 4]);

        // Act
        $stats = $this->repository->getDashboardStats();

        // Assert
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_shipments', $stats);
        $this->assertArrayHasKey('today_shipments', $stats);
        $this->assertArrayHasKey('pending_shipments', $stats);
        $this->assertArrayHasKey('completed_shipments', $stats);
    }
}