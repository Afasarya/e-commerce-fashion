<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Fashion Store') }} - @yield('title', 'Home')</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Additional Styles -->
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
        </style>
        
        @yield('styles')
    </head>
    <body class="antialiased min-h-screen bg-gray-50">
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900">FASHION</a>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <!-- Navigation Links -->
                            <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-yellow-400 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                                Home
                            </a>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('products.*') ? 'border-yellow-400 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                                Shop
                            </a>
                            
                            <a href="{{ route('contact') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('contact') ? 'border-yellow-400 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                                Contact
                            </a>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                        @auth
                            <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-gray-900">
                                <i class="ri-shopping-cart-line text-xl"></i>
                                @php
                                    $cartCount = auth()->user()->cartItems()->sum('quantity');
                                @endphp
                                @if($cartCount > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-yellow-500 rounded-full">{{ $cartCount }}</span>
                                @endif
                            </a>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex text-sm rounded-full focus:outline-none">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700">
                                        <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                </button>
                                <div x-show="open" 
                                     @click.away="open = false"
                                     class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95">
                                    <span class="block px-4 py-2 text-xs text-gray-500">
                                        {{ auth()->user()->name }}
                                    </span>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Log in</a>
                            <a href="{{ route('register') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Sign up
                            </a>
                        @endauth
                    </div>
                    <div class="-mr-2 flex items-center sm:hidden">
                        <!-- Mobile menu button -->
                        <button id="mobile-menu-btn" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-yellow-500">
                            <span class="sr-only">Open main menu</span>
                            <i class="ri-menu-line text-xl" id="menu-icon"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="sm:hidden hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('home') ? 'border-yellow-400 text-yellow-700 bg-yellow-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                        Home
                    </a>
                    <a href="{{ route('products.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('products.*') ? 'border-yellow-400 text-yellow-700 bg-yellow-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                        Shop
                    </a>
                   
                    <a href="{{ route('contact') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('contact') ? 'border-yellow-400 text-yellow-700 bg-yellow-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                        Contact
                    </a>
                </div>
                @auth
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-700">
                                    <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                                <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                            </div>
                            <a href="{{ route('cart.index') }}" class="ml-auto flex-shrink-0 p-1 rounded-full text-gray-600 hover:text-gray-900 focus:outline-none">
                                <i class="ri-shopping-cart-line text-xl"></i>
                            </a>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                Profile
                            </a>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                My Orders
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                    Admin Dashboard
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="space-y-1 px-4">
                            <a href="{{ route('login') }}" class="block text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 py-2">
                                Log in
                            </a>
                            <a href="{{ route('register') }}" class="block text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 py-2">
                                Sign up
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>

        <main>
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 mx-4 md:mx-8 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mx-4 md:mx-8 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4 mx-4 md:mx-8 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="text-2xl font-bold mb-4">FASHION</div>
                        <p class="text-gray-400 mb-6 max-w-md">Complete your style with awesome clothes from us.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-yellow-500 rounded-md p-2 text-gray-900 hover:text-white transition duration-150">
                                <i class="ri-facebook-fill"></i>
                            </a>
                            <a href="#" class="bg-yellow-500 rounded-md p-2 text-gray-900 hover:text-white transition duration-150">
                                <i class="ri-instagram-line"></i>
                            </a>
                            <a href="#" class="bg-yellow-500 rounded-md p-2 text-gray-900 hover:text-white transition duration-150">
                                <i class="ri-twitter-fill"></i>
                            </a>
                            <a href="#" class="bg-yellow-500 rounded-md p-2 text-gray-900 hover:text-white transition duration-150">
                                <i class="ri-linkedin-fill"></i>
                            </a>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Company</h3>
                        <ul class="space-y-3">
                            <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white">Contact Us</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Support</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Careers</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Quick Links</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-400 hover:text-white">Store Location</a></li>
                            <li><a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-white">Order Tracking</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Size Guide</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">FAQs</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-12 border-t border-gray-700 pt-8 text-center text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Fashion Store. All rights reserved.
                </div>
            </div>
        </footer>

        <script>
            // Mobile menu toggle
            document.addEventListener('DOMContentLoaded', function() {
                const menuBtn = document.getElementById('mobile-menu-btn');
                const mobileMenu = document.getElementById('mobile-menu');
                const menuIcon = document.getElementById('menu-icon');
                
                menuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    if (mobileMenu.classList.contains('hidden')) {
                        menuIcon.className = 'ri-menu-line text-xl';
                    } else {
                        menuIcon.className = 'ri-close-line text-xl';
                    }
                });
            });
        </script>
        
        @yield('scripts')
    </body>
</html>
