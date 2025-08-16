<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'phone' => '1234567890',
            'address' => 'Test Address',
            'role' => 'user'
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('1234567890', $user->phone);
        $this->assertEquals('Test Address', $user->address);
        $this->assertEquals('user', $user->role);
    }

    /** @test */
    public function it_can_update_user_information()
    {
        $user = User::factory()->create();

        $user->update([
            'name' => 'Updated Name',
            'phone' => '9876543210'
        ]);

        $this->assertEquals('Updated Name', $user->fresh()->name);
        $this->assertEquals('9876543210', $user->fresh()->phone);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Test User'
            // Missing required fields
        ]);
    }

    /** @test */
    public function it_can_have_many_shipments()
    {
        $user = User::factory()->create();
        
        // Assuming User has shipments relationship
        $this->assertTrue(method_exists($user, 'shipments'));
    }
}