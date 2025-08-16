<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Searchable;
use App\Traits\Filterable;
use App\Traits\Sortable;

class Role extends Model
{
    use HasFactory, SoftDeletes, Searchable, Filterable, Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
        'is_active',
        'color',
        'icon',
        'level',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
        'level' => 'integer',
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
        'display_name',
        'description'
    ];

    /**
     * Filterable fields for the Filterable trait
     *
     * @var array
     */
    protected $filterable = [
        'is_active',
        'level',
        'created_by',
        'created_at'
    ];

    /**
     * Sortable fields for the Sortable trait
     *
     * @var array
     */
    protected $sortable = [
        'name',
        'display_name',
        'level',
        'created_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'permissions_count',
        'users_count',
        'formatted_level'
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the users that have this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    /**
     * Get the permissions for this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Get the user who created this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ===== SCOPES =====

    /**
     * Scope a query to only include active roles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive roles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive(Builder $query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include roles by level.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $level
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLevel(Builder $query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope a query to only include roles above a certain level.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $level
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAboveLevel(Builder $query, $level)
    {
        return $query->where('level', '>', $level);
    }

    /**
     * Scope a query to only include roles below a certain level.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $level
     * @param  int  $level
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBelowLevel(Builder $query, $level)
    {
        return $query->where('level', '<', $level);
    }

    // ===== ACCESSORS =====

    /**
     * Get permissions count attribute.
     *
     * @return int
     */
    public function getPermissionsCountAttribute()
    {
        return $this->permissions()->count();
    }

    /**
     * Get users count attribute.
     *
     * @return int
     */
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    /**
     * Get formatted level attribute.
     *
     * @return string
     */
    public function getFormattedLevelAttribute()
    {
        $levels = [
            1 => 'مبتدئ',
            2 => 'متوسط',
            3 => 'متقدم',
            4 => 'خبير',
            5 => 'مدير'
        ];
        
        return $levels[$this->level] ?? "مستوى {$this->level}";
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
        $this->attributes['name'] = strtolower(trim($value));
    }

    /**
     * Set the display name attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setDisplayNameAttribute($value)
    {
        $this->attributes['display_name'] = ucwords(trim($value));
    }

    // ===== METHODS =====

    /**
     * Check if role has specific permission.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->permissions()->where('name', $permission)->exists();
    }

    /**
     * Check if role has any of the specified permissions.
     *
     * @param  array  $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions)
    {
        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    /**
     * Check if role has all of the specified permissions.
     *
     * @param  array  $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions)
    {
        $rolePermissions = $this->permissions()->pluck('name')->toArray();
        
        return count(array_intersect($permissions, $rolePermissions)) === count($permissions);
    }

    /**
     * Grant permission to role.
     *
     * @param  string|Permission  $permission
     * @return bool
     */
    public function grantPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }
        
        if ($permission && !$this->hasPermission($permission->name)) {
            $this->permissions()->attach($permission->id);
            return true;
        }
        
        return false;
    }

    /**
     * Revoke permission from role.
     *
     * @param  string|Permission  $permission
     * @return bool
     */
    public function revokePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }
        
        if ($permission && $this->hasPermission($permission->name)) {
            $this->permissions()->detach($permission->id);
            return true;
        }
        
        return false;
    }

    /**
     * Sync permissions for role.
     *
     * @param  array  $permissionIds
     * @return bool
     */
    public function syncPermissions(array $permissionIds)
    {
        return $this->permissions()->sync($permissionIds);
    }

    /**
     * Check if role can be deleted.
     *
     * @return bool
     */
    public function canBeDeleted()
    {
        return $this->users()->count() === 0;
    }

    /**
     * Get role summary for reports.
     *
     * @return array
     */
    public function getSummary()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'level' => $this->formatted_level,
            'is_active' => $this->is_active ? 'نشط' : 'غير نشط',
            'permissions_count' => $this->permissions_count,
            'users_count' => $this->users_count,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'created_by' => $this->creator ? $this->creator->name : 'غير محدد'
        ];
    }

    // ===== STATIC METHODS =====

    /**
     * Get role statistics.
     *
     * @return array
     */
    public static function getStatistics()
    {
        $total = self::count();
        $active = self::active()->count();
        $inactive = self::inactive()->count();
        $withUsers = self::whereHas('users')->count();
        $withoutUsers = self::whereDoesntHave('users')->count();
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'with_users' => $withUsers,
            'without_users' => $withoutUsers,
            'active_rate' => $total > 0 ? round(($active / $total) * 100, 2) : 0
        ];
    }

    /**
     * Get default roles.
     *
     * @return array
     */
    public static function getDefaultRoles()
    {
        return [
            [
                'name' => 'super_admin',
                'display_name' => 'مدير عام',
                'description' => 'مدير عام للنظام مع جميع الصلاحيات',
                'level' => 5,
                'is_active' => true,
                'color' => '#dc2626',
                'icon' => 'shield-check'
            ],
            [
                'name' => 'admin',
                'display_name' => 'مدير',
                'description' => 'مدير للنظام مع صلاحيات إدارية',
                'level' => 4,
                'is_active' => true,
                'color' => '#ea580c',
                'icon' => 'shield'
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'مشرف',
                'description' => 'مشرف على العمليات',
                'level' => 3,
                'is_active' => true,
                'color' => '#2563eb',
                'icon' => 'eye'
            ],
            [
                'name' => 'operator',
                'display_name' => 'مشغل',
                'description' => 'مشغل للنظام مع صلاحيات محدودة',
                'level' => 2,
                'is_active' => true,
                'color' => '#059669',
                'icon' => 'user-check'
            ],
            [
                'name' => 'viewer',
                'display_name' => 'مشاهد',
                'description' => 'مستخدم للقراءة فقط',
                'level' => 1,
                'is_active' => true,
                'color' => '#6b7280',
                'icon' => 'eye-off'
            ]
        ];
    }

    /**
     * Create default roles if they don't exist.
     *
     * @return void
     */
    public static function createDefaultRoles()
    {
        $defaultRoles = self::getDefaultRoles();
        
        foreach ($defaultRoles as $roleData) {
            if (!self::where('name', $roleData['name'])->exists()) {
                self::create($roleData);
            }
        }
    }
}