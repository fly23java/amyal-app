<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletLimit extends Model
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
        'operation',
        'limit_amount',
        'used_amount',
        'remaining_amount',
        'period_start',
        'period_end',
        'is_active',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'limit_amount' => 'decimal:2',
        'used_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($limit) {
            if (empty($limit->remaining_amount)) {
                $limit->remaining_amount = $limit->limit_amount - $limit->used_amount;
            }
        });

        static::updating(function ($limit) {
            $limit->remaining_amount = $limit->limit_amount - $limit->used_amount;
        });
    }

    /**
     * Get the wallet that owns the limit.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Check if limit is exceeded
     *
     * @param float $amount
     * @return bool
     */
    public function isExceeded(float $amount = 0): bool
    {
        return ($this->used_amount + $amount) > $this->limit_amount;
    }

    /**
     * Check if limit is active and within period
     *
     * @return bool
     */
    public function isValidForPeriod(): bool
    {
        $now = now()->toDateString();
        return $this->is_active && 
               $now >= $this->period_start->toDateString() && 
               $now <= $this->period_end->toDateString();
    }

    /**
     * Get remaining amount
     *
     * @return float
     */
    public function getRemainingAmount(): float
    {
        return max(0, $this->limit_amount - $this->used_amount);
    }

    /**
     * Get usage percentage
     *
     * @return float
     */
    public function getUsagePercentage(): float
    {
        if ($this->limit_amount == 0) {
            return 0;
        }
        
        return min(100, ($this->used_amount / $this->limit_amount) * 100);
    }

    /**
     * Add usage to the limit
     *
     * @param float $amount
     * @return bool
     */
    public function addUsage(float $amount): bool
    {
        $this->used_amount += $amount;
        $this->remaining_amount = $this->getRemainingAmount();
        
        return $this->save();
    }

    /**
     * Reset limit usage (for new period)
     *
     * @return bool
     */
    public function resetUsage(): bool
    {
        $this->used_amount = 0;
        $this->remaining_amount = $this->limit_amount;
        
        return $this->save();
    }

    /**
     * Get limit type display name
     *
     * @return string
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'daily' => 'يومي',
            'weekly' => 'أسبوعي',
            'monthly' => 'شهري',
            'yearly' => 'سنوي',
            'transaction' => 'لكل معاملة',
            default => 'غير محدد'
        };
    }

    /**
     * Get operation display name
     *
     * @return string
     */
    public function getOperationDisplayAttribute(): string
    {
        return match($this->operation) {
            'deposit' => 'الإيداع',
            'withdrawal' => 'السحب',
            'transfer' => 'التحويل',
            'total' => 'إجمالي المعاملات',
            default => 'غير محدد'
        };
    }

    /**
     * Get formatted limit amount
     *
     * @return string
     */
    public function getFormattedLimitAmountAttribute(): string
    {
        return number_format($this->limit_amount, 2) . ' ر.س';
    }

    /**
     * Get formatted used amount
     *
     * @return string
     */
    public function getFormattedUsedAmountAttribute(): string
    {
        return number_format($this->used_amount, 2) . ' ر.س';
    }

    /**
     * Get formatted remaining amount
     *
     * @return string
     */
    public function getFormattedRemainingAmountAttribute(): string
    {
        return number_format($this->remaining_amount, 2) . ' ر.س';
    }

    /**
     * Get status color based on usage
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        $percentage = $this->getUsagePercentage();
        
        if ($percentage >= 90) {
            return 'danger';
        } elseif ($percentage >= 70) {
            return 'warning';
        } elseif ($percentage >= 50) {
            return 'info';
        } else {
            return 'success';
        }
    }

    /**
     * Scope for active limits
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for current period limits
     */
    public function scopeCurrentPeriod($query)
    {
        $now = now()->toDateString();
        return $query->where('period_start', '<=', $now)
                    ->where('period_end', '>=', $now);
    }

    /**
     * Scope by limit type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope by operation
     */
    public function scopeOfOperation($query, $operation)
    {
        return $query->where('operation', $operation);
    }

    /**
     * Scope for exceeded limits
     */
    public function scopeExceeded($query)
    {
        return $query->whereRaw('used_amount >= limit_amount');
    }

    /**
     * Scope for nearly exceeded limits (90%+)
     */
    public function scopeNearlyExceeded($query)
    {
        return $query->whereRaw('used_amount >= (limit_amount * 0.9)');
    }
}