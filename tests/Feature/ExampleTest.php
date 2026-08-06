<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
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

    public function test_products_can_be_filtered_by_category_and_keyword(): void
    {
        $admin = User::factory()->create(['username' => 'admin-filter', 'role' => 'admin', 'is_active' => true]);
        $fishFeed = Category::create(['name' => 'Pakan Ikan', 'type' => 'pakan_ikan', 'color' => '#0f766e']);
        $fishingGear = Category::create(['name' => 'Alat Pancing', 'type' => 'alat_pancing', 'color' => '#2563eb']);
        Product::create(['category_id' => $fishFeed->id, 'name' => 'PF 100', 'sku' => 'PF-100', 'price' => 220000, 'unit' => 'sak', 'stock' => 0, 'minimum_stock' => 5]);
        Product::create(['category_id' => $fishingGear->id, 'name' => 'Joran Carbon', 'sku' => 'JOR-001', 'price' => 300000, 'unit' => 'unit', 'stock' => 0, 'minimum_stock' => 5]);

        $this->actingAs($admin)
            ->get('/produk?category_id='.$fishFeed->id.'&q=PF-100')
            ->assertOk()
            ->assertSee('PF 100')
            ->assertDontSee('Joran Carbon')
            ->assertSee('Pakan Ikan');
    }
}
