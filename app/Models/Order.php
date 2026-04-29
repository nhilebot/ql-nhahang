<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

protected $fillable = ['user_id', 'total_price', 'status', 'table_number', 'name', 
'phone', 'address', 'payment_method','notes', 'menu_id', 'code'];
protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function items()
{
    return $this->hasMany(\App\Models\OrderItem::class);
}
// Quan hệ với bảng Tables
public function table() {
    return $this->belongsTo(Table::class, 'table_number', 'name'); 
}

// Giúp Blade hiển thị màu sắc dựa trên trạng thái
public function getStatusColorAttribute() {
    return match($this->status) {
        'pending' => 'secondary',
        'processing' => 'warning',
        'ready' => 'success',
        default => 'dark'
    };
}
}
