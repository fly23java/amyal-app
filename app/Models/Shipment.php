<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Traits\Searchable;
use App\Traits\Filterable;
use App\Traits\Sortable;

class Shipment extends Model
{
    use HasFactory, SoftDeletes, Searchable, Filterable, Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shipments';

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serial_number',
        'account_id',
        'loading_city_id',
        'unloading_city_id',
        'loading_location',
        'unloading_location',
        'vehicle_type_id',
        'goods_id',
        'price',
        'carrier_price',
        'supervisor_user_id',
        'status_id',
        'notes',
        'priority',
        'estimated_delivery_date',
        'actual_delivery_date',
        'weight',
        'dimensions',
        'insurance_amount',
        'tracking_number'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'carrier_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
        'estimated_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'estimated_delivery_date',
        'actual_delivery_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Searchable fields for the Searchable trait
     *
     * @var array
     */
    protected $searchable = [
        'serial_number',
        'loading_location',
        'unloading_location',
        'tracking_number'
    ];

    /**
     * Filterable fields for the Filterable trait
     *
     * @var array
     */
    protected $filterable = [
        'status_id',
        'account_id',
        'loading_city_id',
        'unloading_city_id',
        'vehicle_type_id',
        'goods_id',
        'supervisor_user_id',
        'priority',
        'created_at',
        'estimated_delivery_date'
    ];

    /**
     * Sortable fields for the Sortable trait
     *
     * @var array
     */
    protected $sortable = [
        'serial_number',
        'price',
        'carrier_price',
        'created_at',
        'estimated_delivery_date',
        'priority'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_price',
        'formatted_carrier_price',
        'formatted_weight',
        'delivery_status',
        'days_until_delivery',
        'profit_margin'
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the Account for this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * Get the User (supervisor) for this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_user_id');
    }

    /**
     * Get the loading City for this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loadingCity()
    {
        return $this->belongsTo(City::class, 'loading_city_id');
    }

    /**
     * Get the unloading City for this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unloadingCity()
    {
        return $this->belongsTo(City::class, 'unloading_city_id');
    }

    /**
     * Get the VehicleType for this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    /**
     * Get the Goods for this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    /**
     * Get the Status for this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * Get the shipment delivery details for this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deliveryDetail()
    {
        return $this->hasOne(ShipmentDeliveryDetail::class, 'shipment_id');
    }

    /**
     * Get the vehicle assigned to this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function vehicle()
    {
        return $this->hasOneThrough(
            Vehicle::class,
            ShipmentDeliveryDetail::class,
            'shipment_id',
            'id',
            'id',
            'vehicle_id'
        );
    }

    /**
     * Get the carrier (account) for this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function carrier()
    {
        return $this->hasOneThrough(
            Account::class,
            ShipmentDeliveryDetail::class,
            'shipment_id',
            'id',
            'id',
            'vehicle_id'
        )->join('vehicles', 'vehicles.id', '=', 'shipment_delivery_details.vehicle_id');
    }

    /**
     * Get the status changes for this shipment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statusChanges()
    {
        return $this->hasMany(StatusChange::class, 'shipment_id');
    }

    // ===== SCOPES =====

    /**
     * Scope a query to only include active shipments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('is_active', true);
        });
    }

    /**
     * Scope a query to only include completed shipments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted(Builder $query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('name', 'مكتمل');
        });
    }

    /**
     * Scope a query to only include pending shipments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending(Builder $query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('name', 'قيد الانتظار');
        });
    }

    /**
     * Scope a query to only include shipments by priority.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $priority
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPriority(Builder $query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include shipments by date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $startDate
     * @param  string  $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDateRange(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include shipments by account.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $accountId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAccount(Builder $query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    // ===== ACCESSORS =====

    /**
     * Get formatted price attribute.
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' ريال';
    }

    /**
     * Get formatted carrier price attribute.
     *
     * @return string
     */
    public function getFormattedCarrierPriceAttribute()
    {
        return number_format($this->carrier_price, 2) . ' ريال';
    }

    /**
     * Get formatted weight attribute.
     *
     * @return string
     */
    public function getFormattedWeightAttribute()
    {
        return $this->weight ? number_format($this->weight, 2) . ' كجم' : 'غير محدد';
    }

    /**
     * Get delivery status attribute.
     *
     * @return string
     */
    public function getDeliveryStatusAttribute()
    {
        if ($this->actual_delivery_date) {
            return 'تم التسليم';
        }
        
        if ($this->estimated_delivery_date && $this->estimated_delivery_date->isPast()) {
            return 'متأخر';
        }
        
        return 'قيد التسليم';
    }

    /**
     * Get days until delivery attribute.
     *
     * @return int|null
     */
    public function getDaysUntilDeliveryAttribute()
    {
        if (!$this->estimated_delivery_date) {
            return null;
        }
        
        return Carbon::now()->diffInDays($this->estimated_delivery_date, false);
    }

    /**
     * Get profit margin attribute.
     *
     * @return float
     */
    public function getProfitMarginAttribute()
    {
        if ($this->carrier_price == 0) {
            return 0;
        }
        
        return round((($this->price - $this->carrier_price) / $this->carrier_price) * 100, 2);
    }

    // ===== MUTATORS =====

    /**
     * Set the serial number attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setSerialNumberAttribute($value)
    {
        $this->attributes['serial_number'] = strtoupper($value);
    }

    /**
     * Set the tracking number attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setTrackingNumberAttribute($value)
    {
        $this->attributes['tracking_number'] = strtoupper($value);
    }

    // ===== METHODS =====

    /**
     * Check if shipment is overdue.
     *
     * @return bool
     */
    public function isOverdue()
    {
        return $this->estimated_delivery_date && 
               $this->estimated_delivery_date->isPast() && 
               !$this->actual_delivery_date;
    }

    /**
     * Check if shipment is completed.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status && $this->status->name === 'مكتمل';
    }

    /**
     * Check if shipment is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status && $this->status->name === 'قيد الانتظار';
    }

    /**
     * Get the next status for this shipment.
     *
     * @return Status|null
     */
    public function getNextStatus()
    {
        if (!$this->status) {
            return Status::first();
        }
        
        return Status::where('order', '>', $this->status->order)
                    ->orderBy('order')
                    ->first();
    }

    /**
     * Update shipment status.
     *
     * @param  int  $statusId
     * @param  string  $notes
     * @return bool
     */
    public function updateStatus($statusId, $notes = null)
    {
        $oldStatusId = $this->status_id;
        
        $this->update(['status_id' => $statusId]);
        
        // Create status change record
        StatusChange::create([
            'shipment_id' => $this->id,
            'old_status_id' => $oldStatusId,
            'new_status_id' => $statusId,
            'notes' => $notes,
            'changed_by' => auth()->id()
        ]);
        
        return true;
    }

    /**
     * Mark shipment as delivered.
     *
     * @param  string  $notes
     * @return bool
     */
    public function markAsDelivered($notes = null)
    {
        $deliveredStatus = Status::where('name', 'مكتمل')->first();
        
        if ($deliveredStatus) {
            $this->update([
                'status_id' => $deliveredStatus->id,
                'actual_delivery_date' => now()
            ]);
            
            $this->updateStatus($deliveredStatus->id, $notes);
            
            return true;
        }
        
        return false;
    }

    /**
     * Calculate total cost including carrier price.
     *
     * @return float
     */
    public function getTotalCost()
    {
        return $this->carrier_price;
    }

    /**
     * Calculate profit.
     *
     * @return float
     */
    public function getProfit()
    {
        return $this->price - $this->carrier_price;
    }

    /**
     * Get shipment summary for reports.
     *
     * @return array
     */
    public function getSummary()
    {
        return [
            'id' => $this->id,
            'serial_number' => $this->serial_number,
            'tracking_number' => $this->tracking_number,
            'status' => $this->status ? $this->status->name : 'غير محدد',
            'price' => $this->formatted_price,
            'carrier_price' => $this->formatted_carrier_price,
            'profit' => number_format($this->getProfit(), 2) . ' ريال',
            'profit_margin' => $this->profit_margin . '%',
            'delivery_status' => $this->delivery_status,
            'days_until_delivery' => $this->days_until_delivery,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'estimated_delivery_date' => $this->estimated_delivery_date ? $this->estimated_delivery_date->format('Y-m-d') : 'غير محدد'
        ];
    }

    // ===== STATIC METHODS =====

    /**
     * Get shipment statistics.
     *
     * @return array
     */
    public static function getStatistics()
    {
        $total = self::count();
        $completed = self::completed()->count();
        $pending = self::pending()->count();
        $overdue = self::where('estimated_delivery_date', '<', now())
                      ->whereNull('actual_delivery_date')
                      ->count();
        
        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'overdue' => $overdue,
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0
        ];
    }

    /**
     * Generate unique serial number.
     *
     * @return string
     */
    public static function generateSerialNumber()
    {
        $prefix = 'SH';
        $year = date('Y');
        $month = date('m');
        
        $lastShipment = self::whereYear('created_at', $year)
                           ->whereMonth('created_at', $month)
                           ->orderBy('id', 'desc')
                           ->first();
        
        if ($lastShipment) {
            $lastNumber = (int) substr($lastShipment->serial_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
