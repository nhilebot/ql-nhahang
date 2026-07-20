<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
                [
                'role_id' => 1, 
                'name' => 'Thu ngân',
                'email' => 'cashier@gmail.com',
                'phone' => '0112233445',
                'password' => Hash::make('123456'),
            ],
            [
                'role_id' => 2,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'phone' => null,
                'password' => Hash::make('123456'),
            ],
            [
                'role_id' => 3,
                'name' => 'Staff',
                'email' => 'staff@gmail.com',
                'phone' => null,
                'password' => Hash::make('123456'),
            ],
            [
                'role_id' => 4,
                'name' => 'chef',
                'email' => 'chef@gmail.com',
                'phone' => '0123456789',
                'password' => Hash::make('123456'),
            ],
            [
                'role_id' => 5,
                'name' => 'Lê Yến Nhi',
                'email' => 'leyennhi22122006@gmail.com',
                'phone' => '0987654321',
                'password' => Hash::make('123456'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}