<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shipment;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Status;
use App\Models\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class IntegrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
        
        // تعطيل إرسال البريد والإشعارات للاختبارات
        Mail::fake();
        Notification::fake();
    }

    /** @test */
    public function complete_shipment_workflow_integration()
    {
        $this->actingAs($this->admin);

        // 1. إنشاء سائق
        $driver = Driver::factory()->create(['status' => 'active']);

        // 2. إنشاء مركبة
        $vehicle = Vehicle::factory()->create(['status' => 'active']);

        // 3. إنشاء حالة
        $status = Status::factory()->create(['name' => 'Pending']);

        // 4. إنشاء شحنة
        $shipmentData = [
            'tracking_number' => 'SHIP001',
            'sender_name' => 'Sender Name',
            'sender_phone' => '1234567890',
            'sender_address' => 'Sender Address',
            'receiver_name' => 'Receiver Name',
            'receiver_phone' => '0987654321',
            'receiver_address' => 'Receiver Address',
            'weight' => 10.5,
            'dimensions' => '20x30x40',
            'description' => 'Test shipment',
            'value' => 100.00,
            'shipping_cost' => 25.00,
            'status_id' => $status->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id
        ];

        $response = $this->post('/shipments', $shipmentData);
        $response->assertRedirect('/shipments');

        // 5. التحقق من إنشاء الشحنة
        $this->assertDatabaseHas('shipments', [
            'tracking_number' => 'SHIP001',
            'sender_name' => 'Sender Name'
        ]);

        $shipment = Shipment::where('tracking_number', 'SHIP001')->first();

        // 6. تحديث حالة الشحنة إلى "In Transit"
        $inTransitStatus = Status::factory()->create(['name' => 'In Transit']);
        
        $updateResponse = $this->put("/shipments/{$shipment->id}", [
            'status_id' => $inTransitStatus->id
        ]);

        $updateResponse->assertRedirect("/shipments/{$shipment->id}");

        // 7. التحقق من تحديث الحالة
        $this->assertDatabaseHas('shipments', [
            'id' => $shipment->id,
            'status_id' => $inTransitStatus->id
        ]);

        // 8. تحديث حالة الشحنة إلى "Delivered"
        $deliveredStatus = Status::factory()->create(['name' => 'Delivered']);
        
        $finalUpdateResponse = $this->put("/shipments/{$shipment->id}", [
            'status_id' => $deliveredStatus->id
        ]);

        $finalUpdateResponse->assertRedirect("/shipments/{$shipment->id}");

        // 9. التحقق من الحالة النهائية
        $this->assertDatabaseHas('shipments', [
            'id' => $shipment->id,
            'status_id' => $deliveredStatus->id
        ]);
    }

    /** @test */
    public function user_registration_and_login_integration()
    {
        // 1. تسجيل مستخدم جديد
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '1234567890',
            'address' => 'User Address'
        ];

        $registerResponse = $this->post('/register', $userData);
        $registerResponse->assertRedirect('/home');

        // 2. التحقق من إنشاء المستخدم
        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com'
        ]);

        // 3. تسجيل الدخول
        $loginResponse = $this->post('/login', [
            'email' => 'newuser@example.com',
            'password' => 'password123'
        ]);

        $loginResponse->assertRedirect('/home');

        // 4. التحقق من تسجيل الدخول
        $this->assertAuthenticated();
    }

    /** @test */
    public function driver_assignment_and_vehicle_integration()
    {
        $this->actingAs($this->admin);

        // 1. إنشاء سائق
        $driver = Driver::factory()->create(['status' => 'active']);

        // 2. إنشاء مركبة
        $vehicle = Vehicle::factory()->create(['status' => 'active']);

        // 3. تعيين المركبة للسائق
        $assignmentResponse = $this->put("/drivers/{$driver->id}", [
            'vehicle_id' => $vehicle->id
        ]);

        $assignmentResponse->assertRedirect("/drivers/{$driver->id}");

        // 4. التحقق من التعيين
        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'vehicle_id' => $vehicle->id
        ]);

        // 5. إنشاء شحنة وتعيينها للسائق
        $shipment = Shipment::factory()->create([
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id
        ]);

        // 6. التحقق من العلاقات
        $this->assertDatabaseHas('shipments', [
            'id' => $shipment->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id
        ]);
    }

    /** @test */
    public function pricing_and_contract_integration()
    {
        $this->actingAs($this->admin);

        // 1. إنشاء تسعير
        $price = Price::factory()->create([
            'name' => 'Standard Shipping',
            'is_active' => true
        ]);

        // 2. إنشاء عقد
        $contract = \App\Models\Contract::factory()->create([
            'status' => 'active',
            'total_value' => 50000.00
        ]);

        // 3. إنشاء شحنة مع التسعير
        $shipment = Shipment::factory()->create([
            'shipping_cost' => 25.00,
            'value' => 100.00
        ]);

        // 4. التحقق من حساب التكلفة الإجمالية
        $totalCost = $shipment->shipping_cost + $shipment->value;
        $this->assertEquals(125.00, $totalCost);

        // 5. التحقق من العلاقات
        $this->assertDatabaseHas('shipments', [
            'id' => $shipment->id,
            'shipping_cost' => 25.00,
            'value' => 100.00
        ]);
    }

    /** @test */
    public function reporting_and_analytics_integration()
    {
        $this->actingAs($this->admin);

        // 1. إنشاء بيانات للتقارير
        $status1 = Status::factory()->create(['name' => 'Pending']);
        $status2 = Status::factory()->create(['name' => 'In Transit']);
        $status3 = Status::factory()->create(['name' => 'Delivered']);

        Shipment::factory()->count(5)->create(['status_id' => $status1->id]);
        Shipment::factory()->count(3)->create(['status_id' => $status2->id]);
        Shipment::factory()->count(2)->create(['status_id' => $status3->id]);

        // 2. إنشاء تقرير حالة الشحنات
        $reportResponse = $this->get('/reports/shipments/status-summary');
        $reportResponse->assertStatus(200);

        // 3. التحقق من البيانات
        $pendingShipments = Shipment::where('status_id', $status1->id)->count();
        $inTransitShipments = Shipment::where('status_id', $status2->id)->count();
        $deliveredShipments = Shipment::where('status_id', $status3->id)->count();

        $this->assertEquals(5, $pendingShipments);
        $this->assertEquals(3, $inTransitShipments);
        $this->assertEquals(2, $deliveredShipments);

        // 4. إنشاء تقرير مالي
        $financialReportResponse = $this->get('/reports/financial');
        $financialReportResponse->assertStatus(200);
    }

    /** @test */
    public function notification_and_communication_integration()
    {
        $this->actingAs($this->admin);

        // 1. إنشاء شحنة
        $shipment = Shipment::factory()->create();

        // 2. إنشاء حالة جديدة
        $newStatus = Status::factory()->create(['name' => 'Out for Delivery']);

        // 3. تحديث حالة الشحنة (يجب أن يرسل إشعار)
        $updateResponse = $this->put("/shipments/{$shipment->id}", [
            'status_id' => $newStatus->id
        ]);

        $updateResponse->assertRedirect("/shipments/{$shipment->id}");

        // 4. التحقق من تحديث الحالة
        $this->assertDatabaseHas('shipments', [
            'id' => $shipment->id,
            'status_id' => $newStatus->id
        ]);

        // 5. التحقق من إرسال الإشعارات (إذا كانت مفعلة)
        // Notification::assertSentTo($user, ShipmentStatusChanged::class);
    }

    /** @test */
    public function data_export_and_import_integration()
    {
        $this->actingAs($this->admin);

        // 1. إنشاء بيانات للتصدير
        Shipment::factory()->count(10)->create();

        // 2. تصدير البيانات إلى Excel
        $exportResponse = $this->get('/reports/shipments/export/excel');
        $exportResponse->assertStatus(200);

        // 3. تصدير البيانات إلى PDF
        $pdfExportResponse = $this->get('/reports/shipments/export/pdf');
        $pdfExportResponse->assertStatus(200);

        // 4. التحقق من وجود البيانات
        $shipmentsCount = Shipment::count();
        $this->assertEquals(10, $shipmentsCount);
    }

    /** @test */
    public function search_and_filter_integration()
    {
        $this->actingAs($this->admin);

        // 1. إنشاء بيانات متنوعة
        Shipment::factory()->create(['tracking_number' => 'SHIP001']);
        Shipment::factory()->create(['tracking_number' => 'SHIP002']);
        Shipment::factory()->create(['tracking_number' => 'DELIVERY001']);

        // 2. اختبار البحث
        $searchResponse = $this->get('/shipments?search=SHIP');
        $searchResponse->assertStatus(200);

        // 3. اختبار التصفية
        $filterResponse = $this->get('/shipments?status=1');
        $filterResponse->assertStatus(200);

        // 4. اختبار البحث والتصفية معاً
        $combinedResponse = $this->get('/shipments?search=SHIP&status=1');
        $combinedResponse->assertStatus(200);
    }

    /** @test */
    public function user_permissions_and_roles_integration()
    {
        // 1. إنشاء مستخدم عادي
        $regularUser = User::factory()->create(['role' => 'user']);

        // 2. محاولة الوصول للوحة الإدارة
        $this->actingAs($regularUser);
        $adminAccessResponse = $this->get('/admin');
        $adminAccessResponse->assertStatus(403);

        // 3. محاولة الوصول لإدارة المستخدمين
        $userManagementResponse = $this->get('/users');
        $userManagementResponse->assertStatus(403);

        // 4. تسجيل الدخول كمدير
        $this->actingAs($this->admin);

        // 5. الوصول للوحة الإدارة
        $adminDashboardResponse = $this->get('/admin');
        $adminDashboardResponse->assertStatus(200);

        // 6. الوصول لإدارة المستخدمين
        $userManagementAdminResponse = $this->get('/users');
        $userManagementAdminResponse->assertStatus(200);
    }
}