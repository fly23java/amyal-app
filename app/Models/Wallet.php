<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wallet_number',
        'user_id',
        'wallet_type',
        'currency',
        'balance',
        'pending_balance',
        'reserved_balance',
        'is_active',
        'is_verified',
        'status',
        'settings',
        'metadata',
        'last_transaction_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'reserved_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'settings' => 'array',
        'metadata' => 'array',
        'last_transaction_at' => 'datetime',
        'pin_set_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pin_hash',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wallet) {
            if (empty($wallet->wallet_number)) {
                $wallet->wallet_number = self::generateWalletNumber();
            }
        });
    }

    /**
     * Generate unique wallet number
     *
     * @return string
     */
    public static function generateWalletNumber(): string
    {
        do {
            $number = 'W' . str_pad(random_int(1, 99999999999999999), 17, '0', STR_PAD_LEFT);
        } while (self::where('wallet_number', $number)->exists());

        return $number;
    }

    /**
     * Get the user that owns the wallet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get the wallet payment methods.
     */
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(WalletPaymentMethod::class);
    }

    /**
     * Get the wallet limits.
     */
    public function limits(): HasMany
    {
        return $this->hasMany(WalletLimit::class);
    }

    /**
     * Get recent transactions.
     */
    public function recentTransactions($limit = 10)
    {
        return $this->transactions()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get available balance (balance - reserved)
     *
     * @return float
     */
    public function getAvailableBalanceAttribute(): float
    {
        return $this->balance - $this->reserved_balance;
    }

    /**
     * Get total balance (balance + pending)
     *
     * @return float
     */
    public function getTotalBalanceAttribute(): float
    {
        return $this->balance + $this->pending_balance;
    }

    /**
     * Check if wallet can perform transaction
     *
     * @param float $amount
     * @return bool
     */
    public function canTransact(float $amount): bool
    {
        return $this->is_active && 
               $this->status === 'active' && 
               $this->available_balance >= $amount;
    }

    /**
     * Set wallet PIN
     *
     * @param string $pin
     * @return bool
     */
    public function setPin(string $pin): bool
    {
        $this->pin_hash = Hash::make($pin);
        $this->pin_set_at = now();
        return $this->save();
    }

    /**
     * Verify wallet PIN
     *
     * @param string $pin
     * @return bool
     */
    public function verifyPin(string $pin): bool
    {
        return Hash::check($pin, $this->pin_hash);
    }

    /**
     * Check if PIN is set
     *
     * @return bool
     */
    public function hasPinSet(): bool
    {
        return !is_null($this->pin_hash);
    }

    /**
     * Freeze wallet
     *
     * @param string $reason
     * @return bool
     */
    public function freeze(string $reason = ''): bool
    {
        $this->status = 'suspended';
        $this->is_active = false;
        
        if ($reason) {
            $metadata = $this->metadata ?? [];
            $metadata['freeze_reason'] = $reason;
            $metadata['frozen_at'] = now()->toISOString();
            $this->metadata = $metadata;
        }
        
        return $this->save();
    }

    /**
     * Unfreeze wallet
     *
     * @return bool
     */
    public function unfreeze(): bool
    {
        $this->status = 'active';
        $this->is_active = true;
        
        $metadata = $this->metadata ?? [];
        unset($metadata['freeze_reason']);
        $metadata['unfrozen_at'] = now()->toISOString();
        $this->metadata = $metadata;
        
        return $this->save();
    }

    /**
     * Get wallet status color
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'suspended' => 'warning',
            'closed' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get wallet type display name
     *
     * @return string
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->wallet_type) {
            'main' => 'المحفظة الرئيسية',
            'savings' => 'محفظة الادخار',
            'business' => 'محفظة الأعمال',
            default => 'محفظة عامة'
        };
    }

    /**
     * Scope for active wallets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    /**
     * Scope for verified wallets
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope by wallet type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('wallet_type', $type);
    }

    /**
     * Scope by currency
     */
    public function scopeByCurrency($query, $currency)
    {
        return $query->where('currency', $currency);
    }
}