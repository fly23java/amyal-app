<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_view_users_list()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/users');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_new_user()
    {
        $this->actingAs($this->admin);

        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '1234567890',
            'address' => 'User Address',
            'role' => 'user'
        ];

        $response = $this->post('/users', $userData);

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'phone' => '1234567890',
            'role' => 'user'
        ]);
    }

    /** @test */
    public function admin_can_view_user_details()
    {
        $this->actingAs($this->admin);
        
        $user = User::factory()->create();

        $response = $this->get("/users/{$user->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_update_user()
    {
        $this->actingAs($this->admin);
        
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'phone' => '9876543210',
            'role' => 'manager'
        ];

        $response = $this->put("/users/{$user->id}", $updateData);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'phone' => '9876543210',
            'role' => 'manager'
        ]);
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $this->actingAs($this->admin);
        
        $user = User::factory()->create();

        $response = $this->delete("/users/{$user->id}");

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function user_can_update_own_profile()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $updateData = [
            'name' => 'My Updated Name',
            'phone' => '5555555555'
        ];

        $response = $this->put("/profile", $updateData);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'My Updated Name',
            'phone' => '5555555555'
        ]);
    }

    /** @test */
    public function non_admin_cannot_access_user_management()
    {
        $regularUser = User::factory()->create(['role' => 'user']);
        $this->actingAs($regularUser);

        $response = $this->get('/users');

        $response->assertStatus(403);
    }
}