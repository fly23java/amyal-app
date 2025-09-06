<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletPaymentMethod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wallet_id',
        'type',
        'provider',
        'last_four',
        'token',
        'fingerprint',
        'is_default',
        'is_verified',
        'metadata',
        'expires_at',
        'verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
        'is_verified' => 'boolean',
        'metadata' => 'array',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'token',
        'fingerprint',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paymentMethod) {
            // Ensure only one default payment method per wallet
            if ($paymentMethod->is_default) {
                self::where('wallet_id', $paymentMethod->wallet_id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });

        static::updating(function ($paymentMethod) {
            // Ensure only one default payment method per wallet
            if ($paymentMethod->is_default && $paymentMethod->isDirty('is_default')) {
                self::where('wallet_id', $paymentMethod->wallet_id)
                    ->where('id', '!=', $paymentMethod->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });
    }

    /**
     * Get the wallet that owns the payment method.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Check if payment method is expired
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if payment method is valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->is_verified && !$this->isExpired();
    }

    /**
     * Get payment method type display name
     *
     * @return string
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'card' => 'بطاقة',
            'bank_account' => 'حساب بنكي',
            'mobile_payment' => 'دفع جوال',
            default => 'غير محدد'
        };
    }

    /**
     * Get payment method provider display name
     *
     * @return string
     */
    public function getProviderDisplayAttribute(): string
    {
        return match($this->provider) {
            'visa' => 'فيزا',
            'mastercard' => 'ماستركارد',
            'mada' => 'مدى',
            'stc_pay' => 'STC Pay',
            'apple_pay' => 'Apple Pay',
            'google_pay' => 'Google Pay',
            'paypal' => 'PayPal',
            default => $this->provider
        };
    }

    /**
     * Get masked card number
     *
     * @return string
     */
    public function getMaskedNumberAttribute(): string
    {
        if ($this->last_four) {
            return '**** **** **** ' . $this->last_four;
        }
        
        return '****';
    }

    /**
     * Get payment method icon
     *
     * @return string
     */
    public function getIconAttribute(): string
    {
        return match($this->provider) {
            'visa' => 'fab fa-cc-visa',
            'mastercard' => 'fab fa-cc-mastercard',
            'mada' => 'fas fa-credit-card',
            'stc_pay' => 'fas fa-mobile-alt',
            'apple_pay' => 'fab fa-apple-pay',
            'google_pay' => 'fab fa-google-pay',
            'paypal' => 'fab fa-paypal',
            default => 'fas fa-credit-card'
        };
    }

    /**
     * Get payment method color
     *
     * @return string
     */
    public function getColorAttribute(): string
    {
        return match($this->provider) {
            'visa' => '#1A1F71',
            'mastercard' => '#EB001B',
            'mada' => '#00A651',
            'stc_pay' => '#662D91',
            'apple_pay' => '#000000',
            'google_pay' => '#4285F4',
            'paypal' => '#003087',
            default => '#6c757d'
        };
    }

    /**
     * Scope for verified payment methods
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for default payment methods
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for valid payment methods
     */
    public function scopeValid($query)
    {
        return $query->where('is_verified', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope by payment method type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope by payment method provider
     */
    public function scopeOfProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }
}