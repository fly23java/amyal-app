<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletLimit;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get existing users or create some
        $users = User::take(10)->get();
        
        if ($users->count() < 5) {
            $users = User::factory(10)->create();
        }

        foreach ($users as $user) {
            // Create main wallet for each user
            $mainWallet = Wallet::factory()
                ->main()
                ->sar()
                ->active()
                ->verified()
                ->create(['user_id' => $user->id]);

            // Set PIN for main wallet
            $mainWallet->setPin('1234');

            // Create some sample transactions
            $this->createSampleTransactions($mainWallet);
            
            // Create default limits
            $this->createDefaultLimits($mainWallet);

            // 50% chance to create a savings wallet
            if (rand(1, 100) <= 50) {
                $savingsWallet = Wallet::factory()
                    ->savings()
                    ->sar()
                    ->active()
                    ->verified()
                    ->create(['user_id' => $user->id]);

                $this->createSampleTransactions($savingsWallet, 3);
                $this->createDefaultLimits($savingsWallet);
            }

            // 30% chance to create a business wallet
            if (rand(1, 100) <= 30) {
                $businessWallet = Wallet::factory()
                    ->business()
                    ->sar()
                    ->active()
                    ->verified()
                    ->create(['user_id' => $user->id]);

                $this->createSampleTransactions($businessWallet, 5);
                $this->createDefaultLimits($businessWallet);
            }
        }
    }

    /**
     * Create sample transactions for a wallet
     *
     * @param Wallet $wallet
     * @param int $count
     * @return void
     */
    private function createSampleTransactions(Wallet $wallet, int $count = 10): void
    {
        $currentBalance = $wallet->balance;
        
        for ($i = 0; $i < $count; $i++) {
            $isCredit = rand(1, 100) <= 60; // 60% chance for credit
            $amount = rand(50, 2000);
            
            if ($isCredit) {
                $balanceBefore = $currentBalance;
                $currentBalance += $amount;
                $balanceAfter = $currentBalance;
                
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => 'credit',
                    'category' => fake()->randomElement(['deposit', 'refund', 'cashback']),
                    'amount' => $amount,
                    'net_amount' => $amount,
                    'currency' => $wallet->currency,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'status' => 'completed',
                    'payment_method' => fake()->randomElement(['mada', 'visa', 'bank_transfer']),
                    'description' => fake()->sentence(),
                    'processed_at' => fake()->dateTimeBetween('-1 month', 'now'),
                    'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
                ]);
            } else {
                if ($currentBalance >= $amount) {
                    $fee = $amount * 0.01; // 1% fee
                    $balanceBefore = $currentBalance;
                    $currentBalance -= $amount;
                    $balanceAfter = $currentBalance;
                    
                    WalletTransaction::create([
                        'wallet_id' => $wallet->id,
                        'type' => 'debit',
                        'category' => fake()->randomElement(['withdrawal', 'payment', 'transfer']),
                        'amount' => $amount,
                        'fee' => $fee,
                        'net_amount' => $amount - $fee,
                        'currency' => $wallet->currency,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'status' => 'completed',
                        'payment_method' => fake()->randomElement(['bank_transfer', 'cash']),
                        'description' => fake()->sentence(),
                        'processed_at' => fake()->dateTimeBetween('-1 month', 'now'),
                        'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
                    ]);
                }
            }
        }
        
        // Update wallet balance
        $wallet->update(['balance' => $currentBalance]);
    }

    /**
     * Create default limits for a wallet
     *
     * @param Wallet $wallet
     * @return void
     */
    private function createDefaultLimits(Wallet $wallet): void
    {
        $limits = [
            [
                'type' => 'daily',
                'operation' => 'withdrawal',
                'limit_amount' => 5000.00,
                'used_amount' => rand(0, 2000),
                'period_start' => now()->toDateString(),
                'period_end' => now()->toDateString(),
            ],
            [
                'type' => 'weekly',
                'operation' => 'withdrawal',
                'limit_amount' => 25000.00,
                'used_amount' => rand(0, 10000),
                'period_start' => now()->startOfWeek()->toDateString(),
                'period_end' => now()->endOfWeek()->toDateString(),
            ],
            [
                'type' => 'monthly',
                'operation' => 'withdrawal',
                'limit_amount' => 100000.00,
                'used_amount' => rand(0, 30000),
                'period_start' => now()->startOfMonth()->toDateString(),
                'period_end' => now()->endOfMonth()->toDateString(),
            ],
            [
                'type' => 'daily',
                'operation' => 'transfer',
                'limit_amount' => 10000.00,
                'used_amount' => rand(0, 3000),
                'period_start' => now()->toDateString(),
                'period_end' => now()->toDateString(),
            ],
            [
                'type' => 'transaction',
                'operation' => 'transfer',
                'limit_amount' => 25000.00,
                'used_amount' => 0,
                'period_start' => now()->toDateString(),
                'period_end' => now()->addYear()->toDateString(),
            ],
        ];

        foreach ($limits as $limitData) {
            WalletLimit::create(array_merge([
                'wallet_id' => $wallet->id,
                'is_active' => true,
            ], $limitData));
        }
    }
}