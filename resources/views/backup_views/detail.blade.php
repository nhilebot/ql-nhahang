<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $menu->name }} - Chi tiết món ăn</title>
    
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

    <style>
        body { font-family: 'Arial', sans-serif; background-color: #fff; padding-top: 100px; }
        
        .navbar-default { background-color: #222 !important; border: none !important; }
        .navbar-brand { font-family: 'Pacifico' !important; font-size: 28px !important; color: #ffffff !important; }
        .nav li a { color: #fff !important; font-weight: 600; }
        .nav li a:hover { color: #e74c3c !important; }

        .product-container { margin-top: 50px; margin-bottom: 50px; }
        .image-section { position: relative; text-align: center; }
        .image-section img {
            width: 450px; height: 450px; object-fit: cover;
            border-radius: 50% 40% 50% 40%; border: 10px solid #fff5f5;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .sale-tag {
            position: absolute; top: 20px; right: 15%; background: #ff0000;
            color: #fff; padding: 10px; border-radius: 50%; font-weight: bold;
            font-size: 12px; transform: rotate(-10deg); line-height: 1.2;
        }
        .price-bar {
            background-color: #fff0f3; padding: 15px 20px; border-radius: 4px;
            display: flex; align-items: center; margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .price-text { font-size: 26px; font-weight: bold; color: #333; margin-right: 30px; }
        
        .btn-order {
            background-color: #d9534f; color: white !important; padding: 12px 35px;
            font-size: 18px; border: none; border-radius: 6px; transition: 0.3s;
            text-decoration: none; display: inline-block; cursor: pointer;
        }
        .btn-order:hover { background-color: #c9302c; text-decoration: none; }

        .comments-section {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 2px solid #eee;
        }
        .comments-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #333;
        }
        .comments-title .badge {
            background: #e74c3c;
            margin-left: 10px;
        }
        .comment-form {
            background: #f9f9f9;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 40px;
        }
        .comment-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .btn-submit-comment {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            cursor: pointer;
        }
        .btn-submit-comment:hover {
            background: #c0392b;
        }
        .comment-item {
            display: flex;
            gap: 15px;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .comment-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .comment-content {
            flex: 1;
        }
        .comment-user {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .comment-rating {
            color: #ffc107;
            font-size: 13px;
            margin-left: 10px;
        }
        .comment-date {
            font-size: 12px;
            color: #999;
            margin-left: 10px;
        }
        .comment-text {
            color: #555;
            margin-top: 8px;
            line-height: 1.5;
        }
        .delete-comment {
            color: #e74c3c;
            cursor: pointer;
            font-size: 12px;
            margin-top: 8px;
            display: inline-block;
        }
        .login-to-comment {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .toast-message {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #27ae60;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 9999;
            display: none;
        }
        .toast-message.error {
            background: #e74c3c;
        }
        
    </style>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">Nhà hàng Việt</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-navbar">
            <ul class="nav navbar-nav main-nav navbar-right">
                <li><a href="{{ url('/') }}">Trang chủ</a></li>
                <li><a href="{{ url('/menu') }}" style="color: #e74c3c !important;">Thực đơn</a></li>
                <li><a href="{{ url('/reservation') }}">Đặt bàn</a></li>
                <li><a href="{{ url('/contact') }}">Liên hệ</a></li>
                <li><a href="{{ url('/cart') }}"><i class="fa fa-shopping-cart"></i> Giỏ hàng @if(session('cart')) <span class="badge" style="background:#e74c3c">{{ count(session('cart')) }}</span> @endif</a></li>
                @guest
                    <li><a href="{{ url('/login') }}">Đăng nhập</a></li>
                    <li><a href="{{ url('/register') }}">Đăng ký</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('/profile') }}">Tài khoản</a></li>
                            <li><a href="{{ url('/orders') }}">Đơn hàng</a></li>
                            <li><a href="{{ url('/logout') }}">Đăng xuất</a></li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<div class="container product-container">
    <div class="row">
        <div class="col-md-6 image-section">
            <div style="position: relative; display: inline-block;">
                <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}">
                <div class="sale-tag">SALE<br>50%<br>OFF</div>
            </div>
        </div>

        <div class="col-md-6">
            <h1 style="font-family: 'Playball', cursive; color: #333;">{{ $menu->name }}</h1>
            <div class="product-info-box" style="background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <div class="price-bar">
                    <span class="price-text">{{ number_format($menu->price, 0, ',', '.') }} VNĐ</span>
                    <span class="label label-danger" style="padding: 5px 10px;">5 <i class="fa fa-star"></i></span>
                    <span style="margin-left: 15px; color: #e74c3c;"><i class="fa fa-comment"></i> {{ $menu->comments->count() }} bình luận</span>
                </div>
                <p style="font-size: 16px; color: #666; line-height: 1.8; margin-bottom: 30px;">{{ $menu->description ?? 'Đại tiệc ẩm thực tươi ngon mang hương vị biển cả đến bàn ăn của bạn.' }}</p>
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                    <div class="form-group">
                        <label>Số lượng:</label><br>
                        <input type="number" name="quantity" value="1" min="1" style="width: 70px; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                        <button type="submit" class="btn-order" style="margin-left: 15px;">THÊM VÀO GIỎ HÀNG</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== PHẦN BÌNH LUẬN ===== -->
    <div class="comments-section">
        <h3 class="comments-title">Đánh giá & Bình luận <span class="badge">{{ $menu->comments->count() }}</span></h3>

        @auth
            <div class="comment-form">
                <form id="commentForm" method="POST">
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                    <div style="margin-bottom: 15px;">
                        <label>Đánh giá của bạn:</label>
                        <select name="rating" style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 5px; margin-left: 10px;">
                            <option value="5">★★★★★ (5 sao)</option>
                            <option value="4">★★★★☆ (4 sao)</option>
                            <option value="3">★★★☆☆ (3 sao)</option>
                            <option value="2">★★☆☆☆ (2 sao)</option>
                            <option value="1">★☆☆☆☆ (1 sao)</option>
                        </select>
                    </div>
                    <textarea name="content" class="comment-textarea" rows="3" placeholder="Chia sẻ cảm nhận của bạn..." required></textarea>
                    <button type="submit" class="btn-submit-comment">Gửi bình luận</button>
                </form>
            </div>
        @else
            <div class="login-to-comment">
                <p>Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.</p>
            </div>
        @endauth

        <div id="commentsList">
            @forelse($menu->comments as $comment)
                <div class="comment-item" data-id="{{ $comment->id }}">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=e74c3c&color=fff&size=50" class="comment-avatar">
                    <div class="comment-content">
                        <div class="comment-user">
                            {{ $comment->user->name }}
                            <span class="comment-rating">
                                @for($i=1;$i<=5;$i++) @if($i<=$comment->rating) ★ @else ☆ @endif @endfor
                            </span>
                            <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="comment-text">{{ $comment->content }}</div>
                        @auth @if(Auth::id() === $comment->user_id)
                            <span class="delete-comment" data-id="{{ $comment->id }}">Xóa</span>
                        @endif @endauth
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:40px; color:#999;">Chưa có bình luận nào.</div>
            @endforelse
        </div>
    </div>
</div>

<footer style="background:#222; color:#fff; padding:40px 0; text-align:center;">
    <p>&copy; 2026 TamNhi Quán.</p>
</footer>

<div id="toastMessage" class="toast-message"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $('#commentForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var btn = $(this).find('.btn-submit-comment');
        
        $.ajax({
            url: '{{ route("comment.store", $menu->id) }}',
            type: 'POST',
            data: formData,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            beforeSend: function() {
                btn.prop('disabled', true).html('Đang gửi...');
            },
            success: function(response) {
                if(response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Lỗi!');
                btn.prop('disabled', false).html('Gửi bình luận');
            }
        });
    });
    
    $(document).on('click', '.delete-comment', function() {
        if(confirm('Xóa bình luận?')) {
            var id = $(this).data('id');
            var item = $(this).closest('.comment-item');
            $.ajax({
                url: '/comment/' + id,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function() {
                    item.fadeOut(300, function() { $(this).remove(); location.reload(); });
                }
            });
        }
    });
});
</script>

</body>
</html>