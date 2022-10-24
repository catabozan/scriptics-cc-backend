<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $products = Product::orderBy('created_at', 'desc')->paginate();

        return ProductResource::collection($products);
    }

    public function show(Request $request, Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function store(CreateProductRequest $request): ProductResource
    {
        /** @var array<string, mixed> */
        $data = $request->validated();

        $product = Product::create($data);

        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        /** @var array<string, mixed> */
        $data = $request->validated();

        $product->update($data);

        return new ProductResource($product->fresh());
    }

    public function destroy(Request $request, Product $product): ProductResource
    {
        $product->delete();

        return new ProductResource($product);
    }
}
