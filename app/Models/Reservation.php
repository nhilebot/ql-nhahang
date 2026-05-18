<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    // Định nghĩa các trạng thái
    // app/Models/Reservation.php
    const STATUS_PREPARING = 'preparing'; // Đang chuẩn bị
    const STATUS_SERVED    = 'served';    // Đã lên món
    const STATUS_SERVING   = 'serving';   // Đang phục vụ
    const STATUS_COMPLETED = 'completed'; // Đã xong

    protected $fillable = [
    'user_id', 
    'reservation_date', 
    'reservation_time', 
    'table_id', 
    'full_name', 
    'email',
    'phone', 
    'notes', 
    'cart_data', 
    'total_price', // THÊM DÒNG NÀY (Trong ảnh DB có cột này nhưng Fillable thiếu nên ko lưu được tiền)
    'reservation_status', 
    'payment_status', 
    'payment_method', 
    'cleanup_started_at', 
    'status'
];  
    protected $casts = [
        'cleanup_started_at' => 'datetime',
        'reservation_date' => 'date',
        'cart_data' => 'array',
         'chef_statuses'  => 'array',
    ];
   public function orderItems()
{
    // Cực kỳ quan trọng: Liên kết trực tiếp bằng reservation_id
    // Nó sẽ chỉ lấy những món ăn có gắn ID của đúng hóa đơn này
    return $this->hasMany(\App\Models\OrderItem::class, 'reservation_id', 'id');
}
public function order()
{
    // Reservation nối với Order qua user_id và table_id (hoặc reservation_id nếu có)
    return $this->hasOne(\App\Models\Order::class, 'user_id', 'user_id')
                ->where('table_number', $this->table_id)
                ->latest();
}
public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function table()
    {
        // 'table_id' là tên cột khóa ngoại trong bảng reservations nối với bảng tables
        return $this->belongsTo(\App\Models\Table::class, 'table_id');
    }
}   