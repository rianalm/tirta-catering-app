{{-- resources/views/layouts/partials/admin_navbar.blade.php --}}
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">Tirta Catering</a>
    </div>

    {{-- BAGIAN PROFIL PENGGUNA BARU --}}
    <div class="user-panel">
        <div class="user-info">
            {{-- Pastikan user sudah login untuk mengakses properti ini --}}
            @auth
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role">{{ Auth::user()->getRoleNames()->first() }}</span>
            @endauth
        </div>
    </div>
    {{-- AKHIR BAGIAN PROFIL PENGGUNA --}}

    <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Dashboard</span>
            </a>
        </li>

        @hasanyrole('admin|tim_dapur|tim_packing|driver')
        <li class="nav-item">
            <a href="{{ route('admin.pesanan.operasional') }}" class="nav-link {{ request()->routeIs('admin.pesanan.operasional') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Daftar Pesanan Harian</span>
            </a>
        </li>
        @endhasanyrole
        
        @hasrole('admin')
            <li class="nav-header" style="padding: 10px 20px; font-size: 0.8em; color: #95a5a6; margin-top:15px;">ADMINISTRASI</li>
            <li class="nav-item">
                <a href="{{ route('admin.pesanan.index') }}" class="nav-link {{ request()->routeIs('admin.pesanan.index', 'admin.pesanan.create', 'admin.pesanan.edit', 'admin.pesanan.show') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span>Kelola Semua Pesanan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.produks.index') }}" class="nav-link {{ request()->routeIs('admin.produks.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span>Kelola Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.komponen-masakan.index') }}" class="nav-link {{ request()->routeIs('admin.komponen-masakan.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7.014A8.003 8.003 0 0112 3c1.398 0 2.743.57 3.714 1.586C18.5 6.5 19 9 19 11c2 1 2.657 1.657 2.657 1.657a8 8 0 01-14 6z"></path></svg>
                    <span>Kelola Komponen</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link {{ request()->routeIs('admin.laporan.penjualan') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Laporan Penjualan</span>
                </a>
            </li>
        @endhasrole

         @hasrole('admin')
            <li class="nav-header" style="padding: 10px 20px; font-size: 0.8em; color: #95a5a6; margin-top:15px;">ADMINISTRASI</li>
    
    {{-- ... (menu Kelola Pesanan, Produk, Komponen, Laporan) ... --}}

    {{-- TAMBAHKAN MENU BARU DI SINI --}}
             <li class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            {{-- Menggunakan ikon dari Font Awesome --}}
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0012 12a5.995 5.995 0 00-3-5.197M15 21a2 2 0 01-2 2h-2a2 2 0 01-2-2m9-13a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V7a2 2 0 012-2h2z"></path></svg>
            <span>Kelola User</span>
        </a>
    </li>
        @endhasrole
        <li class="nav-item" style="margin-top: auto; padding-top: 20px; border-top: 1px solid #34495e;">
            <a href="{{ route('logout') }}" class="nav-link" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path></svg>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</aside>