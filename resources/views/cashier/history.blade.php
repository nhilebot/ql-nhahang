@extends('layouts.admin')

@section('page_title', 'Lịch sử hóa đơn')
@section('topbar_title', 'Lịch sử hóa đơn')

@section('admin_content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-size:1.3rem;font-weight:800;color:#0f172a;margin:0;">🧾 Lịch sử hóa đơn</h2>
        <p style="font-size:.85rem;color:#64748b;margin:4px 0 0;">Tất cả giao dịch đã thanh toán</p>
    </div>
    <a href="{{ route('cashier.index') }}" class="btn btn-ghost">← Quay lại</a>
</div>

<div class="stats-row" style="grid-template-columns:1fr 1fr;max-width:500px;margin-bottom:24px;">
    <div class="stat-card">
        <div>
            <div class="stat-label">Doanh thu hôm nay</div>
            <div class="stat-value" style="font-size:1.3rem;color:#16a34a;">{{ number_format($revenueToday) }}đ</div>
        </div>
        <div class="stat-icon-wrap">📅</div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Tổng doanh thu</div>
            <div class="stat-value" style="font-size:1.3rem;color:#5b3cff;">{{ number_format($revenueTotal) }}đ</div>
        </div>
        <div class="stat-icon-wrap">💰</div>
    </div>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Bàn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức</th>
                    <th>Ngày thanh toán</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paidReservations as $res)
                <tr>
                    <td style="color:#94a3b8;font-size:.82rem;">{{ $res->id }}</td>
                    <td><span style="font-weight:600;">Bàn {{ $res->table_id }}</span></td>
                    <td>
                        <div style="font-weight:600;">{{ $res->full_name }}</div>
                        <div style="font-size:.82rem;color:#64748b;">{{ $res->phone }}</div>
                    </td>
                    <td style="font-weight:700;color:#16a34a;">{{ number_format($res->total_price ?? 0) }}đ</td>
                    <td>
                        @if($res->status === 'paid_cash')
                            <span class="badge">{{ \App\Helpers\StatusHelper::getTextWithEmoji('paid_cash') }}</span>
                        @elseif($res->status === 'paid_transfer')
                            <span class="badge">{{ \App\Helpers\StatusHelper::getTextWithEmoji('paid_transfer') }}</span>
                        @else
                            <span class="badge">{{ \App\Helpers\StatusHelper::getTextWithEmoji('completed') }}</span>
                        @endif
                    </td>
                    <td style="color:#64748b;font-size:.83rem;">{{ $res->updated_at->format('H:i – d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:#94a3b8;">Chưa có hóa đơn nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px;">{{ $paidReservations->links() }}</div>
</div>

@endsection
