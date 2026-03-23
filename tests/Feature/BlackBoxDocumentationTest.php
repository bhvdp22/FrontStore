<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BlackBoxDocumentationTest extends TestCase
{
    public function test_customer_registration_accepts_valid_input(): void
    {
        Mail::fake();

        $response = $this->post(route('customer.register.submit'), [
            'name' => 'Ravi Kumar',
            'email' => 'ravi@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('shop.index'));
        $response->assertSessionHas('customer_email', 'ravi@example.com');
        $this->assertDatabaseHas('customers', [
            'name' => 'Ravi Kumar',
            'email' => 'ravi@example.com',
        ]);
    }

    public function test_customer_registration_rejects_invalid_email(): void
    {
        Mail::fake();

        $response = $this
            ->from(route('customer.register'))
            ->post(route('customer.register.submit'), [
                'name' => 'Ravi Kumar',
                'email' => 'not-an-email',
                'password' => 'secret123',
            ]);

        $response->assertRedirect(route('customer.register'));
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_add_to_cart_accepts_a_product_when_stock_is_available(): void
    {
        $product = $this->createProduct([
            'name' => 'Organic Honey',
            'sku' => 'SKU2001',
            'quantity' => 2,
        ]);

        $response = $this
            ->withSession(['customer_email' => 'ravi@example.com'])
            ->postJson(route('shop.addToCart'), [
                'product_id' => $product->id,
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Product added to cart!',
                'cartCount' => 1,
            ]);
    }

    public function test_add_to_cart_rejects_request_when_stock_limit_is_exceeded(): void
    {
        $product = $this->createProduct([
            'name' => 'Organic Honey',
            'sku' => 'SKU2002',
            'quantity' => 1,
        ]);

        $response = $this
            ->withSession([
                'customer_email' => 'ravi@example.com',
                'cart' => [
                    $product->id => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => 1,
                        'sku' => $product->sku,
                        'image' => $product->img_path,
                    ],
                ],
            ])
            ->postJson(route('shop.addToCart'), [
                'product_id' => $product->id,
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => false,
                'message' => 'Sorry, only 1 item(s) available in stock!',
            ]);
    }

    public function test_admin_login_rejects_invalid_credentials(): void
    {
        Admin::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this
            ->from(route('admin.login'))
            ->post(route('admin.login.submit'), [
                'email' => 'admin@example.com',
                'password' => 'wrong-password',
            ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_smoke_pages_load_for_core_modules(): void
    {
        $this->get(route('customer.register'))->assertOk();
        $this->get(route('customer.login'))->assertOk();
        $this->get(route('admin.login'))->assertOk();

        $this
            ->withSession(['customer_email' => 'ravi@example.com'])
            ->get(route('shop.cart'))
            ->assertOk();
    }

    private function createProduct(array $attributes = []): Product
    {
        return Product::create(array_merge([
            'name' => 'Sample Product',
            'sku' => 'SKU-DEFAULT-BB',
            'asin' => 'ASIN-DEFAULT-BB',
            'price' => 1000,
            'quantity' => 1,
            'status' => 'active',
            'description' => 'Sample description',
            'img_path' => 'products/sample.png',
        ], $attributes));
    }
}
