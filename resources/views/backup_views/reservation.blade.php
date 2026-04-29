<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant - Reservation</title>
    
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-portfolio.css') }}">
    <link rel="stylesheet" href="{{ asset('css/picto-foundry-food.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>

    <style>
        /* ===== GIỮ NGUYÊN TOÀN BỘ CSS CỦA BẠN ===== */
        .dropdown-custom { position: relative; }
        .dropdown-custom .dropdown-menu {
            position: absolute; top: 100%; left: 0; background: #e9e4dd; 
            border-radius: 14px; padding: 12px 0; min-width: 240px; border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15); opacity: 0; visibility: hidden;
            transform: translateY(10px); transition: 0.3s; z-index: 999;
        }
        .dropdown-custom .dropdown-menu li a {
            display: block; padding: 10px 20px; color: #2c5c7c !important; 
            font-weight: 500; border-radius: 8px; margin: 2px 10px; transition: 0.3s;
        }
        .dropdown-custom .dropdown-menu li a:hover { background: #dcd6ce; color: #e74c3c !important; }
        .dropdown-custom:hover .dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); display: block; }
        
        .badge { padding: 3px 6px; border-radius: 50%; font-family: sans-serif; }
        .nav i.fa { margin-right: 5px; font-size: 16px; }
        body { padding-top: 70px; } 
        
        #reservation .featured.background_content {
            margin-top: -70px; padding: 100px 0;
            background-size: cover; background-position: center;
        }
        .reservation-section { background-color: #ffffff; padding-bottom: 60px; }
        .form-container-custom { max-width: 900px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .form-title { font-family: 'Playball', cursive; color: #d9534f; font-size: 32px; text-align: center; margin-bottom: 30px; }
        .label-custom { font-weight: 600; color: #444; margin-top: 15px; display: block; margin-bottom: 8px; }
        .input-custom { height: 45px !important; border: 1px solid #ddd !important; border-radius: 5px !important; }
        
        .table-selection-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; margin-bottom: 25px; }
        .table-item input[type="radio"] { display: none; }
        .table-item label { display: block; padding: 12px; border: 2px solid #eee; border-radius: 6px; text-align: center; cursor: pointer; transition: 0.2s; font-weight: 500; color: #666; }
        .table-item input[type="radio"]:checked + label { border-color: #d9534f; background-color: #d9534f; color: white; }
        
        .btn-reserve { background-color: #d9534f; color: white; padding: 14px; border: none; border-radius: 6px; width: 100%; font-size: 18px; font-weight: bold; text-transform: uppercase; margin-top: 20px; }
        .btn-food-select { background: white; color: #d9534f; border: 1px solid #d9534f; width: 100%; padding: 12px; margin: 10px 0; font-weight: 600; border-radius: 6px; }

        .stock-label { font-size: 12px; margin-bottom: 5px; display: block; }
        .text-out-stock { color: #d9534f; font-weight: bold; }
        .text-in-stock { color: #2ecc71; }
        .btn-add-card:disabled { background: #ccc; cursor: not-allowed; }

        .search-container { position: sticky; top: -15px; background: white; padding: 15px 0; z-index: 10; border-bottom: 1px solid #eee; margin-bottom: 20px; }
        .search-input { width: 100%; padding: 10px 20px; border: 2px solid #ddd; border-radius: 25px; outline: none; }
        .category-divider { background: #fcf8f2; padding: 10px 15px; margin: 20px 0 15px 0; border-left: 5px solid #d9534f; font-weight: bold; color: #d9534f; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .menu-card { border: 1px solid #eee; border-radius: 10px; overflow: hidden; display: flex; flex-direction: column; background: #fff;}
        .card-img { width: 100%; height: 160px; object-fit: cover; }
        .card-body { padding: 15px; flex-grow: 1; }
        .btn-add-card { background: #d9534f; color: white; border: none; padding: 8px; border-radius: 5px; width: 100%; transition: 0.3s; }

        .cart-wrapper { background: #fdfaf9; padding: 15px; border-radius: 6px; border: 1px dashed #d9534f; margin-bottom: 20px; }
        .cart-item { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .cart-item-img { width: 50px; height: 50px; border-radius: 5px; object-fit: cover; }
        .table-item label:hover { border-color: #d9534f; background-color: #fff5f5; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .table-item input[type="radio"]:disabled + label:hover { border-color: #ddd; background-color: #eee; transform: none; box-shadow: none; }
    </style>
</head>
<body>

    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Restaurant</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav main-nav navbar-right">
                    <li><a class="color_animation" href="{{ url('/') }}">Trang chủ</a></li>
                    <li class="dropdown-custom">
                        <a class="color_animation" href="#">Thực đơn</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('/menu') }}">Thực đơn chi tiết</a></li>
                            <li><a href="{{ url('/seafood') }}">Hải sản</a></li>
                            <li><a href="{{ url('/special') }}">Món đặc biệt</a></li>
                        </ul>
                    </li>
                    <li><a class="navactive color_animation" href="{{ url('/reservation') }}">Đặt bàn</a></li>
                    <li>
                        <a class="color_animation" href="{{ url('/cart') }}" style="position: relative;">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="badge" id="nav-cart-count" style="background: #96E16B; color: #000; position: absolute; top: 0; right: 0; font-size: 10px; display: none;">0</span>
                        </a>
                    </li>
                    @auth
                        <li class="dropdown-custom">
                            <a class="color_animation" href="#"><i class="fa fa-user"></i> {{ Auth::user()->name }}</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/profile') }}">Hồ sơ</a></li>
                                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a></li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <section id="reservation" class="description_content">
        <div class="featured background_content">
            <h1>Reserve a Table!</h1>
        </div>

        <div class="reservation-section">
            <div class="container">
                <div class="form-container-custom">
                    <h2 class="form-title">Thông Tin Đặt Bàn</h2>

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
                                <input type="text" name="full_name" class="form-control input-custom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="label-custom">Số điện thoại</label>
                                <input type="tel" name="phone" class="form-control input-custom" required>
                            </div>
                        </div>

                        <label class="label-custom">Vị trí bàn</label>
                        <div class="table-selection-grid">
                            @for ($i = 1; $i <= 10; $i++)
                                @php $isBooked = in_array($i, $bookedTableIds ?? []); @endphp
                                <div class="table-item">
                                    <input type="radio" name="table_id" id="t{{$i}}" value="{{$i}}" {{ $isBooked ? 'disabled' : '' }} required>
                                    <label for="t{{$i}}" style="{{ $isBooked ? 'background-color: #eee; color: #aaa; cursor: not-allowed;' : '' }}">
                                        Bàn {{$i}} <br>
                                        <small style="font-size: 10px;">{{ $isBooked ? 'Đã đặt' : 'Trống' }}</small>
                                    </label>
                                </div>
                            @endfor
                        </div>

                        <label class="label-custom">Thực đơn đặt trước</label>
                        <button type="button" class="btn-food-select" data-toggle="modal" data-target="#foodMenuModal">
    <i class="fa fa-search"></i> TÌM KIẾM VÀ CHỌN MÓN ĂN
</button>

                        <div id="cart-container" class="cart-wrapper" style="display: none;">
                            <h5 class="label-custom" style="margin-top: 0; color: #d9534f;">Món ăn đã chọn:</h5>
                            <div id="cart-list"></div>
                            <div class="cart-total text-right" style="margin-top: 15px;">
                                <strong style="font-size: 16px;">Tổng cộng: <span id="total-price">0</span> VNĐ</strong>
                            </div>
                        </div>

                        <div id="hidden-inputs-container"></div>

                        <label class="label-custom">Ghi chú</label>
                        <textarea name="notes" class="form-control input-custom" rows="3" style="height: auto !important;"></textarea>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-danger btn-block btn-reserve" style="border-radius: 25px;">
                                XÁC NHẬN ĐẶT BÀN
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
                    <h4 class="modal-title">Thực Đơn Nhà Hàng</h4>
                </div>
                <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
                    <div class="search-container">
                        <input type="text" id="menuSearch" class="search-input" placeholder="Nhập tên món ăn..." onkeyup="filterMenu()">
                    </div>
                    <div id="menu-list"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Hoàn tất chọn món</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
        // Lấy dữ liệu từ Laravel gửi sang
        const menuItems = @json($menus ?? []);
window.menuItems = menuItems;
        let cart = @json(array_values(session('cart', []))); 

        const formatCurrency = (num) => new Intl.NumberFormat('vi-VN').format(num);

        $(document).ready(function () {
            renderMenu();
            updateCartUI(); // Cập nhật giỏ hàng nếu đã có trong session
        });

        // 1. Hàm hiển thị danh sách món ăn trong Modal
        function renderMenu(filter = '') {
            const listElement = document.getElementById('menu-list');
            const groups = menuItems.reduce((acc, item) => {
                const cat = item.category || 'Món khác';
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
                    html += `<div class="category-divider">${category}</div><div class="menu-grid">`;
                    filteredItems.forEach(item => {
                        const isOutOfStock = item.stock <= 0;
                        html += `
                            <div class="menu-card">
                                <img src="{{ asset('') }}${item.image}" class="card-img" onerror="this.src='https://via.placeholder.com/200x160'">
                                <div class="card-body">
                                    <h4 class="card-title" style="font-size:14px; height:35px; overflow:hidden;">${item.name}</h4>
                                    <div class="stock-label">
                                        ${isOutOfStock ? '<span class="text-out-stock">Hết hàng</span>' : `<span class="text-in-stock">Còn: ${item.stock}</span>`}
                                    </div>
                                    <span style="color:#d9534f; font-weight:bold;">${formatCurrency(item.price)}đ</span>
                                    <button type="button" class="btn-add-card" onclick="addToCart(${item.id})" ${isOutOfStock ? 'disabled' : ''}>
                                        ${isOutOfStock ? 'Hết' : 'Chọn'}
                                    </button>
                                </div>
                            </div>`;
                    });
                    html += `</div>`;
                }
            }
            listElement.innerHTML = html || '<p class="text-center">Không tìm thấy món ăn.</p>';
        }

        // 2. Hàm thêm vào giỏ (Dùng Ajax để đồng bộ Session Laravel)
        function addToCart(id) {
            const item = menuItems.find(i => i.id === id);
            const existing = cart.find(i => i.id === id);
            const currentQty = existing ? existing.quantity : 0;

            if (currentQty < item.stock) {
                fetch("{{ route('cart.addAjax') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ id: item.id, name: item.name, price: item.price, image: item.image })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        if (existing) { existing.quantity += 1; } 
                        else { cart.push({ ...item, quantity: 1 }); }
                        updateCartUI();
                    }
                });
            } else {
                alert('Món này đã đạt giới hạn tồn kho!');
            }
        }

        // 3. Hàm tăng giảm số lượng trực tiếp trong giỏ hàng
        function updateQuantity(id, change) {
            const index = cart.findIndex(i => i.id === id);
            const item = menuItems.find(i => i.id === id);
            if (index > -1) {
                const newQty = cart[index].quantity + change;
                if (newQty <= item.stock && newQty > 0) {
                    cart[index].quantity = newQty;
                    updateCartUI();
                } else if (newQty <= 0) {
                    cart.splice(index, 1);
                    updateCartUI();
                } else {
                    alert('Vượt quá số lượng có sẵn!');
                }
            }
        }

        // 4. Hàm cập nhật Giao diện (Vùng đã chọn + Badge Icon Giỏ hàng + Hidden Inputs)
        function updateCartUI() {
            const container = document.getElementById('cart-container');
            const list = document.getElementById('cart-list');
            const hidden = document.getElementById('hidden-inputs-container');
            const navCount = document.getElementById('nav-cart-count');
            
            let total = 0, cartHtml = '', inputsHtml = '', totalQty = 0;

            cart.forEach(item => {
                total += item.price * item.quantity;
                totalQty += item.quantity;

                cartHtml += `
                    <div class="cart-item">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <img src="{{ asset('') }}${item.image}" class="cart-item-img" onerror="this.src='https://via.placeholder.com/50'">
                            <div><strong>${item.name}</strong><br><small>${formatCurrency(item.price)} VNĐ</small></div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-xs btn-default" onclick="updateQuantity(${item.id}, -1)">-</button>
                            <span style="margin:0 5px; font-weight:bold;">${item.quantity}</span>
                            <button type="button" class="btn btn-xs btn-default" onclick="updateQuantity(${item.id}, 1)">+</button>
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

            if(totalQty > 0) {
                navCount.innerText = totalQty;
                navCount.style.display = 'block';
            } else {
                navCount.style.display = 'none';
            }
            document.getElementById('total-price').innerText = formatCurrency(total);
        }

        function filterMenu() { 
            renderMenu(document.getElementById('menuSearch').value); 
        }
    </script>
</body>
</html>