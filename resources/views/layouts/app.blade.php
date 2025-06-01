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
    <body class="font-sans antialiased bg-[#f8f0e3] dark:bg-[#0a0a0a] text-slate-800 dark:text-slate-200 min-h-screen">
        <div class="relative">
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

            <div class="min-h-screen flex flex-col">
                <!-- Navigation -->
                <nav class="border-b border-gray-200/30 dark:border-gray-700/30 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md relative z-10">
                    <div class="mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <a href="{{ route('welcome') }}" class="text-2xl font-semibold text-[#3498db] dark:text-[#5dade2]">
                                        Windkracht <span class="text-[#e67e22]">13</span>
                                    </a>
                                </div>

                                <!-- Navigation Links -->
                                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                        {{ __('Dashboard') }}
                                    </x-nav-link>
                                    
                                    @auth
                                        @if(Auth::user()->isCustomer())
                                            <x-nav-link :href="route('customer.reservations.index')" :active="request()->routeIs('customer.reservations.*')">
                                                {{ __('My Reservations') }}
                                            </x-nav-link>
                                        @elseif(Auth::user()->isInstructor())
                                            <x-nav-link :href="route('instructor.schedule')" :active="request()->routeIs('instructor.schedule*')">
                                                {{ __('My Schedule') }}
                                            </x-nav-link>
                                        @elseif(Auth::user()->isAdmin())
                                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')">
                                                {{ __('Users') }}
                                            </x-nav-link>
                                        @endif
                                        
                                        {{-- <x-nav-link :href="route('booking')" :active="request()->routeIs('booking')">
                                            {{ __('Book Lesson') }}
                                        </x-nav-link> --}}
                                    @endauth
                                </div>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="hidden sm:flex sm:items-center sm:ml-6">
                                @auth
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                                <div>{{ Auth::user()->name }}</div>

                                                <div class="ml-1">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <x-dropdown-link :href="route('profile.edit')">
                                                {{ __('Profile') }}
                                            </x-dropdown-link>

                                            <x-dropdown-link :href="route('profile.notifications')">
                                                {{ __('Notification Settings') }}
                                            </x-dropdown-link>

                                            <!-- Authentication -->
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <x-dropdown-link :href="route('logout')"
                                                        onclick="event.preventDefault();
                                                                    this.closest('form').submit();">
                                                    {{ __('Log Out') }}
                                                </x-dropdown-link>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                @else
                                    <div class="space-x-4">
                                        <a href="{{ route('login') }}" class="font-medium text-[#3498db] hover:text-[#2980b9]">Log In</a>
                                        <a href="{{ route('register') }}" class="font-medium px-4 py-2 bg-[#3498db] hover:bg-[#2980b9] text-white rounded-lg">Register</a>
                                    </div>
                                @endauth
                            </div>

                            <!-- Hamburger -->
                            <div class="-mr-2 flex items-center sm:hidden">
                                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Navigation Menu -->
                    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                        <div class="pt-2 pb-3 space-y-1">
                            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-responsive-nav-link>
                            
                            @auth
                                @if(Auth::user()->isCustomer())
                                    <x-responsive-nav-link :href="route('customer.reservations.index')" :active="request()->routeIs('customer.reservations.*')">
                                        {{ __('My Reservations') }}
                                    </x-responsive-nav-link>
                                @elseif(Auth::user()->isInstructor())
                                    <x-responsive-nav-link :href="route('instructor.schedule')" :active="request()->routeIs('instructor.schedule*')">
                                        {{ __('My Schedule') }}
                                    </x-responsive-nav-link>
                                @elseif(Auth::user()->isAdmin())
                                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')">
                                        {{ __('Users') }}
                                    </x-responsive-nav-link>
                                @endif
                                
                                {{-- <x-responsive-nav-link :href="route('booking')" :active="request()->routeIs('booking')">
                                    {{ __('Book Lesson') }}
                                </x-responsive-nav-link> --}}
                            @endauth
                        </div>

                        <!-- Responsive Settings Options -->
                        @auth
                            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                                <div class="px-4">
                                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                </div>

                                <div class="mt-3 space-y-1">
                                    <x-responsive-nav-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-responsive-nav-link>

                                    <x-responsive-nav-link :href="route('profile.notifications')">
                                        {{ __('Notification Settings') }}
                                    </x-responsive-nav-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-responsive-nav-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-responsive-nav-link>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                                <div class="mt-3 space-y-1 px-4">
                                    <x-responsive-nav-link :href="route('login')">
                                        {{ __('Login') }}
                                    </x-responsive-nav-link>
                                    <x-responsive-nav-link :href="route('register')">
                                        {{ __('Register') }}
                                    </x-responsive-nav-link>
                                </div>
                            </div>
                        @endauth
                    </div>
                </nav>

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="py-6 sm:py-8 relative z-10">
                        <div class="mx-auto px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Flash Messages -->
                @if (session('status'))
                    <div class="mx-auto px-4 sm:px-6 lg:px-8 mt-4 mb-2">
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md" role="alert">
                            <p>{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mx-auto px-4 sm:px-6 lg:px-8 mt-4 mb-2">
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                <main class="flex-grow relative z-10">
                    @if (isset($slot))
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
                </main>

                <!-- Footer -->
                <footer class="relative z-10 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md py-6 mt-12 border-t border-gray-200/30 dark:border-gray-700/30">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="mb-4 md:mb-0">
                                <div class="text-xl font-semibold text-[#3498db] dark:text-[#5dade2]">
                                    Windkracht <span class="text-[#e67e22]">13</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    &copy; {{ date('Y') }} Windkracht 13 Kitesurf School. All rights reserved.
                                </p>
                            </div>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#3498db] dark:hover:text-[#5dade2]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#3498db] dark:hover:text-[#5dade2]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#3498db] dark:hover:text-[#5dade2]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                                        <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>

<nav class="navbar">
    <!-- Add this at the end of your navbar, before the closing nav tag -->
    @auth
        <div class="ml-auto" style="margin-left: auto;">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <span class="mr-2">{{ Auth::user()->name }}</span>
                <button type="submit" class="btn btn-link">Logout</button>
            </form>
        </div>
    @endauth
</nav>
