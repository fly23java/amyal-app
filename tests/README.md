# اختبارات النظام

هذا المجلد يحتوي على جميع اختبارات النظام المطورة باستخدام PHPUnit و Laravel Testing Framework.

## هيكل الاختبارات

### اختبارات الوحدات (Unit Tests)
- `UserTest.php` - اختبارات نموذج المستخدم
- `ShipmentTest.php` - اختبارات نموذج الشحنة
- `DriverTest.php` - اختبارات نموذج السائق
- `VehicleTest.php` - اختبارات نموذج المركبة
- `StatusTest.php` - اختبارات نموذج الحالة
- `PriceTest.php` - اختبارات نموذج التسعير
- `ContractTest.php` - اختبارات نموذج العقد

### اختبارات الميزات (Feature Tests)
- `SimpleTest.php` - اختبارات بسيطة للنظام
- `ShipmentManagementTest.php` - اختبارات إدارة الشحنات
- `UserManagementTest.php` - اختبارات إدارة المستخدمين
- `DriverManagementTest.php` - اختبارات إدارة السائقين
- `AuthenticationTest.php` - اختبارات المصادقة
- `ReportTest.php` - اختبارات التقارير
- `UserInterfaceTest.php` - اختبارات واجهة المستخدم
- `SecurityTest.php` - اختبارات الأمان

## كيفية تشغيل الاختبارات

### تشغيل جميع الاختبارات
```bash
php artisan test
```

### تشغيل اختبارات معينة
```bash
# تشغيل اختبارات الوحدات فقط
php artisan test --testsuite=Unit

# تشغيل اختبارات الميزات فقط
php artisan test --testsuite=Feature

# تشغيل اختبار معين
php artisan test --filter=UserTest

# تشغيل اختبار محدد
php artisan test --filter=test_user_can_login
```

### تشغيل الاختبارات مع تغطية الكود
```bash
php artisan test --coverage
```

### تشغيل الاختبارات مع تقرير مفصل
```bash
php artisan test --verbose
```

### تشغيل الاختبارات في وضع التطوير
```bash
php artisan test --env=testing
```

## إعدادات الاختبارات

### ملف phpunit.xml
يحتوي على إعدادات PHPUnit للنظام:
- تحديد مجلدات الاختبارات
- إعدادات قاعدة البيانات
- متغيرات البيئة

### متغيرات البيئة للاختبارات
```env
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
```

## أنواع الاختبارات

### 1. اختبارات الوحدات (Unit Tests)
- اختبار النماذج والعلاقات
- اختبار المنطق التجاري
- اختبار الحقول والتحقق من صحة البيانات
- اختبار العمليات الأساسية (CRUD)

### 2. اختبارات الميزات (Feature Tests)
- اختبار نقاط النهاية (Endpoints)
- اختبار سير العمل (Workflows)
- اختبار التفاعل مع قاعدة البيانات
- اختبار المصادقة والصلاحيات

### 3. اختبارات الأمان (Security Tests)
- اختبار الوصول غير المصرح
- اختبار الصلاحيات
- اختبار حماية البيانات
- اختبار المصادقة

### 4. اختبارات الواجهة (UI Tests)
- اختبار صفحات النظام
- اختبار التنقل
- اختبار النماذج
- اختبار الاستجابة

## أفضل الممارسات

### 1. تسمية الاختبارات
```php
/** @test */
public function it_can_create_a_user()
{
    // اختبار إنشاء مستخدم
}

/** @test */
public function user_cannot_access_admin_panel()
{
    // اختبار عدم الوصول للوحة الإدارة
}
```

### 2. استخدام Factories
```php
$user = User::factory()->create(['role' => 'admin']);
$shipment = Shipment::factory()->create(['status_id' => 1]);
```

### 3. استخدام Database Transactions
```php
use RefreshDatabase;

protected function setUp(): void
{
    parent::setUp();
    // إعداد البيانات
}
```

### 4. اختبار الاستثناءات
```php
$this->expectException(\Illuminate\Database\QueryException::class);
```

## إضافة اختبارات جديدة

### 1. إنشاء اختبار وحدة
```bash
php artisan make:test NewModelTest --unit
```

### 2. إنشاء اختبار ميزة
```bash
php artisan make:test NewFeatureTest
```

### 3. إنشاء Factory
```bash
php artisan make:factory NewModelFactory
```

### 4. إنشاء Seeder
```bash
php artisan make:seeder NewModelSeeder
```

## استكشاف الأخطاء

### مشاكل شائعة
1. **خطأ في قاعدة البيانات**: تأكد من إعدادات قاعدة البيانات للاختبارات
2. **خطأ في المصادقة**: تأكد من إعدادات المصادقة في الاختبارات
3. **خطأ في المسارات**: تأكد من وجود المسارات المطلوبة

### حل المشاكل
```bash
# مسح الكاش
php artisan cache:clear

# مسح التكوين
php artisan config:clear

# إعادة إنشاء قاعدة البيانات
php artisan migrate:fresh --seed

# تشغيل الاختبارات مع تفاصيل أكثر
php artisan test --verbose
```

## التقارير والإحصائيات

### تقرير تغطية الكود
```bash
php artisan test --coverage --min=80
```

### تقرير الأداء
```bash
php artisan test --stop-on-failure
```

### تقرير مفصل
```bash
php artisan test --testdox
```

## التكامل المستمر (CI/CD)

### GitHub Actions
```yaml
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: php artisan test
```

## المراجع

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Testing Best Practices](https://laravel.com/docs/testing#best-practices)