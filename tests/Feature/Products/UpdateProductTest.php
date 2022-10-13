<?php

use App\Enums\UserRoleEnum;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\patchJson;
use function PHPUnit\Framework\assertEquals;

it('can update a product', function () {
    $bob = User::factory()->create(['role' => UserRoleEnum::admin()]);
    actingAs($bob);

    $product = Product::factory()->create();

    patchJson(
        route('products.update', ['product' => $product->getKey()]),
        [
            'title' => 'new very awesome title',
        ]
    );

    assertEquals('new very awesome title', $product->fresh()->title);
});

it('validates the request', function () {
    $bob = User::factory()->create(['role' => UserRoleEnum::admin()]);
    actingAs($bob);

    $product = Product::factory()->create();

    patchJson(
        route('products.update', ['product' => $product->getKey()]),
        [
            'price' => 'not a number',
        ]
    );
})->expectException(ValidationException::class);

it('checks for permission to update a product', function () {
    $bob = User::factory()->create(['role' => UserRoleEnum::customer()]);
    actingAs($bob);

    $product = Product::factory()->create();

    patchJson(
        route('products.update', ['product' => $product->getKey()]),
        [
            'title' => 'new very awesome title',
        ]
    );
})->expectException(AuthorizationException::class);

test('you must be authenticated to update a product', function () {
    $product = Product::factory()->create();

    patchJson(
        route('products.update', ['product' => $product->getKey()]),
        [
            'title' => 'new very awesome title',
        ]
    );
})->expectException(AuthenticationException::class);
