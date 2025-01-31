<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{

    protected $model = Order::class;


    public function definition()
    {
        $statuses = ['new', 'processed', 'completed'];

        return [
            'user_id' => $this->faker->randomElement(User::pluck('id')->toArray()),
            'status' => $this->faker->randomElement($statuses),
            'total_price' => 0,
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::inRandomOrder()->take(rand(2, 5))->get();

            $totalPrice = 0;
            foreach ($products as $product) {
                $quantity = rand(1, 5);

                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                ]);
                $totalPrice += $product->price * $quantity;
            }

            $order->update(['total_price' => $totalPrice]);
        });
    }
}
