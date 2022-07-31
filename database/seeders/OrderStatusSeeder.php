<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['paid','open','shipped','canceled','pending payment'];

        foreach ($statuses as $status){
            OrderStatus::create([
                'uuid' =>  Str::uuid(),
                'title' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
