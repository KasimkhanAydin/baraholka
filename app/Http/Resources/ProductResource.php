<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->pivot->quantity ?? null,
            'total_line_price' => $this->pivot ?
                $this->price * $this->pivot->quantity :
                null
        ];
    }
}
