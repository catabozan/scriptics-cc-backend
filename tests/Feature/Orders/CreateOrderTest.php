<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertNotEmpty;

it('can create an order', function () {
    $bob = User::factory()->create();
    actingAs($bob);

    $product = Product::factory()->create();

    postJson(
        route('orders.store', ['product' => $product->getKey()]),
        [
            'address' => 'Real Street, nr.1',
            'phone_number' => '23456789',
        ]
    );

    assertNotEmpty(Order::firstWhere('address', 'Real Street, nr.1'));
    assertNotEmpty($bob->orders);
    assertNotEmpty($product->orders);
});

it('can create an anonymous order', function () {
    $product = Product::factory()->create();

    postJson(
        route('orders.store', ['product' => $product->getKey()]),
        [
            'address' => 'Real Street, nr.1',
            'phone_number' => '23456789',
        ]
    );

    $order = Order::firstWhere('address', 'Real Street, nr.1');

    assertNotEmpty($order);
    assertEmpty($order->customer);
    assertNotEmpty($product->orders);
});

it('validates the request', function () {
    $product = Product::factory()->create();

    postJson(
        route('orders.store', ['product' => $product->getKey()]),
        [
            'address' => 'Real Street, nr.1',
            // 'phone_number' => '23456789'
        ]
    );
})->expectException(ValidationException::class);
