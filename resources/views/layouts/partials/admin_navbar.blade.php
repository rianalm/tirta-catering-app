{{-- resources/views/layouts/partials/admin_navbar.blade.php --}}
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">Tirta Catering</a>
    </div>

    <ul class="sidebar-nav">
        {{-- Daftar menu navigasi Anda seperti versi terakhir --}}
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt nav-icon"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @hasanyrole('admin|tim_dapur|tim_packing|driver')
        <li class="nav-item">
            <a href="{{ route('admin.pesanan.operasional') }}" class="nav-link {{ request()->routeIs('admin.pesanan.operasional*') ? 'active' : '' }}">
                <i class="fas fa-calendar-day nav-icon"></i>
                <span>Daftar Pesanan Harian</span>
            </a>
        </li>
        @endhasanyrole

        @hasanyrole('admin|tim_dapur|tim_packing')
        <li class="nav-item">
            <a href="{{ route('admin.laporan.dapur') }}" class="nav-link {{ request()->routeIs('admin.laporan.dapur') ? 'active' : '' }}">
                <i class="fas fa-utensils nav-icon"></i>
                <span>Lap. Kebutuhan Dapur</span>
            </a>
        </li>
        @endhasanyrole
        
        @hasrole('admin')
            <li class="nav-header">ADMINISTRASI</li>
            <li class="nav-item">
                <a href="{{ route('admin.pesanan.index') }}" class="nav-link {{ request()->routeIs(['admin.pesanan.index', 'admin.pesanan.create', 'admin.pesanan.edit', 'admin.pesanan.show']) ? 'active' : '' }}">
                    <i class="fas fa-file-invoice nav-icon"></i>
                    <span>Kelola Semua Pesanan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.produks.index') }}" class="nav-link {{ request()->routeIs('admin.produks.*') ? 'active' : '' }}">
                    <i class="fas fa-box-open nav-icon"></i>
                    <span>Kelola Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.komponen-masakan.index') }}" class="nav-link {{ request()->routeIs('admin.komponen-masakan.*') ? 'active' : '' }}">
                    <i class="fas fa-lemon nav-icon"></i>
                    <span>Kelola Komponen</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link {{ request()->routeIs('admin.laporan.penjualan') ? 'active' : '' }}">
                    <i class="fas fa-chart-line nav-icon"></i>
                    <span>Laporan Penjualan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog nav-icon"></i>
                    <span>Kelola User</span>
                </a>
            </li>
        @endhasrole
    </ul>
</aside>