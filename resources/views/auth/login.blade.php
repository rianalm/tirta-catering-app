<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - Tirta Catering</title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Keyframes untuk Animasi */
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes slideUp {
                from { transform: translateY(20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            
            /* Styling Utama */
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f4f7f6;
            }

            .login-wrapper {
                display: grid;
                grid-template-columns: 1fr 1fr;
                min-height: 100vh;
                animation: fadeIn 0.7s ease-in-out;
            }

            /* Kolom Kiri - Showcase Visual */
            .login-showcase {
                background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('/images/bg-login.jpg');
                background-size: cover;
                background-position: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 40px;
                color: white;
            }
            .login-showcase .logo {
                max-width: 150px;
                margin-bottom: 20px;
                animation: slideUp 0.6s ease-out forwards;
                opacity: 0;
            }
            .login-showcase h1 {
                font-size: 2.5em;
                font-weight: 700;
                text-align: center;
                text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
                animation: slideUp 0.8s ease-out 0.2s forwards;
                opacity: 0;
            }
            .login-showcase p {
                font-size: 1.1em;
                margin-top: 10px;
                text-align: center;
                max-width: 400px;
                line-height: 1.6;
                animation: slideUp 1s ease-out 0.4s forwards;
                opacity: 0;
            }

            /* Kolom Kanan - Form Login */
            .login-form-wrapper {
                background-color: #fff;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 40px;
            }
            .login-form {
                width: 100%;
                max-width: 400px;
            }
            .login-form h2 {
                font-size: 2em;
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 10px;
                animation: slideUp 0.9s ease-out 0.6s forwards;
                opacity: 0;
            }
            .login-form p {
                color: #777;
                margin-bottom: 30px;
                animation: slideUp 1.0s ease-out 0.7s forwards;
                opacity: 0;
            }
            .form-group {
                margin-bottom: 20px;
                animation: slideUp 1.1s ease-out 0.8s forwards;
                opacity: 0;
            }
            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
            }
            .form-group input {
                width: 100%;
                padding: 12px 15px;
                border: 1px solid #ced4da;
                border-radius: 8px;
                font-size: 1em;
                transition: border-color 0.3s, box-shadow 0.3s;
            }
            .form-group input:focus {
                outline: none;
                border-color: #1abc9c;
                box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.2);
            }
            .actions {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 25px;
                animation: slideUp 1.2s ease-out 0.9s forwards;
                opacity: 0;
            }
            .btn-login {
                background-color: #2c3e50;
                color: white;
                padding: 12px 30px;
                font-size: 1em;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                font-weight: 600;
                transition: background-color 0.3s ease;
            }
            .btn-login:hover {
                background-color: #34495e;
            }

            /* Responsive */
            @media (max-width: 992px) {
                .login-wrapper {
                    grid-template-columns: 1fr;
                }
                .login-showcase {
                    display: none; /* Sembunyikan kolom gambar di mobile */
                }
            }
        </style>
    </head>
    <body>
        <div class="login-wrapper">
            <div class="login-showcase">
                <img src="/images/logo-tirta.png" alt="Logo Tirta Catering" class="logo">
                <h1>Tirta Catering</h1>
                <p>Sistem Manajemen Catering Profesional untuk mendukung operasional bisnis Anda.</p>
            </div>
    
            <div class="login-form-wrapper">
                <div class="login-form">
                    <h2>Selamat Datang!</h2>
                    <p>Silakan masuk untuk melanjutkan ke dashboard.</p>
                    
                    <x-auth-session-status class="mb-4" :status="session('status')" />
    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
    
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
    
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
    
                        <div class="actions">
                            <label for="remember" class="inline-flex items-center">
                                <input id="remember" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span style="margin-left: 8px; font-size: 0.9em;">Ingat Saya</span>
                            </label>
    
                            <button type="submit" class="btn-login">
                                Log In
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>