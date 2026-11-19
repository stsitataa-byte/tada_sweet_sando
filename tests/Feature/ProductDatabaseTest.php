<?php

namespace Tests\Feature;

use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_displays_products_seeded_in_the_database(): void
    {
        $this->seed(ProductSeeder::class);

        $this->get('/shop')
            ->assertOk()
            ->assertSee('MANGO SANDO')
            ->assertSee('STRAWBERRY SANDO')
            ->assertSee('GRAPE SANDO')
            ->assertSee('MIX SANDO');
    }

    public function test_product_detail_uses_database_product_information(): void
    {
        $this->seed(ProductSeeder::class);

        $this->get('/product/strawberry')
            ->assertOk()
            ->assertSee('Strawberry')
            ->assertSee('Stroberi segar')
            ->assertSee('220–250 kkal');
    }
}
