<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    public function store(CreateOrderRequest $request, Product $product): OrderResource
    {
        /** @var array<string, mixed> */
        $data = $request->validated();
        $user = $request->user();

        $order = Order::create([
            ...$data,
            'product_id' => $product->getKey(),
            'user_id' => $user?->getKey(),
        ]);

        return new OrderResource($order);
    }
}
