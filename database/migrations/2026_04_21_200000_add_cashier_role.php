<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm role cashier (thu ngân) nếu chưa có
        if (!DB::table('roles')->where('name', 'cashier')->exists()) {
            DB::table('roles')->insert(['name' => 'cashier', 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        DB::table('roles')->where('name', 'cashier')->delete();
    }
};
