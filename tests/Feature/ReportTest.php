<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shipment;
use App\Models\Driver;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_view_shipment_reports()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/reports/shipments');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_driver_reports()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/reports/drivers');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_financial_reports()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/reports/financial');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_generate_shipment_report_by_date_range()
    {
        $this->actingAs($this->admin);

        $startDate = '2024-01-01';
        $endDate = '2024-12-31';

        $response = $this->get("/reports/shipments?start_date={$startDate}&end_date={$endDate}");

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_generate_driver_performance_report()
    {
        $this->actingAs($this->admin);

        $driver = Driver::factory()->create();
        
        $response = $this->get("/reports/drivers/{$driver->id}/performance");

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_export_reports_to_pdf()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/reports/shipments/export/pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function admin_can_export_reports_to_excel()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/reports/shipments/export/excel');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function admin_can_view_shipment_status_summary()
    {
        $this->actingAs($this->admin);

        // Create some test shipments with different statuses
        $status1 = Status::factory()->create(['name' => 'Pending']);
        $status2 = Status::factory()->create(['name' => 'In Transit']);
        $status3 = Status::factory()->create(['name' => 'Delivered']);

        Shipment::factory()->create(['status_id' => $status1->id]);
        Shipment::factory()->create(['status_id' => $status2->id]);
        Shipment::factory()->create(['status_id' => $status3->id]);

        $response = $this->get('/reports/shipments/status-summary');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_revenue_reports()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/reports/revenue');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_cost_analysis_reports()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/reports/costs');

        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_cannot_access_reports()
    {
        $regularUser = User::factory()->create(['role' => 'user']);
        $this->actingAs($regularUser);

        $response = $this->get('/reports/shipments');

        $response->assertStatus(403);
    }

    /** @test */
    public function reports_require_authentication()
    {
        $response = $this->get('/reports/shipments');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_can_filter_reports_by_multiple_criteria()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/reports/shipments?status=delivered&driver_id=1&start_date=2024-01-01&end_date=2024-12-31');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_dashboard_analytics()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/dashboard/analytics');

        $response->assertStatus(200);
    }
}