@extends('layouts.admin')

@section('page_title', 'Chi tiết hóa đơn')
@section('topbar_title', 'Chi tiết hóa đơn')

@section('admin_content')

<div class="bill-wrapper">

    {{-- HEADER --}}
    <div class="bill-header">
        <div>
            <h1>🧾 Hóa đơn #{{ $bill->id }}</h1>
            <p>Ngày thanh toán:
                {{ $bill->updated_at->format('d/m/Y H:i') }}
            </p>
        </div>

        <div class="status-badge">
            @php
    $statusText = match($bill->status) {
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'arrived' => 'Khách đã đến',
        'serving' => 'Đang phục vụ',
        'served' => 'Đã phục vụ',
        'paid_cash' => 'Đã thanh toán tiền mặt',
        'paid_transfer' => 'Đã chuyển khoản',
        'completed' => 'Hoàn tất',
        'cancelled' => 'Đã hủy',
        default => $bill->status
    };
@endphp

{{ $statusText }}
        </div>
    </div>

    {{-- INFO --}}
    <div class="info-grid">

        <div class="info-card">
            <h3>👤 Thông tin khách hàng</h3>

            <div class="info-item">
                <span>Họ tên</span>
                <strong>{{ $bill->user->name ?? '---' }}</strong>
            </div>

            <div class="info-item">
                <span>Email</span>
                <strong>{{ $bill->user->email ?? '---' }}</strong>
            </div>
        </div>

        <div class="info-card">
            <h3>🪑 Thông tin bàn</h3>

            <div class="info-item">
                <span>Số bàn</span>
                <strong>
                    {{ $bill->table->name ?? '---' }}
                </strong>
            </div>

            <div class="info-item">
                <span>Trạng thái</span>
               <strong>{{ $statusText }}</strong>
            </div>
        </div>

    </div>
{{-- MÓN ĂN --}}
<div class="food-card">

    <h3>🍽 Danh sách món ăn</h3>

    <table class="food-table">

        <thead>
            <tr>
                <th>Món ăn</th>
                <th>SL</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>

        <tbody>

            @forelse($bill->orderItems as $item)

            <tr>
                <td>{{ $item->menu->name ?? $item->product_name ?? '---' }}</td>

                <td>{{ $item->quantity }}</td>

                <td>
                    {{ number_format($item->price) }}đ
                </td>

                <td>
                    {{ number_format($item->quantity * $item->price) }}đ
                </td>
            </tr>

            @empty

            <tr>
                <td colspan="4" style="text-align:center;">
                    Không có món ăn
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>
    {{-- TỔNG TIỀN --}}
    <div class="total-card">

        <div>
            <p>Tổng thanh toán</p>
            <h2>{{ number_format($bill->total_price) }}đ</h2>
        </div>

        <div class="money-icon">
            💰
        </div>

    </div>

    {{-- ACTION --}}
    <div class="action-bar">

        <a href="{{ route('admin.bills.index') }}"
           class="btn-back">
            ← Quay lại danh sách
        </a>

        <button onclick="window.print()"
                class="btn-print">
            🖨️ In hóa đơn
        </button>

    </div>

</div>

<style>

.bill-wrapper{
    max-width:1000px;
    margin:auto;
}

/* HEADER */

.bill-header{
    background:#fff;
    border-radius:20px;
    padding:30px;
    margin-bottom:25px;

    display:flex;
    justify-content:space-between;
    align-items:center;

    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.bill-header h1{
    margin:0;
    font-size:32px;
    color:#1e293b;
}

.bill-header p{
    margin-top:8px;
    color:#64748b;
}

.status-badge{
    background:#10b981;
    color:white;
    padding:12px 22px;
    border-radius:999px;
    font-weight:700;
    letter-spacing:1px;
}

/* GRID */

.info-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:24px;
    margin-bottom:25px;
}

.info-card{
    background:#fff;
    border-radius:20px;
    padding:25px;

    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.info-card h3{
    margin-top:0;
    margin-bottom:20px;
    color:#1e293b;
}

.info-item{
    display:flex;
    justify-content:space-between;
    padding:14px 0;
    border-bottom:1px solid #f1f5f9;
}

.info-item span{
    color:#64748b;
}

/* TOTAL */

.total-card{
    background:linear-gradient(135deg,#0f172a,#1e293b);
    color:white;

    border-radius:24px;
    padding:35px;

    display:flex;
    justify-content:space-between;
    align-items:center;

    margin-bottom:25px;
}

.total-card p{
    margin:0;
    opacity:0.8;
}

.total-card h2{
    margin-top:10px;
    font-size:42px;
}

.money-icon{
    font-size:60px;
}

/* ACTION */

.action-bar{
    display:flex;
    gap:16px;
}

.btn-back,
.btn-print{
    border:none;
    padding:14px 24px;
    border-radius:14px;
    font-weight:700;
    cursor:pointer;
    text-decoration:none;
    transition:0.3s;
}

.btn-back{
    background:#e2e8f0;
    color:#1e293b;
}

.btn-back:hover{
    background:#cbd5e1;
}

.btn-print{
    background:#d4af37;
    color:white;
}

.btn-print:hover{
    background:#b89020;
}

/* RESPONSIVE */

@media(max-width:768px){

    .info-grid{
        grid-template-columns:1fr;
    }

    .bill-header{
        flex-direction:column;
        gap:20px;
        align-items:flex-start;
    }

    .total-card{
        flex-direction:column;
        align-items:flex-start;
        gap:20px;
    }

}
/* FOOD */

.food-card{
    background:#fff;
    border-radius:20px;
    padding:25px;
    margin-bottom:25px;

    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.food-card h3{
    margin-top:0;
    margin-bottom:20px;
}

.food-table{
    width:100%;
    border-collapse:collapse;
}

.food-table th,
.food-table td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:left;
}
</style>

@endsection