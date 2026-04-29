@extends('shared')

@section('title', 'Restaurant - Reservation')

@section('head')
<style>
    /* ===== CSS LUXURY THEME ===== */
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap');

    body { 
        padding-top: 70px; 
        background-color: #F9F8F6; /* Màu nền trắng kem sang trọng */
        font-family: 'Plus Jakarta Sans', sans-serif;
    } 
    
    /* Header Banner */
    #reservation .featured.background_content {
        margin-top: -70px; 
        padding: 120px 0 80px 0;
        background-image: linear-gradient(rgba(26, 34, 40, 0.7), rgba(26, 34, 40, 0.7)), url('https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=2070&auto=format&fit=crop'); /* Hình nền mờ tối sang trọng */
        background-size: cover; 
        background-position: center;
        background-attachment: fixed;
    }
    #reservation .featured h1 {
        font-family: 'Playfair Display', serif;
        color: #fffefa; /* Màu vàng đồng (Gold) */
        font-size: 48px;
        text-transform: uppercase;
        letter-spacing: 3px;
        text-shadow: 2px 4px 10px rgba(0,0,0,0.3);
    }

    .reservation-section { background-color: transparent; padding-bottom: 80px; margin-top: -50px; }
    
    /* Form Container */
    .form-container-custom { 
        max-width: 900px; 
        margin: 0 auto; 
        padding: 45px 50px; 
        background: #ffffff; 
        border-radius: 12px; 
        box-shadow: 0 15px 40px rgba(0,0,0,0.08); 
        position: relative;
        border-top: 5px solid #D4AF37; /* Viền vàng đồng phía trên */
    }

    .form-title { 
        font-family: 'Playfair Display', serif; 
        color: #1A2228; 
        font-size: 32px; 
        text-align: center; 
        margin-bottom: 40px; 
        font-weight: 700;
        letter-spacing: 1px;
    }

    .form-title::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background: #D4AF37;
        margin: 15px auto 0;
    }

    /* Inputs */
    .label-custom { 
        font-weight: 600; 
        color: #4A5568; 
        margin-top: 20px; 
        display: block; 
        margin-bottom: 8px; 
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .input-custom { 
        height: 50px !important; 
        border: 1px solid #E2E8F0 !important; 
        border-radius: 8px !important; 
        background-color: #FDFDFD;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    .input-custom:focus {
        border-color: #D4AF37 !important;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15) !important;
        background-color: #FFF;
    }

    /* Table Selection Grid */
    .table-selection-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); 
        gap: 15px; 
        margin-bottom: 10px; 
    }
    .table-item input[type="radio"] { display: none; }
    .table-item label { 
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 80px;
        border: 1px solid #E2E8F0; 
        border-radius: 8px; 
        cursor: pointer; 
        transition: all 0.3s ease; 
        font-weight: 600; 
        color: #1A2228; 
        background: #FFF;
    }
    .table-item label small { margin-top: 5px; font-weight: 500; }
    
    /* Hover & Checked state for tables */
    .table-item label:hover { 
        border-color: #D4AF37; 
        transform: translateY(-3px); 
        box-shadow: 0 8px 15px rgba(212, 175, 55, 0.1); 
    }
    .table-item input[type="radio"]:checked + label { 
        border-color: #1A2228; 
        background-color: #1A2228; 
        color: #D4AF37; 
    }
    .table-item input[type="radio"]:checked + label small { color: #FFF !important; }

    /* Disabled table state */
    input[disabled] + label {
        background-color: #F7FAFC !important;
        color: #A0AEC0 !important;
        cursor: not-allowed !important;
        border: 1px dashed #CBD5E0 !important;
        transform: none !important;
        box-shadow: none !important;
    }
    input[disabled] + label small { color: #A0AEC0 !important; }

    /* Buttons */
    .btn-food-select { 
        background: #FFF; 
        color: #1A2228; 
        border: 2px solid #1A2228; 
        width: 100%; 
        padding: 15px; 
        margin: 10px 0 20px 0; 
        font-weight: 600; 
        border-radius: 8px; 
        transition: all 0.3s ease;
        letter-spacing: 1px;
    }
    .btn-food-select:hover {
        background: #1A2228;
        color: #D4AF37;
    }

    .btn-reserve { 
        background-color: #D4AF37; 
        color: #FFF; 
        padding: 16px; 
        border: none; 
        border-radius: 8px; 
        width: 100%; 
        font-size: 16px; 
        font-weight: 700; 
        letter-spacing: 2px;
        text-transform: uppercase; 
        margin-top: 30px; 
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }
    .btn-reserve:hover {
        background-color: #B5952F;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
    }

    /* Cart & Items */
    .cart-wrapper { 
        background: #FAFAFA; 
        padding: 25px; 
        border-radius: 8px; 
        border: 1px solid #EAEAEA; 
        margin-bottom: 25px; 
    }
    
    /* Search & Modal */
    .search-input { 
        width: 100%; 
        padding: 12px 25px; 
        border: 1px solid #E2E8F0; 
        border-radius: 30px; 
        outline: none; 
        background: #F7FAFC;
        transition: all 0.3s;
    }
    .search-input:focus { border-color: #D4AF37; background: #FFF; }
    
    .modal-content { border-radius: 12px; border: none; overflow: hidden; }
    .modal-header { background: #1A2228; color: #fffffd; border-bottom: none; padding: 20px 25px; }
    .modal-title { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 600; }
    .modal-header .close { color: #FFF; opacity: 0.8; text-shadow: none; }
    .modal-header .close:hover { color: #D4AF37; opacity: 1; }
    .btn-finish-modal { background: #D4AF37; border: none; color: #FFF; font-weight: 600; padding: 10px 25px; border-radius: 6px; }
    .btn-finish-modal:hover { background: #1A2228; color: #D4AF37; }

    /* Category Divider in Modal */
    .category-divider { 
        background: transparent; 
        padding: 10px 0; 
        margin: 30px 0 15px 0; 
        border-bottom: 2px solid #E2E8F0; 
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        color: #1A2228; 
    }
</style>
@endsection

@section('content')
<section id="reservation" class="description_content">
        <div class="featured background_content">
            <h1 class="text-center">Reserve Your Experience</h1>
        </div>

        <div class="reservation-section">
            <div class="container">
                <div class="form-container-custom">
                    <h2 class="form-title">Yêu cầu đặt bàn</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px; border-left: 4px solid #e74c3c;">
                            <strong>❌ Lỗi xác thực:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px; border-left: 4px solid #e74c3c;">
                            <strong>❌ Lỗi:</strong> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                
                    <form id="reservation-form" method="post" action="{{ route('reservation.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="label-custom">Ngày đặt bàn</label>
                                <input type="date" name="reservation_date" class="form-control input-custom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="label-custom">Giờ đến</label>
                                <input type="time" name="reservation_time" class="form-control input-custom" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="label-custom">Họ và Tên</label>
                                <input type="text" name="full_name" class="form-control input-custom" placeholder="Nhập tên của bạn" required>
                            </div>
                            <div class="col-md-6">
                                <label class="label-custom">Số điện thoại</label>
                                <input type="tel" name="phone" class="form-control input-custom" placeholder="Nhập số điện thoại liên lạc" required>
                            </div>
                        </div>

                        <label class="label-custom">Lựa Chọn Vị Trí Bàn</label>
 <div class="table-selection-grid">
    @foreach($tables as $table)
        <div class="table-item" id="table-container-{{ $table->id }}">
            {{-- Khóa bàn nếu status không phải là 'empty' --}}
            <input type="radio" name="table_id" value="{{ $table->id }}" id="table{{ $table->id }}" 
                   {{ $table->status !== 'empty' ? 'disabled' : '' }}>
            
            <label for="table{{ $table->id }}" id="label-{{ $table->id }}">
                <strong>{{ $table->name }}</strong>
                
                <div id="status-text-{{ $table->id }}">
                    @if($table->status === 'empty')
                        <small style="color:#D4AF37;">(Sẵn sàng)</small>
                    @elseif($table->status === 'reserved')
                        <small style="color:#e74c3c;">(Đã đặt)</small>
                    @elseif($table->status === 'cleaning')
                        {{-- Hiển thị đếm ngược nếu đang dọn dẹp --}}
                        <small style="color:#718096;">
                            🧹 Dọn dẹp (<span class="cleaning-timer" 
                                              data-start="{{ $table->cleanup_started_at }}" 
                                              data-id="{{ $table->id }}">60</span>s)
                        </small>
                    @else
                        <small style="color:#3498db;">(Đang phục vụ)</small>
                    @endif
                </div>
            </label>
        </div>
    @endforeach
</div>
                        
                        <div class="mt-3 p-3 rounded" style="background: #FCFBF8; border-left: 4px solid #D4AF37;">
                            <small class="text-muted" style="font-size: 13px;">
                                <i class="fa fa-info-circle" style="color: #D4AF37;"></i> 
                                Bàn trạng thái <strong>(Đã đặt)</strong> bao gồm bàn đang phục vụ hoặc đang trong thời gian <strong>chuẩn bị không gian (15 phút)</strong> để mang lại trải nghiệm hoàn hảo nhất cho quý khách.
                            </small>
                        </div>

                        <label class="label-custom">Thực Đơn Đặt Trước (Tùy Chọn)</label>
                        <button type="button" class="btn-food-select" id="openFoodModalBtn">
                            <i class="fa fa-cutlery" style="margin-right: 8px;"></i> KHÁM PHÁ & CHỌN MÓN ĂN
                        </button>

                        <div id="cart-container" class="cart-wrapper" style="display: none;">
                            <h5 style="margin-top: 0; color: #1A2228; font-family: 'Playfair Display', serif; font-weight: bold; border-bottom: 1px solid #EAEAEA; padding-bottom: 10px;">Thực Đơn Của Bạn:</h5>
                            <div id="cart-list"></div>
                            <div class="cart-total text-right" style="margin-top: 20px; border-top: 1px solid #EAEAEA; padding-top: 15px;">
                                <strong style="font-size: 18px; color: #1A2228;">Tổng hóa đơn: <span id="total-price" style="color: #D4AF37; font-size: 22px;">0</span> VNĐ</strong>
                            </div>
                        </div>

                        <div id="hidden-inputs-container"></div>

                        <label class="label-custom">Ghi Chú Yêu Cầu Đặc Biệt</label>
                        <textarea name="notes" class="form-control input-custom" rows="3" placeholder="Ví dụ: Dị ứng đậu phộng, cần chuẩn bị hoa hồng, kỷ niệm sinh nhật..." style="height: auto !important; padding: 15px;"></textarea>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-reserve">
                                HOÀN TẤT ĐẶT BÀN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div id="foodMenuModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thực Đơn Tinh Hoa</h4>
                </div>
                <div class="modal-body" style="max-height: 65vh; overflow-y: auto; background: #FAFAFA;">
                    <div class="search-container" style="position: sticky; top: -15px; background: #FAFAFA; padding: 15px 0; z-index: 10;">
                        <input type="text" id="menuSearch" class="search-input" placeholder="Tìm kiếm món ăn yêu thích..." onkeyup="filterMenu()">
                    </div>
                    <div id="menu-list"></div>
                </div>
                <div class="modal-footer" style="background: #FAFAFA; border-top: 1px solid #EAEAEA;">
                    <button type="button" class="btn btn-finish-modal" data-dismiss="modal">Hoàn Tất Lựa Chọn</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // TẤT CẢ LOGIC GIỮ NGUYÊN 100% NHƯ CŨ
        const menuItems = @json($menus ?? []);
        let cart = @json($cart ?? []);
        window.cart = cart;
        const formatCurrency = (num) => new Intl.NumberFormat('vi-VN').format(num);
        const getImageUrl = (image) => {
            if (!image) return 'https://via.placeholder.com/200x160?text=No+Image';
            if (typeof image === 'string' && (image.startsWith('http://') || image.startsWith('https://') || image.startsWith('//'))) {
                return image;
            }
            return `{{ asset('') }}${image}`;
        };

        console.log('=== RESERVATION PAGE LOADED ===');

        $(document).ready(function () {
            renderMenu();
            updateCartUI();
            
            $('#foodMenuModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: false
            });
            
            $('#openFoodModalBtn').on('click', function(e) {
                e.preventDefault();
                console.log('Opening food modal...');
                $('#foodMenuModal').modal('show');
            });
        });

        function renderMenu(filter = '') {
            const listElement = document.getElementById('menu-list');
            if (!listElement) return;
            
            if (!menuItems || menuItems.length === 0) {
                listElement.innerHTML = '<div style="color: #e74c3c; padding: 20px; text-align: center;">Thực đơn đang được cập nhật. Vui lòng quay lại sau!</div>';
                return;
            }

            const categoryNames = {
                'seafood': 'Hải Sản Cao Cấp',
                'special': 'Món Đặc Biệt Bếp Trưởng',
                'salad': 'Salad & Khai Vị',
                'Món khác': 'Các Tuyệt Phẩm Khác'
            };

            const groups = menuItems.reduce((acc, item) => {
                const rawCat = item.category || 'Món khác';
                const cat = categoryNames[rawCat] || rawCat; 
                
                if (!acc[cat]) acc[cat] = [];
                acc[cat].push(item);
                return acc;
            }, {});

            let html = '';
            for (const category in groups) {
                const filteredItems = groups[category].filter(item => 
                    item.name.toLowerCase().includes(filter.toLowerCase())
                );

                if (filteredItems.length > 0) {
                    html += `<div class="category-divider">${category}</div><div class="menu-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">`;
                    
                    filteredItems.forEach(item => {
                        const isOutOfStock = item.stock <= 0;
                        const imagePath = getImageUrl(item.image);
                        
                        // Đã thay đổi nhẹ inline-CSS ở đây để thẻ món ăn khớp với phong cách Luxury
                        html += `
                            <div class="menu-card" style="border: none; border-radius: 12px; background: #FFF; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; transition: transform 0.3s;">
                                <img src="${imagePath}" 
                                     alt="${item.name}"
                                     style="width:100%; height:160px; object-fit:cover;"
                                     onerror="this.src='https://via.placeholder.com/200x160?text=Error+Image'">
                                <div class="card-body" style="padding: 15px;">
                                    <h5 style="font-size:15px; font-weight: 600; margin: 0 0 8px 0; height: 38px; overflow: hidden; color: #1A2228;">${item.name}</h5>
                                    <p style="color:#D4AF37; font-weight:bold; margin-bottom: 12px; font-size: 16px;">${formatCurrency(item.price)}đ</p>
                                    <button type="button" onclick="addToCart(${item.id})" ${isOutOfStock ? 'disabled' : ''} 
                                            style="width:100%; background:${isOutOfStock ? '#E2E8F0' : '#1A2228'}; color:${isOutOfStock ? '#A0AEC0' : '#FFF'}; border:none; padding:10px; border-radius:6px; font-weight: 600; transition: background 0.3s;">
                                        ${isOutOfStock ? 'Tạm Hết' : 'Thêm vào bàn'}
                                    </button>
                                </div>
                            </div>`;
                    });
                    html += `</div>`;
                }
            }
            listElement.innerHTML = html || '<p style="text-align: center; padding: 20px; color: #718096;">Không tìm thấy món ăn phù hợp.</p>';
        }

        function addToCart(id) {
            const item = menuItems.find(i => i.id === id);
            if (!item) return;

            fetch("{{ route('reservation.addToCart') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ 
                food_id: item.id,   
                quantity: 1         
            })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    if (data.cartData) cart = data.cartData;
                    updateCartUI();
                    
                    if(data.cartCount !== "Đã thêm vào thực đơn") {
                        $('.badge').text(data.cartCount);
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã thêm món!',
                        text: `${item.name} đã được chọn`,
                        showConfirmButton: false,
                        timer: 1500,
                        position: 'top-end',
                        toast: true,
                        iconColor: '#D4AF37'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: data.message || 'Không thể thêm vào giỏ hàng',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            })
            .catch(err => {
                console.error('Lỗi AJAX:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối!',
                    text: 'Không thể kết nối đến máy chủ',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        }

     function updateQuantity(id, change) {
    const index = cart.findIndex(i => i.id === id);
    const item = menuItems.find(i => i.id === id);
    
    if (index === -1 || !item) return;

    const newQty = cart[index].quantity + change;

    // 1. Xử lý khi số lượng giảm về 0
    if (newQty <= 0) {
        // Gửi AJAX xóa món khỏi DB trước khi xóa trong mảng JS (tùy chọn)
        // Nếu không, khi load lại trang nó vẫn hiện 1 món
        cart.splice(index, 1);
    } 
    // 2. Kiểm tra tồn kho
    else if (newQty > item.stock) {
        Swal.fire({
            icon: 'error',
            title: 'Hết hàng!',
            text: `Rất tiếc, nhà hàng hiện chỉ còn ${item.stock} phần cho món ${item.name}.`,
            confirmButtonColor: '#1A2228',
            iconColor: '#e74c3c'
        });
        return; 
    } 
    // 3. Giới hạn số lượng (ví dụ 50)
    else if (newQty > 50) { 
        Swal.fire({
            icon: 'warning',
            title: 'Số lượng quá lớn',
            text: 'Với đơn hàng trên 50 phần, vui lòng liên hệ hotline!',
            confirmButtonColor: '#D4AF37'
        });
        return;
    }
    else {
        // CẬP NHẬT TRONG MẢNG JAVASCRIPT
        cart[index].quantity = newQty;
    }

    // --- ĐÂY LÀ NƠI THỰC THI CẬP NHẬT ---

    // 1. Cập nhật giao diện ngay lập tức
    updateCartUI();

    // 2. GỬI AJAX ĐỒNG BỘ XUỐNG DATABASE NGAY LẬP TỨC
    // Lưu ý: Route của bạn ở Controller là reservation.addToCart
    fetch("{{ route('reservation.addToCart') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ 
            food_id: id, 
            quantity: newQty, // Gửi số lượng mới (ví dụ: 3)
            is_update: true  // Flag quan trọng để Controller gán thẳng số 3 vào DB
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Đã đồng bộ số lượng mới vào DB:", data);
    })
    .catch(err => {
        console.error("Lỗi đồng bộ:", err);
    });    
    updateCartUI();
}
        function updateCartUI() {
            const container = document.getElementById('cart-container');
            const list = document.getElementById('cart-list');
            const hidden = document.getElementById('hidden-inputs-container');
            const totalPriceElement = document.getElementById('total-price');
            
            if (!container || !list || !hidden) return; 

            let total = 0, cartHtml = '', inputsHtml = '';

            cart.forEach(item => {
                total += item.price * item.quantity;
                
                // Đã chỉnh sửa style hiển thị trong giỏ hàng để sang trọng hơn
                cartHtml += `
                    <div class="cart-item" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; padding-bottom:12px; border-bottom:1px solid #EAEAEA;">
                        <div style="display:flex; align-items:center; gap:15px;">
                            <img src="${getImageUrl(item.image)}" style="width:50px; height:50px; border-radius:6px; object-fit: cover;" alt="${item.name}">
                            <div>
                                <span style="font-weight: 600; color: #1A2228; display: block;">${item.name}</span>
                                <span style="font-size: 13px; color: #D4AF37;">${formatCurrency(item.price)}đ</span>
                            </div>
                        </div>
                        <div style="display:flex; align-items:center; background: #F7FAFC; border-radius: 20px; padding: 2px 5px; border: 1px solid #E2E8F0;">
                            <button type="button" onclick="updateQuantity(${item.id}, -1)" style="border:none; background:transparent; font-size:18px; color:#4A5568; cursor:pointer; width: 25px; outline:none;">-</button>
                            <span style="padding:0 10px; font-weight: 600; min-width: 20px; text-align: center;">${item.quantity}</span>
                            <button type="button" onclick="updateQuantity(${item.id}, 1)" style="border:none; background:transparent; font-size:18px; color:#4A5568; cursor:pointer; width: 25px; outline:none;">+</button>
                        </div>
                    </div>`;

                inputsHtml += `
                    <input type="hidden" name="cart[${item.id}][id]" value="${item.id}">
                    <input type="hidden" name="cart[${item.id}][name]" value="${item.name}">
                    <input type="hidden" name="cart[${item.id}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="cart[${item.id}][price]" value="${item.price}">
                    <input type="hidden" name="cart[${item.id}][image]" value="${item.image}">`;
            });

            container.style.display = cart.length > 0 ? 'block' : 'none';
            list.innerHTML = cartHtml;
            hidden.innerHTML = inputsHtml;

            if (totalPriceElement) {
                totalPriceElement.innerText = formatCurrency(total);
            }
        }

        function filterMenu() { 
            renderMenu(document.getElementById('menuSearch').value); 
        }

        $(document).ready(function () {
            renderMenu();
            updateCartUI();
            
            $('#foodMenuModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: false
            });
            
            $('#openFoodModalBtn').on('click', function(e) {
                e.preventDefault();
                $('#foodMenuModal').modal('show');
            });

            $('#reservation-form').on('submit', function(e) {
                e.preventDefault(); 
                
                if (!$('input[name="table_id"]:checked').val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa Chọn Bàn',
                        text: 'Quý khách vui lòng chọn vị trí bàn mong muốn trước khi hoàn tất.',
                        confirmButtonColor: '#1A2228',
                        iconColor: '#D4AF37'
                    });
                    return;
                }

                const form = this; // ✅ LƯU LẠI FORM ELEMENT
                
                // ✅ KIỂM TRA DỮ LIỆU TRƯỚC KHI GỬI
                console.log('=== Form Submission Debug ===');
                console.log('Form name:', $('input[name="full_name"]').val());
                console.log('Form table_id:', $('input[name="table_id"]:checked').val());
                console.log('Form date:', $('input[name="reservation_date"]').val());
                console.log('Form time:', $('input[name="reservation_time"]').val());
                console.log('Hidden inputs:', $('#hidden-inputs-container').html());
                
                Swal.fire({
                    icon: 'success',
                    title: 'Đặt Bàn Thành Công!',
                    text: 'Yêu cầu của quý khách đã được ghi nhận. Nhà hàng đang chuẩn bị chu đáo nhất...',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    iconColor: '#D4AF37'
                }).then(() => {
                    form.submit(); // ✅ SỬ DỤNG FORM ĐÃ LƯU
                });
            });
        });
    </script>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timers = document.querySelectorAll('.cleaning-timer');

        timers.forEach(timer => {
            const tableId = timer.getAttribute('data-id');
            const startTimeStr = timer.getAttribute('data-start');
            if (!startTimeStr) return;

            // Chuyển thời gian từ DB sang JavaScript Timestamp
            const startTime = new Date(startTimeStr).getTime();
            const duration = 60 * 1000; // 60 giây (1 phút)

            const updateCountdown = setInterval(function() {
                const now = new Date().getTime();
                const distance = now - startTime;
                const secondsLeft = Math.ceil((duration - distance) / 1000);

                if (secondsLeft <= 0) {
                    // Hết thời gian: Tự động mở khóa
                    clearInterval(updateCountdown);
                    
                    // 1. Cập nhật chữ hiển thị
                    document.getElementById('status-text-' + tableId).innerHTML = '<small style="color:#D4AF37;">(Sẵn sàng)</small>';
                    
                    // 2. Mở khóa nút bấm (Radio)
                    const input = document.getElementById('table' + tableId);
                    if (input) {
                        input.disabled = false;
                    }

                    // 3. Reset lại style label (bỏ trạng thái mờ của disabled)
                    const label = document.getElementById('label-' + tableId);
                    if (label) {
                        label.style.cursor = 'pointer';
                    }
                } else {
                    timer.innerText = secondsLeft;
                }
            }, 1000);
        });
    });
</script>