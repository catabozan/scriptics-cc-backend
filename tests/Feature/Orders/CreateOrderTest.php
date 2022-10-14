<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEmpty;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

it('decreases product stock', function () {
    $product = Product::factory()->create([
        'stock' => 2,
    ]);

    postJson(
        route('orders.store', ['product' => $product->getKey()]),
        [
            'address' => 'Real Street, nr.1',
            'phone_number' => '23456789',
        ]
    );

    $order = Order::firstWhere('address', 'Real Street, nr.1');

    assertNotEmpty($order);
    assertEquals(1, $product->fresh()->stock);
});

it('checks product stock', function () {
    $product = Product::factory()->create([
        'stock' => 0,
    ]);

    postJson(
        route('orders.store', ['product' => $product->getKey()]),
        [
            'address' => 'Real Street, nr.1',
            'phone_number' => '23456789',
        ]
    );
})->expectException(NotFoundHttpException::class);

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
