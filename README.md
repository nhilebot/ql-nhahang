🍽️ WEBSITE QUẢN LÝ NHÀ HÀNG & ĐẶT BÀN ONLINE

👥 Thành viên nhóm

- Lê Yến Nhi
- Trần Minh Hải Tâm

---

📌 Mô tả hệ thống

Hệ thống website quản lý nhà hàng cho phép:

- Khách hàng xem thực đơn
- Đặt bàn online
- Thanh toán (tiền mặt / chuyển khoản)
- Admin quản lý:
  - Danh mục món ăn
  - Sản phẩm (món ăn)
  - Bàn
  - Đơn đặt bàn
  - Người dùng

---

⚙️ Công nghệ sử dụng

- Backend: Laravel (PHP)
- Frontend: HTML, CSS, JavaScript
- Database: MySQL
- Server: Laragon / XAMPP

---

🚀 Hướng dẫn cài đặt

1. Clone project

git clone <link_project>

2. Cài thư viện

composer install

3. Tạo file môi trường

cp .env.example .env

---

4. Cấu hình database trong file ".env"

DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

---

5. Tạo database

- Mở phpMyAdmin
- Tạo database tên: "laravel"

---

6. Chạy migrate & seed

php artisan migrate --seed

---

7. Generate key

php artisan key:generate

---

8. Chạy server

php artisan serve

👉 Truy cập: http://127.0.0.1:8000

---

🔑 Tài khoản đăng nhập

Admin

- Email: admin@gmail.com
- Password: 123456

User

- Email: user@gmail.com
- Password: 123456

---

📂 Cấu trúc chức năng chính

👤 Người dùng

- Xem menu
- Đặt bàn online
- Chọn phương thức thanh toán

🔐 Admin

- Dashboard (thống kê)
- Quản lý danh mục
- Quản lý món ăn
- Quản lý bàn
- Quản lý đơn đặt bàn
- Quản lý người dùng

---

📊 File báo cáo

- Slide báo cáo: "report.pptx"

---

📝 Ghi chú

- Nếu lỗi thư viện → chạy lại "composer install"
- Nếu lỗi key → chạy "php artisan key:generate"
- Nếu lỗi database → kiểm tra file ".env"
- Đảm bảo MySQL đã chạy trước khi migrate

---

📌 Yêu cầu hệ thống

- PHP >= 8.x
- Composer
- MySQL
- Laragon / XAMPP

---

✨ Hoàn thành bởi nhóm: Lê Yến Nhi & Trần Minh Hải Tâm