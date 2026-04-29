<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
   public function run()
{
    DB::table('orders')->insert([
        [
            'table_number' => 'B1',
            'total_price' => 300000,
            'status' => 'completed',
            'user_id' => 3,
            'name' => 'Nguyen Van A',
            'phone' => '0123456789',
        ],
        [
            'table_number' => 'B2',
            'total_price' => 150000,
            'status' => 'pending',
            'user_id' => 3,
            'name' => 'Nguyen Van A',
            'phone' => '0123456789',
        ]
    ]);
}
}