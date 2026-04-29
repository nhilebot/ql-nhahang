<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thực đơn - Restaurant</title>

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-portfolio.css') }}">
    <link rel="stylesheet" href="{{ asset('css/picto-foundry-food.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">

    <style>
        body {
            background-color: #f9f9f9;
            padding-top: 100px;
        }

        .navbar-default {
            background-color: #222 !important;
            border: none !important;
        }

        .navbar-brand {
            font-family: 'Pacifico', cursive;
            font-size: 28px;
            color: #fff !important;
        }

        .navbar-nav li a {
            color: #fff !important;
        }

        .navbar-nav li a:hover {
            color: #e74c3c !important;
        }

        .navactive {
            color: #e74c3c !important;
        }

        .dropdown-menu {
            background: #222;
            border-top: 3px solid #e74c3c;
        }

        .dropdown-menu li a {
            color: #fff !important;
        }

        .dropdown-menu li a:hover {
            background: #e74c3c !important;
        }

        .section-title {
            font-family: 'Pacifico', cursive;
            font-size: 55px;
            color: #e74c3c;
            text-align: center;
            margin: 40px 0;
        }

        .menu-card {
            background: #fff;
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-8px);
        }

        .product-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #eee;
        }

        .product-name {
            font-size: 20px;
            margin-top: 15px;
        }

        .price-text {
            color: #e74c3c;
            font-weight: bold;
        }

        .btn-detail {
            background: #e74c3c;
            color: #fff !important;
            padding: 6px 18px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
        }
        /* --- CSS FIX LỖI NHẢY HÀNG --- */
        
        /* 1. Chỉ áp dụng Flexbox cho hàng chứa sản phẩm, đặt tên là menu-grid */
        .menu-grid {
            display: flex !important;
            flex-wrap: wrap !important;
        }

        /* 2. Đảm bảo các cột có độ cao bằng nhau để không bị kẹt hàng */
        .menu-grid > [class*='col-'] {
            display: flex !important;
            float: none !important; /* Quan trọng: Bỏ float của Bootstrap 3 */
            margin-bottom: 30px;
        }

        /* 3. Làm cho các khung trắng (menu-card) cao bằng nhau */
        .menu-card {
            background: #fff;
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            
            /* Thêm 3 dòng này */
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
        }
        
        /* Giữ cho tên món không bị lệch nút bấm */
        .product-name {
            min-height: 50px; 
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* 1. ÉP HIỂN THỊ: Phá bỏ lệnh ẩn từ style-portfolio.css */
.menu-card, .item, .col-md-3, .col-sm-6 {
    display: block !important; 
    opacity: 1 !important; 
    visibility: visible !important;
}

/* 2. FIX HÀNG LỐI: Đảm bảo 25 món xếp hàng ngay ngắn (Bootstrap 3) */
@media (min-width: 992px) {
    .row > div:nth-child(4n+1) {
        clear: left !important;
    }
}
@media (min-width: 768px) and (max-width: 991px) {
    .row > div:nth-child(2n+1) {
        clear: left !important;
    }
}

/* 3. CHỈNH CHIỀU CAO: Để các nút chi tiết không bị lệch nhau */
.menu-card {
    min-height: 420px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-bottom: 30px;
}
/* 1. PHÁ BỎ LỆNH ẨN: Ép tất cả các món ăn phải hiện ra */
    .menu-card, .item, .col-md-3, .col-sm-6 {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        transform: none !important;
    }

    /* 2. FIX HÀNG LỐI: Quan trọng nhất để hiện đủ 25 món không bị lệch */
    @media (min-width: 992px) {
        .row > div:nth-child(4n+1) {
            clear: left !important;
        }
    }
    @media (min-width: 768px) and (max-width: 991px) {
        .row > div:nth-child(2n+1) {
            clear: left !important;
        }
    }

    /* 3. ĐỒNG NHẤT CHIỀU CAO: Để các thẻ không cao thấp làm hàng bị méo */
    .menu-card {
        background: #fff;
        text-align: center;
        padding: 20px;
        margin-bottom: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        min-height: 450px; /* Thêm dòng này */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    </style>
</head>

<body>

<!-- NAVBAR (đồng bộ layout) -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="{{ url('/') }}">Restaurant</a>
        </div>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav navbar-right">

                <li><a href="{{ url('/') }}">Trang chủ</a></li>

                <!-- MENU dropdown chuẩn -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        Thực đơn <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li><a href="{{ url('/menu') }}">Tất cả</a></li>
                        <li><a href="{{ url('/special') }}">Món đặc biệt</a></li>
                        <li><a href="{{ url('/seafood') }}">Hải sản</a></li>
                        <li><a href="{{ url('/salad') }}">Salad</a></li>
                        <li><a href="{{ url('/vietnamese') }}">Món Việt</a></li>
                        <li><a href="{{ url('/desserts') }}">Tráng miệng</a></li>
                        <li><a href="{{ url('/drinks') }}">Đồ uống</a></li>
                    </ul>
                </li>

                <li><a href="{{ url('/reservation') }}">Đặt bàn</a></li>

                <!-- CART -->
                <li>
                    <a href="{{ url('/cart') }}" style="position:relative;">
                        <i class="fa fa-shopping-cart"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="badge" style="position:absolute;top:0;right:0;">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                </li>

                @guest
                    <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                    <li><a href="{{ route('register') }}">Đăng ký</a></li>
                @endguest

                @auth
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user"></i> {{ Auth::user()->name }}
                        </a>

                        <ul class="dropdown-menu">
                            @if(Auth::user()->role == 'admin')
                                <li><a href="{{ url('/admin') }}">Admin</a></li>
                            @endif
                            <li><a href="{{ url('/profile') }}">Hồ sơ</a></li>
                            <li>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Đăng xuất
                                </a>
                                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div>

    </div>
</nav>

<!-- TITLE -->
<div class="container">
    <!-- <h1 class="section-title">Khám Phá Thực Đơn</h1> -->

    <!-- CATEGORY -->
    <div class="text-center" style="margin-bottom:30px;">
        <a href="{{ url('/menu') }}" class="btn btn-default">Tất cả</a>
        <a href="{{ url('/seafood') }}" class="btn btn-default">Hải sản</a>
        <a href="{{ url('/special') }}" class="btn btn-default">Món đặc biệt</a>
        <a href="{{ url('/salad') }}" class="btn btn-default">Salad</a>
        <a href="{{ url('/vietnamese') }}" class="btn btn-default">Món Việt</a>
        <a href="{{ url('/desserts') }}" class="btn btn-default">Tráng miệng</a>
        <a href="{{ url('/drinks') }}" class="btn btn-default">Đồ uống</a>
    </div>

    <!-- MENU LIST -->
    <div class="row">
        @foreach($menus as $menu)
        <div class="col-md-3 col-sm-6">
            <div class="menu-card">

                <a href="{{ route('menu.detail', $menu->id) }}">
                    <img src="{{ asset($menu->image) }}" class="product-img">
                </a>

                <h4 class="product-name">{{ $menu->name }}</h4>

                <p class="price-text">
                    {{ number_format($menu->price, 0, ',', '.') }} VNĐ
                </p>

                <a href="{{ route('menu.detail', $menu->id) }}" class="btn-detail">
                    Xem chi tiết
                </a>

            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- FOOTER -->
<footer style="background:#222;color:#fff;padding:30px;margin-top:50px;text-align:center;">
    <p>&copy; 2026 Restaurant</p>
</footer>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

</body>
</html>