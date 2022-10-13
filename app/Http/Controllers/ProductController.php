<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(CreateProductRequest $request): ProductResource
    {
        /** @var array<string, mixed> */
        $data = $request->validated();

        $product = Product::create($data);

        return new ProductResource($product);
    }
}
