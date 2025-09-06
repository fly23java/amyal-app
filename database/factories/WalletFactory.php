<?php

namespace Database\Factories;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wallet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'wallet_number' => $this->generateWalletNumber(),
            'user_id' => User::factory(),
            'wallet_type' => $this->faker->randomElement(['main', 'savings', 'business']),
            'currency' => $this->faker->randomElement(['SAR', 'USD', 'EUR']),
            'balance' => $this->faker->randomFloat(2, 0, 50000),
            'pending_balance' => $this->faker->randomFloat(2, 0, 1000),
            'reserved_balance' => $this->faker->randomFloat(2, 0, 500),
            'is_active' => $this->faker->boolean(90), // 90% active
            'is_verified' => $this->faker->boolean(80), // 80% verified
            'status' => $this->faker->randomElement(['active', 'suspended', 'closed']),
            'settings' => [
                'notifications' => $this->faker->boolean(),
                'auto_backup' => $this->faker->boolean(),
                'transaction_alerts' => $this->faker->boolean(),
            ],
            'metadata' => [
                'created_via' => 'web',
                'initial_deposit' => $this->faker->randomFloat(2, 100, 1000),
            ],
            'last_transaction_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Generate a unique wallet number
     *
     * @return string
     */
    private function generateWalletNumber(): string
    {
        do {
            $number = 'W' . str_pad($this->faker->numerify('#################'), 17, '0', STR_PAD_LEFT);
        } while (Wallet::where('wallet_number', $number)->exists());

        return $number;
    }

    /**
     * Indicate that the wallet is active.
     *
     * @return static
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
                'status' => 'active',
            ];
        });
    }

    /**
     * Indicate that the wallet is verified.
     *
     * @return static
     */
    public function verified()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_verified' => true,
            ];
        });
    }

    /**
     * Indicate that the wallet is suspended.
     *
     * @return static
     */
    public function suspended()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
                'status' => 'suspended',
            ];
        });
    }

    /**
     * Indicate that the wallet has high balance.
     *
     * @return static
     */
    public function highBalance()
    {
        return $this->state(function (array $attributes) {
            return [
                'balance' => $this->faker->randomFloat(2, 10000, 100000),
            ];
        });
    }

    /**
     * Indicate that the wallet is empty.
     *
     * @return static
     */
    public function empty()
    {
        return $this->state(function (array $attributes) {
            return [
                'balance' => 0.00,
                'pending_balance' => 0.00,
                'reserved_balance' => 0.00,
            ];
        });
    }

    /**
     * Indicate that the wallet is a main wallet.
     *
     * @return static
     */
    public function main()
    {
        return $this->state(function (array $attributes) {
            return [
                'wallet_type' => 'main',
            ];
        });
    }

    /**
     * Indicate that the wallet is a savings wallet.
     *
     * @return static
     */
    public function savings()
    {
        return $this->state(function (array $attributes) {
            return [
                'wallet_type' => 'savings',
            ];
        });
    }

    /**
     * Indicate that the wallet is a business wallet.
     *
     * @return static
     */
    public function business()
    {
        return $this->state(function (array $attributes) {
            return [
                'wallet_type' => 'business',
            ];
        });
    }

    /**
     * Indicate that the wallet uses SAR currency.
     *
     * @return static
     */
    public function sar()
    {
        return $this->state(function (array $attributes) {
            return [
                'currency' => 'SAR',
            ];
        });
    }

    /**
     * Indicate that the wallet uses USD currency.
     *
     * @return static
     */
    public function usd()
    {
        return $this->state(function (array $attributes) {
            return [
                'currency' => 'USD',
            ];
        });
    }
}