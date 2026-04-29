<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function menus()
{
    // Giả sử tên cột thực tế trong database của bạn là 'id_category'
   return $this->hasMany(Menu::class, 'category_id');
}
}