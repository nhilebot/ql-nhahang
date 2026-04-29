<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thực đơn chi tiết - Restaurant</title>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

    <style>
        body { background-color: #f9f9f9; padding-top: 100px; }
        
        /* XÓA VẠCH KẺ ĐEN CỦA NAVBAR */
        .navbar-default {
            background-color: #fff;
            border: none !important; /* Xóa viền mặc định */
            box-shadow: none !important; /* Xóa bóng đổ nếu có */
        }

        /* CHỈNH TIÊU ĐỀ NẰM TRÊN 1 HÀNG DUY NHẤT */
       /* CHỈNH TIÊU ĐỀ NẰM TRÊN 1 HÀNG VÀ ẨN KHUNG KẺ */
    .section-title {
        font-family: 'Pacifico', cursive;
        font-size: 60px;
        color: #e74c3c;
        margin-top: 20px;
        margin-bottom: 40px;
        font-weight: normal;
        text-transform: none;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        
        /* Ép 1 hàng */
        white-space: nowrap;      
        display: block;           
        width: 100%;              
        overflow: visible;        
        text-align: center;

        /* TRIỆT TIÊU VẠCH KẺ ĐEN */
        border: none !important;    /* Xóa mọi loại viền */
        outline: none !important;   /* Xóa đường viền tập trung */
    }

        @media (max-width: 768px) {
            .section-title {
                font-size: 40px; 
            }
        }

        /* Các style khác giữ nguyên */
        .category-nav { margin-bottom: 50px; text-align: center; }
        .btn-category {
            padding: 10px 22px;
            margin: 5px;
            border-radius: 30px;
            text-transform: uppercase;
            font-weight: bold;
            transition: 0.3s;
            border: 2px solid #e74c3c;
            color: #e74c3c;
            display: inline-block;
            text-decoration: none;
            font-size: 13px;
        }
        .btn-category:hover, .btn-category.active {
            background: #e74c3c;
            color: white !important;
        }

        .menu-card { 
            background: #fff;
            margin-bottom: 30px; 
            text-align: center; 
            transition: 0.4s;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .menu-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .product-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 45% 55% 45% 55% / 55% 45% 55% 45%; 
            border: 6px solid #fff2f2;
            transition: 0.5s;
        }
        
        .menu-card:hover .product-img {
            border-color: #e74c3c;
            transform: rotate(5deg);
        }

        .product-name { 
            font-family: 'Playball', cursive;
            font-size: 22px; 
            color: #333; 
            margin-top: 15px; 
        }

        .price-text { 
            font-size: 18px; 
            color: #e74c3c; 
            font-weight: bold; 
            margin: 8px 0; 
        }

        .btn-detail {
            background-color: #e74c3c;
            color: white !important;
            padding: 8px 20px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            font-weight: bold;
            border: none;
        }

    </style>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed"
                data-toggle="collapse"
                data-target="#navbar-main">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="{{ url('/') }}"
               style="font-family: 'Pacifico'; font-size: 28px;">
                Restaurant
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navbar-main">
            <ul class="nav navbar-nav navbar-right main-nav clear">

                <li><a class="color_animation" href="{{ url('/') }}">Trang chủ</a></li>

                <li><a class="color_animation" href="{{ url('/menu') }}">Thực đơn</a></li>

                <li><a class="color_animation" href="{{ url('/reservation') }}">Đặt bàn</a></li>

                <li>
                    <a class="color_animation" href="{{ url('/cart') }}" style="position:relative;">
                        <i class="fa fa-shopping-cart"></i>

                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="badge"
                                  style="background:#96E16B;color:#000;position:absolute;top:0;right:0;font-size:10px;">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                </li>

                @guest
                    <li><a class="color_animation" href="{{ route('login') }}">Đăng nhập</a></li>
                    <li><a class="color_animation" href="{{ route('register') }}">Đăng ký</a></li>
                @endguest

                @auth
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle color_animation"
                           data-toggle="dropdown">
                            <i class="fa fa-user"></i> {{ Auth::user()->name }}
                            <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            @if(Auth::user()->role == 'admin')
                                <li><a href="{{ url('/admin') }}">Quản trị viên</a></li>
                            @endif

                            <li><a href="{{ url('/profile') }}">Hồ sơ của tôi</a></li>

                            <li>
                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   style="color:#e74c3c;">
                                    Đăng xuất
                                </a>

                                <form id="logout-form"
                                      action="{{ route('logout') }}"
                                      method="POST"
                                      style="display:none;">
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

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <!-- <h1 class="section-title">Khám Phá Thực Đơn</h1> -->
        </div>
    </div>
    
    <div class="category-nav">
        <a href="{{ url('/menu') }}" class="btn-category active">Tất cả</a>
        <a href="{{ url('/seafood') }}" class="btn-category">Hải sản</a>
        <a href="{{ url('/special') }}" class="btn-category">Món đặc biệt</a>
        <a href="{{ url('/salad') }}" class="btn-category">Salad</a>
        <a href="{{ url('/vietnamese') }}" class="btn-category">Món Việt</a>
        <a href="{{ url('/desserts') }}" class="btn-category">Tráng miệng</a>
        <a href="{{ url('/drinks') }}" class="btn-category">Đồ uống</a>
    </div>

    <div class="row">
        @foreach($menus as $menu)
        <div class="col-md-3 col-sm-6">
            <div class="menu-card">
                <a href="{{ route('menu.detail', $menu->id) }}">
                    <img src="{{ asset($menu->image) }}" class="product-img" alt="{{ $menu->name }}">
                </a>
                <h4 class="product-name">{{ $menu->name }}</h4>
                <p class="price-text">{{ number_format($menu->price, 0, ',', '.') }} VNĐ</p>
                <a href="{{ route('menu.detail', $menu->id) }}" class="btn-detail">Xem Chi Tiết</a>
            </div>
        </div>
        @endforeach
    </div>
</div>

<footer style="background: #222; color: #fff; padding: 40px 0; margin-top: 50px;" class="text-center">
    <div class="container">
        <p>&copy; 2026 Restaurant. All rights reserved.</p>
        <div class="footer-social">
            <a href="#" style="color: #fff; margin: 0 10px;"><i class="fa fa-facebook"></i></a>
            <a href="#" style="color: #fff; margin: 0 10px;"><i class="fa fa-instagram"></i></a>
            <a href="#" style="color: #fff; margin: 0 10px;"><i class="fa fa-twitter"></i></a>
        </div>
    </div>
</footer>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
</body>
</html>