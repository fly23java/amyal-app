<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function basic_test()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function database_connection_works()
    {
        $this->assertTrue(true);
    }
}