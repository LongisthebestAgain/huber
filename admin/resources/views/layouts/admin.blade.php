<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #111;
            min-height: 100vh;
            margin: 0;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background: #fff;
            color: #111;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            padding: 2rem 1rem 1rem 1rem;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
        }

        .sidebar .sidebar-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            letter-spacing: 1px;
            text-align: center;
            color: #111;
        }

        .sidebar-nav {
            flex: 1;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #111;
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
        }

        .sidebar-nav a.active {
            background: #111;
            color: #fff;
            font-weight: 700;
        }

        .sidebar-nav a:hover {
            background: #f1f1f1;
            color: #111;
        }

        .sidebar-nav i {
            font-size: 1.2rem;
        }

        .sidebar .logout-form {
            margin-top: 2rem;
            text-align: center;
        }

        .sidebar .logout-btn {
            width: 100%;
            background: #fff;
            color: #e74c3c;
            border: 1px solid #e74c3c;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .sidebar .logout-btn:hover {
            background: #e74c3c;
            color: #fff;
        }

        .admin-main {
            margin-left: 220px;
            flex: 1;
            padding: 2.5rem 2rem 2rem 2rem;
            min-height: 100vh;
            background: #fff;
        }

        .admin-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f1f3f4;
        }

        .admin-title {
            color: #111;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .admin-subtitle {
            color: #555;
            font-weight: 400;
            font-size: 1.1rem;
        }

        .admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .admin-card {
            background: #fff;
            border-radius: 15px;
            padding: 1.5rem;
            text-decoration: none;
            color: #111;
            transition: box-shadow 0.2s;
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .admin-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            text-decoration: none;
            color: #111;
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
            color: #111;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #111;
        }

        .card-description {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Action button colors */
        .btn-success {
            background: #27ae60 !important;
            border-color: #27ae60 !important;
            color: #fff !important;
        }
        .btn-success:hover {
            background: #219150 !important;
            border-color: #219150 !important;
        }
        .btn-primary {
            background: #007bff !important;
            border-color: #007bff !important;
            color: #fff !important;
        }
        .btn-primary:hover {
            background: #0056b3 !important;
            border-color: #0056b3 !important;
        }
        .btn-danger {
            background: #e74c3c !important;
            border-color: #e74c3c !important;
            color: #fff !important;
        }
        .btn-danger:hover {
            background: #c0392b !important;
            border-color: #c0392b !important;
        }
        .table {
            background: #fff;
            color: #111;
            border-radius: 10px;
            overflow: hidden;
        }
        .table th, .table td {
            border-color: #e5e7eb !important;
        }
        .table thead {
            background: #f8f9fa;
        }
        .card, .table, .form-control, .form-select {
            box-shadow: none !important;
        }
        .form-control, .form-select {
            background: #fff;
            color: #111;
            border: 1px solid #e5e7eb;
        }
        .form-control:focus, .form-select:focus {
            border-color: #111;
            box-shadow: 0 0 0 2px #1111;
        }
        .success-alert {
            background: #27ae60;
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
        }
        .error-alert {
            background: #e74c3c;
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
        }
        @media (max-width: 991px) {
            .sidebar {
                width: 70px;
                padding: 1rem 0.5rem;
            }
            .sidebar .sidebar-title, .sidebar-nav a span {
                display: none;
            }
            .admin-main {
                margin-left: 70px;
                padding: 1.5rem 0.5rem 1rem 0.5rem;
            }
        }
        @media (max-width: 768px) {
            .admin-header {
                padding-bottom: 1rem;
            }
            .admin-title {
                font-size: 2rem;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-title mb-4">
                <i class="fas fa-cogs me-2"></i> Admin
            </div>
            <div class="sidebar-nav">
                <div class="sidebar-section-title" style="font-size:0.85rem; color:#888; text-transform:uppercase; margin:1.5rem 0 0.5rem 0; letter-spacing:1px;">Users</div>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> <span>Users</span>
                </a>
                <div class="sidebar-section-title" style="font-size:0.85rem; color:#888; text-transform:uppercase; margin:1.5rem 0 0.5rem 0; letter-spacing:1px;">Drivers</div>
                <a href="{{ route('admin.drivers.index') }}" class="{{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i> <span>Drivers</span>
                </a>
                <a href="{{ route('admin.rides.index') }}" class="{{ request()->routeIs('admin.rides.*') ? 'active' : '' }}">
                    <i class="fas fa-car"></i> <span>Rides</span>
                </a>
                <a href="{{ route('admin.driver_documents.index') }}" class="{{ request()->routeIs('admin.driver_documents.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> <span>Driver Documents</span>
                </a>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>
        </nav>
        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">@yield('title', 'Admin Dashboard')</h1>
                <p class="admin-subtitle">@yield('subtitle', 'Manage your application with ease')</p>
            </div>
            @if(session('success'))
                <div class="success-alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="error-alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="error-alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html> 