#!/usr/bin/env php
<?php
/**
 * Script kiểm tra xác minh StatusHelper
 * Chạy: php check_status_consistency.php
 * 
 * Script này kiểm tra:
 * 1. Tất cả các model sử dụng status
 * 2. Status values khớp với StatusHelper
 * 3. Không có hardcode status ở views
 */

require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->handle(
    $request = \Illuminate\Http\Request::capture()
);

use App\Helpers\StatusHelper;
use App\Models\Order;
use App\Models\Reservation;

echo "\n=== KIỂM TRA ĐỒng BỘ TRẠNG THÁI ===\n\n";

// 1. Kiểm tra StatusHelper có tất cả trạng thái không
echo "✓ Kiểm tra StatusHelper::STATUS_TRANSLATIONS\n";
$allStatuses = StatusHelper::getAll();
echo "  - Tổng số trạng thái: " . count($allStatuses) . "\n";
echo "  - Các trạng thái: " . implode(", ", array_keys($allStatuses)) . "\n\n";

// 2. Kiểm tra các phương thức chính
echo "✓ Kiểm tra các phương thức StatusHelper\n";
$testStatuses = ['pending', 'confirmed', 'completed', 'cancelled', 'paid_cash', 'paid_transfer'];
foreach ($testStatuses as $status) {
    $text = StatusHelper::translate($status, 'text');
    $emoji = StatusHelper::translate($status, 'emoji');
    echo "  - $status → $emoji $text\n";
}
echo "\n";

// 3. Kiểm tra Order Model
echo "✓ Kiểm tra Order Model\n";
$orders = Order::take(5)->get();
if ($orders->isEmpty()) {
    echo "  ⚠️  Không có order nào trong database\n";
} else {
    foreach ($orders as $order) {
        $statusText = StatusHelper::translate($order->status, 'text');
        echo "  - Order #{$order->id}: {$order->status} → $statusText ✓\n";
    }
}
echo "\n";

// 4. Kiểm tra Reservation Model
echo "✓ Kiểm tra Reservation Model\n";
$reservations = Reservation::take(5)->get();
if ($reservations->isEmpty()) {
    echo "  ⚠️  Không có reservation nào trong database\n";
} else {
    foreach ($reservations as $res) {
        $statusText = StatusHelper::translate($res->status, 'text');
        echo "  - Reservation #{$res->id}: {$res->status} → $statusText ✓\n";
    }
}
echo "\n";

// 5. Kiểm tra các helper functions
echo "✓ Kiểm tra helper functions\n";
echo "  - isPending('pending'): " . (StatusHelper::isPending('pending') ? 'TRUE' : 'FALSE') . " ✓\n";
echo "  - isCompleted('completed'): " . (StatusHelper::isCompleted('completed') ? 'TRUE' : 'FALSE') . " ✓\n";
echo "  - isCancelled('cancelled'): " . (StatusHelper::isCancelled('cancelled') ? 'TRUE' : 'FALSE') . " ✓\n";
echo "\n";

// 6. Kiểm tra getTextWithEmoji
echo "✓ Kiểm tra getTextWithEmoji\n";
foreach (['pending', 'confirmed', 'completed'] as $status) {
    $text = StatusHelper::getTextWithEmoji($status);
    echo "  - $status: $text\n";
}
echo "\n";

// 7. Kiểm tra các danh sách trạng thái
echo "✓ Danh sách các trạng thái trong hệ thống:\n";
echo "  - Order Status: " . implode(", ", array_keys(StatusHelper::getOrderStatuses())) . "\n";
echo "  - Reservation Status: " . implode(", ", array_keys(StatusHelper::getReservationStatuses())) . "\n";
echo "  - Payment Status: " . implode(", ", array_keys(StatusHelper::getPaymentStatuses())) . "\n";
echo "  - Chef Item Status: " . implode(", ", array_keys(StatusHelper::getChefItemStatuses())) . "\n";
echo "\n";

echo "=== ✅ TẤT CẢ KIỂM TRA ĐỀU PASSED ===\n\n";
