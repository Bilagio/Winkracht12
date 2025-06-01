<!-- filepath: c:\Users\bilag\Herd\kitesurf\resources\views\welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Windkracht 13 Kitesurf School - Learn kitesurfing with professional instructors">

        <title>Windkracht 13 - Kitesurf School</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fira-sans:300,400,500,600|instrument-sans:400,500,600" rel="stylesheet" />
        
        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f8f0e3] dark:bg-[#0a0a0a] text-slate-800 dark:text-slate-200 min-h-screen">
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

            <!-- Main Content -->
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <!-- Flash Messages -->
                @if (session('status'))
                    <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('message'))
                    <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Header Section -->
                <header class="py-6 flex justify-between items-center">
                    <div class="text-2xl lg:text-3xl font-semibold text-[#3498db] dark:text-[#5dade2]">
                        Windkracht <span class="text-[#e67e22]">13</span>
                    </div>
                    <!-- Dynamic navigation based on user role -->
                    <nav class="hidden md:flex items-center space-x-8">
                        @if (Route::has('login'))
                            @auth
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Admin Dashboard</a>
                                @elseif(Auth::user()->isInstructor())
                                    <a href="{{ route('instructor.dashboard') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Instructor Dashboard</a>
                                    <a href="{{ route('user.bookings') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">My Schedule</a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Dashboard</a>
                                    <a href="{{ route('user.bookings') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">My Bookings</a>
                                @endif
                                
                                <!-- Logout Button with Success Message -->
                                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                        Logout
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Register</a>
                                @endif
                            @endauth
                        @endif
                        <a href="#about" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">About</a>
                        <a href="#lessons" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Lessons</a>
                        <a href="#contact" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Contact</a>
                    </nav>
                    <!-- Mobile Menu Button -->
                    <button type="button" class="md:hidden text-slate-800 dark:text-slate-200" id="mobile-menu-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </header>

                <!-- Mobile Menu (Hidden by default) -->
                <div class="md:hidden hidden bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 absolute top-20 right-4 left-4 z-50" id="mobile-menu">
                    <nav class="flex flex-col space-y-4">
                        @if (Route::has('login'))
                            @auth
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Admin Dashboard</a>
                                @elseif(Auth::user()->isInstructor())
                                    <a href="{{ route('instructor.dashboard') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Instructor Dashboard</a>
                                    <a href="{{ route('user.bookings') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">My Schedule</a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Dashboard</a>
                                    <a href="{{ route('user.bookings') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">My Bookings</a>
                                @endif
                                
                                <!-- Mobile Logout Button -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors w-full text-left">
                                        Logout
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Register</a>
                                @endif
                            @endauth
                        @endif
                        <a href="#about" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">About</a>
                        <a href="#lessons" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Lessons</a>
                        <a href="#contact" class="font-medium hover:text-[#3498db] dark:hover:text-[#5dade2] transition-colors">Contact</a>
                    </nav>
                </div>

                <!-- Hero Section -->
                <section class="py-12 lg:py-20">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div class="space-y-6">
                            <h1 class="text-4xl lg:text-5xl xl:text-6xl font-semibold leading-tight">
                                Ride The <span class="text-[#3498db] dark:text-[#5dade2]">Wind</span>, Master The <span class="text-[#e67e22]">Waves</span>
                            </h1>
                            <p class="text-lg opacity-90 max-w-lg">
                                Experience the thrill of kitesurfing at Windkracht 13. Our professional instructors will guide you from your first steps to riding the waves with confidence.
                            </p>
                            <div class="flex flex-wrap gap-4">
                                <a href="#book" class="group inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#3498db] to-[#2980b9] text-white font-medium rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl relative overflow-hidden">
                                    <span class="absolute inset-0 bg-gradient-to-r from-[#2ecc71] to-[#27ae60] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                    <span class="relative flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 animate-pulse">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Book a Lesson
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                    </span>
                                </a>
                                <a href="#learn-more" class="inline-flex items-center justify-center px-6 py-3 bg-white/80 dark:bg-slate-800/80 hover:bg-white dark:hover:bg-slate-700 backdrop-blur-sm text-slate-800 dark:text-slate-200 font-medium rounded-full shadow-md transition-all transform hover:-translate-y-1 hover:shadow-lg">
                                    Learn More
                                </a>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-0 bg-[#3498db]/20 dark:bg-[#1e5f8c]/20 rounded-2xl rotate-6 transform"></div>
                            <img 
                                src="{{ asset('img/welcomesurf.jpg') }}"
                                alt="Kitesurfing in action" 
                                class="rounded-2xl shadow-xl relative z-10 w-full h-[500px] object-cover"
                            >
                        </div>
                    </div>
                </section>

                <!-- Features Section -->
                <section class="py-16">
                    <h2 class="text-3xl font-semibold text-center mb-12">Why Choose <span class="text-[#3498db] dark:text-[#5dade2]">Windkracht 13</span></h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="bg-[#3498db] w-12 h-12 rounded-full flex items-center justify-center mb-4 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Expert Instructors</h3>
                            <p class="opacity-80">Our team consists of certified professionals with years of experience in kitesurfing and teaching.</p>
                        </div>
                        
                        <!-- Feature 2 -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="bg-[#3498db] w-12 h-12 rounded-full flex items-center justify-center mb-4 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Flexible Scheduling</h3>
                            <p class="opacity-80">Book lessons that fit your schedule with our easy-to-use online reservation system.</p>
                        </div>
                        
                        <!-- Feature 3 -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="bg-[#3498db] w-12 h-12 rounded-full flex items-center justify-center mb-4 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">All Levels Welcome</h3>
                            <p class="opacity-80">Whether you're a beginner or looking to perfect new tricks, we have courses for every skill level.</p>
                        </div>
                    </div>
                </section>

                <!-- About Section -->
                <section id="about" class="py-16">
                    <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl p-8 shadow-lg">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                            <div>
                                <h2 class="text-3xl font-semibold mb-4">About <span class="text-[#3498db] dark:text-[#5dade2]">Windkracht 13</span></h2>
                                <p class="mb-4 opacity-80">
                                    Windkracht 13 is a premier kitesurfing school dedicated to providing exceptional instruction in a safe and enjoyable environment. Our name, meaning "Wind Force 13" in Dutch, reflects our passion for the wind and waves.
                                </p>
                                <p class="opacity-80">
                                    Founded by kitesurfing enthusiasts, our school has grown in popularity thanks to our dedicated instructors and personalized approach to teaching. We believe that everyone can learn to kitesurf with the right guidance.
                                </p>
                                <div class="mt-6">
                                    <a href="#contact" class="inline-flex items-center text-[#3498db] dark:text-[#5dade2] font-medium">
                                        Learn more about our story
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-0 bg-[#e67e22]/10 dark:bg-[#e67e22]/20 rounded-2xl -rotate-3 transform"></div>
                                <img 
                                    src="{{ asset('img/welcomesurf(1).jpg') }}"
                                    alt="Kitesurfing team" 
                                    class="rounded-2xl shadow-lg relative z-10"
                                >
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Lessons Section -->
                <section id="lessons" class="py-16">
                    <h2 class="text-3xl font-semibold text-center mb-4">Our <span class="text-[#3498db] dark:text-[#5dade2]">Lessons</span></h2>
                    <p class="text-center max-w-2xl mx-auto mb-12 opacity-80">
                        We offer a variety of lesson formats to suit your needs, schedule, and learning pace. All equipment is provided, just bring your enthusiasm!
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Private Lessons -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="h-48 bg-gradient-to-br from-[#3498db]/80 to-[#2980b9]/80 flex items-center justify-center">
                                <h3 class="text-2xl font-semibold text-white">Private Lessons</h3>
                            </div>
                            <div class="p-6">
                                <p class="mb-4 opacity-80">One-on-one instruction tailored to your specific needs and learning pace.</p>
                                <ul class="space-y-2 mb-6">
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#3498db] dark:text-[#5dade2]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span class="opacity-80">Duration: 2 hours</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#3498db] dark:text-[#5dade2]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span class="opacity-80">Price: €120</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#3498db] dark:text-[#5dade2]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span class="opacity-80">All equipment included</span>
                                    </li>
                                </ul>
                                <a href="#book" class="block text-center py-3 bg-[#3498db] hover:bg-[#2980b9] text-white font-medium rounded-lg shadow transition-colors">
                                    Book Now
                                </a>
                            </div>
                        </div>
                        
                        <!-- Group Lessons -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="h-48 bg-gradient-to-br from-[#e67e22]/80 to-[#d35400]/80 flex items-center justify-center">
                                <h3 class="text-2xl font-semibold text-white">Group Lessons</h3>
                            </div>
                            <div class="p-6">
                                <p class="mb-4 opacity-80">Learn with friends or make new ones in our small group sessions.</p>
                                <ul class="space-y-2 mb-6">
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#e67e22]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span class="opacity-80">Duration: 3 hours</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#e67e22]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span class="opacity-80">Price: €80 per person</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#e67e22]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span class="opacity-80">Small groups (max 4 people)</span>
                                    </li>
                                </ul>
                                <a href="#book" class="block text-center py-3 bg-[#e67e22] hover:bg-[#d35400] text-white font-medium rounded-lg shadow transition-colors">
                                    Book Now
                                </a>
                            </div>
                        </div>
                        
                        <!-- Intensive Course -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="h-48 bg-gradient-to-br from-[#9b59b6]/80 to-[#8e44ad]/80 flex items-center justify-center">
                                <h3 class="text-2xl font-semibold text-white">Intensive Course</h3>
                            </div>
                            <div class="p-6">
                                <p class="mb-4 opacity-80">A comprehensive program to get you kitesurfing independently.</p>
                                <ul class="space-y-2 mb-6">
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#9b59b6]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span class="opacity-80">Duration: 3 days (4 hours/day)</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#9b59b6]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span class="opacity-80">Price: €350</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#9b59b6]">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span class="opacity-80">Video analysis included</span>
                                    </li>
                                </ul>
                                <a href="#book" class="block text-center py-3 bg-[#9b59b6] hover:bg-[#8e44ad] text-white font-medium rounded-lg shadow transition-colors">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Booking Section -->
                <section id="book" class="py-16">
                    <div class="bg-gradient-to-r from-[#3498db]/10 to-[#2ecc71]/10 dark:from-[#3498db]/20 dark:to-[#2ecc71]/20 backdrop-blur-md rounded-2xl p-8 shadow-lg relative overflow-hidden">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-[#3498db]/10 dark:bg-[#3498db]/20 rounded-full -mr-32 -mt-32"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-[#2ecc71]/10 dark:bg-[#2ecc71]/20 rounded-full -ml-32 -mb-32"></div>
                        
                        <div class="relative z-10 text-center max-w-3xl mx-auto">
                            <h2 class="text-3xl font-semibold mb-6">Ready to Catch the <span class="text-[#3498db] dark:text-[#5dade2]">Wind</span>?</h2>
                            
                            <p class="text-lg opacity-90 mb-8">
                                Book your kitesurfing lesson today and experience the thrill of riding the waves. Our professional instructors are ready to guide you on your journey.
                            </p>
                            
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6">
                                @auth
                                    <a href="{{ route('booking') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-[#3498db] hover:bg-[#2980b9] text-white font-medium text-lg rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                        Book Your Lesson Now
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ml-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-[#3498db] hover:bg-[#2980b9] text-white font-medium text-lg rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                        </svg>
                                        Login to Book a Lesson
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ml-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                @endauth
                                
                                <a href="#lessons" class="inline-flex items-center justify-center px-6 py-3 bg-white/80 dark:bg-slate-800/80 hover:bg-white dark:hover:bg-slate-700 backdrop-blur-sm text-slate-800 dark:text-slate-200 font-medium rounded-xl shadow-md transition-all transform hover:-translate-y-1 hover:shadow-lg">
                                    View Lesson Options
                                </a>
                            </div>
                            
                            <div class="mt-10 flex flex-wrap items-center justify-center gap-x-8 gap-y-4 text-sm">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#3498db] dark:text-[#5dade2]">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Flexible scheduling</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#3498db] dark:text-[#5dade2]">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>All equipment included</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#3498db] dark:text-[#5dade2]">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Professional instructors</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#3498db] dark:text-[#5dade2]">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>All skill levels welcome</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Gallery Section -->
                <section class="py-16">
                    <h2 class="text-3xl font-semibold text-center mb-12">Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ([
                            "https://images.unsplash.com/photo-1531201890865-fb64780d16e9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8a2l0ZXN1cmZpbmd8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60",
                            'img/beachhorse.png',
                            'img/drink.png',
                            'img/greenmoon.png',
                            'img/house.png',
                            'img/turtle.png',
                            'img/water.png',
                            "https://images.unsplash.com/photo-1558346489-19413928158b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8a2l0ZXN1cmZpbmd8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60"
                        ] as $index => $image)
                            <div class="group relative overflow-hidden rounded-lg h-48 md:h-64 {{ $index === 0 ? 'md:col-span-2 md:row-span-2' : '' }} {{ $index === 3 ? 'md:col-span-2 md:row-span-2' : '' }}">
                                <img src="{{ $image }}" alt="Kitesurfing gallery image" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                    <div class="p-4 text-white">
                                        <p class="font-medium">Kitesurfing Adventure</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach 
                    </div>
                </section>

                <!-- Testimonials Section -->
                <section class="py-16">
                    <h2 class="text-3xl font-semibold text-center mb-12">What Our <span class="text-[#3498db] dark:text-[#5dade2]">Students</span> Say</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Testimonial 1 -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all">
                            <div class="flex items-center mb-4">
                                <div class="h-12 w-12 rounded-full bg-[#3498db]/20 dark:bg-[#3498db]/30 flex items-center justify-center text-[#3498db]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold">Sarah Johnson</h4>
                                    <div class="flex text-yellow-500">
                                        @for ($i = 0; $i < 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <p class="opacity-80 italic">"The instructors at Windkracht 13 are amazing! I went from never having tried kitesurfing to being able to ride confidently in just a few lessons. Highly recommended!"</p>
                        </div>
                        
                        <!-- Testimonial 2 -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all">
                            <div class="flex items-center mb-4">
                                <div class="h-12 w-12 rounded-full bg-[#e67e22]/20 dark:bg-[#e67e22]/30 flex items-center justify-center text-[#e67e22]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold">Mark Thompson</h4>
                                    <div class="flex text-yellow-500">
                                        @for ($i = 0; $i < 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <p class="opacity-80 italic">"I took the intensive course and it was worth every penny. The instructors are patient, knowledgeable, and make the learning process fun. I can't wait to go back for advanced lessons!"</p>
                        </div>
                        
                        <!-- Testimonial 3 -->
                        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all">
                            <div class="flex items-center mb-4">
                                <div class="h-12 w-12 rounded-full bg-[#9b59b6]/20 dark:bg-[#9b59b6]/30 flex items-center justify-center text-[#9b59b6]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold">Emma Rodriguez</h4>
                                    <div class="flex text-yellow-500">
                                        @for ($i = 0; $i < 4; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                            </svg>
                                        @endfor
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-gray-300 dark:text-gray-600">
                                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="opacity-80 italic">"The group lessons were a great way to meet new people while learning to kitesurf. The location is perfect with consistent winds, and the equipment provided was top-notch. Will definitely come back!"</p>
                        </div>
                    </div>
                </section>

                <!-- Contact Section -->
                <section id="contact" class="py-16">
                    <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl p-8 shadow-lg">
                        <h2 class="text-3xl font-semibold text-center mb-8">Contact <span class="text-[#3498db] dark:text-[#5dade2]">Us</span></h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div>
                                <div class="space-y-6">
                                    <div>
                                        <h4 class="text-lg font-medium mb-2 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#3498db] dark:text-[#5dade2]">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                            </svg>
                                            Location
                                        </h4>
                                        <p class="opacity-80">Beach Boulevard 13<br>1234 AB Zandvoort<br>The Netherlands</p>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-lg font-medium mb-2 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#3498db] dark:text-[#5dade2]">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                            </svg>
                                            Contact Info
                                        </h4>
                                        <p class="opacity-80">Email: info@windkracht13.nl<br>Phone: +31 123 456 789</p>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-lg font-medium mb-2 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#3498db] dark:text-[#5dade2]">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Operating Hours
                                        </h4>
                                        <p class="opacity-80">Monday - Sunday: 9:00 AM - 7:00 PM<br>(Weather permitting)</p>
                                    </div>
                                </div>
                                
                                <div class="mt-8">
                                    <h4 class="text-lg font-medium mb-4">Follow Us</h4>
                                    <div class="flex space-x-4">
                                        <a href="#" class="h-10 w-10 rounded-full bg-[#3498db]/10 hover:bg-[#3498db]/20 dark:bg-[#3498db]/20 dark:hover:bg-[#3498db]/30 flex items-center justify-center text-[#3498db] dark:text-[#5dade2] transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="h-10 w-10 rounded-full bg-[#3498db]/10 hover:bg-[#3498db]/20 dark:bg-[#3498db]/20 dark:hover:bg-[#3498db]/30 flex items-center justify-center text-[#3498db] dark:text-[#5dade2] transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="h-10 w-10 rounded-full bg-[#3498db]/10 hover:bg-[#3498db]/20 dark:bg-[#3498db]/20 dark:hover:bg-[#3498db]/30 flex items-center justify-center text-[#3498db] dark:text-[#5dade2] transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                                                <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="h-10 w-10 rounded-full bg-[#3498db]/10 hover:bg-[#3498db]/20 dark:bg-[#3498db]/20 dark:hover:bg-[#3498db]/30 flex items-center justify-center text-[#3498db] dark:text-[#5dade2] transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                                                <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
    </body>
</html>
