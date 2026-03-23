<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WhiteBoxDocumentationTest extends TestCase
{
    public function test_it_prevents_order_placement_when_cart_is_empty(): void
    {
        Mail::fake();

        $response = $this
            ->withSession([
                'customer_email' => 'ravi@example.com',
                'customer_id' => 1,
            ])
            ->post(route('shop.placeOrder'), $this->validCheckoutPayload());

        $response->assertRedirect(route('shop.index'));
        $response->assertSessionHas('error', 'Your cart is empty!');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('payments', 0);
    }

    public function test_it_creates_a_cash_on_delivery_order_when_stock_is_available(): void
    {
        Mail::fake();

        $product = $this->createProduct([
            'name' => 'Organic Honey',
            'sku' => 'SKU1001',
            'price' => 1000,
            'quantity' => 2,
            'seller_id' => null,
        ]);

        $response = $this
            ->withSession([
                'customer_email' => 'ravi@example.com',
                'customer_id' => 1,
                'cart' => [
                    $product->id => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => 1000,
                        'quantity' => 1,
                        'sku' => $product->sku,
                        'image' => $product->img_path,
                    ],
                ],
            ])
            ->post(route('shop.placeOrder'), $this->validCheckoutPayload([
                'payment_method' => 'cod',
            ]));

        $response->assertRedirect(route('shop.success'));

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseHas('payments', [
            'payment_method' => 'Cash on Delivery',
            'status' => 'Pending',
        ]);

        $product->refresh();
        $this->assertSame(1, $product->quantity);
    }

    public function test_it_rejects_an_order_when_requested_quantity_exceeds_stock(): void
    {
        Mail::fake();

        $product = $this->createProduct([
            'name' => 'Organic Honey',
            'sku' => 'SKU1002',
            'price' => 1000,
            'quantity' => 1,
            'seller_id' => null,
        ]);

        $response = $this
            ->withSession([
                'customer_email' => 'ravi@example.com',
                'customer_id' => 1,
                'cart' => [
                    $product->id => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => 1000,
                        'quantity' => 2,
                        'sku' => $product->sku,
                        'image' => $product->img_path,
                    ],
                ],
            ])
            ->post(route('shop.placeOrder'), $this->validCheckoutPayload());

        $response->assertRedirect(route('shop.checkout'));
        $response->assertSessionHas('error', function (string $message): bool {
            return str_contains($message, 'Stock unavailable');
        });

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('payments', 0);

        $product->refresh();
        $this->assertSame(1, $product->quantity);
    }

    public function test_it_creates_a_completed_order_for_a_valid_razorpay_payment(): void
    {
        Mail::fake();

        config()->set('services.razorpay.key', 'rzp_test_documentation');
        config()->set('services.razorpay.secret', 'documentation_secret');

        $product = $this->createProduct([
            'name' => 'Organic Honey',
            'sku' => 'SKU1003',
            'price' => 1000,
            'quantity' => 2,
            'seller_id' => null,
        ]);

        $razorpayOrderId = 'order_test_123';
        $razorpayPaymentId = 'pay_test_123';
        $signature = hash_hmac(
            'sha256',
            $razorpayOrderId.'|'.$razorpayPaymentId,
            'documentation_secret'
        );

        $response = $this
            ->withSession([
                'customer_email' => 'ravi@example.com',
                'customer_id' => 1,
                'razorpay_order_id' => $razorpayOrderId,
                'cart' => [
                    $product->id => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => 1000,
                        'quantity' => 1,
                        'sku' => $product->sku,
                        'image' => $product->img_path,
                    ],
                ],
            ])
            ->post(route('shop.placeOrder'), $this->validCheckoutPayload([
                'payment_method' => 'razorpay',
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_signature' => $signature,
            ]));

        $response->assertRedirect(route('shop.success'));

        $this->assertDatabaseHas('payments', [
            'payment_method' => 'Razorpay (Online)',
            'status' => 'Completed',
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_order_id' => $razorpayOrderId,
        ]);

        $this->assertSame(1, Order::count());
        $this->assertSame(1, Payment::count());
    }

    public function test_it_rejects_a_return_request_when_return_quantity_exceeds_purchased_quantity(): void
    {
        $seller = User::create([
            'name' => 'Seller One',
            'password' => 'secret',
        ]);

        $customer = Customer::create([
            'name' => 'Ravi Kumar',
            'email' => 'ravi@example.com',
            'password' => 'secret',
        ]);

        $product = $this->createProduct([
            'name' => 'Organic Honey',
            'sku' => 'SKU1004',
            'price' => 1000,
            'quantity' => 5,
            'seller_id' => $seller->id,
        ]);

        $order = Order::create([
            'order_id' => 'ORD-RETURN-1',
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => '9876543210',
            'shipping_address' => 'Ahmedabad, Gujarat',
            'product_name' => $product->name,
            'sku' => $product->sku,
            'quantity' => 2,
            'subtotal' => 2000,
            'tax_rate' => 18,
            'tax_amount' => 360,
            'platform_fee' => 20,
            'commission_rate' => 10,
            'commission_amount' => 200,
            'seller_earnings' => 1800,
            'total_price' => 2380,
            'grand_total' => 2380,
            'seller_id' => $seller->id,
            'status' => 'Delivered',
            'order_source' => 'online',
        ]);

        $response = $this
            ->from(route('returns.create', ['order_item_id' => $order->id]))
            ->withSession([
                'customer_email' => $customer->email,
                'customer_id' => $customer->id,
            ])
            ->post(route('returns.store'), [
                'order_item_id' => $order->id,
                'return_reason' => 'defective',
                'reason_details' => 'The jar arrived broken.',
                'quantity' => 3,
            ]);

        $response->assertRedirect(route('returns.create', ['order_item_id' => $order->id]));
        $response->assertSessionHasErrors([
            'quantity' => 'Return quantity cannot exceed ordered quantity.',
        ]);

        $this->assertDatabaseCount('returns', 0);
    }

    private function validCheckoutPayload(array $overrides = []): array
    {
        return array_merge([
            'customer_name' => 'Ravi Kumar',
            'customer_email' => 'ravi@example.com',
            'customer_phone' => '9876543210',
            'shipping_address' => 'Ahmedabad, Gujarat',
            'payment_method' => 'cod',
        ], $overrides);
    }

    private function createProduct(array $attributes = []): Product
    {
        return Product::create(array_merge([
            'name' => 'Sample Product',
            'sku' => 'SKU-DEFAULT',
            'asin' => 'ASIN-DEFAULT',
            'price' => 1000,
            'quantity' => 1,
            'status' => 'active',
            'description' => 'Sample description',
            'img_path' => 'products/sample.png',
        ], $attributes));
    }
}
