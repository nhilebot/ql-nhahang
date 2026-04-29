<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $tables = [
            ['name' => 'Bàn 01', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn 02', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn 03', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn 04', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn 05', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn 06', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn 07', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn 08', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn 09', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn 10', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn VIP 01', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn VIP 02', 'status' => 'empty', 'cleanup_started_at' => null], // Đã sửa lỗi đóng ngoặc ở đây
            ['name' => 'Bàn VIP 03', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn Ngoài Trời 01', 'status' => 'empty', 'cleanup_started_at' => null],
            ['name' => 'Bàn Ngoài Trời 02', 'status' => 'empty', 'cleanup_started_at' => null],
        ];

        foreach ($tables as $table) {
            // Sử dụng updateOrInsert để nếu có tên bàn rồi thì chỉ cập nhật, chưa có thì mới thêm
            DB::table('tables')->updateOrInsert(
                ['name' => $table['name']], 
                $table
            );
        }
    }
}