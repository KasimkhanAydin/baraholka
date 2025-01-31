<?php

namespace App\Http\Services;

use App\Models\Order;

class OrderService
{

    public function get($id=null)
    {
        if($id){
            $order = Order::with(['user', 'products'])->find($id);
            if(!$order){
                abort(404);
            }
        }
        else{
            $order = Order::with(['user', 'products'])->get();
        }

        return $order;
    }
}
