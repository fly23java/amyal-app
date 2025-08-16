# تحسينات النظام - Classes, Views, and Routes

## نظرة عامة

تم إجراء تحسينات شاملة على النظام تشمل:
- تحسين نماذج البيانات (Models)
- إنشاء traits مفيدة
- تحسين نظام التوجيه (Routes)
- إضافة middleware للصلاحيات
- تحسين نظام الإشعارات

## 1. تحسينات النماذج (Models)

### 1.1 نموذج الشحنات (Shipment Model)

**التحسينات المضافة:**
- إضافة traits: `HasFactory`, `SoftDeletes`, `Searchable`, `Filterable`, `Sortable`
- توسيع الحقول القابلة للتعديل
- إضافة علاقات جديدة (vehicle, carrier, statusChanges)
- إضافة scopes مفيدة (active, completed, pending, byPriority)
- إضافة accessors للحقول المحسوبة
- إضافة mutators لتوحيد البيانات
- إضافة methods للعمليات التجارية
- إضافة static methods للإحصائيات

**الخصائص الجديدة:**
```php
// Accessors
'formatted_price', 'formatted_carrier_price', 'delivery_status', 
'days_until_delivery', 'profit_margin'

// Scopes
scopeActive(), scopeCompleted(), scopePending(), scopeByPriority()

// Methods
isOverdue(), updateStatus(), markAsDelivered(), getProfit()
```

### 1.2 نموذج المستخدمين (User Model)

**التحسينات المضافة:**
- إضافة traits: `SoftDeletes`, `Searchable`, `Filterable`, `Sortable`
- توسيع الحقول الشخصية (phone, address, city_id, country_id)
- إضافة علاقات جديدة (city, country, roles, permissions)
- إضافة scopes مفيدة (active, online, verified)
- إضافة accessors للحقول المحسوبة
- إضافة methods للتحقق من الصلاحيات
- إضافة static methods للإحصائيات

**الخصائص الجديدة:**
```php
// Accessors
'full_name', 'age', 'status_label', 'type_label', 'is_online'

// Scopes
scopeActive(), scopeOnline(), scopeVerified()

// Methods
hasRole(), hasPermission(), can(), updateLastLogin()
```

### 1.3 نموذج الأدوار (Role Model)

**النموذج الجديد:**
- إدارة أدوار المستخدمين
- نظام مستويات للأدوار
- علاقات مع المستخدمين والصلاحيات
- scopes للتصفية حسب المستوى
- methods لإدارة الصلاحيات

**الخصائص:**
```php
'name', 'display_name', 'description', 'permissions', 
'is_active', 'color', 'icon', 'level'
```

### 1.4 نموذج الصلاحيات (Permission Model)

**النموذج الجديد:**
- إدارة صلاحيات النظام
- تنظيم الصلاحيات حسب الوحدات
- نظام مستويات للصلاحيات
- علاقات مع الأدوار والمستخدمين
- methods للتحقق من الصلاحيات

**الخصائص:**
```php
'name', 'display_name', 'description', 'module', 
'action', 'resource', 'is_active', 'level'
```

## 2. Traits المفيدة

### 2.1 Searchable Trait

**الوظائف:**
- البحث في الحقول المحددة
- أنواع البحث المختلفة (exact, starts_with, ends_with)
- البحث في حقول محددة
- helpers للتحقق من الحقول القابلة للبحث

**الاستخدام:**
```php
// البحث في جميع الحقول القابلة للبحث
Model::search('كلمة البحث')->get();

// البحث في حقول محددة
Model::searchIn('كلمة البحث', ['name', 'email'])->get();

// البحث الدقيق
Model::searchExact('القيمة', 'field_name')->get();
```

### 2.2 Filterable Trait

**الوظائف:**
- تصفية متقدمة باستخدام operators
- تصفية حسب النطاقات
- تصفية حسب القيم المتعددة
- تصفية حسب القيم المنطقية
- دعم custom filter methods

**الاستخدام:**
```php
// تصفية بسيطة
Model::filter(['status' => 'active'])->get();

// تصفية متقدمة
Model::filter([
    'price' => ['operator' => '>', 'value' => 100],
    'created_at' => '2023-01-01:2023-12-31'
])->get();

// تصفية من request
Model::filterFromRequest($request)->get();
```

### 2.3 Sortable Trait

**الوظائف:**
- ترتيب حسب حقل واحد أو متعدد
- ترتيب حسب العلاقات
- ترتيب حسب التعبيرات المخصصة
- ترتيب حسب المسافة الجغرافية
- دعم custom sorting logic

**الاستخدام:**
```php
// ترتيب بسيط
Model::sort('name', 'asc')->get();

// ترتيب متعدد
Model::sortMultiple(['name' => 'asc', 'created_at' => 'desc'])->get();

// ترتيب من request
Model::sortFromRequest($request)->get();
```

### 2.4 AuditLog Trait

**الوظائف:**
- تسجيل جميع العمليات
- تسجيل التغييرات (قبل وبعد)
- تسجيل محاولات الوصول
- تسجيل العمليات على الملفات
- تسجيل العمليات الجماعية

**الاستخدام:**
```php
// تسجيل إنشاء
$this->logCreate('shipment', $shipment->id, 'تم إنشاء شحنة جديدة', $shipment->toArray());

// تسجيل تحديث
$this->logUpdate('shipment', $shipment->id, 'تم تحديث الشحنة', $oldData, $newData);

// تسجيل حذف
$this->logDelete('shipment', $shipment->id, 'تم حذف الشحنة', $shipment->toArray());
```

### 2.5 ApiResponse Trait

**الوظائف:**
- استجابات API موحدة
- رسائل خطأ منظمة
- استجابات للعمليات المختلفة
- دعم التصفح (pagination)
- رسائل خطأ HTTP standards

**الاستخدام:**
```php
// استجابة نجاح
return $this->successResponse($data, 'تمت العملية بنجاح');

// استجابة خطأ
return $this->errorResponse('حدث خطأ ما', 400);

// استجابة مع pagination
return $this->paginatedResponse($paginatedData, 'تم جلب البيانات');
```

### 2.6 FileHandler Trait

**الوظائف:**
- رفع الملفات مع التحقق
- معالجة الصور (resize, thumbnail)
- إدارة الملفات (حذف، نقل، نسخ)
- التحقق من أنواع الملفات
- تنظيف الملفات القديمة

**الاستخدام:**
```php
// رفع ملف
$filePath = $this->uploadFile($request->file('document'), 'documents');

// رفع صورة مع resize
$imagePath = $this->uploadFile($request->file('image'), 'images', null, [
    'resize' => ['width' => 800, 'height' => 600]
]);

// إنشاء thumbnail
$thumbnail = $this->createThumbnail($imagePath, 150, 150);
```

### 2.7 NotificationHandler Trait

**الوظائف:**
- إرسال إشعارات للمستخدمين
- إشعارات متعددة المستخدمين
- إشعارات حسب الدور أو الصلاحية
- إشعارات البريد الإلكتروني
- إشعارات خاصة بالشحنات

**الاستخدام:**
```php
// إرسال إشعار لمستخدم واحد
$this->sendNotification($user, ShipmentStatusChanged::class, $data);

// إرسال إشعار لعدة مستخدمين
$this->sendNotificationToMultiple($users, ShipmentCreated::class, $data);

// إرسال إشعار حسب الدور
$this->sendNotificationToRole('supervisor', ShipmentDelivered::class, $data);
```

## 3. تحسينات نظام التوجيه (Routes)

### 3.1 التنظيم الجديد

**المجموعات:**
- Routes عامة (public)
- Routes للمصادقة (authentication)
- Routes محمية (protected)
- Routes للـ API
- Routes حسب الصلاحيات

### 3.2 نظام الصلاحيات

**التحكم في الوصول:**
```php
// التحقق من صلاحية
Route::middleware(['permission:view_shipments'])->group(function () {
    Route::resource('shipments', ShipmentsController::class);
});

// التحقق من دور
Route::middleware(['role:admin'])->group(function () {
    Route::resource('users', UsersController::class);
});
```

### 3.3 Routes الجديدة

**API Routes:**
```php
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/shipments/statistics', [ShipmentsController::class, 'statistics']);
    Route::post('/shipments/bulk-update', [ShipmentsController::class, 'bulkUpdate']);
    Route::get('/users/online', [UsersController::class, 'online']);
});
```

**Routes للإدارة:**
```php
Route::prefix('settings')->name('settings.')->middleware(['permission:manage_system'])->group(function () {
    Route::get('/general', [HomeController::class, 'generalSettings']);
    Route::get('/notifications', [HomeController::class, 'notificationSettings']);
    Route::get('/security', [HomeController::class, 'securitySettings']);
});
```

## 4. Middleware الجديد

### 4.1 CheckPermission Middleware

**الوظائف:**
- التحقق من صلاحيات المستخدم
- تسجيل محاولات الوصول
- استجابات مختلفة للـ API والـ Web
- bypass للمدير العام

### 4.2 CheckRole Middleware

**الوظائف:**
- التحقق من دور المستخدم
- تسجيل محاولات الوصول
- bypass للمدير العام

### 4.3 CheckUserActive Middleware

**الوظائف:**
- التحقق من حالة المستخدم
- تسجيل الخروج للمستخدمين غير النشطين
- تحديث آخر نشاط

### 4.4 EnsureEmailIsVerified Middleware

**الوظائف:**
- التحقق من تأكيد البريد الإلكتروني
- توجيه للمستخدمين غير المؤكدين
- bypass للمدير العام

### 4.5 EnsurePhoneIsVerified Middleware

**الوظائف:**
- التحقق من تأكيد رقم الهاتف
- توجيه للمستخدمين غير المؤكدين
- bypass للمدير العام

## 5. الملفات الجديدة

### 5.1 Models
- `app/Models/Role.php`
- `app/Models/Permission.php`

### 5.2 Traits
- `app/Traits/Searchable.php`
- `app/Traits/Filterable.php`
- `app/Traits/Sortable.php`
- `app/Traits/AuditLog.php`
- `app/Traits/ApiResponse.php`
- `app/Traits/FileHandler.php`
- `app/Traits/NotificationHandler.php`

### 5.3 Middleware
- `app/Http/Middleware/CheckPermission.php`
- `app/Http/Middleware/CheckRole.php`
- `app/Http/Middleware/CheckUserActive.php`
- `app/Http/Middleware/EnsureEmailIsVerified.php`
- `app/Http/Middleware/EnsurePhoneIsVerified.php`

## 6. الاستخدام

### 6.1 في Controllers

```php
use App\Traits\{
    Searchable, Filterable, Sortable, 
    AuditLog, ApiResponse, FileHandler, 
    NotificationHandler
};

class ShipmentsController extends Controller
{
    use Searchable, Filterable, Sortable, 
        AuditLog, ApiResponse, FileHandler, 
        NotificationHandler;

    public function index(Request $request)
    {
        $shipments = Shipment::query()
            ->search($request->get('search'))
            ->filterFromRequest($request)
            ->sortFromRequest($request)
            ->paginate(15);

        return $this->paginatedResponse($shipments, 'تم جلب الشحنات بنجاح');
    }
}
```

### 6.2 في Models

```php
use App\Traits\{
    Searchable, Filterable, Sortable, AuditLog
};

class Shipment extends Model
{
    use Searchable, Filterable, Sortable, AuditLog;

    protected $searchable = ['serial_number', 'loading_location'];
    protected $filterable = ['status_id', 'account_id', 'created_at'];
    protected $sortable = ['serial_number', 'created_at', 'price'];
}
```

## 7. الفوائد

### 7.1 الأداء
- تحسين استعلامات قاعدة البيانات
- caching للبيانات المتكررة
- lazy loading للعلاقات

### 7.2 الأمان
- نظام صلاحيات متقدم
- تسجيل جميع العمليات
- التحقق من حالة المستخدمين

### 7.3 قابلية الصيانة
- كود منظم ومنظم
- traits قابلة لإعادة الاستخدام
- توثيق شامل

### 7.4 قابلية التوسع
- بنية مرنة
- إضافة ميزات جديدة بسهولة
- دعم متعدد اللغات

## 8. التطوير المستقبلي

### 8.1 الميزات المقترحة
- نظام caching متقدم
- API documentation
- WebSocket للإشعارات المباشرة
- نظام backup تلقائي

### 8.2 التحسينات التقنية
- استخدام Redis للـ caching
- تحسين استعلامات قاعدة البيانات
- إضافة unit tests
- تحسين الأمان

## 9. الخلاصة

تم إجراء تحسينات شاملة على النظام تشمل:

1. **نماذج محسنة** مع traits مفيدة
2. **نظام صلاحيات متقدم** مع middleware
3. **توجيه منظم** مع حماية أفضل
4. **traits قابلة لإعادة الاستخدام** للوظائف المشتركة
5. **نظام إشعارات متكامل**
6. **إدارة ملفات محسنة**
7. **تسجيل شامل** لجميع العمليات

هذه التحسينات تجعل النظام أكثر أماناً وقابلية للصيانة والتوسع، مع تحسين تجربة المستخدم والأداء العام.