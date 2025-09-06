<?php

namespace Database\Factories;

use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'account_id' => $this->faker->numberBetween(1, 10),
            'loading_city_id' => $this->faker->numberBetween(1, 50),
            'unloading_city_id' => $this->faker->numberBetween(1, 50),
            'vehicle_type_id' => $this->faker->numberBetween(1, 5),
            'goods_id' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'carrier_price' => $this->faker->randomFloat(2, 50, 2000),
            'status_id' => $this->faker->numberBetween(1, 5),
            'serial_number' => $this->generateSerialNumber(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Generate a unique serial number
     *
     * @return string
     */
    private function generateSerialNumber(): string
    {
        $accountId = $this->faker->numberBetween(1, 10);
        $loadingCityId = $this->faker->numberBetween(1, 50);
        $unloadingCityId = $this->faker->numberBetween(1, 50);
        $date = $this->faker->dateTimeBetween('-1 year', 'now')->format('Ymd');
        $sequence = str_pad($this->faker->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "{$accountId}-{$loadingCityId}-{$unloadingCityId}-{$date}{$sequence}";
    }

    /**
     * Indicate that the shipment is pending.
     *
     * @return static
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => 1,
            ];
        });
    }

    /**
     * Indicate that the shipment is in progress.
     *
     * @return static
     */
    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => 2,
            ];
        });
    }

    /**
     * Indicate that the shipment is delivered.
     *
     * @return static
     */
    public function delivered()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => 3,
            ];
        });
    }

    /**
     * Indicate that the shipment is completed.
     *
     * @return static
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => 4,
            ];
        });
    }

    /**
     * Indicate that the shipment is cancelled.
     *
     * @return static
     */
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => 5,
            ];
        });
    }

    /**
     * Indicate that the shipment has high value.
     *
     * @return static
     */
    public function highValue()
    {
        return $this->state(function (array $attributes) {
            return [
                'price' => $this->faker->randomFloat(2, 5000, 20000),
                'carrier_price' => $this->faker->randomFloat(2, 2000, 8000),
            ];
        });
    }

    /**
     * Indicate that the shipment is recent.
     *
     * @return static
     */
    public function recent()
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
                'updated_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the shipment is from today.
     *
     * @return static
     */
    public function today()
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the shipment is from this month.
     *
     * @return static
     */
    public function thisMonth()
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => $this->faker->dateTimeBetween(now()->startOfMonth(), now()),
                'updated_at' => now(),
            ];
        });
    }
}