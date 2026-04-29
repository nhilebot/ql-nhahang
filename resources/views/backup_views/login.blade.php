<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | Tam Nhi Quán</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    
    <style>
        body { 
            background-color: #f9f9f9; 
            font-family: 'Playball', cursive, sans-serif; 
        }
        
        .breeze-card { 
            background: white; 
            max-width: 450px; 
            margin: 80px auto; 
            padding: 2.5rem; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-top: 5px solid #e74c3c;
        }

        .login-title {
            font-family: 'Pacifico', cursive;
            color: #e74c3c;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .form-control { 
            border-radius: 30px; 
            border: 1px solid #ddd; 
            padding: 12px 20px; 
            height: auto;
            font-family: sans-serif;
        }

        .form-control:focus { 
            border-color: #e74c3c; 
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.2); 
            outline: 0; 
        }

        .btn-breeze { 
            background-color: #e74c3c; 
            color: white; 
            border-radius: 30px; 
            padding: 10px 20px; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 14px; 
            border: none; 
            transition: 0.3s; 
            width: 100%; 
            margin-top: 15px;
            font-family: sans-serif;
        }

        .btn-breeze:hover { 
            background-color: #c0392b; 
            color: white; 
            transform: scale(1.02);
        }

        .breeze-label { 
            display: block; 
            font-size: 16px; 
            font-weight: 500; 
            color: #333; 
            margin-bottom: 0.5rem; 
            padding-left: 10px;
        }

        .register-link {
            font-size: 15px; 
            color: #777; 
            text-decoration: none;
            transition: 0.3s;
            font-family: sans-serif;
        }

        .register-link:hover {
            color: #e74c3c;
            text-decoration: underline;
        }

        .form-check-input:checked {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="breeze-card">
            <div class="text-center mb-4">
                <h3 class="login-title">Đăng Nhập</h3>
                <p style="color: #777; font-size: 14px; font-family: sans-serif;">Chào mừng bạn quay trở lại!</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="border-radius: 30px; font-family: sans-serif;">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="border-radius: 15px; font-family: sans-serif; font-size: 13px;">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
    @csrf
                <div class="mb-3">
                    <label for="email" class="breeze-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="user@example.com" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="breeze-label">Mật khẩu</label>
                    <input id="password" type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center" style="font-family: sans-serif; font-size: 14px;">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" style="cursor: pointer;">
                        <label class="form-check-label" for="remember" style="color: #4b5563; cursor: pointer; margin-left: 5px;">Ghi nhớ tôi</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-breeze shadow-sm">ĐĂNG NHẬP</button>
                
                <div class="text-center mt-4">
                    <a href="{{ route('register') }}" class="register-link">Bạn chưa có tài khoản? <strong>Đăng ký ngay</strong></a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>