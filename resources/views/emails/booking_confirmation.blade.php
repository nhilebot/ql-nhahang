<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; color: #333;">
    <div style="background-color: #2c3e50; padding: 20px; text-align: center; color: white;">
        <h2 style="margin: 0; font-size: 24px;">Xác Nhận Đặt Bàn Thành Công</h2>
    </div>

    <div style="padding: 30px; line-height: 1.6;">
        <p style="font-size: 18px;">Chào <strong>{{ $booking->full_name }}</strong>,</p>
        <p>Cảm ơn bạn đã tin tưởng lựa chọn dịch vụ của chúng tôi. Dưới đây là thông tin chi tiết cho yêu cầu đặt bàn của bạn:</p>

        <div style="background-color: #f9f9f9; border-left: 4px solid #27ae60; padding: 15px; margin-bottom: 25px;">
            <p style="margin: 5px 0;"><strong>Mã đơn hàng:</strong> <span style="color: #e67e22;">#{{ $booking->id }}</span></p>
            <p style="margin: 5px 0;"><strong>Vị trí:</strong> {{ $booking->table->name ?? 'Bàn số ' . $booking->table_id }}</p>
            <p style="margin: 5px 0;"><strong>Thời gian:</strong> <span style="color: #2c3e50;">{{ $booking->reservation_time }} ngày {{ \Carbon\Carbon::parse($booking->reservation_date)->format('d/m/Y') }}</span></p>
        </div>

        <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 30px;">Chi tiết món ăn</h3>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background-color: #f2f2f2; text-align: left;">
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">Tên món</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: center;">SL</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: right;">Giá</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($booking->cart_data))
                    @foreach($booking->cart_data as $item)
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $item['name'] }}</td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">{{ $item['quantity'] }}</td>
                            <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">{{ number_format($item['price']) }} đ</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" style="padding: 20px; text-align: center; color: #888;">Quý khách chỉ đặt bàn, chưa chọn món trước.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 20px; font-size: 18px;">
            <p><strong>Tổng cộng thanh toán:</strong> <span style="color: #c0392b; font-size: 22px;">{{ number_format($booking->total_price) }} VNĐ</span></p>
        </div>
    </div>

    <div style="background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 13px; color: #777;">
        <p>Nếu bạn có bất kỳ thay đổi nào, vui lòng liên hệ với chúng tôi qua Hotline.</p>
        <p>Hẹn gặp lại bạn sớm!</p>
    </div>
</div>