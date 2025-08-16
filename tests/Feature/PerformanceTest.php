<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shipment;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class PerformanceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function shipments_list_loads_within_acceptable_time()
    {
        $this->actingAs($this->admin);

        // إنشاء 100 شحنة للاختبار
        Shipment::factory()->count(100)->create();

        $startTime = microtime(true);
        
        $response = $this->get('/shipments');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // بالمللي ثانية

        $response->assertStatus(200);
        
        // يجب أن يتم التحميل في أقل من 500 مللي ثانية
        $this->assertLessThan(500, $executionTime, 
            "Shipments list took {$executionTime}ms to load, expected less than 500ms");
    }

    /** @test */
    public function search_shipments_performs_well_with_large_dataset()
    {
        $this->actingAs($this->admin);

        // إنشاء 1000 شحنة للاختبار
        Shipment::factory()->count(1000)->create();

        $startTime = microtime(true);
        
        $response = $this->get('/shipments?search=SHIP');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        // يجب أن يتم البحث في أقل من 1000 مللي ثانية
        $this->assertLessThan(1000, $executionTime, 
            "Shipment search took {$executionTime}ms, expected less than 1000ms");
    }

    /** @test */
    public function database_queries_are_optimized()
    {
        $this->actingAs($this->admin);

        // إنشاء بيانات للاختبار
        $shipments = Shipment::factory()->count(50)->create();
        $drivers = Driver::factory()->count(20)->create();

        // تفعيل مراقبة الاستعلامات
        DB::enableQueryLog();

        $response = $this->get('/shipments');

        $queryCount = count(DB::getQueryLog());

        $response->assertStatus(200);
        
        // يجب ألا يتجاوز عدد الاستعلامات 10 استعلامات
        $this->assertLessThanOrEqual(10, $queryCount, 
            "Too many database queries: {$queryCount}, expected 10 or less");
    }

    /** @test */
    public function pagination_works_efficiently()
    {
        $this->actingAs($this->admin);

        // إنشاء 500 شحنة للاختبار
        Shipment::factory()->count(500)->create();

        $startTime = microtime(true);
        
        $response = $this->get('/shipments?page=25');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        // يجب أن يتم التحميل في أقل من 300 مللي ثانية
        $this->assertLessThan(300, $executionTime, 
            "Pagination took {$executionTime}ms, expected less than 300ms");
    }

    /** @test */
    public function export_functionality_performs_well()
    {
        $this->actingAs($this->admin);

        // إنشاء 200 شحنة للاختبار
        Shipment::factory()->count(200)->create();

        $startTime = microtime(true);
        
        $response = $this->get('/reports/shipments/export/excel');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        // يجب أن يتم التصدير في أقل من 2000 مللي ثانية
        $this->assertLessThan(2000, $executionTime, 
            "Export took {$executionTime}ms, expected less than 2000ms");
    }

    /** @test */
    public function dashboard_loads_efficiently()
    {
        $this->actingAs($this->admin);

        // إنشاء بيانات للاختبار
        Shipment::factory()->count(300)->create();
        Driver::factory()->count(50)->create();

        $startTime = microtime(true);
        
        $response = $this->get('/dashboard');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        // يجب أن يتم تحميل لوحة التحكم في أقل من 800 مللي ثانية
        $this->assertLessThan(800, $executionTime, 
            "Dashboard took {$executionTime}ms to load, expected less than 800ms");
    }

    /** @test */
    public function memory_usage_is_reasonable()
    {
        $this->actingAs($this->admin);

        $initialMemory = memory_get_usage();

        // إنشاء 1000 شحنة للاختبار
        Shipment::factory()->count(1000)->create();

        $response = $this->get('/shipments');

        $finalMemory = memory_get_usage();
        $memoryUsed = $finalMemory - $initialMemory;

        $response->assertStatus(200);
        
        // يجب ألا يتجاوز استخدام الذاكرة 50 ميجابايت
        $this->assertLessThan(50 * 1024 * 1024, $memoryUsed, 
            "Memory usage too high: " . round($memoryUsed / 1024 / 1024, 2) . "MB");
    }

    /** @test */
    public function concurrent_users_can_access_system()
    {
        $this->actingAs($this->admin);

        // محاكاة 10 مستخدمين متزامنين
        $startTime = microtime(true);
        
        for ($i = 0; $i < 10; $i++) {
            $response = $this->get('/shipments');
            $response->assertStatus(200);
        }
        
        $endTime = microtime(true);
        $totalTime = ($endTime - $startTime) * 1000;

        // يجب أن يتم التعامل مع 10 مستخدمين في أقل من 2000 مللي ثانية
        $this->assertLessThan(2000, $totalTime, 
            "Concurrent access took {$totalTime}ms, expected less than 2000ms");
    }

    /** @test */
    public function large_file_upload_performs_well()
    {
        $this->actingAs($this->admin);

        // إنشاء ملف كبير للاختبار (1 ميجابايت)
        $largeFile = $this->createLargeFile(1024 * 1024); // 1MB

        $startTime = microtime(true);
        
        $response = $this->post('/upload', [
            'file' => $largeFile
        ]);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        // يجب أن يتم رفع الملف في أقل من 5000 مللي ثانية
        $this->assertLessThan(5000, $executionTime, 
            "File upload took {$executionTime}ms, expected less than 5000ms");
    }

    /** @test */
    public function database_connection_pool_works_efficiently()
    {
        $this->actingAs($this->admin);

        $startTime = microtime(true);
        
        // اختبار 100 اتصال متتالي
        for ($i = 0; $i < 100; $i++) {
            DB::connection()->getPdo();
        }
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        // يجب أن يتم إنشاء 100 اتصال في أقل من 1000 مللي ثانية
        $this->assertLessThan(1000, $executionTime, 
            "Database connections took {$executionTime}ms, expected less than 1000ms");
    }

    /**
     * إنشاء ملف كبير للاختبار
     */
    private function createLargeFile($size)
    {
        $content = str_repeat('A', $size);
        $tempFile = tempnam(sys_get_temp_dir(), 'test_file_');
        file_put_contents($tempFile, $content);
        
        return new \Illuminate\Http\UploadedFile(
            $tempFile,
            'test_file.txt',
            'text/plain',
            null,
            true
        );
    }
}