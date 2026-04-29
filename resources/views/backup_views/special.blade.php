<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thực đơn Hải sản - Restaurant</title>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon-1.ico') }}" type="image/x-icon">

    <style>
        body { background-color: #f9f9f9; padding-top: 100px; }
        
        .navbar-default {
            background-color: #fff;
            border: none !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05) !important;
        }

        /* Thêm style để khi hover vào Logo vẫn giữ màu đẹp hoặc đổi nhẹ */
        .navbar-brand:hover {
            color: #f1f1f1 !important; 
            opacity: 0.8;
        }

        .section-title {
            font-family: 'Pacifico', cursive;
            font-size: 60px;
            color: #e74c3c;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: normal;
            text-align: center; 
            display: block;
            width: 100%;
            border: none !important;
            outline: none !important;
            text-transform: none;
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 40px;
                white-space: normal;
            }
        }

        .section-subtitle {
            text-align: center;
            color: #777;
            max-width: 700px;
            margin: 0 auto 40px auto;
            font-size: 16px;
            line-height: 1.6;
        }

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
            text-decoration: none;
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
            transition: 0.3s;
        }
        .btn-detail:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }

    </style>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="row">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- GIỮ LOGO NHƯ MENU -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Restaurant
                </a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <ul class="nav navbar-nav main-nav clear navbar-right">

                    <li>
                        <a class="color_animation" href="{{ url('/') }}">Trang chủ</a>
                    </li>

                    <li>
                        <a class="color_animation" href="{{ url('/menu') }}">Thực đơn</a>
                    </li>

                    <li>
                        <a class="color_animation" href="{{ url('/reservation') }}">Đặt bàn</a>
                    </li>

                    <li>
                        <a class="color_animation" href="{{ url('/cart') }}">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </li>

                    @guest
                        <li>
                            <a class="color_animation" href="{{ route('login') }}">Đăng nhập</a>
                        </li>
                        <li>
                            <a class="color_animation" href="{{ route('register') }}">Đăng ký</a>
                        </li>
                    @endguest

                    @auth
                        <li class="dropdown-custom">
                            <a class="color_animation" href="#">
                                <i class="fa fa-user"></i> {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu">
                                @if(Auth::user()->role == 'admin')
                                    <li><a href="{{ url('/admin') }}">Quản trị viên</a></li>
                                @endif

                                <li><a href="{{ url('/profile') }}">Hồ sơ của tôi</a></li>

                                <li>
                                    <a href="#"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Đăng xuất
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth

                </ul>

            </div>

        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="section-title">Món đặc biệt</h1>
            <p class="section-subtitle">
                Việt Nam luôn khiến du khách phải "thương nhớ" bởi vô vàn đặc sản mang đậm hương vị và bản sắc văn hóa từng vùng miền. 
                Cùng khám phá những món ăn Việt Nam nổi tiếng đã chinh phục cả thế giới nhé!
            </p>
        </div>
    </div>
    
    <div class="category-nav">
        <a href="{{ url('/menu') }}" class="btn-category">Tất cả</a>
        <a href="{{ url('/seafood') }}" class="btn-category active">Hải sản</a>
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