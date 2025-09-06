<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;

class WalletEncryptionService
{
    /**
     * Encrypt sensitive wallet data
     *
     * @param mixed $data
     * @return string
     */
    public static function encryptData($data): string
    {
        try {
            return Crypt::encrypt($data);
        } catch (\Exception $e) {
            throw new \Exception('فشل في تشفير البيانات');
        }
    }

    /**
     * Decrypt sensitive wallet data
     *
     * @param string $encryptedData
     * @return mixed
     */
    public static function decryptData(string $encryptedData)
    {
        try {
            return Crypt::decrypt($encryptedData);
        } catch (DecryptException $e) {
            throw new \Exception('فشل في فك تشفير البيانات');
        }
    }

    /**
     * Hash PIN with salt
     *
     * @param string $pin
     * @return string
     */
    public static function hashPin(string $pin): string
    {
        // Add additional salt for PIN hashing
        $saltedPin = $pin . config('app.key') . 'wallet_pin_salt';
        return Hash::make($saltedPin);
    }

    /**
     * Verify PIN hash
     *
     * @param string $pin
     * @param string $hashedPin
     * @return bool
     */
    public static function verifyPin(string $pin, string $hashedPin): bool
    {
        $saltedPin = $pin . config('app.key') . 'wallet_pin_salt';
        return Hash::check($saltedPin, $hashedPin);
    }

    /**
     * Generate secure transaction token
     *
     * @param array $transactionData
     * @return string
     */
    public static function generateTransactionToken(array $transactionData): string
    {
        $data = [
            'wallet_id' => $transactionData['wallet_id'],
            'amount' => $transactionData['amount'],
            'type' => $transactionData['type'],
            'timestamp' => now()->timestamp,
            'nonce' => \Str::random(32),
        ];

        return hash_hmac('sha256', json_encode($data), config('app.key'));
    }

    /**
     * Verify transaction token
     *
     * @param string $token
     * @param array $transactionData
     * @return bool
     */
    public static function verifyTransactionToken(string $token, array $transactionData): bool
    {
        $expectedToken = self::generateTransactionToken($transactionData);
        return hash_equals($expectedToken, $token);
    }

    /**
     * Encrypt payment method data
     *
     * @param array $paymentData
     * @return array
     */
    public static function encryptPaymentMethod(array $paymentData): array
    {
        $sensitiveFields = ['card_number', 'cvv', 'account_number', 'iban'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($paymentData[$field])) {
                $paymentData[$field] = self::encryptData($paymentData[$field]);
            }
        }

        return $paymentData;
    }

    /**
     * Decrypt payment method data
     *
     * @param array $paymentData
     * @return array
     */
    public static function decryptPaymentMethod(array $paymentData): array
    {
        $sensitiveFields = ['card_number', 'cvv', 'account_number', 'iban'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($paymentData[$field])) {
                try {
                    $paymentData[$field] = self::decryptData($paymentData[$field]);
                } catch (\Exception $e) {
                    $paymentData[$field] = null;
                }
            }
        }

        return $paymentData;
    }

    /**
     * Generate secure API token for wallet operations
     *
     * @param int $userId
     * @param int $walletId
     * @param string $operation
     * @param int $expiresInMinutes
     * @return string
     */
    public static function generateApiToken(
        int $userId, 
        int $walletId, 
        string $operation, 
        int $expiresInMinutes = 60
    ): string {
        $payload = [
            'user_id' => $userId,
            'wallet_id' => $walletId,
            'operation' => $operation,
            'expires_at' => now()->addMinutes($expiresInMinutes)->timestamp,
            'nonce' => \Str::random(32),
        ];

        $token = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $token, config('app.key'));

        return $token . '.' . $signature;
    }

    /**
     * Verify API token for wallet operations
     *
     * @param string $token
     * @param int $userId
     * @param int $walletId
     * @param string $operation
     * @return bool
     */
    public static function verifyApiToken(
        string $token, 
        int $userId, 
        int $walletId, 
        string $operation
    ): bool {
        try {
            [$payload, $signature] = explode('.', $token, 2);
            
            // Verify signature
            $expectedSignature = hash_hmac('sha256', $payload, config('app.key'));
            if (!hash_equals($expectedSignature, $signature)) {
                return false;
            }

            // Decode payload
            $data = json_decode(base64_decode($payload), true);
            
            // Verify token data
            if ($data['user_id'] !== $userId || 
                $data['wallet_id'] !== $walletId || 
                $data['operation'] !== $operation) {
                return false;
            }

            // Check expiration
            if ($data['expires_at'] < now()->timestamp) {
                return false;
            }

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Mask sensitive data for logging
     *
     * @param array $data
     * @return array
     */
    public static function maskSensitiveData(array $data): array
    {
        $sensitiveFields = [
            'pin', 'password', 'card_number', 'cvv', 
            'account_number', 'iban', 'token', 'secret'
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $value = $data[$field];
                if (strlen($value) > 4) {
                    $data[$field] = str_repeat('*', strlen($value) - 4) . substr($value, -4);
                } else {
                    $data[$field] = str_repeat('*', strlen($value));
                }
            }
        }

        return $data;
    }

    /**
     * Generate one-time password for sensitive operations
     *
     * @param int $userId
     * @param string $operation
     * @return array
     */
    public static function generateOTP(int $userId, string $operation): array
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);
        
        $key = "wallet_otp:{$userId}:{$operation}";
        
        \Cache::put($key, [
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'attempts' => 0,
        ], 300); // 5 minutes

        return [
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'expires_in_minutes' => 5,
        ];
    }

    /**
     * Verify one-time password
     *
     * @param int $userId
     * @param string $operation
     * @param string $otp
     * @return bool
     */
    public static function verifyOTP(int $userId, string $operation, string $otp): bool
    {
        $key = "wallet_otp:{$userId}:{$operation}";
        $otpData = \Cache::get($key);

        if (!$otpData) {
            return false;
        }

        // Check attempts
        if ($otpData['attempts'] >= 3) {
            \Cache::forget($key);
            return false;
        }

        // Increment attempts
        $otpData['attempts']++;
        \Cache::put($key, $otpData, 300);

        // Check expiration
        if (now()->gt($otpData['expires_at'])) {
            \Cache::forget($key);
            return false;
        }

        // Verify OTP
        if ($otpData['otp'] === $otp) {
            \Cache::forget($key);
            return true;
        }

        return false;
    }

    /**
     * Encrypt wallet metadata
     *
     * @param array $metadata
     * @return array
     */
    public static function encryptMetadata(array $metadata): array
    {
        $encryptedMetadata = [];
        
        foreach ($metadata as $key => $value) {
            if (in_array($key, ['sensitive_info', 'personal_data', 'financial_data'])) {
                $encryptedMetadata[$key] = self::encryptData($value);
            } else {
                $encryptedMetadata[$key] = $value;
            }
        }

        return $encryptedMetadata;
    }

    /**
     * Decrypt wallet metadata
     *
     * @param array $metadata
     * @return array
     */
    public static function decryptMetadata(array $metadata): array
    {
        $decryptedMetadata = [];
        
        foreach ($metadata as $key => $value) {
            if (in_array($key, ['sensitive_info', 'personal_data', 'financial_data'])) {
                try {
                    $decryptedMetadata[$key] = self::decryptData($value);
                } catch (\Exception $e) {
                    $decryptedMetadata[$key] = null;
                }
            } else {
                $decryptedMetadata[$key] = $value;
            }
        }

        return $decryptedMetadata;
    }

    /**
     * Generate secure session token for wallet operations
     *
     * @param int $walletId
     * @return string
     */
    public static function generateSessionToken(int $walletId): string
    {
        $sessionData = [
            'wallet_id' => $walletId,
            'user_id' => Auth::id(),
            'created_at' => now()->timestamp,
            'expires_at' => now()->addHour()->timestamp,
            'session_id' => session()->getId(),
            'nonce' => \Str::random(32),
        ];

        $token = base64_encode(json_encode($sessionData));
        $signature = hash_hmac('sha256', $token, config('app.key'));

        return $token . '.' . $signature;
    }

    /**
     * Verify session token
     *
     * @param string $token
     * @param int $walletId
     * @return bool
     */
    public static function verifySessionToken(string $token, int $walletId): bool
    {
        try {
            [$payload, $signature] = explode('.', $token, 2);
            
            // Verify signature
            $expectedSignature = hash_hmac('sha256', $payload, config('app.key'));
            if (!hash_equals($expectedSignature, $signature)) {
                return false;
            }

            // Decode and verify payload
            $data = json_decode(base64_decode($payload), true);
            
            return $data['wallet_id'] === $walletId &&
                   $data['user_id'] === Auth::id() &&
                   $data['session_id'] === session()->getId() &&
                   $data['expires_at'] > now()->timestamp;

        } catch (\Exception $e) {
            return false;
        }
    }
}