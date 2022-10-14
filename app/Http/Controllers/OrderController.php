<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $user = $request->user();

        if (empty($user)) {
            return OrderResource::collection([]);
        }

        $orders = Order::with(['customer', 'product']);

        if (! $user->isAdmin()) {
            $orders->where('user_id', $user->getKey());
        }

        return OrderResource::collection($orders->paginate());
    }

    public function show(Request $request, Order $order): OrderResource
    {
        $user = $request->user();

        throw_if(empty($user), new NotFoundHttpException());

        if (
            $user->isAdmin()
            || $user->is($order->customer)
        ) {
            return new OrderResource($order);
        }

        throw new NotFoundHttpException();
    }

    public function store(CreateOrderRequest $request, Product $product): OrderResource
    {
        if ($product->stock <= 0) {
            throw new NotFoundHttpException();
        }

        /** @var array<string, mixed> */
        $data = $request->validated();
        $user = $request->user();

        $order = Order::create([
            ...$data,
            'product_id' => $product->getKey(),
            'user_id' => $user?->getKey(),
        ]);

        $product->stock -= 1;
        $product->save();

        return new OrderResource($order);
    }

    public function destroy(Request $request, Order $order): OrderResource
    {
        /** @var User */
        $user = $request->user();

        if (
            $user->isAdmin()
            || $user->is($order->customer)
        ) {
            $order->delete();

            return new OrderResource($order);
        }

        throw new NotFoundHttpException();
    }
}
