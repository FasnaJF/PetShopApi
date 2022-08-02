<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         \App\Models\Category::factory(10)->create();
//         \App\Models\Brand::factory(10)->create();
         \App\Models\Order::factory(50)->create();
//         \App\Models\Product::factory(10)->create();
//        $this->call([
//            OrderStatusSeeder::class
//        ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
