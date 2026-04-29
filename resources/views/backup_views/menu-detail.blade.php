<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ $menu->name }} - Restaurant</title>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    
    <style>
        /* Đảm bảo nội dung không bị Navbar đè lên */
        body { 
            padding-top: 120px; 
            background-color: #f4f4f4; 
        }

        /* Style Navbar đen giống trang chủ */
        .navbar-default {
            background-color: #222;
            border: none;
        }
        .navbar-brand {
            font-family: 'Pacifico';
            font-size: 28px !important;
            color: #efefef !important;
        }
        .nav li a {
            color: #fff !important;
            transition: 0.3s;
        }
        .nav li a:hover {
            color: #e74c3c !important;
        }

        /* Khung chi tiết sản phẩm */
        .product-detail-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 50px;
        }
        .img-detail {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .product-info h1 {
            font-family: 'Playball', cursive;
            font-size: 42px;
            color: #e74c3c;
            margin-top: 0;
        }
        .price-large {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }
        .description-text {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
            margin-bottom: 30px;
        }
        .btn-order {
            background-color: #e74c3c;
            color: #fff !important;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-block;
        }
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
            <a class="navbar-brand" href="{{ url('/') }}">Restaurant</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav main-nav navbar-right">
                <li><a href="{{ url('/') }}">Trang chủ</a></li>
                <li><a href="{{ url('/menu') }}">Thực đơn</a></li>
                <li><a href="{{ url('/reservation') }}">Đặt bàn</a></li>
                <li>
                    <a href="{{ url('/cart') }}">
                        <i class="fa fa-shopping-cart"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="badge" style="background: #e74c3c;">{{ count(session('cart')) }}</span>
                        @endif
                    </a>
                </li>
                @auth
                    <li><a href="#"><i class="fa fa-user"></i> {{ Auth::user()->name }}</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="product-detail-container">
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset($menu->image) }}" class="img-detail" alt="{{ $menu->name }}">
            </div>
            <div class="col-md-6 product-info">
                <h1>{{ $menu->name }}</h1>
                <div class="price-large">{{ number_format($menu->price, 0, ',', '.') }} VNĐ</div>
                <div class="description-text">
                    {{ $menu->description ?? 'Món ăn tuyệt vời được chế biến từ những nguyên liệu tươi ngon nhất trong ngày bởi các đầu bếp hàng đầu.' }}
                </div>
                
                <form action="{{ route('cart.add', $menu->id) }}" method="POST">
                    @csrf
                    <div class="form-group" style="width: 100px;">
                        <label>Số lượng:</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1">
                    </div>
                    <button type="submit" class="btn-order">Thêm vào giỏ hàng</button>
                    <a href="{{ url('/menu') }}" class="btn btn-default" style="padding: 12px 20px; margin-left: 10px;">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>

<footer style="background: #222; color: #fff; padding: 40px 0;" class="text-center">
    <div class="container">
        <p>&copy; 2026 Restaurant. All rights reserved.</p>
        <div class="footer-social">
            <a href="#" style="color: #fff; margin: 0 10px;"><i class="fa fa-facebook"></i></a>
            <a href="#" style="color: #fff; margin: 0 10px;"><i class="fa fa-instagram"></i></a>
        </div>
    </div>
</footer>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
</body>
</html>