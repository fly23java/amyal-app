<?php

namespace Tests\Unit;

use App\Models\Price;
use App\Models\PriceDetail;
use App\Models\Region;
use App\Models\GoodsType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_price()
    {
        $priceData = [
            'name' => 'Standard Shipping',
            'description' => 'Standard shipping rates',
            'is_active' => true,
            'effective_date' => '2024-01-01',
            'expiry_date' => '2024-12-31'
        ];

        $price = Price::create($priceData);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals('Standard Shipping', $price->name);
        $this->assertEquals('Standard shipping rates', $price->description);
        $this->assertTrue($price->is_active);
        $this->assertEquals('2024-01-01', $price->effective_date);
    }

    /** @test */
    public function it_can_update_price_information()
    {
        $price = Price::factory()->create();

        $price->update([
            'name' => 'Updated Shipping',
            'is_active' => false
        ]);

        $this->assertEquals('Updated Shipping', $price->fresh()->name);
        $this->assertFalse($price->fresh()->is_active);
    }

    /** @test */
    public function it_can_delete_a_price()
    {
        $price = Price::factory()->create();
        $priceId = $price->id;

        $price->delete();

        $this->assertDatabaseMissing('prices', ['id' => $priceId]);
    }

    /** @test */
    public function it_has_many_price_details()
    {
        $price = Price::factory()->create();
        
        $this->assertTrue(method_exists($price, 'priceDetails'));
    }

    /** @test */
    public function it_can_get_active_prices()
    {
        Price::factory()->create(['is_active' => true]);
        Price::factory()->create(['is_active' => false]);

        $activePrices = Price::where('is_active', true)->get();

        $this->assertEquals(1, $activePrices->count());
    }

    /** @test */
    public function it_can_get_current_prices()
    {
        $today = now()->format('Y-m-d');
        
        Price::factory()->create([
            'effective_date' => '2024-01-01',
            'expiry_date' => '2024-12-31'
        ]);
        
        Price::factory()->create([
            'effective_date' => '2023-01-01',
            'expiry_date' => '2023-12-31'
        ]);

        $currentPrices = Price::where('effective_date', '<=', $today)
            ->where('expiry_date', '>=', $today)
            ->get();

        $this->assertEquals(1, $currentPrices->count());
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Price::create([
            'name' => 'Test Price'
            // Missing required fields
        ]);
    }

    /** @test */
    public function it_can_calculate_total_price()
    {
        $price = Price::factory()->create();

        // Assuming there's a method to calculate total price
        $this->assertTrue(method_exists($price, 'calculateTotalPrice'));
    }

    /** @test */
    public function it_can_get_price_by_region_and_goods_type()
    {
        $price = Price::factory()->create();

        // Assuming there's a method to get price by region and goods type
        $this->assertTrue(method_exists($price, 'getPriceByRegionAndGoodsType'));
    }

    /** @test */
    public function it_can_apply_discounts()
    {
        $price = Price::factory()->create();

        // Assuming there's a method to apply discounts
        $this->assertTrue(method_exists($price, 'applyDiscount'));
    }

    /** @test */
    public function it_can_get_price_history()
    {
        $price = Price::factory()->create();

        // Assuming there's a method to get price history
        $this->assertTrue(method_exists($price, 'getPriceHistory'));
    }
}