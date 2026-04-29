# 🎯 Quick Reference - StatusHelper

## Blade Usage

```blade
<!-- HTML Badge -->
@statusBadge('pending')

<!-- Text + Emoji -->
@statusIcon('pending')

<!-- Text only -->
@status('pending')

<!-- Full Description -->
@statusFull('pending')

<!-- Emoji only -->
@statusEmoji('pending')
```

## PHP Usage

```php
use App\Helpers\StatusHelper;

// Translate
StatusHelper::translate('pending', 'text')      // "Chờ duyệt"
StatusHelper::translate('pending', 'emoji')     // "⏳"
StatusHelper::translate('pending', 'all')       // Array

// Get Display
StatusHelper::getTextWithEmoji('pending')       // "⏳ Chờ duyệt"
StatusHelper::getBadgeClass('pending')          // "badge-warning"
StatusHelper::getBgClass('pending')             // "status-pending"

// Check Status
StatusHelper::isPending($status)                // true/false
StatusHelper::isCompleted($status)              // true/false
StatusHelper::isCancelled($status)              // true/false

// Get Lists
StatusHelper::getOrderStatuses()                // Array
StatusHelper::getReservationStatuses()          // Array
StatusHelper::getPaymentStatuses()              // Array
StatusHelper::getChefItemStatuses()             // Array
```

## Status Chart

### Order
| Status | Display | Emoji |
|--------|---------|-------|
| pending | Chờ duyệt | ⏳ |
| confirmed | Đã xác nhận | ✅ |
| processing | Đang xử lý | ⚙️ |
| ready | Chờ phục vụ | 🍽️ |
| served | Đã lên món | 😋 |
| completed | Hoàn tất | 🏁 |
| cancelled | Đã hủy | ❌ |

### Reservation
| Status | Display | Emoji |
|--------|---------|-------|
| pending | Chờ duyệt | ⏳ |
| confirmed | Đã xác nhận | ✅ |
| arrived | Khách đã đến | 👤 |
| preparing | Đang chuẩn bị | 🍳 |
| serving | Đang phục vụ | 👨‍🍳 |
| served | Đã lên món | 😋 |
| completed | Hoàn tất | 🏁 |
| cleaning | Đang dọn dẹp | 🧹 |
| cancelled | Đã hủy | ❌ |

### Payment
| Status | Display | Emoji |
|--------|---------|-------|
| paid_cash | Tiền mặt | 💵 |
| paid_transfer | Chuyển khoản | 📱 |
| paid | Đã thanh toán | 💳 |

### Chef Items
| Status | Display | Emoji |
|--------|---------|-------|
| pending | Chờ | ⏳ |
| cooking | Đang nấu | 🔥 |
| done | Đã xong | ✓ |

## Examples

### Order History Page
```blade
@foreach($orders as $order)
    <div class="status-badge {{ \App\Helpers\StatusHelper::getBgClass($order->status) }}">
        {{ \App\Helpers\StatusHelper::getTextWithEmoji($order->status) }}
    </div>
@endforeach
```

### Dropdown Filter
```blade
<select name="status">
    @foreach(\App\Helpers\StatusHelper::getOrderStatuses() as $key => $status)
        <option value="{{ $key }}">{{ $status['emoji'] }} {{ $status['text'] }}</option>
    @endforeach
</select>
```

### Controller Logic
```php
if (StatusHelper::isPending($order->status)) {
    $order->status = 'confirmed';
    $order->save();
}
```

### Conditional View
```blade
@if(StatusHelper::isCompleted($order->status))
    <p style="color: green;">✓ Completed</p>
@elseif(StatusHelper::isCancelled($order->status))
    <p style="color: red;">✗ Cancelled</p>
@else
    <p style="color: orange;">⏳ Pending</p>
@endif
```

## Files

| File | Purpose |
|------|---------|
| `app/Helpers/StatusHelper.php` | Main helper class |
| `app/Providers/HelperServiceProvider.php` | Register macros |
| `STATUS_HELPER_GUIDE.md` | Full documentation |
| `README_STATUS_SYNC.md` | Setup guide |
| `CHANGELOG_STATUS_SYNC.md` | Changes summary |

## Setup

```bash
# Clear cache
php artisan cache:clear
php artisan view:clear

# Verify
php artisan tinker
>>> StatusHelper::translate('pending', 'text')
```

## Troubleshooting

```bash
# Composer autoload
composer dump-autoload

# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Test
php check_status_consistency.php
```

---

**Want full docs?** See `STATUS_HELPER_GUIDE.md`
