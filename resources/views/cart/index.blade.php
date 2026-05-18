@extends('shared')

@section('title', 'Thanh Toán Bàn Của Bạn')

@section('head')
<style>
    /* ===== CSS LUXURY THEME ĐỒNG BỘ ===== */
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    body { 
        background-color: #F9F8F6; /* Màu nền trắng kem */
        padding: 50px 0; 
        font-family: 'Plus Jakarta Sans', sans-serif; 
    }
    
    .order-container { 
        background: #ffffff; 
        padding: 45px 50px; 
        border-radius: 12px; 
        box-shadow: 0 15px 40px rgba(0,0,0,0.08); 
        border-top: 5px solid #D4AF37; /* Viền vàng đồng */
    }

    .page-title {
        font-family: 'Playfair Display', serif; 
        color: #1A2228; 
        font-size: 32px; 
        text-align: center; 
        margin-bottom: 30px; 
        font-weight: 700;
        letter-spacing: 1px;
    }
    .page-title::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background: #D4AF37;
        margin: 15px auto 0;
    }

    /* Bill Info Section */
    .bill-info { 
        background: #FCFBF8; 
        padding: 25px; 
        border-radius: 8px; 
        border-left: 4px solid #D4AF37; 
        margin-bottom: 30px; 
        color: #4A5568;
    }
    .bill-info p { margin-bottom: 8px; }
    .bill-info strong { color: #1A2228; font-weight: 600; }

    /* Table Order */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
    }
    .table-order { margin-bottom: 0; }
    .table-order thead { background-color: #1A2228; color: #FFF; }
    .table-order th { 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        font-size: 13px; 
        border-bottom: none !important;
        padding: 15px;
    }
    .table-order td { vertical-align: middle; padding: 15px; border-color: #E2E8F0; }
    
    .item-img-mini { 
        width: 60px; 
        height: 60px; 
        object-fit: cover; 
        border-radius: 8px; 
        margin-right: 15px; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* Quantity Controls */
    .qty-box {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #F7FAFC;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        padding: 3px 5px;
        width: fit-content;
        margin: 0 auto;
    }
    .qty-btn {
        width: 28px;
        height: 28px;
        border: none;
        background: transparent;
        color: #4A5568;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }
    .qty-btn:hover { color: #D4AF37; }
    .qty-input {
        width: 35px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 700;
        color: #1A2228;
    }

    /* Total Footer */
    .table-order tfoot th { background: #FCFBF8; font-family: 'Playfair Display', serif; }
    .total-amount-text { color: #D4AF37 !important; font-size: 1.6rem !important; font-weight: 700; }

    /* Buttons */
    .btn-checkout { 
        background-color: #D4AF37; 
        color: #FFF; 
        padding: 16px; 
        border: none; 
        border-radius: 8px; 
        width: 100%; 
        max-width: 400px;
        font-size: 16px; 
        font-weight: 700; 
        letter-spacing: 2px;
        text-transform: uppercase; 
        margin-top: 20px; 
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }
    .btn-checkout:hover { 
        background-color: #B5952F;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
    }

    /* Payment Methods */
    .payment-methods-box { 
        border: none; 
        padding: 30px; 
        border-radius: 12px; 
        background: #FAFAFA; 
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);
    }
    .payment-methods-box h5 {
        font-family: 'Playfair Display', serif;
        color: #1A2228;
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .payment-item { 
        display: flex; 
        align-items: center; 
        padding: 20px; 
        border-radius: 10px; 
        border: 2px solid #E2E8F0; 
        cursor: pointer; 
        transition: all 0.3s ease; 
        background: #FFF; 
        margin-bottom: 15px;
    }
    .payment-item input[type="radio"] {
        display: block !important;
        width: 22px;
        height: 22px;
        margin-right: 15px;
        cursor: pointer;
        accent-color: #D4AF37; 
    }
    .payment-item i { 
        font-size: 24px; 
        width: 40px; 
        text-align: center; 
        margin-right: 15px;
        color: #A0AEC0;
        transition: color 0.3s;
    }
    .payment-item h6 { margin-bottom: 4px; font-weight: 700; color: #1A2228; font-size: 16px;}
    .payment-item small { color: #718096; font-size: 13px;}

    .payment-item:hover { border-color: #D4AF37; transform: translateY(-2px); }
    .payment-item.active { 
        border-color: #D4AF37; 
        background: #FFF; 
        box-shadow: 0 5px 20px rgba(212, 175, 55, 0.1); 
    }
    .payment-item.active i { color: #D4AF37; }

    /* QR Area */
    #qr-inline-area { 
        display: none; 
        margin-top: 15px; 
        padding: 25px; 
        background: #FFF; 
        border: 2px dashed #E2E8F0; 
        border-radius: 10px; 
        text-align: center; 
        animation: slideDown 0.4s ease-out;
    }
    #qr-inline-area h6 { color: #1A2228; letter-spacing: 1px; }
    #qr-inline-area img { max-width: 220px; border: 1px solid #E2E8F0; padding: 10px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);}
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Modal Bill Cao Cấp */
    .premium-bill {
        background: #FFFDF8;
        border-radius: 4px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1A2228;
    }
    .bill-header-gold { padding: 25px 20px 10px; text-align: center; }
    .restaurant-logo-center { width: 70px; height: 70px; object-fit: cover; border-radius: 50%; margin-bottom: 10px; border: 2px solid #D4AF37; padding: 2px;}
    .bill-title-luxury {
        font-family: 'Playfair Display', serif;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-weight: 700;
        color: #1A2228;
        font-size: 1.3rem;
        margin-bottom: 2px;
    }
    .luxury-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #D4AF37, transparent);
        margin: 20px 0;
    }
    .table-premium { width: 100%; font-size: 13px; line-height: 1.4; }
    .table-premium th { font-weight: 600; text-transform: uppercase; color: #718096; border-bottom: 1px solid #E2E8F0; padding: 8px 0; }
    .table-premium td { padding: 8px 0; border-bottom: 1px dashed #E2E8F0; }
    
    .total-highlight {
        background: #1A2228;
        color: #FFF;
        padding: 15px;
        border-radius: 6px;
        margin-top: 15px;
        text-align: right;
    }
    .total-highlight .h5 { color: #D4AF37; font-family: 'Playfair Display', serif; font-size: 1.4rem;}

    .btn-luxury-pay {
        background-color: #D4AF37; 
        color: white;
        border: none;
        padding: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        width: 100%;
        border-radius: 6px;
        margin-top: 20px;
        transition: 0.3s;
    }
    .btn-luxury-pay:hover { background-color: #1A2228; color: #D4AF37; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

@section('content')

@php
    $bankID = 'MB'; 
    $accountNo = '0343970743'; 
    $accountName = 'Le Yen Nhi'; 
    $info = "Thanh toan " . ($reservation['table'] ?? '0'); 
    $infoEncoded = urlencode($info);
    $nameEncoded = urlencode($accountName);
    $qrUrl = "https://img.vietqr.io/image/{$bankID}-{$accountNo}-compact.png?amount={$total}&addInfo={$infoEncoded}&accountName={$nameEncoded}";
    $invoiceNumber = 'INV-' . strtoupper(substr(md5(now()->timestamp . auth()->id()), 0, 8));
    $currentDateTime = now()->format('H:i d/m/Y');
@endphp

<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px; border-left: 4px solid #e74c3c;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <div class="order-container">
        <h3 class="page-title">Hóa đơn của quý khách</h3>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="bill-info">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Mã phiếu:</strong> {{ $invoiceNumber }}</p>
                    <p><strong>Thời gian:</strong> {{ $currentDateTime }}</p>
                    <p><strong>Lịch đặt:</strong> {{ $reservation['time'] }} - {{ $reservation['date'] }}</p>
                    <p><strong>Khách hàng:</strong> {{ $reservation['name'] }}</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <p><strong>Vị trí:</strong> <span style="background: #1A2228; color: #D4AF37; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 13px;">{{ $reservation['table'] }}</span></p>
                    <p><strong>Trạng thái:</strong> 
                        <span class="font-weight-bold" style="color: #D4AF37;">
                            @if($reservation['status'] == 'preparing')
                                👨‍🍳 Đang phục vụ
                            @elseif($reservation['status'] == 'served')
                                🍽️ Đã lên món
                            @elseif($reservation['status'] == 'completed')
                                ✅ Hoàn tất
                            @else
                                ⏳ {{ $reservation['status'] }}
                            @endif
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="table-responsive mb-5">
            <table class="table table-order">
                <thead>
                    <tr>
                        <th width="40%">Chi tiết món ăn</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-right">Đơn giá</th>
                        <th class="text-right">Thành tiền</th>
                        <th class="text-center">Tùy chỉnh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cart as $item)
                        {{-- ẨN MÓN 0 ĐỒNG TẠI ĐÂY --}}
                        @if($item['quantity'] > 0)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset($item['image'] ?? 'images/default.jpg') }}" class="item-img-mini">
                                    <strong style="color: #1A2228;">{{ $item['name'] ?? 'Món không tồn tại' }}</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="qty-box">
                                    <button type="button" class="qty-btn" onclick="changeQty(this, -1)">-</button>
                                    <input type="text" class="qty-input" data-id="{{ $item['id'] }}" value="{{ $item['quantity'] }}" readonly data-price="{{ $item['price'] ?? 0 }}" data-stock="{{ \App\Models\Menu::find($item['id'])->stock ?? 0 }}">
                                    <button type="button" class="qty-btn" onclick="changeQty(this, 1)">+</button>
                                </div>
                            </td>
                            <td class="text-right" style="color: #718096;">{{ number_format($item['price'] ?? 0, 0, ',', '.') }}đ</td>
                            <td class="text-right font-weight-bold" style="color: #1A2228; font-size: 15px;">
                                {{ number_format(($item['price'] ?? 0) * $item['quantity'], 0, ',', '.') }}đ
                            </td>
                            <td class="text-center">
                                <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background: #FEE2E2; color: #EF4444; border: none; padding: 6px 10px; border-radius: 6px;" onclick="return confirm('Bạn có chắc chắn muốn bỏ món này?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr><td colspan="5" class="text-center py-4" style="color: #718096;">Danh sách thực đơn trống.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right" style="font-size: 15px; vertical-align: middle;">Tổng thanh toán:</th>
                        <th class="text-right total-amount-text">
                            {{ number_format($total, 0, ',', '.') }} VNĐ
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

@if(count($cart))
<form id="checkoutForm" action="{{ route('cart.checkout') }}" method="POST">
    @csrf
    <input type="hidden" name="total_price" value="{{ $total }}">
    <input type="hidden" name="table_number" value="{{ $reservation['table_id'] }}">
    <input type="hidden" name="order_notes" value="{{ $reservation['notes'] ?? '' }}">

    <div class="payment-methods-box mt-2">
        <h5>
            <i class="fas fa-gem mr-2" style="color: #D4AF37;"></i> Phương Thức Thanh Toán
        </h5>

        <div class="row">
            <div class="col-md-6">
                <label class="payment-item active" id="label-cod">
                    <input type="radio" name="payment_method" value="COD" checked>
                    <i class="fas fa-concierge-bell"></i>
                    <div>
                        <h6>Thanh Toán Tại Bàn</h6>
                        <small>Tiền mặt hoặc Quẹt thẻ qua POS</small>
                    </div>
                </label>
            </div>
            
            <div class="col-md-6">
                <label class="payment-item" id="label-bank">
                    <input type="radio" name="payment_method" value="BANK">
                    <!-- <i class="fas fa-qrcode"></i> -->
                    <div>
                        <h6>Chuyển Khoản Ngân Hàng</h6>
                        <small>Tiện lợi, an toàn qua mã QR</small>
                    </div>
                </label>
            </div>
        </div>

        <div id="qr-inline-area">
            <h6 class="font-weight-bold mb-3">MÃ QR THANH TOÁN TỰ ĐỘNG</h6>
            <img src="{{ $qrUrl }}" alt="QR Code Payment" class="img-fluid mb-3">
            <div style="background: #F7FAFC; padding: 12px; border-radius: 8px; display: inline-block; text-align: left;">
                <p class="small mb-1" style="color: #4A5568;">Chủ tài khoản: <strong style="color: #1A2228;">{{ $accountName }}</strong></p>
                <p class="small mb-0" style="color: #4A5568;">Nội dung CK: <strong style="color: #D4AF37;">{{ $info }}</strong></p>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <button type="button" class="btn-checkout" data-toggle="modal" data-target="#billModal">
            Xác nhận đặt bàn
        </button>
    </div>
</form> 
@else
<div style="background: #FCFBF8; border: 2px solid #E2E8F0; border-radius: 12px; padding: 40px 30px; text-align: center; margin: 40px 0;">
    <p style="font-size: 18px; color: #1A2228; margin-bottom: 10px; font-weight: 600;">✨ Chỉ Đặt Bàn (Không Có Thực Đơn)</p>
    <p style="color: #718096; margin-bottom: 25px;">Quý khách có thể thêm món ăn hoặc tiếp tục chỉ với đặt bàn.</p>
    <div class="row" style="gap: 15px; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('menu.index') }}" class="btn-checkout" style="width: auto; max-width: 300px; text-decoration: none; display: inline-block;">
            <i class="fas fa-plus-circle mr-2"></i> THÊM MÓN ĂN
        </a>
    </div>
</div>
@endif
    </div>
</div>

<div class="modal fade" id="billModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content premium-bill">
            <div class="modal-body p-4">
                <div class="bill-header-gold">
                    <img src="{{ asset('images/logo.png') }}" alt="Món Việt Logo" style="height: 115px; width: auto;">
                    <h5 class="bill-title-luxury">MÓN VIỆT</h5>
                    <p class="small text-muted mb-0" style="font-size: 12px;">Đỉnh Cao Ẩm Thực Truyền Thống</p>
                </div>
                
                <div class="luxury-divider"></div>
                
                <div class="mb-3" style="font-size: 13px; line-height: 1.6;">
                    <p class="mb-1"><span style="color: #718096; display: inline-block; width: 75px;">Số phiếu:</span> <strong>{{ $invoiceNumber }}</strong></p>
                    <p class="mb-1"><span style="color: #718096; display: inline-block; width: 75px;">Thời gian:</span> <strong>{{ $currentDateTime }}</strong></p>
                    <p class="mb-1"><span style="color: #718096; display: inline-block; width: 75px;">Phục vụ:</span> <strong style="color: #D4AF37;">{{ $reservation['table'] ?? '1' }}</strong></p>
                    <p class="mb-1"><span style="color: #718096; display: inline-block; width: 75px;">Thanh toán:</span> <strong id="bill-payment-method-text">Tại Bàn (Tiền mặt / POS)</strong></p>
                </div>

                <table class="table-premium mt-3">
                    <thead>
                        <tr>
                            <th class="text-left">Tên món</th>
                            <th class="text-center">SL</th>
                            <th class="text-right">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                            {{-- ẨN MÓN 0 ĐỒNG TRONG MODAL TẠI ĐÂY --}}
                            @if($item['quantity'] > 0)
                            <tr>
                                <td style="font-weight: 500;">{{ $item['name'] }}</td>
                                <td class="text-center">{{ $item['quantity'] }}</td>
                                <td class="text-right">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                
                <div class="total-highlight">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold" style="font-size: 14px; letter-spacing: 1px;">TỔNG HÓA ĐƠN</span>
                        <span class="h5 mb-0 font-weight-bold">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p style="font-size: 12px; font-style: italic; color: #718096;" class="mb-0">Trân trọng cảm ơn Quý khách!</p>
                </div>
                
                <button type="button" class="btn-luxury-pay" onclick="submitOrder()">XÁC NHẬN THANH TOÁN</button>
            </div>
        </div>
    </div>
</div>

<script>
  function changeQty(btn, value) {
    const row = btn.closest('tr');
    const input = row.querySelector('.qty-input');
    const stockAvailable = parseInt(input.dataset.stock);
    const menuId = input.dataset.id;
    
    let qty = parseInt(input.value);
    let newQty = qty + value;

    // Cho phép xuống 0 để xóa món
    if (newQty < 0) return; 

    // Nếu newQty = 0, hỏi khách có muốn xóa không
    if (newQty === 0) {
        if (confirm('Bạn có chắc chắn muốn bỏ món này khỏi thực đơn?')) {
            row.remove(); // Xóa dòng trên giao diện
            updateGrandTotal();
        } else {
            return;
        }
    } else {
        if (value > 0 && newQty > stockAvailable) {
            alert('Hết hàng! Còn ' + stockAvailable + ' phần.');
            return;
        }
        input.value = newQty;
        updateRowTotal(row);
        updateGrandTotal();
    }

    // Gửi AJAX về Controller để gọi hàm syncCustomerCartToStaff mà mình đã sửa lúc nãy
    fetch('{{ route("cart.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            id: menuId, 
            quantity: newQty // Server sẽ nhận số lượng (có thể là 0) để xóa món
        })
    })
    .then(response => response.json())
    .then(data => {
        if(!data.success) {
            alert("Có lỗi xảy ra, vui lòng thử lại!");
            location.reload(); // Reload nếu có lỗi để đồng bộ lại dữ liệu chuẩn
        }
    })
    .catch(error => console.error('Lỗi đồng bộ:', error));
}
    function updateRowTotal(row) {
        const qty = parseInt(row.querySelector('.qty-input').value);
        const price = parseInt(row.querySelector('.qty-input').dataset.price);
        row.querySelector('.font-weight-bold').innerText = new Intl.NumberFormat('vi-VN').format(qty * price) + 'đ';
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.qty-input').forEach(input => {
            total += parseInt(input.value) * parseInt(input.dataset.price);
        });
        document.querySelector('.total-amount-text').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' VNĐ';
        // ✅ Thêm dòng này — cập nhật hidden input để form submit đúng
    document.querySelector('input[name="total_price"]').value = total;
    }

    function submitOrder() {
        document.getElementById('checkoutForm').submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const qrArea = document.getElementById('qr-inline-area');
        const labelCod = document.getElementById('label-cod');
        const labelBank = document.getElementById('label-bank');
        const radios = document.querySelectorAll('input[name="payment_method"]');

        function updateUI(value) {
            const methodText = document.getElementById('bill-payment-method-text');
            if (value === 'BANK') {
                qrArea.style.display = 'block';
                labelBank.classList.add('active');
                labelCod.classList.remove('active');
                if (methodText) methodText.innerText = 'Chuyển Khoản QR';
            } else {
                qrArea.style.display = 'none';
                labelCod.classList.add('active');
                labelBank.classList.remove('active');
                if (methodText) methodText.innerText = 'Tại Bàn (Tiền mặt / POS)';
            }
        }

        radios.forEach(radio => {
            radio.addEventListener('change', function () {
                updateUI(this.value);
            });
        });
    });
</script>
@endsection