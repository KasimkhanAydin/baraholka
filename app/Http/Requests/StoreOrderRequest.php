<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
            ],
            'products' => 'required|array|min:1',
            'products.*.product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id'),
            ],
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}
