<?php

use App\Enums\UserRoleEnum;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEmpty;

it('can create a product', function () {
    $bob = User::factory()->create(['role' => UserRoleEnum::admin()]);
    actingAs($bob);

    $product = Product::factory()->make();

    postJson(route('products.store'), [
        'title' => $product->title,
        'description' => $product->description,
        'price' => $product->price,
        'stock' => $product->stock,
    ]);

    $createdProduct = Product::firstWhere('title', $product->title);

    assertNotEmpty($createdProduct);
    assertEquals($product->title, $createdProduct->title);
    assertEquals($product->description, $createdProduct->description);
    assertEquals($product->price, $createdProduct->price);
    assertEquals($product->stock, $createdProduct->stock);
});

it('validates the request', function () {
    $bob = User::factory()->create(['role' => UserRoleEnum::admin()]);
    actingAs($bob);

    $product = Product::factory()->make();

    postJson(route('products.store'), [
        'title' => $product->title,
        // 'description' => $product->description,
        // 'price' => $product->price,
        // 'stock' => $product->stock,
    ]);
})->expectException(ValidationException::class);

it('checks for permission to create a product', function () {
    $bob = User::factory()->create(['role' => UserRoleEnum::customer()]);
    actingAs($bob);

    $product = Product::factory()->make();

    postJson(route('products.store'), [
        'title' => $product->title,
        'description' => $product->description,
        'price' => $product->price,
        'stock' => $product->stock,
    ]);
})->expectException(AuthorizationException::class);

test('you must be authenticated to create a product', function () {
    $product = Product::factory()->make();

    postJson(route('products.store'), [
        'title' => $product->title,
        'description' => $product->description,
        'price' => $product->price,
        'stock' => $product->stock,
    ]);
})->expectException(AuthenticationException::class);
