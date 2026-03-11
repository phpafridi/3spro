{{-- resources/views/service/jobcard/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Advisor Dashboard</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Service Advisor Panel</span>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    Welcome, {{ session('user_name') }} ({{ session('position') }})
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2>Service Advisor Dashboard</h2>
                <p>Your department: {{ session('dept') }}</p>
                <p>Login time: {{ date('Y-m-d H:i:s', session('login_waqat')) }}</p>
            </div>
        </div>
    </div>
</body>
</html>
