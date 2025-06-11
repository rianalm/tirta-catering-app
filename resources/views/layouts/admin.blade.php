{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Area') - Tirta Catering</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body, h1, h2, h3, p, ul, li { margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #f0f2f5; color: #333; line-height: 1.6; }
        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        .admin-wrapper { display: flex; min-height: 100vh; }
        .admin-sidebar {
            width: 260px; background-color: #2c3e50; color: #ecf0f1; padding: 20px 0;
            display: flex; flex-direction: column; position: fixed; height: 100%; overflow-y: auto;
        }
        .sidebar-header { padding: 0 20px 20px 20px; text-align: center; border-bottom: 1px solid #34495e; margin-bottom: 20px; }
        .sidebar-brand { font-size: 1.8em; font-weight: 700; color: #ffffff; }
        .sidebar-nav { flex-grow: 1; }
        .nav-item { margin-bottom: 5px; }
        .nav-link {
            display: flex; align-items: center; padding: 12px 20px; color: #bdc3c7;
            transition: background-color 0.2s ease, color 0.2s ease; font-size: 1em;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #34495e; color: #ffffff; border-left: 3px solid #1abc9c; padding-left: 17px;
        }
        .nav-icon { width: 20px; height: 20px; margin-right: 15px; stroke-width: 2; }
        .admin-main-content { margin-left: 260px; flex-grow: 1; padding: 30px; overflow-y: auto; }
        .content-header h1 { color: #2c3e50; margin-bottom: 25px; font-size: 2.2em; font-weight: 700; text-align: left; }
        .container-content { background-color: #ffffff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); }
        .btn {
            padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease; border: none; font-size: 0.95em;
            display: inline-flex; align-items: center; justify-content: center;
        }
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
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 1em; font-weight: 500; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error, .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* CSS BARU UNTUK USER PANEL DI SIDEBAR */
        .user-panel {
            padding: 10px 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #34495e;
            border-top: 1px solid #34495e;
        }
        .user-info {
            color: #fff;
            text-align: center;
        }
        .user-name {
            display: block;
            font-weight: 600;
            font-size: 1.1em;
        }
        .user-role {
            display: block;
            font-size: 0.8em;
            color: #1abc9c; /* Warna aksen untuk peran */
            text-transform: capitalize;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        @include('layouts.partials.admin_navbar')
        <main class="admin-main-content">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>