<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HUYMOI Store') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f3460 100%);
            min-height: 100vh;
        }
        .auth-card {
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .auth-input {
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            padding: 10px 14px;
            width: 100%;
            font-size: 15px;
            transition: border-color 0.2s;
        }
        .auth-input:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56,189,248,0.15);
        }
        .auth-btn {
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 11px 28px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .auth-btn:hover { opacity: 0.9; }
        .auth-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        {{-- LOGO --}}
        <div class="mb-6">
            <a href="{{ route('home') }}">
                <svg width="220" height="60" viewBox="0 0 220 60"
                     fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0" y="4" width="52" height="52" rx="12" fill="#0f172a"/>
                    <rect x="8" y="13" width="36" height="24" rx="4"
                          fill="none" stroke="#38bdf8" stroke-width="2.5"/>
                    <rect x="20" y="37" width="12" height="4" rx="2" fill="#38bdf8"/>
                    <rect x="16" y="41" width="20" height="4" rx="2" fill="#38bdf8"/>
                    <rect x="12" y="18" width="24" height="2.5" rx="1.2"
                          fill="#38bdf8" opacity="0.7"/>
                    <rect x="12" y="24" width="17" height="2.5" rx="1.2"
                          fill="#38bdf8" opacity="0.5"/>
                    <rect x="12" y="30" width="21" height="2.5" rx="1.2"
                          fill="#38bdf8" opacity="0.3"/>
                    <circle cx="40" cy="16" r="4" fill="#f472b6"/>
                    <text x="62" y="34"
                          font-family="Arial, sans-serif"
                          font-weight="700"
                          font-size="24"
                          letter-spacing="2"
                          fill="#f8fafc">HUYMOI</text>
                    <text x="65" y="52"
                          font-family="Arial, sans-serif"
                          font-weight="400"
                          font-size="12"
                          letter-spacing="7"
                          fill="#38bdf8">STORE</text>
                </svg>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="w-full sm:max-w-md px-8 py-8 bg-white auth-card">
            {{ $slot }}
        </div>

        {{-- Link về trang chủ --}}
        <div class="mt-4">
            <a href="{{ route('home') }}"
               style="color:#94a3b8; font-size:14px; text-decoration:none">
                ← Quay về trang chủ
            </a>
        </div>
    </div>
</body>
</html>