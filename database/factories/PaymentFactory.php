<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class PaymentFactory extends Factory
{

    public function definition()
    {
        $paymentTypes = [
            'credit_card' => [
                'holder_name' => fake()->name(),
                'number' => fake()->creditCardNumber(),
                'ccv' => rand(100, 999),
                'expire_date' => fake()->creditCardExpirationDateString(),
            ],
            'cash_on_delivery' => [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'address' => fake()->address(),
            ],
            'bank_transfer' => [
                'swift' => fake()->swiftBicNumber(),
                'iban' => fake()->iban(),
                'name' => fake()->name(),
            ],
        ];

        $arrayKey = array_rand($paymentTypes);
        $paymentDetail = $paymentTypes[$arrayKey];

        return [
            'uuid' => Str::uuid(),
            'type' => $arrayKey,
            'details' => ($paymentDetail),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
