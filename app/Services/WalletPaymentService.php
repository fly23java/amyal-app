<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletPaymentMethod;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class WalletPaymentService
{
    /**
     * Add payment method to wallet
     *
     * @param Wallet $wallet
     * @param array $paymentData
     * @return WalletPaymentMethod
     */
    public function addPaymentMethod(Wallet $wallet, array $paymentData): WalletPaymentMethod
    {
        try {
            DB::beginTransaction();

            // Validate payment data
            $this->validatePaymentData($paymentData);

            $paymentMethod = WalletPaymentMethod::create([
                'wallet_id' => $wallet->id,
                'type' => $paymentData['type'],
                'provider' => $paymentData['provider'],
                'last_four' => $paymentData['last_four'] ?? null,
                'token' => $this->generateSecureToken($paymentData),
                'fingerprint' => $this->generateFingerprint($paymentData),
                'is_default' => $paymentData['is_default'] ?? false,
                'is_verified' => false,
                'metadata' => $paymentData['metadata'] ?? [],
                'expires_at' => isset($paymentData['expires_at']) ? 
                    \Carbon\Carbon::parse($paymentData['expires_at']) : null,
            ]);

            DB::commit();

            Log::info('Payment method added successfully', [
                'wallet_id' => $wallet->id,
                'payment_method_id' => $paymentMethod->id,
                'type' => $paymentData['type']
            ]);

            return $paymentMethod;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to add payment method', [
                'wallet_id' => $wallet->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Process payment using payment method
     *
     * @param WalletPaymentMethod $paymentMethod
     * @param float $amount
     * @param string $description
     * @param array $metadata
     * @return WalletTransaction
     */
    public function processPayment(
        WalletPaymentMethod $paymentMethod,
        float $amount,
        string $description = 'دفعة باستخدام المحفظة',
        array $metadata = []
    ): WalletTransaction {
        try {
            DB::beginTransaction();

            $wallet = $paymentMethod->wallet;

            if (!$paymentMethod->isValid()) {
                throw new Exception('طريقة الدفع غير صالحة أو منتهية الصلاحية');
            }

            if (!$wallet->canTransact($amount)) {
                throw new Exception('الرصيد غير كافي أو المحفظة غير نشطة');
            }

            // Process payment through gateway
            $gatewayResponse = $this->processPaymentGateway($paymentMethod, $amount, $metadata);

            $fee = $this->calculatePaymentFee($amount, $paymentMethod->provider);
            $netAmount = $amount - $fee;
            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore - $amount;

            // Create transaction
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'debit',
                'category' => 'payment',
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'currency' => $wallet->currency,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'status' => $gatewayResponse['status'],
                'payment_method' => $paymentMethod->provider,
                'gateway_transaction_id' => $gatewayResponse['transaction_id'] ?? null,
                'description' => $description,
                'metadata' => array_merge($metadata, [
                    'payment_method_id' => $paymentMethod->id,
                    'gateway_response' => $gatewayResponse
                ]),
                'processed_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Update wallet balance if payment successful
            if ($gatewayResponse['status'] === 'completed') {
                $wallet->update([
                    'balance' => $balanceAfter,
                    'last_transaction_at' => now(),
                ]);
            }

            DB::commit();

            Log::info('Payment processed successfully', [
                'wallet_id' => $wallet->id,
                'payment_method_id' => $paymentMethod->id,
                'transaction_id' => $transaction->transaction_id,
                'amount' => $amount,
                'status' => $gatewayResponse['status']
            ]);

            return $transaction;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed', [
                'payment_method_id' => $paymentMethod->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Refund payment
     *
     * @param WalletTransaction $originalTransaction
     * @param float $amount
     * @param string $reason
     * @return WalletTransaction
     */
    public function refundPayment(
        WalletTransaction $originalTransaction,
        float $amount = null,
        string $reason = 'استرداد المبلغ'
    ): WalletTransaction {
        try {
            DB::beginTransaction();

            $wallet = $originalTransaction->wallet;
            $refundAmount = $amount ?? $originalTransaction->net_amount;

            if ($refundAmount > $originalTransaction->net_amount) {
                throw new Exception('مبلغ الاسترداد لا يمكن أن يكون أكبر من المبلغ الأصلي');
            }

            // Process refund through gateway
            $gatewayResponse = $this->processRefundGateway($originalTransaction, $refundAmount);

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $refundAmount;

            // Create refund transaction
            $refundTransaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'credit',
                'category' => 'refund',
                'amount' => $refundAmount,
                'net_amount' => $refundAmount,
                'currency' => $wallet->currency,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'status' => $gatewayResponse['status'],
                'reference_id' => $originalTransaction->transaction_id,
                'gateway_transaction_id' => $gatewayResponse['transaction_id'] ?? null,
                'description' => $reason,
                'metadata' => [
                    'original_transaction_id' => $originalTransaction->id,
                    'refund_type' => $amount ? 'partial' : 'full',
                    'gateway_response' => $gatewayResponse
                ],
                'processed_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Update wallet balance if refund successful
            if ($gatewayResponse['status'] === 'completed') {
                $wallet->update([
                    'balance' => $balanceAfter,
                    'last_transaction_at' => now(),
                ]);
            }

            DB::commit();

            Log::info('Refund processed successfully', [
                'wallet_id' => $wallet->id,
                'original_transaction_id' => $originalTransaction->transaction_id,
                'refund_transaction_id' => $refundTransaction->transaction_id,
                'refund_amount' => $refundAmount
            ]);

            return $refundTransaction;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Refund processing failed', [
                'original_transaction_id' => $originalTransaction->transaction_id,
                'refund_amount' => $refundAmount,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Validate payment data
     *
     * @param array $paymentData
     * @return void
     * @throws Exception
     */
    private function validatePaymentData(array $paymentData): void
    {
        $requiredFields = ['type', 'provider'];
        
        foreach ($requiredFields as $field) {
            if (empty($paymentData[$field])) {
                throw new Exception("حقل {$field} مطلوب");
            }
        }

        $validTypes = ['card', 'bank_account', 'mobile_payment'];
        if (!in_array($paymentData['type'], $validTypes)) {
            throw new Exception('نوع طريقة الدفع غير صالح');
        }

        $validProviders = ['visa', 'mastercard', 'mada', 'stc_pay', 'apple_pay', 'google_pay'];
        if (!in_array($paymentData['provider'], $validProviders)) {
            throw new Exception('مقدم الخدمة غير مدعوم');
        }
    }

    /**
     * Generate secure token for payment method
     *
     * @param array $paymentData
     * @return string
     */
    private function generateSecureToken(array $paymentData): string
    {
        $data = json_encode([
            'type' => $paymentData['type'],
            'provider' => $paymentData['provider'],
            'timestamp' => now()->timestamp,
            'random' => \Str::random(32)
        ]);

        return hash('sha256', $data);
    }

    /**
     * Generate fingerprint for payment method
     *
     * @param array $paymentData
     * @return string
     */
    private function generateFingerprint(array $paymentData): string
    {
        $fingerprintData = [
            'type' => $paymentData['type'],
            'provider' => $paymentData['provider'],
            'last_four' => $paymentData['last_four'] ?? '',
        ];

        return hash('md5', json_encode($fingerprintData));
    }

    /**
     * Process payment through gateway (mock implementation)
     *
     * @param WalletPaymentMethod $paymentMethod
     * @param float $amount
     * @param array $metadata
     * @return array
     */
    private function processPaymentGateway(
        WalletPaymentMethod $paymentMethod,
        float $amount,
        array $metadata
    ): array {
        // This is a mock implementation
        // In real implementation, integrate with actual payment gateways
        
        $success = random_int(1, 100) > 5; // 95% success rate for demo
        
        return [
            'status' => $success ? 'completed' : 'failed',
            'transaction_id' => 'GW' . now()->format('YmdHis') . random_int(1000, 9999),
            'gateway_response_code' => $success ? '00' : '05',
            'gateway_message' => $success ? 'Transaction approved' : 'Transaction declined',
            'processed_at' => now()->toISOString(),
        ];
    }

    /**
     * Process refund through gateway (mock implementation)
     *
     * @param WalletTransaction $originalTransaction
     * @param float $amount
     * @return array
     */
    private function processRefundGateway(WalletTransaction $originalTransaction, float $amount): array
    {
        // This is a mock implementation
        // In real implementation, integrate with actual payment gateways
        
        $success = random_int(1, 100) > 10; // 90% success rate for refunds
        
        return [
            'status' => $success ? 'completed' : 'failed',
            'transaction_id' => 'RF' . now()->format('YmdHis') . random_int(1000, 9999),
            'gateway_response_code' => $success ? '00' : '05',
            'gateway_message' => $success ? 'Refund approved' : 'Refund declined',
            'processed_at' => now()->toISOString(),
        ];
    }

    /**
     * Calculate payment processing fee
     *
     * @param float $amount
     * @param string $provider
     * @return float
     */
    private function calculatePaymentFee(float $amount, string $provider): float
    {
        $feeRates = [
            'visa' => 0.025,        // 2.5%
            'mastercard' => 0.025,  // 2.5%
            'mada' => 0.015,        // 1.5%
            'stc_pay' => 0.02,      // 2%
            'apple_pay' => 0.03,    // 3%
            'google_pay' => 0.03,   // 3%
        ];

        $feeRate = $feeRates[$provider] ?? 0.025;
        $fee = $amount * $feeRate;
        
        // Minimum fee 1 SAR, Maximum fee 100 SAR
        return max(1.00, min(100.00, $fee));
    }

    /**
     * Verify payment method
     *
     * @param WalletPaymentMethod $paymentMethod
     * @return bool
     */
    public function verifyPaymentMethod(WalletPaymentMethod $paymentMethod): bool
    {
        try {
            // Mock verification process
            // In real implementation, this would involve actual verification
            
            $verified = random_int(1, 100) > 20; // 80% verification success rate
            
            if ($verified) {
                $paymentMethod->update([
                    'is_verified' => true,
                    'verified_at' => now(),
                ]);

                Log::info('Payment method verified successfully', [
                    'payment_method_id' => $paymentMethod->id,
                    'wallet_id' => $paymentMethod->wallet_id
                ]);
            }

            return $verified;

        } catch (Exception $e) {
            Log::error('Payment method verification failed', [
                'payment_method_id' => $paymentMethod->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Remove payment method
     *
     * @param WalletPaymentMethod $paymentMethod
     * @return bool
     */
    public function removePaymentMethod(WalletPaymentMethod $paymentMethod): bool
    {
        try {
            // If this is the default payment method, find another to set as default
            if ($paymentMethod->is_default) {
                $newDefault = WalletPaymentMethod::where('wallet_id', $paymentMethod->wallet_id)
                    ->where('id', '!=', $paymentMethod->id)
                    ->where('is_verified', true)
                    ->first();

                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }

            $paymentMethod->delete();

            Log::info('Payment method removed successfully', [
                'payment_method_id' => $paymentMethod->id,
                'wallet_id' => $paymentMethod->wallet_id
            ]);

            return true;

        } catch (Exception $e) {
            Log::error('Failed to remove payment method', [
                'payment_method_id' => $paymentMethod->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}