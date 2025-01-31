<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function getAllOrders()
    {
        return Order::with(['user', 'products'])->get();
    }

    public function findOrFail(int $id)
    {
        return Order::with(['user', 'products'])->findOrFail($id);
    }

    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => $data['user_id'],
                'status' => 'new',
                'total_price' => 0
            ]);

            $totalPrice = $this->attachProductsToOrder($order, $data['products']);

            $order->update(['total_price' => $totalPrice]);

            return $order->load(['user', 'products']);
        });
    }

    private function attachProductsToOrder(Order $order, array $products)
    {
        $totalPrice = 0;

        foreach ($products as $item) {
            $product = Product::findOrFail($item['product_id']);
            $quantity = $item['quantity'];

            $order->products()->attach($product->id, [
                'quantity' => $quantity
            ]);

            $totalPrice += $product->price * $quantity;
        }

        return $totalPrice;
    }

    public function updateOrderStatus(int $id, string $status)
    {
        $order = $this->findOrFail($id);
        $order->update(['status' => $status]);
        return $order->load(['user', 'products']);
    }

    public function deleteOrder(int $id)
    {
        $order = $this->findOrFail($id);
        $order->delete();
    }
}
