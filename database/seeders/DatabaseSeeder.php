<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\OrderStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(30)->create();
         \App\Models\Category::factory(20)->create();
         \App\Models\Brand::factory(20)->create();
         \App\Models\Payment::factory(20)->create();
        $this->call([
            OrderStatusSeeder::class
        ]);
         \App\Models\Product::factory(50)->create();
        \App\Models\Order::factory(50)->create();

        /*create admin*/
         \App\Models\User::factory()->create([
             'first_name' => 'admin',
             'last_name' => 'user',
             'email' => 'admin@petshop.net',
             'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
             'uuid' => Str::uuid(),
             'email_verified_at' => now(),
             'remember_token' => Str::random(10),
             'avatar' => Str::uuid(),
             'address' => fake()->address(),
             'phone_number' => fake()->phoneNumber(),
             'is_marketing' => 1,
             'created_at' => now(),
             'updated_at' => now(),
         ]);
    }
}
