<?php

namespace App\Http\Resources;


use App\Models\Product;

class OrderResource extends BaseResource
{
    public function toArray($request)
    {
        if (isset($this->uuid)) {
            return [
                'uuid' => $this->uuid,
                'address' => $this->address,
                'delivery_fee' => $this->delivery_fee,
                'amount' => $this->amount,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'shipped_at' => $this->shipped_at,
                'order_status' => new OrderStatusResource($this->order_status),
                'user' => new UserResource($this->user),
                'payment' => ($this->payment) ? new PaymentResource($this->payment) : null,
                'products' => array_map("self::getOrderProducts", $this->products)

            ];
        }
        return [
            'data' => $this->map(function ($data) {
                return [
                    'uuid' => $data->uuid,
                    'address' => $data->address,
                    'delivery_fee' => $data->delivery_fee,
                    'amount' => $data->amount,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'shipped_at' => $data->shipped_at,
                    'order_status' => new OrderStatusResource($data->order_status),
                    'user' => new UserResource($data->user),
                    'payment' => ($data->payment) ? new PaymentResource($data->payment) : null,
                    'products' => array_map("self::getOrderProducts", $data->products)
                ];
            })
        ];
    }

    protected function getOrderProducts($orderProduct)
    {
        $product = Product::where('uuid', $orderProduct['product'])->select('title', 'price')->first();
        return [
            'uuid' => $orderProduct['product'],
            'price' => $product->price,
            'product' => $product->title,
            'quantity' => $orderProduct['quantity'],
        ];
    }
}
