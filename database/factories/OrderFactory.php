<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{

    public function definition()
    {
        $amount = 0;
        $user_ids = User::select('id')->get();
        $user_id = fake()->randomElement($user_ids)->id;
        $payment_ids = Payment::select('id')->get();
        $payment_id = fake()->randomElement($payment_ids)->id;
        $order_status_ids = OrderStatus::select('id', 'title')->get();
        $order_status_id = fake()->randomElement($order_status_ids)->id;
        $order_status = OrderStatus::where('id', $order_status_id)->pluck('title')->first();

        if (!in_array($order_status, ['paid', 'shipped'])) {
            $payment_id = null;
        }

        $products = Product::select('uuid', 'price')->get()->toArray();
        $order_products = array_slice($products, 0, rand(2, 10));

        $purchasedProducts = [];
        $purchasedProduct = [];
        foreach ($order_products as $order_product) {
            $quantity = rand(1, 5);
            $amount += $quantity * $order_product['price'];
            $purchasedProduct['product'] = $order_product['uuid'];
            $purchasedProduct['quantity'] = $quantity;
            $purchasedProducts[] = $purchasedProduct;
        }

        $shippedTime = [now(), null];

        return [
            'user_id' => $user_id,
            'order_status_id' => $order_status_id,
            'payment_id' => $payment_id,
            'uuid' => Str::uuid(),
            'products' => json_encode($purchasedProducts),
            'address' => json_encode([
                'billing' => fake()->address(),
                'shipping' => fake()->address()
            ]),
            'delivery_fee' => ($amount > 500) ? 15 : 0,
            'amount' => $amount,
            'created_at' => now(),
            'updated_at' => now(),
            'shipped_at' => $shippedTime[array_rand($shippedTime)],
        ];
    }
}
