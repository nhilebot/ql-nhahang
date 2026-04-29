<?php
// Đơn giản check menu data
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Menu;

$count = Menu::count();
echo "Total menus in DB: $count\n";

if ($count > 0) {
    $menus = Menu::take(5)->get();
    foreach ($menus as $menu) {
        echo "- {$menu->id}: {$menu->name} - {$menu->category} - {$menu->price}d - Stock: {$menu->stock}\n";
    }
} else {
    echo "ERROR: No menus found! Run: php artisan db:seed --class=MenuSeeder\n";
}
?>
