<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'admin'],
            ['name' => 'staff'],
            ['name' => 'chef'],
            ['name' => 'cashier'],
            ['name' => 'customer'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::updateOrInsert(['name' => $role['name']], $role);
        }
    }
}
