<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

protected $fillable = ['order_id', 'product_name', 'quantity', 'price', 'menu_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        // return $this->belongsTo(Menu::class);
        // Liên kết cột menu_id của bảng order_items với id của bảng menus
    return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}
