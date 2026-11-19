<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_auth_pages_and_cart_render(): void
    {
        $product = Product::create([
            'name' => 'Peachy Cream',
            'slug' => 'peachy-cream',
            'description' => 'Krim dan persik.',
            'price' => 30000,
            'stock' => 2,
        ]);

        $this->get(route('home'))->assertOk()->assertSee('JAPANESE-INSPIRED FRUIT SANDO');
        $this->get(route('shop'))->assertOk()->assertSee('Peachy Cream');
        $this->get(route('login'))->assertRedirect(route('home').'#login');
        $this->get(route('register'))->assertRedirect(route('home').'#register');
        $this->post(route('cart.store', $product))->assertRedirect(route('home').'#cart');
        $this->get(route('cart.index'))->assertRedirect(route('home').'#cart');
    }

    public function test_original_storefront_routes_render_products_using_legacy_slugs(): void
    {
        Product::create([
            'name' => 'Sando Strawberry',
            'slug' => 'sando-strawberry',
            'description' => 'Stroberi dan krim.',
            'price' => 7000,
            'stock' => 5,
        ]);

        $this->get(route('home'))->assertOk()->assertSee('JAPANESE-INSPIRED FRUIT SANDO')->assertSee('Sando Strawberry');
        $this->get(route('shop'))->assertOk()->assertSee('Sando Strawberry');
        $this->get(route('product', 'strawberry'))->assertOk()->assertSee('Strawberry')->assertSee('ingredients');
    }

    public function test_storefront_cart_json_endpoints_use_the_server_session(): void
    {
        $product = Product::create([
            'name' => 'Grape Cream',
            'slug' => 'grape-cream',
            'description' => 'Krim dan anggur.',
            'price' => 28000,
            'stock' => 3,
        ]);

        $this->postJson(route('cart.store', $product), ['quantity' => 2])
            ->assertOk()
            ->assertJsonPath('items.0.slug', 'grape-cream')
            ->assertJsonPath('items.0.qty', 2);

        $this->getJson(route('cart.contents'))
            ->assertOk()
            ->assertJsonPath('items.0.slug', 'grape-cream')
            ->assertJsonPath('items.0.qty', 2);

        $this->patchJson(route('cart.update', $product), ['quantity' => 1])
            ->assertOk()
            ->assertJsonPath('items.0.qty', 1);
    }

    public function test_customer_can_register_log_in_and_log_out(): void
    {
        $this->post('/daftar', [
            'name' => 'Pelanggan Tada',
            'email' => 'buyer@example.com',
            'password' => 'sandwich123',
            'password_confirmation' => 'sandwich123',
        ])->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'email' => 'buyer@example.com',
            'is_admin' => false,
        ]);
        $this->assertAuthenticated();

        $this->post('/keluar')->assertRedirect(route('home'));
        $this->assertGuest();

        $this->post('/masuk', [
            'email' => 'buyer@example.com',
            'password' => 'sandwich123',
        ])->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }

    public function test_checkout_decreases_stock_and_creates_customer_order(): void
    {
        $product = Product::create([
            'name' => 'Strawberry Cream',
            'slug' => 'strawberry-cream',
            'description' => 'Krim dan stroberi segar.',
            'price' => 28000,
            'stock' => 3,
        ]);
        $customer = User::factory()->create();

        $this->post(route('cart.store', $product), ['quantity' => 2])
            ->assertRedirect(route('home').'#cart');

        $this->actingAs($customer)
            ->post(route('cart.checkout'), [
                'customer_phone' => '08123456789',
                'payment_method' => 'COD',
            ])
            ->assertRedirect(route('orders.mine'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 1]);
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'total' => 56000,
            'order_status' => 'pending',
        ]);
        $this->assertStringStartsWith('TADA-', (string) Order::query()->value('order_code'));
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'product_price' => 28000,
            'subtotal' => 56000,
        ]);
        $this->assertSame([], session('cart', []));
        $this->get(route('orders.mine'))->assertOk()->assertSee('Pesanan #1');
    }

    public function test_checkout_rejects_quantity_above_available_stock(): void
    {
        $product = Product::create([
            'name' => 'Choco Berry',
            'slug' => 'choco-berry',
            'description' => 'Cokelat dan beri.',
            'price' => 30000,
            'stock' => 1,
        ]);

        $this->from(route('home'))
            ->post(route('cart.store', $product), ['quantity' => 2])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('cart');

        $this->assertSame([], session('cart', []));
    }

    public function test_only_admin_can_manage_stock_cash_flow_and_order_status(): void
    {
        $customer = User::factory()->create();
        $admin = User::factory()->create();
        $admin->is_admin = true;
        $admin->save();
        $product = Product::create([
            'name' => 'Grape Milk',
            'slug' => 'grape-milk',
            'description' => 'Krim susu dan anggur.',
            'price' => 28000,
            'stock' => 4,
        ]);
        $order = Order::create([
            'user_id' => $customer->id,
            'order_code' => 'TADA-TEST-ORDER',
            'customer_name' => $customer->name,
            'customer_phone' => '08123456789',
            'subtotal' => 56000,
            'shipping_cost' => 0,
            'total' => 56000,
            'payment_method' => 'COD',
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'product_price' => 28000,
            'subtotal' => 56000,
        ]);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard admin');

        $this->patch(route('admin.products.stock', $product), ['stock' => 10])
            ->assertSessionHasNoErrors();
        $this->post(route('admin.cash-flows.store'), [
            'type' => 'expense',
            'amount' => 15000,
            'description' => 'Beli bahan',
        ])->assertSessionHasNoErrors();
        $this->patch(route('admin.orders.update', $order), [
            'status' => 'completed',
            'admin_note' => 'Pesanan sudah selesai.',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 10, 'sold_quantity' => 2]);
        $this->assertDatabaseHas('cash_flows', ['type' => 'expense', 'amount' => 15000]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'completed',
            'admin_note' => 'Pesanan sudah selesai.',
        ]);
    }
}
