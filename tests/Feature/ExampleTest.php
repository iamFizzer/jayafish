<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->get('/')->assertRedirect('/login');
        $user = User::factory()->create(['username' => 'owner-test', 'role' => 'owner', 'is_active' => true]);
        $this->actingAs($user)->get('/')->assertOk()->assertSee('Dashboard Analitik');
    }

    public function test_owner_cannot_access_operational_pages(): void
    {
        $user = User::factory()->create(['username' => 'owner-two', 'role' => 'owner', 'is_active' => true]);
        $this->actingAs($user)->get('/produk')->assertForbidden();
        $this->actingAs($user)->get('/laporan')->assertOk();
    }
}
