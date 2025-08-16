<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use App\Traits\Searchable;
use App\Traits\Filterable;
use App\Traits\Sortable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, Searchable, Filterable, Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birth_date',
        'account_id',
        'type',
        'status',
        'phone',
        'address',
        'city_id',
        'country_id',
        'profile_image',
        'last_login_at',
        'email_verified_at',
        'phone_verified_at',
        'two_factor_enabled',
        'two_factor_secret',
        'preferences',
        'notes'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'birth_date' => 'date',
        'two_factor_enabled' => 'boolean',
        'preferences' => 'array',
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
        'birth_date',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
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
        'name',
        'email',
        'phone',
        'address'
    ];

    /**
     * Filterable fields for the Filterable trait
     *
     * @var array
     */
    protected $filterable = [
        'type',
        'status',
        'account_id',
        'city_id',
        'country_id',
        'created_at',
        'last_login_at',
        'email_verified_at'
    ];

    /**
     * Sortable fields for the Sortable trait
     *
     * @var array
     */
    protected $sortable = [
        'name',
        'email',
        'created_at',
        'last_login_at',
        'type',
        'status'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
        'age',
        'status_label',
        'type_label',
        'last_login_formatted',
        'is_online',
        'profile_image_url'
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the Account for this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * Get the City for this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Get the Country for this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Get the shipments supervised by this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function supervisedShipments()
    {
        return $this->hasMany(Shipment::class, 'supervisor_user_id');
    }

    /**
     * Get the status changes made by this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statusChanges()
    {
        return $this->hasMany(StatusChange::class, 'changed_by');
    }

    /**
     * Get the user's roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Get the user's permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    // ===== SCOPES =====

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive(Builder $query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include users by type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include users by account.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $accountId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAccount(Builder $query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    /**
     * Scope a query to only include online users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnline(Builder $query)
    {
        return $query->where('last_login_at', '>=', now()->subMinutes(5));
    }

    /**
     * Scope a query to only include offline users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOffline(Builder $query)
    {
        return $query->where('last_login_at', '<', now()->subMinutes(5))
                    ->orWhereNull('last_login_at');
    }

    /**
     * Scope a query to only include verified users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified(Builder $query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope a query to only include unverified users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnverified(Builder $query)
    {
        return $query->whereNull('email_verified_at');
    }

    // ===== ACCESSORS =====

    /**
     * Get full name attribute.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get age attribute.
     *
     * @return int|null
     */
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        
        return $this->birth_date->age;
    }

    /**
     * Get status label attribute.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'suspended' => 'معلق',
            'pending' => 'قيد الانتظار'
        ];
        
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get type label attribute.
     *
     * @return string
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'admin' => 'مدير',
            'supervisor' => 'مشرف',
            'operator' => 'مشغل',
            'viewer' => 'مشاهد'
        ];
        
        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Get last login formatted attribute.
     *
     * @return string
     */
    public function getLastLoginFormattedAttribute()
    {
        if (!$this->last_login_at) {
            return 'لم يسجل الدخول';
        }
        
        return $this->last_login_at->diffForHumans();
    }

    /**
     * Get is online attribute.
     *
     * @return bool
     */
    public function getIsOnlineAttribute()
    {
        if (!$this->last_login_at) {
            return false;
        }
        
        return $this->last_login_at->isAfter(now()->subMinutes(5));
    }

    /**
     * Get profile image URL attribute.
     *
     * @return string
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        
        return asset('images/default-avatar.png');
    }

    // ===== MUTATORS =====

    /**
     * Set the name attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(trim($value));
    }

    /**
     * Set the email attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Set the phone attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9+]/', '', $value);
    }

    /**
     * Set the password attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    // ===== METHODS =====

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    /**
     * Check if user is supervisor.
     *
     * @return bool
     */
    public function isSupervisor()
    {
        return $this->type === 'supervisor';
    }

    /**
     * Check if user is operator.
     *
     * @return bool
     */
    public function isOperator()
    {
        return $this->type === 'operator';
    }

    /**
     * Check if user is viewer.
     *
     * @return bool
     */
    public function isViewer()
    {
        return $this->type === 'viewer';
    }

    /**
     * Check if user has specific role.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user has specific permission.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->permissions()->where('name', $permission)->exists();
    }

    /**
     * Check if user can perform specific action.
     *
     * @param  string  $action
     * @return bool
     */
    public function can($action)
    {
        // Check direct permissions
        if ($this->hasPermission($action)) {
            return true;
        }

        // Check role permissions
        foreach ($this->roles as $role) {
            if ($role->hasPermission($action)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Update last login timestamp.
     *
     * @return bool
     */
    public function updateLastLogin()
    {
        return $this->update(['last_login_at' => now()]);
    }

    /**
     * Mark email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->update(['email_verified_at' => now()]);
    }

    /**
     * Mark phone as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified()
    {
        return $this->update(['phone_verified_at' => now()]);
    }

    /**
     * Enable two-factor authentication.
     *
     * @return bool
     */
    public function enableTwoFactor()
    {
        return $this->update(['two_factor_enabled' => true]);
    }

    /**
     * Disable two-factor authentication.
     *
     * @return bool
     */
    public function disableTwoFactor()
    {
        return $this->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null
        ]);
    }

    /**
     * Get user preferences.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function getPreference($key, $default = null)
    {
        $preferences = $this->preferences ?? [];
        
        return $preferences[$key] ?? $default;
    }

    /**
     * Set user preference.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return bool
     */
    public function setPreference($key, $value)
    {
        $preferences = $this->preferences ?? [];
        $preferences[$key] = $value;
        
        return $this->update(['preferences' => $preferences]);
    }

    /**
     * Get user summary for reports.
     *
     * @return array
     */
    public function getSummary()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type_label,
            'status' => $this->status_label,
            'account' => $this->account ? $this->account->name : 'غير محدد',
            'city' => $this->city ? $this->city->name : 'غير محدد',
            'last_login' => $this->last_login_formatted,
            'is_online' => $this->is_online ? 'متصل' : 'غير متصل',
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'supervised_shipments_count' => $this->supervisedShipments()->count()
        ];
    }

    // ===== STATIC METHODS =====

    /**
     * Get user statistics.
     *
     * @return array
     */
    public static function getStatistics()
    {
        $total = self::count();
        $active = self::active()->count();
        $inactive = self::inactive()->count();
        $online = self::online()->count();
        $verified = self::verified()->count();
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'online' => $online,
            'verified' => $verified,
            'verification_rate' => $total > 0 ? round(($verified / $total) * 100, 2) : 0
        ];
    }

    /**
     * Get users by type statistics.
     *
     * @return array
     */
    public static function getTypeStatistics()
    {
        $types = ['admin', 'supervisor', 'operator', 'viewer'];
        $statistics = [];
        
        foreach ($types as $type) {
            $statistics[$type] = self::byType($type)->count();
        }
        
        return $statistics;
    }

    /**
     * Generate unique user ID.
     *
     * @return string
     */
    public static function generateUserId()
    {
        $prefix = 'USR';
        $year = date('Y');
        $month = date('m');
        
        $lastUser = self::whereYear('created_at', $year)
                       ->whereMonth('created_at', $month)
                       ->orderBy('id', 'desc')
                       ->first();
        
        if ($lastUser) {
            $lastNumber = (int) substr($lastUser->id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
