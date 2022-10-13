<?php

use App\Enums\UserRoleEnum;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function PHPUnit\Framework\assertEmpty;

it('can delete a product', function () {
    $bob = User::factory()->create(['role' => UserRoleEnum::admin()]);
    actingAs($bob);

    $product = Product::factory()->create();

    deleteJson(route('products.destroy', ['product' => $product->getKey()]));

    assertEmpty(Product::find($product->getKey()));
});

it('checks for permission to delete a product', function () {
    $bob = User::factory()->create(['role' => UserRoleEnum::customer()]);
    actingAs($bob);

    $product = Product::factory()->create();

    deleteJson(route('products.destroy', ['product' => $product->getKey()]));
})->expectException(AuthorizationException::class);

test('you must be authenticated to delete a product', function () {
    $product = Product::factory()->create();

    deleteJson(route('products.destroy', ['product' => $product->getKey()]));
})->expectException(AuthenticationException::class);
