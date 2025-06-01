<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Windkracht 13') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fira-sans:300,400,500,600|instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#f8f0e3] dark:bg-[#0a0a0a] text-slate-800 dark:text-slate-200">
        <div class="relative min-h-screen">
            <!-- Wave Background Element -->
            <div class="absolute inset-0 overflow-hidden z-0 pointer-events-none">
                <div class="absolute bottom-0 left-0 w-full h-64 bg-[#3498db]/20 dark:bg-[#1e5f8c]/20" style="
                    mask-image: url('data:image/svg+xml;utf8,<svg viewBox=\"0 0 1440 320\" xmlns=\"http://www.w3.org/2000/svg\"><path fill=\"currentColor\" d=\"M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,192C672,181,768,139,864,128C960,117,1056,139,1152,149.3C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z\"></path></svg>');
                    -webkit-mask-image: url('data:image/svg+xml;utf8,<svg viewBox=\"0 0 1440 320\" xmlns=\"http://www.w3.org/2000/svg\"><path fill=\"currentColor\" d=\"M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,192C672,181,768,139,864,128C960,117,1056,139,1152,149.3C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z\"></path></svg>');
                    mask-size: cover;
                    -webkit-mask-size: cover;
                    mask-repeat: no-repeat;
                    -webkit-mask-repeat: no-repeat;
                    mask-position: bottom;
                    -webkit-mask-position: bottom;
                ">
                </div>
            </div>

            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0 relative z-10">
                <!-- Logo -->
                <div class="mt-10">
                    <a href="{{ route('welcome') }}" class="text-3xl font-semibold text-[#3498db] dark:text-[#5dade2]">
                        Windkracht <span class="text-[#e67e22]">13</span>
                    </a>
                </div>

                <!-- Flash Messages -->
                @if (session('status'))
                    <div class="mt-4 mb-4 w-full max-w-md px-4">
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md" role="alert">
                            <p>{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-4 mb-4 w-full max-w-md px-4">
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden rounded-2xl shadow-md">
                    @if (isset($slot))
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
                </div>

                <div class="w-full sm:max-w-md mt-4 px-6 py-2 text-center">
                    <a href="{{ route('welcome') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-[#3498db] dark:hover:text-[#5dade2]">
                        ← Back to Home Page
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
