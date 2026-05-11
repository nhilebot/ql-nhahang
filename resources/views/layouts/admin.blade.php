<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('page_title', 'Quản trị') – Nhà hàng Việt</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('admin_assets/style.css') }}">
<style>
.sub-menu{display:none;flex-direction:column;padding-left:42px;margin:4px 0 6px}
.sub-item{color:#8c98a4;text-decoration:none;font-size:13.5px;padding:8px 14px;border-radius:8px;transition:all .2s;margin-bottom:2px;display:block}
.sub-item:hover,.sub-item.active{color:#a78bff;background:rgba(255,255,255,.06)}
.nav-arrow{transition:transform .28s;display:inline-block;font-style:normal}
.rotate-90{transform:rotate(90deg)}
.role-badge{display:inline-block;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;letter-spacing:.04em;text-transform:uppercase}
.role-admin{background:rgba(239,68,68,.18);color:#f87171}
.role-staff{background:rgba(59,130,246,.18);color:#60a5fa}
.role-chef{background:rgba(245,158,11,.18);color:#fbbf24}
.role-cashier{background:rgba(16,185,129,.18);color:#34d399}
.role-customer{background:rgba(148,163,184,.12);color:#94a3b8}
.sidebar-divider{height:1px;background:rgba(255,255,255,.07);margin:10px 16px}
.flash-bar{position:fixed;top:0;left:0;right:0;z-index:9999;padding:12px 24px;font-weight:600;font-size:.88rem;display:flex;align-items:center;gap:10px;animation:slideDown .35s ease}
.flash-success{background:#16a34a;color:#fff}
.flash-error{background:#dc2626;color:#fff}
@keyframes slideDown{from{transform:translateY(-100%)}to{transform:translateY(0)}}
.stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px}
.stat-card{background:#fff;border-radius:16px;padding:20px 22px;box-shadow:0 4px 20px rgba(15,23,42,.07);display:flex;align-items:center;justify-content:space-between}
.stat-label{font-size:.78rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.05em}
.stat-value{font-size:1.6rem;font-weight:800;color:#0f172a;margin-top:4px}
.stat-icon-wrap{font-size:2rem}
.panel{background:#fff;border-radius:16px;padding:24px;box-shadow:0 4px 20px rgba(15,23,42,.07)}
.panel-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.panel-title{font-size:1.05rem;font-weight:700;color:#0f172a}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{text-align:left;padding:11px 14px;font-size:.76rem;font-weight:700;text-transform:uppercase;color:#64748b;border-bottom:2px solid #f1f5f9;background:#f8fafc}
td{padding:13px 14px;border-bottom:1px solid #f1f5f9;font-size:.9rem;vertical-align:middle}
tr:last-child td{border-bottom:none}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9px;font-size:.84rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .18s}
.btn-primary{background:#5b3cff;color:#fff}.btn-primary:hover{background:#4a30e0}
.btn-success{background:#16a34a;color:#fff}.btn-success:hover{background:#15803d}
.btn-warning{background:#d97706;color:#fff}.btn-warning:hover{background:#b45309}
.btn-danger{background:#dc2626;color:#fff}.btn-danger:hover{background:#b91c1c}
.btn-ghost{background:transparent;color:#64748b;border:1px solid #e2e8f0}.btn-ghost:hover{background:#f8fafc}
.btn-sm{padding:5px 12px;font-size:.8rem}
.form-group{margin-bottom:18px}
.form-label{display:block;font-size:.83rem;font-weight:600;color:#374151;margin-bottom:6px}
.form-control{width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:9px;font-size:.9rem;outline:none;transition:border .2s;font-family:inherit}
.form-control:focus{border-color:#5b3cff}
.form-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:36px}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700}
.badge-pending{background:#fff4e5;color:#92400e}
.badge-confirmed{background:#eff6ff;color:#1d4ed8}
.badge-serving{background:#fefce8;color:#854d0e}
.badge-served{background:#f0fdf4;color:#15803d}
.badge-paid{background:#ecfdf5;color:#059669}
.badge-cancelled{background:#fef2f2;color:#dc2626}
.user-row{display:flex;align-items:center;gap:10px;margin-bottom:14px}
.avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#5b3cff,#a78bff);display:grid;place-items:center;font-weight:800;font-size:.85rem;color:#fff;flex-shrink:0}
.user-name{font-size:.88rem;font-weight:700;color:#fff}
.user-role{font-size:.72rem;color:rgba(255,255,255,.45);margin-top:2px}
.btn-logout{width:100%;padding:9px;border-radius:9px;background:rgba(220,38,38,.15);color:#f87171;border:1px solid rgba(220,38,38,.25);font-weight:600;font-size:.83rem;cursor:pointer;transition:all .2s}
.btn-logout:hover{background:rgba(220,38,38,.28)}
.topbar{background:#fff;border-bottom:1px solid #f1f5f9;padding:14px 28px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100}
.topbar-left{display:flex;align-items:center;gap:14px}
.topbar-title{font-size:1.05rem;font-weight:700;color:#0f172a}
.menu-btn{background:none;border:none;font-size:1.3rem;cursor:pointer;color:#64748b}
.search-box{display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:8px 14px}
.search-box input{background:none;border:none;outline:none;font-size:.88rem;width:180px;font-family:inherit}
.content{padding:24px 28px}
</style>
</head>
<body>

@if(session('success'))
<div class="flash-bar flash-success" id="flash-msg">✓ {{ session('success') }}</div>
@elseif(session('error'))
<div class="flash-bar flash-error" id="flash-msg">✗ {{ session('error') }}</div>
@endif

<div class="app">

  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon">
        <a href="{{ url('/') }}" style="display:flex;align-items:center;">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:100px;filter:drop-shadow(0 0 2px rgba(0,0,0,.4));">
        </a>
      </div>
      <div>
        <div class="logo-text">Nhà hàng Việt</div>
        <div class="logo-sub">Quản trị hệ thống</div>
      </div>
    </div>

    @php
    // Lấy role_id từ database
    $rawRole = auth()->user()->role_id;
    
    // Ánh xạ số sang chữ để các lệnh @if bên dưới chạy đúng
    // Giả sử: 2 là admin, 3 là staff, 4 là chef, 1 là cashier
    $roleMap = [
        1 => 'cashier',
        2 => 'admin',
        3 => 'staff',
        4 => 'chef'
    ];

    $role = $roleMap[$rawRole] ?? 'customer';
    
    $userName = auth()->user()->name ?? 'User';
    $initials = mb_strtoupper(mb_substr($userName, 0, 1));
@endphp

    <div class="sidebar-section-label">Menu chức năng</div>

    <nav class="sidebar-nav">

      {{-- ═══ ADMIN ═══ --}}
      @if($role === 'admin')

        <a class="nav-item {{ (Request::is('admin') && !Request::is('admin/*')) ? 'active' : '' }}" href="{{ route('admin.index') }}">
          <div class="nav-item-left"><div class="nav-icon">📊</div>Bảng điều khiển</div>
          <span class="nav-arrow">›</span>
        </a>

        <div>
          <a class="nav-item {{ Request::routeIs('admin.categories.*') ? 'active' : '' }}" href="#" onclick="toggleSub(event,'cat-sub',this)">
            <div class="nav-item-left"><div class="nav-icon">📁</div>Danh mục</div>
            <span class="nav-arrow {{ Request::routeIs('admin.categories.*') ? 'rotate-90':'' }}">›</span>
          </a>
          <div class="sub-menu" id="cat-sub" style="display:{{ Request::routeIs('admin.categories.*')?'flex':'none' }}">
            <a class="sub-item {{ Request::routeIs('admin.categories.index')?'active':'' }}" href="{{ route('admin.categories.index') }}">Danh sách danh mục</a>
            <a class="sub-item {{ Request::routeIs('admin.categories.create')?'active':'' }}" href="{{ route('admin.categories.create') }}">+ Thêm danh mục</a>
          </div>
        </div>

        <div>
          <a class="nav-item {{ Request::routeIs('admin.menus.*') ? 'active' : '' }}" href="#" onclick="toggleSub(event,'menu-sub',this)">
            <div class="nav-item-left"><div class="nav-icon">🍽️</div>Thực đơn</div>
            <span class="nav-arrow {{ Request::routeIs('admin.menus.*') ? 'rotate-90':'' }}">›</span>
          </a>
          <div class="sub-menu" id="menu-sub" style="display:{{ Request::routeIs('admin.menus.*')?'flex':'none' }}">
            <a class="sub-item {{ Request::routeIs('admin.menus.index')?'active':'' }}" href="{{ route('admin.menus.index') }}">Danh sách món ăn</a>
            <a class="sub-item {{ Request::routeIs('admin.menus.create')?'active':'' }}" href="{{ route('admin.menus.create') }}">+ Thêm món ăn</a>
          </div>
        </div>

        <div>
          <a class="nav-item {{ Request::routeIs('admin.tables.*') ? 'active' : '' }}" href="#" onclick="toggleSub(event,'table-sub',this)">
            <div class="nav-item-left"><div class="nav-icon">🪑</div>Quản lý bàn</div>
            <span class="nav-arrow {{ Request::routeIs('admin.tables.*') ? 'rotate-90':'' }}">›</span>
          </a>
          <div class="sub-menu" id="table-sub" style="display:{{ Request::routeIs('admin.tables.*')?'flex':'none' }}">
            <a class="sub-item {{ Request::routeIs('admin.tables.index')?'active':'' }}" href="{{ route('admin.tables.index') }}">Danh sách bàn</a>
            <a class="sub-item {{ Request::routeIs('admin.tables.create')?'active':'' }}" href="{{ route('admin.tables.create') }}">+ Tạo đơn đặt bàn</a>
          </div>
        </div>

        <a class="nav-item {{ Request::routeIs('admin.reservations.*') ? 'active' : '' }}" href="{{ route('admin.reservations.index') }}">
          <div class="nav-item-left"><div class="nav-icon">📅</div>Đặt bàn & Phục vụ</div>
          <span class="nav-arrow">›</span>
        </a>

        <div>
          <a class="nav-item {{ Request::routeIs('admin.employees.*') ? 'active' : '' }}"
             href="#" onclick="toggleSub(event,'emp-sub',this)">
            <div class="nav-item-left"><div class="nav-icon">👥</div>Quản lý nhân viên</div>
            <span class="nav-arrow {{ Request::routeIs('admin.employees.*') ? 'rotate-90':'' }}">›</span>
          </a>
          <div class="sub-menu" id="emp-sub"
               style="display:{{ Request::routeIs('admin.employees.*') ? 'flex':'none' }}">
            <a class="sub-item {{ request('role') === 'staff' ? 'active':'' }}"
               href="{{ route('admin.employees.index', ['role' => 'staff']) }}">🧑‍💼 Nhân viên phục vụ</a>
            <a class="sub-item {{ request('role') === 'chef' ? 'active':'' }}"
               href="{{ route('admin.employees.index', ['role' => 'chef']) }}">👨‍🍳 Đầu bếp</a>
          </div>
          
        </div>

      @endif
      {{-- ═══ KẾT THÚC ADMIN ═══ --}}

      {{-- ═══ STAFF ═══ --}}
      @if($role === 'staff' || auth()->user()->role_id == 3)
        <div>
          <a class="nav-item {{ Request::routeIs('admin.tables.*') ? 'active' : '' }}" href="#" onclick="toggleSub(event,'table-sub',this)">
            <div class="nav-item-left"><div class="nav-icon">🪑</div>Quản lý bàn</div>
            <span class="nav-arrow {{ Request::routeIs('admin.tables.*') ? 'rotate-90':'' }}">›</span>
          </a>
          <div class="sub-menu" id="table-sub" style="display:{{ Request::routeIs('admin.tables.*')?'flex':'none' }}">
                    <a class="nav-item {{ Request::routeIs('staff.tables.index') ? 'active' : '' }}" href="{{ route('staff.tables.index') }}">Danh sách bàn</a>
                    <a class="nav-item {{ Request::routeIs('staff.reservations.create') ? 'active' : '' }}" href="{{ route('staff.reservations.create') }}">
                      + Tạo đơn đặt bàn</a>
          </div>
        </div>
        <a class="nav-item {{ Request::routeIs('staff.reservations.index') ? 'active' : '' }}" href="{{ route('staff.reservations.index') }}">
          <div class="nav-item-left"><div class="nav-icon">📋</div>Danh sách phục vụ</div>
        </a>
        
      @endif

      {{-- ═══ CHEF ═══ --}}
      @if($role === 'chef')
        <a class="nav-item {{ Request::routeIs('chef.index') ? 'active' : '' }}" href="{{ route('chef.index') }}">
          <div class="nav-item-left"><div class="nav-icon">🔥</div>Màn hình bếp</div>
          <span class="nav-arrow">›</span>
        </a>
      @endif

      {{-- ═══ CASHIER ═══ --}}
      @if($role === 'cashier')
        <a class="nav-item {{ Request::routeIs('cashier.index') ? 'active' : '' }}" href="{{ route('cashier.index') }}">
          <div class="nav-item-left"><div class="nav-icon">💳</div>Thanh toán</div>
          <span class="nav-arrow">›</span>
        </a>
        <a class="nav-item {{ Request::routeIs('cashier.history') ? 'active' : '' }}" href="{{ route('cashier.history') }}">
          <div class="nav-item-left"><div class="nav-icon">🧾</div>Lịch sử hóa đơn</div>
          <span class="nav-arrow">›</span>
        </a>
      @endif

    </nav>

    <div style="flex:1"></div>

    <div class="sidebar-footer">
      <div class="user-row">
        <div class="avatar">{{ $initials }}</div>
        <div>
          <div class="user-name">{{ $userName }}</div>
          <div class="user-role">
            @php $roleLabels=['admin'=>'Quản trị viên','staff'=>'Nhân viên phục vụ','chef'=>'Đầu bếp','cashier'=>'Thu ngân','customer'=>'Khách hàng']; @endphp
            <span class="role-badge role-{{ $role }}">{{ $roleLabels[$role] ?? $role }}</span>
          </div>
        </div>
      </div>
      <button class="btn-logout" onclick="document.getElementById('logout-form').submit();">🚪 Đăng xuất</button>
    </div>
  </aside>

  <div class="main">
    <header class="topbar">
      <div class="topbar-left">
        <button class="menu-btn">☰</button>
        <span class="topbar-title">@yield('topbar_title','Bảng điều khiển')</span>
      </div>
      <div style="display:flex;align-items:center;gap:14px;">
        <form action="{{ url()->current() }}" method="GET" class="search-box">
  <span>🔍</span>
  <!-- name="search" là tên biến sẽ gửi lên server -->
  <!-- value="{{ request('search') }}" giúp giữ lại từ khóa vừa nhập -->
  <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm...">
  
  <!-- Thêm các input hidden để giữ lại các tham số URL khác nếu có (ví dụ: role=staff) -->
  @foreach(request()->except('search', 'page') as $key => $value)
      <input type="hidden" name="{{ $key }}" value="{{ $value }}">
  @endforeach
</form>
        <div style="font-size:.82rem;color:#64748b;">{{ now()->format('d/m/Y H:i') }}</div>
      </div>
    </header>

    <div class="content">
      @yield('admin_content')
    </div>
  </div>

</div>

@auth
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
@endauth

<script>
function toggleSub(e,id,el){
  e.preventDefault();
  var s=document.getElementById(id);
  var a=el.querySelector('.nav-arrow');
  var o=s.style.display==='flex';
  s.style.display=o?'none':'flex';
  a.classList.toggle('rotate-90',!o);
}
var f=document.getElementById('flash-msg');
if(f)setTimeout(function(){f.style.opacity='0';f.style.transition='opacity .5s';setTimeout(function(){f.remove()},500)},3500);
</script>
<!-- @include('partials.chatbox') -->
</body>
<!-- @include('partials.chatbox') -->
</html>