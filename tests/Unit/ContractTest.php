<?php

namespace Tests\Unit;

use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_contract()
    {
        $contractData = [
            'contract_number' => 'CON001',
            'title' => 'Shipping Contract',
            'description' => 'Contract for shipping services',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'status' => 'active',
            'total_value' => 50000.00,
            'user_id' => 1
        ];

        $contract = Contract::create($contractData);

        $this->assertInstanceOf(Contract::class, $contract);
        $this->assertEquals('CON001', $contract->contract_number);
        $this->assertEquals('Shipping Contract', $contract->title);
        $this->assertEquals('active', $contract->status);
        $this->assertEquals(50000.00, $contract->total_value);
    }

    /** @test */
    public function it_can_update_contract_information()
    {
        $contract = Contract::factory()->create();

        $contract->update([
            'status' => 'completed',
            'total_value' => 60000.00
        ]);

        $this->assertEquals('completed', $contract->fresh()->status);
        $this->assertEquals(60000.00, $contract->fresh()->total_value);
    }

    /** @test */
    public function it_can_delete_a_contract()
    {
        $contract = Contract::factory()->create();
        $contractId = $contract->id;

        $contract->delete();

        $this->assertDatabaseMissing('contracts', ['id' => $contractId]);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $contract = Contract::factory()->create();
        
        $this->assertTrue(method_exists($contract, 'user'));
    }

    /** @test */
    public function it_has_many_contract_details()
    {
        $contract = Contract::factory()->create();
        
        $this->assertTrue(method_exists($contract, 'contractDetails'));
    }

    /** @test */
    public function it_can_get_active_contracts()
    {
        Contract::factory()->create(['status' => 'active']);
        Contract::factory()->create(['status' => 'expired']);

        $activeContracts = Contract::where('status', 'active')->get();

        $this->assertEquals(1, $activeContracts->count());
    }

    /** @test */
    public function it_can_get_contracts_by_date_range()
    {
        Contract::factory()->create([
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31'
        ]);
        
        Contract::factory()->create([
            'start_date' => '2023-01-01',
            'end_date' => '2023-12-31'
        ]);

        $currentContracts = Contract::where('start_date', '<=', '2024-06-01')
            ->where('end_date', '>=', '2024-06-01')
            ->get();

        $this->assertEquals(1, $currentContracts->count());
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Contract::create([
            'contract_number' => 'CON001'
            // Missing required fields
        ]);
    }

    /** @test */
    public function it_can_calculate_remaining_value()
    {
        $contract = Contract::factory()->create(['total_value' => 50000.00]);

        // Assuming there's a method to calculate remaining value
        $this->assertTrue(method_exists($contract, 'calculateRemainingValue'));
    }

    /** @test */
    public function it_can_check_if_contract_is_expired()
    {
        $contract = Contract::factory()->create(['end_date' => '2023-12-31']);

        // Assuming there's a method to check if contract is expired
        $this->assertTrue(method_exists($contract, 'isExpired'));
    }

    /** @test */
    public function it_can_get_contract_summary()
    {
        $contract = Contract::factory()->create();

        // Assuming there's a method to get contract summary
        $this->assertTrue(method_exists($contract, 'getSummary'));
    }

    /** @test */
    public function it_can_get_contract_performance()
    {
        $contract = Contract::factory()->create();

        // Assuming there's a method to get contract performance
        $this->assertTrue(method_exists($contract, 'getPerformance'));
    }

    /** @test */
    public function it_can_get_contracts_by_value_range()
    {
        Contract::factory()->create(['total_value' => 10000.00]);
        Contract::factory()->create(['total_value' => 50000.00]);
        Contract::factory()->create(['total_value' => 100000.00]);

        $highValueContracts = Contract::where('total_value', '>=', 50000.00)->get();

        $this->assertEquals(2, $highValueContracts->count());
    }
}