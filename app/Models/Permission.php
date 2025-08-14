<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Searchable;
use App\Traits\Filterable;
use App\Traits\Sortable;

class Permission extends Model
{
    use HasFactory, SoftDeletes, Searchable, Filterable, Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'action',
        'resource',
        'is_active',
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
        'description',
        'module',
        'resource'
    ];

    /**
     * Filterable fields for the Filterable trait
     *
     * @var array
     */
    protected $filterable = [
        'is_active',
        'module',
        'action',
        'resource',
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
        'module',
        'action',
        'resource',
        'level',
        'created_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'roles_count',
        'users_count',
        'formatted_level',
        'full_permission_name'
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the roles that have this permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Get the users that have this permission directly.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }

    /**
     * Get the user who created this permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ===== SCOPES =====

    /**
     * Scope a query to only include active permissions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive permissions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive(Builder $query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include permissions by module.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $module
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByModule(Builder $query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope a query to only include permissions by action.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $action
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAction(Builder $query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to only include permissions by resource.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $resource
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByResource(Builder $query, $resource)
    {
        return $query->where('resource', $resource);
    }

    /**
     * Scope a query to only include permissions by level.
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
     * Scope a query to only include permissions above a certain level.
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
     * Scope a query to only include permissions below a certain level.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $level
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBelowLevel(Builder $query, $level)
    {
        return $query->where('level', '<', $level);
    }

    // ===== ACCESSORS =====

    /**
     * Get roles count attribute.
     *
     * @return int
     */
    public function getRolesCountAttribute()
    {
        return $this->roles()->count();
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

    /**
     * Get full permission name attribute.
     *
     * @return string
     */
    public function getFullPermissionNameAttribute()
    {
        return "{$this->action}_{$this->resource}";
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

    /**
     * Set the module attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setModuleAttribute($value)
    {
        $this->attributes['module'] = strtolower(trim($value));
    }

    /**
     * Set the action attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setActionAttribute($value)
    {
        $this->attributes['action'] = strtolower(trim($value));
    }

    /**
     * Set the resource attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setResourceAttribute($value)
    {
        $this->attributes['resource'] = strtolower(trim($value));
    }

    // ===== METHODS =====

    /**
     * Check if permission is for specific module.
     *
     * @param  string  $module
     * @return bool
     */
    public function isForModule($module)
    {
        return $this->module === strtolower($module);
    }

    /**
     * Check if permission is for specific action.
     *
     * @param  string  $action
     * @return bool
     */
    public function isForAction($action)
    {
        return $this->action === strtolower($action);
    }

    /**
     * Check if permission is for specific resource.
     *
     * @param  string  $resource
     * @return bool
     * @return bool
     */
    public function isForResource($resource)
    {
        return $this->resource === strtolower($resource);
    }

    /**
     * Check if permission can be deleted.
     *
     * @return bool
     */
    public function canBeDeleted()
    {
        return $this->roles()->count() === 0 && $this->users()->count() === 0;
    }

    /**
     * Get permission summary for reports.
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
            'module' => $this->module,
            'action' => $this->action,
            'resource' => $this->resource,
            'level' => $this->formatted_level,
            'is_active' => $this->is_active ? 'نشط' : 'غير نشط',
            'roles_count' => $this->roles_count,
            'users_count' => $this->users_count,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'created_by' => $this->creator ? $this->creator->name : 'غير محدد'
        ];
    }

    // ===== STATIC METHODS =====

    /**
     * Get permission statistics.
     *
     * @return array
     */
    public static function getStatistics()
    {
        $total = self::count();
        $active = self::active()->count();
        $inactive = self::inactive()->count();
        $withRoles = self::whereHas('roles')->count();
        $withoutRoles = self::whereDoesntHave('roles')->count();
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'with_roles' => $withRoles,
            'without_roles' => $withoutRoles,
            'active_rate' => $total > 0 ? round(($active / $total) * 100, 2) : 0
        ];
    }

    /**
     * Get permissions by module.
     *
     * @return array
     */
    public static function getPermissionsByModule()
    {
        return self::select('module')
                   ->distinct()
                   ->pluck('module')
                   ->toArray();
    }

    /**
     * Get permissions by action.
     *
     * @return array
     */
    public static function getPermissionsByAction()
    {
        return self::select('action')
                   ->distinct()
                   ->pluck('action')
                   ->toArray();
    }

    /**
     * Get permissions by resource.
     *
     * @return array
     */
    public static function getPermissionsByResource()
    {
        return self::select('resource')
                   ->distinct()
                   ->pluck('resource')
                   ->toArray();
    }

    /**
     * Get default permissions.
     *
     * @return array
     */
    public static function getDefaultPermissions()
    {
        return [
            // Shipments Module
            [
                'name' => 'view_shipments',
                'display_name' => 'عرض الشحنات',
                'description' => 'إمكانية عرض الشحنات',
                'module' => 'shipments',
                'action' => 'view',
                'resource' => 'shipments',
                'level' => 1,
                'is_active' => true
            ],
            [
                'name' => 'create_shipments',
                'display_name' => 'إنشاء شحنات',
                'description' => 'إمكانية إنشاء شحنات جديدة',
                'module' => 'shipments',
                'action' => 'create',
                'resource' => 'shipments',
                'level' => 2,
                'is_active' => true
            ],
            [
                'name' => 'edit_shipments',
                'display_name' => 'تعديل الشحنات',
                'description' => 'إمكانية تعديل الشحنات',
                'module' => 'shipments',
                'action' => 'edit',
                'resource' => 'shipments',
                'level' => 2,
                'is_active' => true
            ],
            [
                'name' => 'delete_shipments',
                'display_name' => 'حذف الشحنات',
                'description' => 'إمكانية حذف الشحنات',
                'module' => 'shipments',
                'action' => 'delete',
                'resource' => 'shipments',
                'level' => 3,
                'is_active' => true
            ],

            // Users Module
            [
                'name' => 'view_users',
                'display_name' => 'عرض المستخدمين',
                'description' => 'إمكانية عرض المستخدمين',
                'module' => 'users',
                'action' => 'view',
                'resource' => 'users',
                'level' => 2,
                'is_active' => true
            ],
            [
                'name' => 'create_users',
                'display_name' => 'إنشاء مستخدمين',
                'description' => 'إمكانية إنشاء مستخدمين جدد',
                'module' => 'users',
                'action' => 'create',
                'resource' => 'users',
                'level' => 3,
                'is_active' => true
            ],
            [
                'name' => 'edit_users',
                'display_name' => 'تعديل المستخدمين',
                'description' => 'إمكانية تعديل المستخدمين',
                'module' => 'users',
                'action' => 'edit',
                'resource' => 'users',
                'level' => 3,
                'is_active' => true
            ],
            [
                'name' => 'delete_users',
                'display_name' => 'حذف المستخدمين',
                'description' => 'إمكانية حذف المستخدمين',
                'module' => 'users',
                'action' => 'delete',
                'resource' => 'users',
                'level' => 4,
                'is_active' => true
            ],

            // Reports Module
            [
                'name' => 'view_reports',
                'display_name' => 'عرض التقارير',
                'description' => 'إمكانية عرض التقارير',
                'module' => 'reports',
                'action' => 'view',
                'resource' => 'reports',
                'level' => 2,
                'is_active' => true
            ],
            [
                'name' => 'generate_reports',
                'display_name' => 'إنشاء تقارير',
                'description' => 'إمكانية إنشاء تقارير جديدة',
                'module' => 'reports',
                'action' => 'generate',
                'resource' => 'reports',
                'level' => 3,
                'is_active' => true
            ],
            [
                'name' => 'export_reports',
                'display_name' => 'تصدير التقارير',
                'description' => 'إمكانية تصدير التقارير',
                'module' => 'reports',
                'action' => 'export',
                'resource' => 'reports',
                'level' => 2,
                'is_active' => true
            ],

            // System Module
            [
                'name' => 'manage_system',
                'display_name' => 'إدارة النظام',
                'description' => 'إمكانية إدارة إعدادات النظام',
                'module' => 'system',
                'action' => 'manage',
                'resource' => 'system',
                'level' => 5,
                'is_active' => true
            ],
            [
                'name' => 'manage_roles',
                'display_name' => 'إدارة الأدوار',
                'description' => 'إمكانية إدارة الأدوار والصلاحيات',
                'module' => 'system',
                'action' => 'manage',
                'resource' => 'roles',
                'level' => 4,
                'is_active' => true
            ],
            [
                'name' => 'manage_permissions',
                'display_name' => 'إدارة الصلاحيات',
                'description' => 'إمكانية إدارة الصلاحيات',
                'module' => 'system',
                'action' => 'manage',
                'resource' => 'permissions',
                'level' => 4,
                'is_active' => true
            ]
        ];
    }

    /**
     * Create default permissions if they don't exist.
     *
     * @return void
     */
    public static function createDefaultPermissions()
    {
        $defaultPermissions = self::getDefaultPermissions();
        
        foreach ($defaultPermissions as $permissionData) {
            if (!self::where('name', $permissionData['name'])->exists()) {
                self::create($permissionData);
            }
        }
    }

    /**
     * Get permissions grouped by module.
     *
     * @return array
     */
    public static function getGroupedPermissions()
    {
        $permissions = self::active()->orderBy('module')->orderBy('level')->get();
        $grouped = [];
        
        foreach ($permissions as $permission) {
            $grouped[$permission->module][] = $permission;
        }
        
        return $grouped;
    }
}