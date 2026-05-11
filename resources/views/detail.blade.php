@extends('shared')

@section('title', $menu->name . ' - Chi tiết món ăn')

@section('head')
<style>
    body { background-color: #f9f9f9; padding-top: 100px; }

    /* Navbar đồng bộ */
    .navbar-default {
        background-color: #fff;
        border: none !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05) !important;
    }

    /* Container chính */
    .product-detail-container {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        /* margin-top: 30px; */
        margin-bottom: 50px;
    }

    /* Khung ảnh vuông 1:1 đồng bộ với trang chủ */
    .detail-image-wrapper {
        position: relative;
        width: 100%;
        padding-bottom: 100%; /* Tạo khung vuông */
        overflow: hidden;
        border-radius: 20px;
        background-color: #fcfcfc;
        border: 1px solid #f0f0f0;
    }

    .detail-image-wrapper img {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .detail-image-wrapper:hover img {
        transform: scale(1.05);
    }

    .sale-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #e74c3c, #ff5e57);
        color: white;
        padding: 15px 10px;
        border-radius: 50%;
        font-weight: bold;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        text-align: center;
        line-height: 1.2;
    }

    /* Phần thông tin món ăn */
    .info-section h1 {
        font-family: 'Playball', cursive;
        /* font-size: 48px; */
        color: #041526e0;
        margin-top: 0;
        margin-bottom: 20px;
    }

    .price-tag {
        font-size: 27px;
        color: #8a6d3b;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .rating-stars {
        font-size: 16px;
        background: #fff4f1;
        padding: 5px 15px;
        border-radius: 30px;
        color: #e74c3c;
    }

    .description-text {
        font-size: 17px;
        color: #666;
        line-height: 1.8;
        margin-bottom: 30px;
        border-left: 4px solid #e74c3c;
        padding-left: 20px;
    }

    /* Form đặt hàng */
    .order-controls {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 25px 0;
        border-top: 1px dashed #eee;
    }

    .qty-input-group {
        display: flex;
        align-items: center;
        border: 2px solid #eee;
        border-radius: 30px;
        overflow: hidden;
    }

    .qty-input-group input {
        width: 60px;
        height: 45px;
        border: none;
        text-align: center;
        font-weight: bold;
    }

    .btn-add-cart {
        background: #e74c3c;
        /* color: white !important; */
        border: none;
        padding: 12px 35px;
        border-radius: 30px;
        font-weight: bold;
        text-transform: uppercase;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
    }

    .btn-add-cart:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    /* Phần bình luận đồng bộ */
    .comments-container {
        margin-top: 50px;
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        margin-top: 30px;
    }

    .comment-item {
        padding: 20px 0;
        border-bottom: 1px solid #f5f5f5;
        display: flex;
        gap: 15px;
    }

    .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .comment-form-box {
        background: #fdfdfd;
        padding: 20px;
        border-radius: 15px;
        border: 1px solid #f0f0f0;
        margin-bottom: 30px;
    }

    .comment-textarea {
        width: 100%;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="product-detail-container">
        <div class="row">
            <div class="col-md-5">
                <div class="detail-image-wrapper">
                    <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}">
                    </div>
            </div>

            <div class="col-md-7 info-section">
                <h1>{{ $menu->name }}</h1>
                
                <div class="price-tag">
                    {{ number_format($menu->price, 0, ',', '.') }} VNĐ
                    <span class="rating-stars">
                        5.0 <i class="fa fa-star"></i> 
                        <span style="color: #999; font-size: 13px; margin-left: 10px;">
                            ({{ $menu->comments->count() }} đánh giá)
                        </span>
                    </span>
                </div>

                <div class="description-text">
                    {{ $menu->description ?? 'Thưởng thức hương vị ẩm thực đặc sắc được chế biến từ những nguyên liệu tươi ngon nhất trong ngày bởi các đầu bếp hàng đầu.' }}
                </div>

                <div class="order-controls">
                    <div class="qty-box">
                        <label style="display: block; font-size: 12px; color: #999; text-transform: uppercase;">Số lượng</label>
                        <div class="qty-input-group">
                            <input type="number" id="quantity" value="1" min="1" class="form-control">
                        </div>
                    </div>
                    
                   <button type="button" 
        class="btn-add-cart" 
        onclick="addToCart({{ $menu->id }})"
        {{ $menu->stock <= 0 ? 'disabled' : '' }}
        style="{{ $menu->stock <= 0 ? 'background: #ccc; cursor: not-allowed;' : '' }}">
    <i class="fa fa-shopping-cart"></i> 
    {{ $menu->stock <= 0 ? 'TẠM HẾT HÀNG' : 'Thêm vào thực đơn' }}
</button>
                </div>

                </div>
        </div>
    </div>

    <div class="comments-container">
        <h3 style="margin-bottom: 30px; font-weight: bold; color: #333;">
            <i class="fa fa-comments-o" style="color: #e74c3c;"></i> Đánh giá từ khách hàng
        </h3>

        @auth
            <div class="comment-form-box">
                <form action="{{ route('comment.store', $menu->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-sm-4">
                            <label>Đánh giá sao:</label>
                            <select name="rating" class="form-control" style="border-radius: 20px;">
                                <option value="5">★★★★★ - Tuyệt vời</option>
                                <option value="4">★★★★☆ - Ngon</option>
                                <option value="3">★★★☆☆ - Tạm được</option>
                                <option value="2">★★☆☆☆ - Không ngon</option>
                                <option value="1">★☆☆☆☆ - Tệ</option>
                            </select>
                        </div>
                    </div>
                    <textarea name="content" class="comment-textarea" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về món ăn..." required></textarea>
                    <button type="submit" class="btn-add-cart" style="padding: 8px 25px; font-size: 14px;">Gửi đánh giá</button>
                </form>
            </div>
        @else
            <div class="alert alert-warning" style="border-radius: 15px;">
                Vui lòng <a href="{{ route('login') }}" class="alert-link">đăng nhập</a> để để lại bình luận và đánh giá của bạn.
            </div>
        @endauth

        <div class="comment-list">
            @forelse($menu->comments as $comment)
                <div class="comment-item">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=e74c3c&color=fff" class="avatar-circle">
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between;">
                            <h5 style="margin: 0; font-weight: bold;">{{ $comment->user->name }}</h5>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <div style="color: #ffc107; font-size: 12px; margin: 5px 0;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa {{ $i <= $comment->rating ? 'fa-star' : 'fa-star-o' }}"></i>
                            @endfor
                        </div>
                        
                        <p id="comment-text-{{ $comment->id }}" style="color: #555; margin-bottom: 5px;">{{ $comment->content }}</p>
                        
                        @auth
                            @if(Auth::id() === $comment->user_id)
                                <div style="display: flex; gap: 15px; margin-top: 8px;">
                                    <button type="button" onclick="toggleEditForm({{ $comment->id }})" style="background: none; border: none; color: #3498db; font-size: 12px; padding: 0; cursor: pointer;">
                                        <i class="fa fa-pencil"></i> Sửa bình luận
                                    </button>

                                    <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" style="margin: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #e74c3c; font-size: 12px; padding: 0; cursor: pointer;" onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này?');">
                                            <i class="fa fa-trash"></i> Xóa bình luận
                                        </button>
                                    </form>
                                </div>

                                <div id="edit-form-{{ $comment->id }}" style="display: none; margin-top: 10px; background: #f9f9f9; padding: 15px; border-radius: 10px; border: 1px solid #eee;">
                                    <form action="{{ route('comment.update', $comment->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="rating" class="form-control" style="border-radius: 10px; margin-bottom: 10px; width: 250px; display: inline-block;">
                                            <option value="5" {{ $comment->rating == 5 ? 'selected' : '' }}>★★★★★ - Tuyệt vời</option>
                                            <option value="4" {{ $comment->rating == 4 ? 'selected' : '' }}>★★★★☆ - Ngon</option>
                                            <option value="3" {{ $comment->rating == 3 ? 'selected' : '' }}>★★★☆☆ - Tạm được</option>
                                            <option value="2" {{ $comment->rating == 2 ? 'selected' : '' }}>★★☆☆☆ - Không ngon</option>
                                            <option value="1" {{ $comment->rating == 1 ? 'selected' : '' }}>★☆☆☆☆ - Tệ</option>
                                        </select>
                                        <textarea name="content" class="comment-textarea" rows="2" required>{{ $comment->content }}</textarea>
                                        <div style="margin-top: 10px;">
                                            <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 6px 15px; border-radius: 5px; cursor: pointer;">Lưu thay đổi</button>
                                            <button type="button" onclick="toggleEditForm({{ $comment->id }})" style="background: #95a5a6; color: white; border: none; padding: 6px 15px; border-radius: 5px; cursor: pointer; margin-left: 5px;">Hủy</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <div class="text-center" style="padding: 40px; color: #bbb;">
                    <i class="fa fa-commenting-o" style="font-size: 40px;"></i>
                    <p>Chưa có đánh giá nào cho món ăn này.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function addToCart(foodId) {
    // 1. Lấy số lượng từ ô input
    let qty = document.getElementById('quantity').value;
    
    // 2. Gửi yêu cầu AJAX
    $.ajax({
        url: "{{ route('cart.add') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            menu_id: foodId,
            quantity: qty
        },
        success: function(response) {
            // Kiểm tra biến success từ Controller trả về
            if (response.success) {
                // ✅ Nếu thành công: Hiện màu xanh
                Swal.fire({
                    icon: 'success',
                    title: 'Đã thêm vào thực đơn!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                
                // Cập nhật số lượng hiển thị trên icon giỏ hàng
                if(response.count !== undefined) {
                    $('.badge').text(response.count);
                }
            } else {
                // ❌ Nếu thất bại (Hết hàng): Hiện màu đỏ
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể thêm món!',
                    text: response.message,
                    confirmButtonColor: '#e74c3c'
                });
            }
        },
        error: function(xhr) {
            // Lỗi hệ thống hoặc lỗi 401 chưa đăng nhập
            let errorText = 'Vui lòng đăng nhập để đặt món!';
            if(xhr.responseJSON && xhr.responseJSON.message) {
                errorText = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Lỗi...',
                text: errorText,
            });
        }
    });
}

// Hàm bật/tắt form sửa bình luận
function toggleEditForm(commentId) {
    let form = document.getElementById('edit-form-' + commentId);
    let text = document.getElementById('comment-text-' + commentId);
    
    if (form.style.display === 'none') {
        form.style.display = 'block'; // Hiện form sửa
        text.style.display = 'none';  // Ẩn dòng chữ cũ
    } else {
        form.style.display = 'none';  // Ẩn form sửa
        text.style.display = 'block'; // Hiện lại dòng chữ cũ
    }
}
</script>

@endsection