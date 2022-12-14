<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->getKey(),
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'product' => new ProductResource($this->product),
            'user' => new UserResource($this->customer),
            'created_at' => $this->created_at,
        ];
    }
}
