@extends('layouts.admin')

@section('page_title', 'Thu ngân')
@section('topbar_title', 'Thu ngân – Thanh toán')

@section('admin_content')

{{-- Thống kê nhanh --}}
<div class="stats-row">
    <div class="stat-card">
        <div>
            <div class="stat-label">Chờ thanh toán</div>
            <div class="stat-value" style="color:#d97706;">{{ $countPending }}</div>
        </div>
        <div class="stat-icon-wrap">⏳</div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Đã thu hôm nay</div>
            <div class="stat-value" style="color:#16a34a;">{{ $countPaid }}</div>
        </div>
        <div class="stat-icon-wrap">✅</div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Doanh thu hôm nay</div>
            <div class="stat-value" style="color:#5b3cff;">{{ number_format($revenueToday) }}đ</div>
        </div>
        <div class="stat-icon-wrap">💰</div>
    </div>
</div>

{{-- Bảng chờ thanh toán --}}
<div class="panel">
    <div class="panel-header">
        <div class="panel-title">💳 Đơn đang chờ thanh toán</div>
        <span style="font-size:.82rem;color:#64748b;">{{ now()->format('H:i – d/m/Y') }}</span>
    </div>

    @if($pendingPayment->isEmpty())
        <div style="text-align:center;padding:48px;color:#94a3b8;">
            <div style="font-size:2.5rem;margin-bottom:10px;">🎉</div>
            <div style="font-weight:600;">Không có đơn nào chờ thanh toán</div>
        </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Bàn số</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th style="text-align:center;">Thanh toán</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingPayment as $res)
                <tr>
                    <td>
                        <span style="background:#eff6ff;color:#1d4ed8;padding:4px 12px;border-radius:20px;font-weight:700;font-size:.9rem;">
                            Bàn {{ $res->table_id }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $res->full_name }}</div>
                        <div style="font-size:.82rem;color:#64748b;">{{ $res->phone }}</div>
                    </td>
                    <td>
                        <span style="font-size:1.1rem;font-weight:800;color:#16a34a;">
                            {{ number_format($res->total_price ?? 0) }}đ
                        </span>
                    </td>
                    <td>
                        @switch($res->status)
                            @case('arrived')  <span class="badge">{{ \App\Helpers\StatusHelper::getTextWithEmoji('arrived') }}</span> @break
                            @case('serving')  <span class="badge">{{ \App\Helpers\StatusHelper::getTextWithEmoji('serving') }}</span> @break
                            @case('served')   <span class="badge">{{ \App\Helpers\StatusHelper::getTextWithEmoji('served') }}</span> @break
                        @endswitch
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
                            {{-- Thanh toán tiền mặt --}}
                            <form action="{{ route('cashier.payment', $res->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="paid_cash">
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Xác nhận thanh toán TIỀN MẶT cho bàn {{ $res->table_id }}?')">
                                    💵 Tiền mặt
                                </button>
                            </form>
                            {{-- Thanh toán chuyển khoản --}}
                            <form action="{{ route('cashier.payment', $res->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="paid_transfer">
                                <button type="submit" class="btn btn-primary btn-sm"
                                    onclick="return confirm('Xác nhận thanh toán CHUYỂN KHOẢN cho bàn {{ $res->table_id }}?')">
                                    📱 Chuyển khoản
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Đã thanh toán hôm nay --}}
@if($paidToday->isNotEmpty())
<div class="panel" style="margin-top:20px;">
    <div class="panel-header">
        <div class="panel-title">✅ Đã thanh toán hôm nay ({{ $paidToday->count() }} đơn)</div>
        <a href="{{ route('cashier.history') }}" class="btn btn-ghost btn-sm">Xem lịch sử →</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Bàn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức</th>
                    <th>Thời gian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paidToday->take(10) as $res)
                <tr>
                    <td><span style="font-weight:600;">Bàn {{ $res->table_id }}</span></td>
                    <td>{{ $res->full_name }}</td>
                    <td style="font-weight:700;color:#16a34a;">{{ number_format($res->total_price ?? 0) }}đ</td>
                    <td>
                        @if($res->status === 'paid_cash')
                            <span class="badge">{{ \App\Helpers\StatusHelper::getTextWithEmoji('paid_cash') }}</span>
                        @else
                            <span class="badge">{{ \App\Helpers\StatusHelper::getTextWithEmoji('paid_transfer') }}</span>
                        @endif
                    </td>
                    <td style="color:#64748b;font-size:.83rem;">{{ $res->updated_at->format('H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
