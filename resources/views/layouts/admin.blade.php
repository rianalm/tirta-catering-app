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
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
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