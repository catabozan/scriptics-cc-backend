<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function PHPUnit\Framework\assertEmpty;

it('can delete a product', function () {
    actingAs(User::factory()->create());

    $product = Product::factory()->create();

    deleteJson(route('products.destroy', ['product' => $product->getKey()]));

    assertEmpty(Product::find($product->getKey()));
});

test('you must be authenticated to delete a product', function () {
    $product = Product::factory()->create();

    deleteJson(route('products.destroy', ['product' => $product->getKey()]));
})->expectException(AuthenticationException::class);
