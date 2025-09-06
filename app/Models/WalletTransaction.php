<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class WalletTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'wallet_id',
        'related_wallet_id',
        'type',
        'category',
        'amount',
        'fee',
        'net_amount',
        'currency',
        'exchange_rate',
        'balance_before',
        'balance_after',
        'status',
        'payment_method',
        'reference_id',
        'gateway_transaction_id',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
        'processed_at',
        'processed_by',
        'failure_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_id)) {
                $transaction->transaction_id = self::generateTransactionId();
            }
            
            if (empty($transaction->net_amount)) {
                $transaction->net_amount = $transaction->amount - ($transaction->fee ?? 0);
            }
        });
    }

    /**
     * Generate unique transaction ID
     *
     * @return string
     */
    public static function generateTransactionId(): string
    {
        do {
            $id = 'TXN' . now()->format('Ymd') . strtoupper(Str::random(10));
        } while (self::where('transaction_id', $id)->exists());

        return $id;
    }

    /**
     * Get the wallet that owns the transaction.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the related wallet (for transfers).
     */
    public function relatedWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'related_wallet_id');
    }

    /**
     * Get the user who processed the transaction.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Check if transaction is completed
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if transaction is pending
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is failed
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if transaction is credit (incoming)
     *
     * @return bool
     */
    public function isCredit(): bool
    {
        return $this->type === 'credit';
    }

    /**
     * Check if transaction is debit (outgoing)
     *
     * @return bool
     */
    public function isDebit(): bool
    {
        return $this->type === 'debit';
    }

    /**
     * Get transaction type display name
     *
     * @return string
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'credit' => 'إيداع',
            'debit' => 'خصم',
            'transfer' => 'تحويل',
            'refund' => 'استرداد',
            'fee' => 'رسوم',
            default => 'غير محدد'
        };
    }

    /**
     * Get transaction category display name
     *
     * @return string
     */
    public function getCategoryDisplayAttribute(): string
    {
        return match($this->category) {
            'deposit' => 'إيداع',
            'withdrawal' => 'سحب',
            'payment' => 'دفعة',
            'refund' => 'استرداد',
            'transfer' => 'تحويل',
            'fee' => 'رسوم',
            'cashback' => 'كاش باك',
            default => 'أخرى'
        };
    }

    /**
     * Get transaction status color
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'cancelled' => 'secondary',
            default => 'info'
        };
    }

    /**
     * Get transaction status display name
     *
     * @return string
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'pending' => 'قيد المعالجة',
            'completed' => 'مكتملة',
            'failed' => 'فاشلة',
            'cancelled' => 'ملغاة',
            default => 'غير محدد'
        };
    }

    /**
     * Get formatted amount with currency
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        $symbol = $this->currency === 'SAR' ? 'ر.س' : $this->currency;
        return number_format($this->amount, 2) . ' ' . $symbol;
    }

    /**
     * Get formatted net amount with currency
     *
     * @return string
     */
    public function getFormattedNetAmountAttribute(): string
    {
        $symbol = $this->currency === 'SAR' ? 'ر.س' : $this->currency;
        return number_format($this->net_amount, 2) . ' ' . $symbol;
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope by transaction type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope by transaction category
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope by amount range
     */
    public function scopeAmountRange($query, $minAmount, $maxAmount)
    {
        return $query->whereBetween('amount', [$minAmount, $maxAmount]);
    }
}