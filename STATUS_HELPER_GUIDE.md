# Hướng Dẫn Sử Dụng StatusHelper - Đồng Bộ Trạng Thái Tiếng Việt

## Giới Thiệu

`StatusHelper` là một helper class tập trung quản lý tất cả các trạng thái của ứng dụng với bản dịch tiếng Việt đầy đủ. Nó đồng bộ hóa trạng thái giữa:
- Khách hàng (Customer)
- Nhân viên (Staff)
- Admin

## Các Trạng Thái Được Hỗ Trợ

### Đơn Hàng (Order)
- `pending` → ⏳ Chờ duyệt
- `confirmed` → ✅ Đã xác nhận
- `processing` → ⚙️ Đang xử lý
- `ready` → 🍽️ Chờ phục vụ
- `served` → 😋 Đã lên món
- `completed` → 🏁 Hoàn tất
- `cancelled` → ❌ Đã hủy

### Đặt Bàn (Reservation)
- `arrived` → 👤 Khách đã đến
- `preparing` → 🍳 Đang chuẩn bị
- `serving` → 👨‍🍳 Đang phục vụ
- `completed` → 🏁 Hoàn tất
- `cancelled` → ❌ Đã hủy

### Thanh Toán (Payment)
- `paid_cash` → 💵 Tiền mặt
- `paid_transfer` → 📱 Chuyển khoản
- `paid` → 💳 Đã thanh toán

### Món Trong Bếp (Chef Item)
- `pending` → ⏳ Chờ
- `cooking` → 🔥 Đang nấu
- `done` → ✓ Đã xong

## Cách Sử Dụng Trong Blade

### 1. Lấy Text Trạng Thái
```blade
@status('pending')
<!-- Kết quả: Chờ duyệt -->
```

### 2. Lấy Text Đầy Đủ
```blade
@statusFull('pending')
<!-- Kết quả: Đơn hàng đang chờ duyệt -->
```

### 3. Lấy Emoji
```blade
@statusEmoji('pending')
<!-- Kết quả: ⏳ -->
```

### 4. Lấy Text + Emoji
```blade
@statusIcon('pending')
<!-- Kết quả: ⏳ Chờ duyệt -->
```

### 5. Lấy HTML Badge
```blade
@statusBadge('pending')
<!-- Kết quả: <span class="badge badge-warning">⏳ Chờ duyệt</span> -->
```

### 6. Sử Dụng Trong PHP Code
```php
use App\Helpers\StatusHelper;

// Cách 1: Sử dụng class trực tiếp
$text = StatusHelper::translate('pending', 'text');
$emoji = StatusHelper::translate('pending', 'emoji');
$all = StatusHelper::translate('pending', 'all');

// Cách 2: Sử dụng helper function
$text = getStatus('pending', 'text');
$badge = getStatusBadge('pending');
```

## Các Method Chính

### StatusHelper::translate($status, $format = 'text')
Lấy bản dịch của trạng thái
- `$format`: 'text', 'text_full', 'emoji', 'color', 'all'

```php
StatusHelper::translate('pending', 'text'); // "Chờ duyệt"
StatusHelper::translate('pending', 'emoji'); // "⏳"
StatusHelper::translate('pending', 'all'); // Mảng đầy đủ
```

### StatusHelper::getTextWithEmoji($status)
Lấy text + emoji kết hợp

```php
StatusHelper::getTextWithEmoji('pending'); // "⏳ Chờ duyệt"
```

### StatusHelper::getBadgeClass($status)
Lấy class CSS cho badge

```php
StatusHelper::getBadgeClass('pending'); // "badge-warning"
```

### StatusHelper::getBgClass($status)
Lấy class CSS cho background

```php
StatusHelper::getBgClass('pending'); // "status-pending"
```

### Kiểm Tra Trạng Thái
```php
StatusHelper::isPending($status);   // Kiểm tra xem có phải "chờ" không
StatusHelper::isCompleted($status); // Kiểm tra xem có phải "hoàn tất" không
StatusHelper::isCancelled($status); // Kiểm tra xem có phải "hủy" không
```

### Lấy Danh Sách Trạng Thái
```php
StatusHelper::getOrderStatuses();        // Danh sách trạng thái Order
StatusHelper::getReservationStatuses();  // Danh sách trạng thái Reservation
StatusHelper::getChefItemStatuses();     // Danh sách trạng thái item bếp
StatusHelper::getPaymentStatuses();      // Danh sách trạng thái thanh toán
```

## Ví Dụ Thực Tế

### Hiển Thị Trạng Thái Đơn Hàng
**Trước đây (không đồng bộ):**
```blade
@switch($order->status)
    @case('pending') ⏳ Chờ duyệt @break
    @case('confirmed') ✅ Đã xác nhận @break
    @case('completed') 🏁 Hoàn tất @break
    @default {{ $order->status }}
@endswitch
```

**Sau (đồng bộ hóa):**
```blade
@statusBadge($order->status)
```

### Lọc Trạng Thái
```blade
@if(StatusHelper::isPending($order->status))
    <p>Đơn hàng đang chờ xử lý</p>
@elseif(StatusHelper::isCompleted($order->status))
    <p>Đơn hàng đã hoàn tất</p>
@elseif(StatusHelper::isCancelled($order->status))
    <p>Đơn hàng đã bị hủy</p>
@endif
```

### Hiển Thị Trong JavaScript
```blade
<script>
    const statusText = "{{ StatusHelper::translate('pending', 'text') }}";
    const statusEmoji = "{{ StatusHelper::translate('pending', 'emoji') }}";
    console.log(statusEmoji + ' ' + statusText); // ⏳ Chờ duyệt
</script>
```

### Dropdown Trạng Thái
```blade
<select class="form-control">
    @foreach(StatusHelper::getOrderStatuses() as $key => $status)
        <option value="{{ $key }}">
            {{ $status['emoji'] }} {{ $status['text'] }}
        </option>
    @endforeach
</select>
```

## Cấu Trúc Dữ Liệu Trạng Thái

Mỗi trạng thái chứa:
```php
[
    'text' => 'Chờ duyệt',                    // Text ngắn
    'text_full' => 'Đơn hàng đang chờ duyệt', // Text đầy đủ
    'emoji' => '⏳',                          // Emoji
    'color' => 'warning',                    // Màu
    'bg_class' => 'status-pending',          // CSS class
    'badge_class' => 'badge-warning',        // Class badge
]
```

## Thêm Trạng Thái Mới

Để thêm trạng thái mới, chỉnh sửa `app/Helpers/StatusHelper.php`:

```php
const STATUS_TRANSLATIONS = [
    'new_status' => [
        'text' => 'Trạng thái mới',
        'text_full' => 'Mô tả đầy đủ',
        'emoji' => '🎉',
        'color' => 'info',
        'bg_class' => 'status-new_status',
        'badge_class' => 'badge-info',
    ],
    // ... trạng thái khác
];
```

## Chú Ý

1. **Tự động lowercase**: StatusHelper tự động chuyển đổi thành chữ thường trước khi kiếm kiếm
2. **Trim spaces**: Tự động loại bỏ khoảng trắng thừa
3. **Default value**: Nếu trạng thái không tồn tại, trả về trạng thái uppercase

## Sửa Lỗi

### "Class StatusHelper not found"
- Chạy: `composer dump-autoload`
- Kiểm tra HelperServiceProvider đã được đăng ký trong `config/app.php` không

### Blade macros không hoạt động
- Kiểm tra HelperServiceProvider đã được đăng ký
- Xóa cache: `php artisan view:clear`
- Chạy: `php artisan cache:clear`

## Lợi Ích

✅ Đồng bộ trạng thái trên toàn ứng dụng
✅ Dễ bảo trì và cập nhật
✅ Hỗ trợ đa ngôn ngữ (dễ mở rộng)
✅ Giảm code duplicate
✅ Nhất quán giữa các role (Admin, Staff, Customer)
