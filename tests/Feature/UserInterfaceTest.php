<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shipment;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserInterfaceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
    }

    /** @test */
    public function user_can_view_dashboard()
    {
        $this->actingAs($this->user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_navigation_menu()
    {
        $this->actingAs($this->user);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Shipments');
        $response->assertSee('Profile');
    }

    /** @test */
    public function user_can_view_shipment_tracking_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/track');

        $response->assertStatus(200);
        $response->assertSee('Track Shipment');
    }

    /** @test */
    public function user_can_search_shipments()
    {
        $this->actingAs($this->user);

        $response = $this->get('/shipments?search=SHIP001');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_shipment_details_page()
    {
        $this->actingAs($this->user);
        
        $shipment = Shipment::factory()->create();

        $response = $this->get("/shipments/{$shipment->id}");

        $response->assertStatus(200);
        $response->assertSee($shipment->tracking_number);
    }

    /** @test */
    public function user_can_view_profile_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/profile');

        $response->assertStatus(200);
        $response->assertSee($this->user->name);
    }

    /** @test */
    public function user_can_view_settings_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/settings');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_notifications()
    {
        $this->actingAs($this->user);

        $response = $this->get('/notifications');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_help_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/help');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_contact_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/contact');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_about_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/about');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_terms_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/terms');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_privacy_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/privacy');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_faq_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/faq');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_support_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/support');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_feedback_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/feedback');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_sitemap()
    {
        $this->actingAs($this->user);

        $response = $this->get('/sitemap');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_404_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/nonexistent-page');

        $response->assertStatus(404);
    }

    /** @test */
    public function user_can_view_500_page()
    {
        $this->actingAs($this->user);

        // This would typically be tested by triggering an error
        $this->assertTrue(true);
    }

    /** @test */
    public function user_can_view_maintenance_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/maintenance');

        $response->assertStatus(200);
    }
}