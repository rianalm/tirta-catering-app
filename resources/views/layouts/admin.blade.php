<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Area') - Tirta Catering</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- PERUBAHAN: Menggunakan link CDN Font Awesome versi lebih baru --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        /* ... (Seluruh CSS lengkap Anda dari respons sebelumnya tetap di sini) ... */
        *, *::before, *::after { box-sizing: border-box; }
        body, h1, h2, h3, p, ul, li { margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; }
        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        .admin-wrapper { display: flex; min-height: 100vh; position: relative; }
        .admin-sidebar {
            width: 260px; background-color: #2c3e50; color: #ecf0f1; position: fixed;
            height: 100%; z-index: 1010; display: flex; flex-direction: column;
            transform: translateX(0); transition: transform 0.3s ease-in-out;
        }
        .admin-sidebar.collapsed { transform: translateX(-100%); }
        .sidebar-header { padding: 20px; text-align: center; }
        .sidebar-brand { font-size: 1.8em; font-weight: 700; color: #ffffff; }
        .sidebar-nav { flex-grow: 1; overflow-y: auto; }
        .nav-link {
            display: flex; align-items: center; padding: 12px 20px; color: #bdc3c7;
            border-left: 3px solid transparent; transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active { background-color: #34495e; color: #ffffff; border-left-color: #1abc9c; }
        .nav-icon { width: 20px; height: 20px; margin-right: 15px; flex-shrink: 0; }
        .nav-header { padding: 25px 20px 10px; font-size: 0.8em; color: #95a5a6; text-transform: uppercase; font-weight: 600; }
        .main-panel {
            margin-left: 260px; width: calc(100% - 260px);
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        .main-panel.sidebar-collapsed { margin-left: 0; width: 100%; }
        .admin-topbar {
            background-color: #ffffff; padding: 0 30px; border-bottom: 1px solid #e9ecef;
            display: flex; justify-content: space-between; align-items: center;
            height: 65px; position: sticky; top: 0; z-index: 1000;
        }
        #sidebar-toggle { background: none; border: none; font-size: 1.4em; cursor: pointer; color: #333; padding: 10px; }
        .user-dropdown { position: relative; }
        .user-dropdown-toggle { display: flex; align-items: center; cursor: pointer; padding: 10px; }
        .user-name { margin-right: 15px; font-weight: 600; color: #333; }
        .user-avatar { font-size: 1.8em; color: #6c757d; }
        .user-dropdown-menu {
            display: none; position: absolute; right: 0; top: 110%; background-color: white;
            min-width: 200px; box-shadow: 0 8px 16px rgba(0,0,0,0.1); z-index: 1;
            border-radius: 8px; overflow: hidden; border: 1px solid #eee;
        }
        .user-dropdown-menu button {
            color: black; padding: 12px 16px; text-decoration: none; display: flex;
            align-items: center; gap: 10px; width: 100%; border: none;
            background: none; text-align: left; font-size: 0.95em; cursor: pointer;
        }
        .user-dropdown-menu button:hover { background-color: #f1f1f1; }
        .user-dropdown:hover .user-dropdown-menu { display: block; }
        .admin-main-content { padding: 30px; }
        .content-header h1 { color: #2c3e50; margin-bottom: 25px; font-size: 2.2em; }
        .container-content { background-color: #ffffff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 1em; }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 1005; }
        .sidebar-overlay.active { display: block; }
         .fas, .fa-solid {
        font-family: 'Font Awesome 6 Free' !important;
        font-weight: 900 !important;
        }
        .far, .fa-regular {
            font-family: 'Font Awesome 6 Free' !important;
            font-weight: 400 !important;
        }
        .fab, .fa-brands {
            font-family: 'Font Awesome 6 Brands' !important;
        }
        .btn { padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; cursor: pointer; transition: all 0.2s ease; border: none; font-size: 0.95em; display: inline-flex; align-items: center; justify-content: center; } 
        .btn-sm { padding: 8px 12px !important; font-size: 0.85em !important; } 
        .btn-primary { background-color: #007bff; color: white; } 
        .btn-primary:hover { background-color: #0056b3; } 
        .btn-success { background-color: #28a745; color: white; } 
        .btn-success:hover { background-color: #1e7e34; } 
        .btn-danger { background-color: #dc3545; color: white; } 
        .btn-danger:hover { background-color: #b02a37; } 
        .btn-warning { background-color: #ffc107; color: #212529; } 
        .btn-warning:hover { background-color: #d39e00; } 
        .btn-info { background-color: #17a2b8; color: white; } 
        .btn-info:hover { background-color: #117a8b; } 
        .btn-secondary { background-color: #6c757d; color: white; } 
        .btn-secondary:hover { background-color: #545b62; } 
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 1em; font-weight: 500; border: 1px solid transparent; } 
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; } 
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; } 
        @media (max-width: 992px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .main-panel { margin-left: 0; width: 100%; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        @include('layouts.partials.admin_navbar')
        <div class="main-panel">
            <header class="admin-topbar">
                <button id="sidebar-toggle"><i class="fas fa-bars"></i></button>
                <div class="user-dropdown">
                    <div class="user-dropdown-toggle">
                        @auth<span class="user-name">{{ Auth::user()->name }}</span>@endauth
                        <i class="fas fa-user-circle user-avatar"></i>
                    </div>
                    <div class="user-dropdown-menu">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                        <button onclick="document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-fw"></i>
                            <span>Logout</span>
                        </button>
                    </div>
                </div>
            </header>
            <main class="admin-main-content">
                @yield('content')
            </main>
        </div>
        <div class="sidebar-overlay"></div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.admin-sidebar');
            const mainPanel = document.querySelector('.main-panel');
            const sidebarToggleBtn = document.getElementById('sidebar-toggle');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');
            function toggleSidebar() {
                const isMobile = window.innerWidth <= 992;
                if (isMobile) {
                    sidebar.classList.toggle('open');
                    sidebarOverlay.classList.toggle('active');
                } else {
                    sidebar.classList.toggle('collapsed');
                    mainPanel.classList.toggle('sidebar-collapsed');
                }
            }
            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', toggleSidebar);
            }
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>