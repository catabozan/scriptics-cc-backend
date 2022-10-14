<?php

use App\Enums\UserRoleEnum;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function PHPUnit\Framework\assertEmpty;

it('can delete its own order', function () {
    $bob = User::factory()->create();
    actingAs($bob);

    $product = Product::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $bob->getKey(),
        'product_id' => $product->getKey(),
    ]);

    deleteJson(route('orders.destroy', ['order' => $order->getKey()]));

    assertEmpty(Order::find($order->id));
});

test("admin can delete another user's order", function () {
    $bob = User::factory()->create();
    $admin = User::factory()->create(['role' => UserRoleEnum::admin()]);
    actingAs($admin);

    $product = Product::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $bob->getKey(),
        'product_id' => $product->getKey(),
    ]);

    deleteJson(route('orders.destroy', ['order' => $order->getKey()]));

    assertEmpty(Order::find($order->id));
});

test('you must be authenticated to delete an order', function () {
    $bob = User::factory()->create();
    // actingAs($admin);

    $product = Product::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $bob->getKey(),
        'product_id' => $product->getKey(),
    ]);

    deleteJson(route('orders.destroy', ['order' => $order->getKey()]));

    assertEmpty(Order::find($order->id));
})->expectException(AuthenticationException::class);
