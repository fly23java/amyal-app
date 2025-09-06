<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletLimit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class WalletService
{
    /**
     * Create a new wallet for user
     *
     * @param User $user
     * @param string $type
     * @param string $currency
     * @return Wallet
     */
    public function createWallet(User $user, string $type = 'main', string $currency = 'SAR'): Wallet
    {
        try {
            DB::beginTransaction();

            $wallet = Wallet::create([
                'user_id' => $user->id,
                'wallet_type' => $type,
                'currency' => $currency,
                'balance' => 0.00,
                'is_active' => true,
                'status' => 'active',
            ]);

            // Create default limits
            $this->createDefaultLimits($wallet);

            DB::commit();

            Log::info('Wallet created successfully', [
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'type' => $type
            ]);

            return $wallet;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create wallet', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create default limits for wallet
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
                'period_start' => now()->toDateString(),
                'period_end' => now()->toDateString(),
            ],
            [
                'type' => 'monthly',
                'operation' => 'withdrawal',
                'limit_amount' => 50000.00,
                'period_start' => now()->startOfMonth()->toDateString(),
                'period_end' => now()->endOfMonth()->toDateString(),
            ],
            [
                'type' => 'transaction',
                'operation' => 'transfer',
                'limit_amount' => 10000.00,
                'period_start' => now()->toDateString(),
                'period_end' => now()->addYear()->toDateString(),
            ],
        ];

        foreach ($limits as $limitData) {
            WalletLimit::create(array_merge([
                'wallet_id' => $wallet->id,
                'used_amount' => 0.00,
                'is_active' => true,
            ], $limitData));
        }
    }

    /**
     * Deposit money to wallet
     *
     * @param Wallet $wallet
     * @param float $amount
     * @param string $paymentMethod
     * @param string $referenceId
     * @param array $metadata
     * @return WalletTransaction
     */
    public function deposit(
        Wallet $wallet, 
        float $amount, 
        string $paymentMethod = 'card', 
        string $referenceId = null,
        array $metadata = []
    ): WalletTransaction {
        try {
            DB::beginTransaction();

            if (!$wallet->is_active || $wallet->status !== 'active') {
                throw new Exception('المحفظة غير نشطة أو معطلة');
            }

            if ($amount <= 0) {
                throw new Exception('مبلغ الإيداع يجب أن يكون أكبر من صفر');
            }

            // Check deposit limits
            $this->checkLimits($wallet, 'deposit', $amount);

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            // Create transaction
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'credit',
                'category' => 'deposit',
                'amount' => $amount,
                'net_amount' => $amount,
                'currency' => $wallet->currency,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'status' => 'completed',
                'payment_method' => $paymentMethod,
                'reference_id' => $referenceId,
                'description' => 'إيداع في المحفظة',
                'metadata' => $metadata,
                'processed_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Update wallet balance
            $wallet->update([
                'balance' => $balanceAfter,
                'last_transaction_at' => now(),
            ]);

            // Update limits
            $this->updateLimitsUsage($wallet, 'deposit', $amount);

            DB::commit();

            Log::info('Deposit completed successfully', [
                'wallet_id' => $wallet->id,
                'transaction_id' => $transaction->transaction_id,
                'amount' => $amount
            ]);

            return $transaction;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Deposit failed', [
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Withdraw money from wallet
     *
     * @param Wallet $wallet
     * @param float $amount
     * @param string $paymentMethod
     * @param string $referenceId
     * @param array $metadata
     * @return WalletTransaction
     */
    public function withdraw(
        Wallet $wallet, 
        float $amount, 
        string $paymentMethod = 'bank', 
        string $referenceId = null,
        array $metadata = []
    ): WalletTransaction {
        try {
            DB::beginTransaction();

            if (!$wallet->canTransact($amount)) {
                throw new Exception('الرصيد غير كافي أو المحفظة غير نشطة');
            }

            if ($amount <= 0) {
                throw new Exception('مبلغ السحب يجب أن يكون أكبر من صفر');
            }

            // Check withdrawal limits
            $this->checkLimits($wallet, 'withdrawal', $amount);

            $fee = $this->calculateWithdrawalFee($amount);
            $netAmount = $amount - $fee;
            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore - $amount;

            // Create transaction
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'debit',
                'category' => 'withdrawal',
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'currency' => $wallet->currency,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'status' => 'completed',
                'payment_method' => $paymentMethod,
                'reference_id' => $referenceId,
                'description' => 'سحب من المحفظة',
                'metadata' => $metadata,
                'processed_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Update wallet balance
            $wallet->update([
                'balance' => $balanceAfter,
                'last_transaction_at' => now(),
            ]);

            // Update limits
            $this->updateLimitsUsage($wallet, 'withdrawal', $amount);

            DB::commit();

            Log::info('Withdrawal completed successfully', [
                'wallet_id' => $wallet->id,
                'transaction_id' => $transaction->transaction_id,
                'amount' => $amount,
                'fee' => $fee
            ]);

            return $transaction;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Withdrawal failed', [
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Transfer money between wallets
     *
     * @param Wallet $fromWallet
     * @param Wallet $toWallet
     * @param float $amount
     * @param string $description
     * @param array $metadata
     * @return array
     */
    public function transfer(
        Wallet $fromWallet,
        Wallet $toWallet,
        float $amount,
        string $description = 'تحويل بين المحافظ',
        array $metadata = []
    ): array {
        try {
            DB::beginTransaction();

            if (!$fromWallet->canTransact($amount)) {
                throw new Exception('الرصيد غير كافي أو المحفظة غير نشطة');
            }

            if (!$toWallet->is_active || $toWallet->status !== 'active') {
                throw new Exception('محفظة المستلم غير نشطة');
            }

            if ($amount <= 0) {
                throw new Exception('مبلغ التحويل يجب أن يكون أكبر من صفر');
            }

            if ($fromWallet->id === $toWallet->id) {
                throw new Exception('لا يمكن التحويل إلى نفس المحفظة');
            }

            // Check transfer limits
            $this->checkLimits($fromWallet, 'transfer', $amount);

            $fee = $this->calculateTransferFee($amount);
            $netAmount = $amount - $fee;

            // Create debit transaction for sender
            $debitTransaction = WalletTransaction::create([
                'wallet_id' => $fromWallet->id,
                'related_wallet_id' => $toWallet->id,
                'type' => 'debit',
                'category' => 'transfer',
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'currency' => $fromWallet->currency,
                'balance_before' => $fromWallet->balance,
                'balance_after' => $fromWallet->balance - $amount,
                'status' => 'completed',
                'description' => $description,
                'metadata' => $metadata,
                'processed_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Create credit transaction for receiver
            $creditTransaction = WalletTransaction::create([
                'wallet_id' => $toWallet->id,
                'related_wallet_id' => $fromWallet->id,
                'type' => 'credit',
                'category' => 'transfer',
                'amount' => $netAmount,
                'net_amount' => $netAmount,
                'currency' => $toWallet->currency,
                'balance_before' => $toWallet->balance,
                'balance_after' => $toWallet->balance + $netAmount,
                'status' => 'completed',
                'reference_id' => $debitTransaction->transaction_id,
                'description' => $description,
                'metadata' => $metadata,
                'processed_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Update wallet balances
            $fromWallet->update([
                'balance' => $fromWallet->balance - $amount,
                'last_transaction_at' => now(),
            ]);

            $toWallet->update([
                'balance' => $toWallet->balance + $netAmount,
                'last_transaction_at' => now(),
            ]);

            // Update limits
            $this->updateLimitsUsage($fromWallet, 'transfer', $amount);

            DB::commit();

            Log::info('Transfer completed successfully', [
                'from_wallet_id' => $fromWallet->id,
                'to_wallet_id' => $toWallet->id,
                'debit_transaction_id' => $debitTransaction->transaction_id,
                'credit_transaction_id' => $creditTransaction->transaction_id,
                'amount' => $amount,
                'fee' => $fee
            ]);

            return [
                'debit_transaction' => $debitTransaction,
                'credit_transaction' => $creditTransaction,
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transfer failed', [
                'from_wallet_id' => $fromWallet->id,
                'to_wallet_id' => $toWallet->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Check wallet limits
     *
     * @param Wallet $wallet
     * @param string $operation
     * @param float $amount
     * @return void
     * @throws Exception
     */
    private function checkLimits(Wallet $wallet, string $operation, float $amount): void
    {
        $limits = $wallet->limits()
            ->where('operation', $operation)
            ->active()
            ->currentPeriod()
            ->get();

        foreach ($limits as $limit) {
            if ($limit->isExceeded($amount)) {
                throw new Exception("تم تجاوز الحد المسموح {$limit->type_display} لعملية {$limit->operation_display}");
            }
        }
    }

    /**
     * Update limits usage
     *
     * @param Wallet $wallet
     * @param string $operation
     * @param float $amount
     * @return void
     */
    private function updateLimitsUsage(Wallet $wallet, string $operation, float $amount): void
    {
        $limits = $wallet->limits()
            ->where('operation', $operation)
            ->active()
            ->currentPeriod()
            ->get();

        foreach ($limits as $limit) {
            $limit->addUsage($amount);
        }
    }

    /**
     * Calculate withdrawal fee
     *
     * @param float $amount
     * @return float
     */
    private function calculateWithdrawalFee(float $amount): float
    {
        // Example fee structure: 1% with minimum 5 SAR, maximum 50 SAR
        $feePercentage = 0.01;
        $minFee = 5.00;
        $maxFee = 50.00;

        $fee = $amount * $feePercentage;
        return max($minFee, min($maxFee, $fee));
    }

    /**
     * Calculate transfer fee
     *
     * @param float $amount
     * @return float
     */
    private function calculateTransferFee(float $amount): float
    {
        // Example fee structure: 0.5% with minimum 2 SAR, maximum 25 SAR
        $feePercentage = 0.005;
        $minFee = 2.00;
        $maxFee = 25.00;

        $fee = $amount * $feePercentage;
        return max($minFee, min($maxFee, $fee));
    }

    /**
     * Get wallet balance
     *
     * @param Wallet $wallet
     * @return array
     */
    public function getBalance(Wallet $wallet): array
    {
        return [
            'balance' => $wallet->balance,
            'pending_balance' => $wallet->pending_balance,
            'reserved_balance' => $wallet->reserved_balance,
            'available_balance' => $wallet->available_balance,
            'total_balance' => $wallet->total_balance,
            'currency' => $wallet->currency,
        ];
    }

    /**
     * Get wallet transactions with filters
     *
     * @param Wallet $wallet
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getTransactions(Wallet $wallet, array $filters = [], int $perPage = 15)
    {
        $query = $wallet->transactions();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['amount_from'])) {
            $query->where('amount', '>=', $filters['amount_from']);
        }

        if (!empty($filters['amount_to'])) {
            $query->where('amount', '<=', $filters['amount_to']);
        }

        return $query->with(['relatedWallet.user'])
                    ->latest()
                    ->paginate($perPage);
    }

    /**
     * Get wallet statistics
     *
     * @param Wallet $wallet
     * @param string $period
     * @return array
     */
    public function getStatistics(Wallet $wallet, string $period = 'month'): array
    {
        $startDate = match($period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $transactions = $wallet->transactions()
            ->where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->get();

        $deposits = $transactions->where('type', 'credit')->sum('amount');
        $withdrawals = $transactions->where('type', 'debit')->sum('amount');
        $totalTransactions = $transactions->count();

        return [
            'period' => $period,
            'total_deposits' => $deposits,
            'total_withdrawals' => $withdrawals,
            'net_flow' => $deposits - $withdrawals,
            'total_transactions' => $totalTransactions,
            'average_transaction' => $totalTransactions > 0 ? ($deposits + $withdrawals) / $totalTransactions : 0,
            'current_balance' => $wallet->balance,
        ];
    }
}