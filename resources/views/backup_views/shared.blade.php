<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restaurant')</title>
    <link rel="icon" href="{{ asset('favicon-1.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-portfolio.css') }}">
    <link rel="stylesheet" href="{{ asset('css/picto-foundry-food.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    @stack('head')
</head>
<body class="@yield('body-class', '')">
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
                    <a class="navbar-brand" href="{{ url('/') }}">Restaurant</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav main-nav navbar-right">
                        <li><a class="color_animation" href="{{ url('/') }}">Trang chủ</a></li>
                        <li><a class="color_animation" href="{{ url('/menu') }}">Thực đơn</a></li>
                        <li><a class="color_animation" href="{{ url('/reservation') }}">Đặt bàn</a></li>
                        <li>
                            <a class="color_animation" href="{{ url('/cart') }}" style="position: relative;">
                                <i class="fa fa-shopping-cart"></i>
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span class="badge" style="background: #96E16B; color: #000; position: absolute; top: 0; right: 0; font-size: 10px;">
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
                                <a href="#" class="dropdown-toggle color_animation" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @if(Auth::user()->role == 'admin')
                                        <li><a href="{{ url('/admin') }}">Quản trị viên</a></li>
                                    @endif
                                    <li><a href="{{ url('/profile') }}">Hồ sơ của tôi</a></li>
                                    <li>
                                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #e74c3c;">
                                            <i class="fa fa-sign-out"></i> Đăng xuất
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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

    @yield('content')

    <footer class="sub_footer">
        <div class="container">
            <div class="col-md-4"><p class="sub-footer-text text-center">&copy; Restaurant 2014, Theme by <a href="https://themewagon.com/">ThemeWagon</a></p></div>
            <div class="col-md-4"><p class="sub-footer-text text-center">Back to <a href="#top">TOP</a></p></div>
            <div class="col-md-4"><p class="sub-footer-text text-center">Built With Care By <a href="#" target="_blank">Us</a></p></div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    @stack('scripts')
</body>
</html>
