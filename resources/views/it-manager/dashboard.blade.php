{{-- resources/views/it-manager/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Manager Dashboard - MG Khyber Motors</title>

    <link href="{{ asset('css/bootstrap.minv4.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .navbar { background: rgba(255,255,255,0.95); box-shadow: 0 2px 20px rgba(0,0,0,0.1); padding: 1rem 2rem; }
        .navbar-brand { font-size: 1.5rem; font-weight: bold; color: #333; }
        .navbar-brand img { height: 40px; margin-right: 10px; }
        .user-info { display: flex; align-items: center; gap: 20px; }
        .user-details { text-align: right; }
        .user-name { font-weight: bold; color: #333; }
        .user-role { font-size: 0.85rem; color: #666; }
        .logout-btn { background: #ff4757; color: white; border: none; padding: 8px 20px; border-radius: 5px; cursor: pointer; transition: all 0.3s; }
        .logout-btn:hover { background: #ff3344; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,71,87,0.3); }
        .container { max-width: 1400px; margin: 100px auto 50px; padding: 0 20px; }
        .welcome-section { background: white; border-radius: 20px; padding: 30px; margin-bottom: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .welcome-title { font-size: 2.5rem; color: #333; margin-bottom: 10px; }
        .welcome-subtitle { color: #666; font-size: 1.1rem; }
        .session-info { background: #f8f9fa; border-radius: 10px; padding: 15px; margin-top: 20px; border-left: 4px solid #667eea; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 30px; }
        .menu-item { background: white; border-radius: 15px; padding: 30px 25px; text-align: center; transition: all 0.3s ease; position: relative; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-decoration: none; display: block; color: inherit; }
        .menu-item:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); color: inherit; text-decoration: none; }
        .menu-item::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #667eea, #764ba2); }
        .menu-icon { font-size: 3rem; margin-bottom: 20px; color: #667eea; }
        .menu-title { font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 10px; }
        .menu-description { color: #666; font-size: 0.95rem; line-height: 1.6; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; border-radius: 15px; padding: 20px; display: flex; align-items: center; gap: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .stat-icon { width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; }
        .stat-info h3 { font-size: 1rem; color: #666; margin-bottom: 5px; }
        .stat-info p { font-size: 1.5rem; font-weight: bold; color: #333; margin: 0; }
        .quick-actions { background: white; border-radius: 15px; padding: 25px; margin-top: 40px; }
        .quick-actions h3 { color: #333; margin-bottom: 20px; font-size: 1.3rem; }
        .action-buttons { display: flex; gap: 15px; flex-wrap: wrap; }
        .action-btn { padding: 10px 25px; border-radius: 8px; text-decoration: none; color: white; font-weight: 500; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); color: white; text-decoration: none; }
        .action-btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .action-btn-success { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .action-btn-warning { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        @media (max-width: 768px) {
            .navbar { padding: 1rem; }
            .user-info { gap: 10px; }
            .welcome-title { font-size: 1.8rem; }
            .menu-grid { grid-template-columns: 1fr; }
            .action-buttons { flex-direction: column; }
            .action-btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

    <nav class="navbar fixed-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="navbar-brand d-flex align-items-center">
                <img src="{{ asset(config('app.logo', 'src/3spro.png')) }}" alt="Logo">
                <span>{{ config('app.name') }} - IT Manager Portal</span>
            </div>
            <div class="user-info">
                <div class="user-details">
                    <div class="user-name">{{ $user_name }}</div>
                    <div class="user-role">{{ $position }} | {{ $dept }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">

        <div class="welcome-section">
            <h1 class="welcome-title">Welcome back, {{ $user_name }}! 👋</h1>
            <p class="welcome-subtitle">IT Manager Dashboard - Monitor and manage all departments from one place</p>
            @php
                $login_timestamp  = isset($login_time) && is_numeric($login_time) ? $login_time : time();
                $session_duration = time() - $login_timestamp;
                $hours   = floor($session_duration / 3600);
                $minutes = floor(($session_duration % 3600) / 60);
                $seconds = $session_duration % 60;
            @endphp
            <div class="session-info">
                <i class="fas fa-clock me-2"></i>
                Logged in: <strong>{{ date('Y-m-d H:i:s', $login_timestamp) }}</strong> |
                Session duration: <strong>{{ $hours }}h {{ $minutes }}m {{ $seconds }}s</strong>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-info"><h3>Active Users</h3><p>24</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-tasks"></i></div>
                <div class="stat-info"><h3>Open Jobs</h3><p>156</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info"><h3>Pending Tasks</h3><p>42</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-info"><h3>Alerts</h3><p>3</p></div>
            </div>
        </div>

        <div class="menu-grid">
            @foreach($menu_items as $item)
            <a href="{{ $item['url'] }}" class="menu-item">
                <div class="menu-icon">
                    @switch($item['icon'])
                        @case('services')  <i class="fas fa-cogs"></i> @break
                        @case('sales')     <i class="fas fa-chart-line"></i> @break
                        @case('finance')   <i class="fas fa-chart-line"></i> @break
                        @case('book')      <i class="fas fa-book"></i> @break
                        @case('job')       <i class="fas fa-clipboard-list"></i> @break
                        @case('advisor')   <i class="fas fa-headset"></i> @break
                        @case('parts')     <i class="fas fa-boxes"></i> @break
                        @case('cr')        <i class="fas fa-handshake"></i> @break
                        @case('recovery')  <i class="fas fa-hand-holding-usd"></i> @break
                        @case('cashier')   <i class="fas fa-cash-register"></i> @break
                        @case('car')   <i class="fas fa-car"></i> @break
                        @default           <i class="fas fa-folder"></i>
                    @endswitch
                </div>
                <h2 class="menu-title">{{ $item['title'] }}</h2>
                <p class="menu-description">{{ $item['description'] }}</p>
            </a>
            @endforeach
        </div>

        <div class="quick-actions">
            <h3><i class="fas fa-bolt me-2"></i>Quick Actions</h3>
            <div class="action-buttons">
                <a href="#" class="action-btn action-btn-primary"><i class="fas fa-user-plus"></i> Add New User</a>
                <a href="#" class="action-btn action-btn-success"><i class="fas fa-database"></i> Backup Database</a>
                <a href="#" class="action-btn action-btn-warning"><i class="fas fa-chart-bar"></i> System Reports</a>
                <a href="#" class="action-btn action-btn-primary"><i class="fas fa-cog"></i> System Settings</a>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/bootstrap.minv4.js') }}"></script>
</body>
</html>
