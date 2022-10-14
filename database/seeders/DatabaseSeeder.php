<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\UserRoleEnum;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => UserRoleEnum::admin(),
        ]);

        $products = Product::factory()
            ->count(20)
            ->create();

        $this->createOrdersForProducts($products);
    }

    protected function createOrdersForProducts(Collection $products): void
    {
        $products->each(function (Product $product) {
            $hasBeenOrdered = (bool) random_int(0, 1);
            $hasBeenOrderedByUser = (bool) random_int(0, 1);

            if ($hasBeenOrdered) {
                $orderState = [
                    'product_id' => $product->getKey(),
                    'user_id' => null,
                ];

                if ($hasBeenOrderedByUser) {
                    $orderState['user_id'] = User::all()?->random()?->getKey();
                }

                Order::factory()->create($orderState);
            }
        });
    }
}
