<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            MenuSeeder::class, 
            RoleSeeder::class,
            UserSeeder::class,
            TableSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            ReservationSeeder::class,
            CartSeeder::class,
        ]);
    }
}