<?php

namespace Database\Factories;

use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
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
        $user_ids = User::select('uuid')->get();
        $user_id = fake()->randomElement($user_ids)->uuid;
        $payment_ids = Payment::select('uuid')->get();
        $payment_id = fake()->randomElement($payment_ids)->uuid;
        $order_status_ids = OrderStatus::select('uuid', 'title')->get();
        $order_status_id = fake()->randomElement($order_status_ids)->uuid;
        $order_status = OrderStatus::where('uuid', $order_status_id)->pluck('title')->first();

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

        $date = Carbon::now();
        $monthDate = Carbon::now()->startOfMonth()->subDays(5);
        $weekDate = Carbon::now()->subDays(12);
        $shippedTime = [$date, $monthDate, $weekDate];

        return [
            'user_id' => $user_id,
            'order_status_id' => $order_status_id,
            'payment_id' => $payment_id,
            'uuid' => Str::uuid(),
            'products' => ($purchasedProducts),
            'address' => ([
                'billing' => fake()->address(),
                'shipping' => fake()->address()
            ]),
            'delivery_fee' => ($amount < 500) ? 15 : 0,
            'amount' => $amount,
            'created_at' => now(),
            'updated_at' => now(),
            'shipped_at' => ($payment_id) ? $shippedTime[array_rand($shippedTime)] : null,
        ];
    }
}
