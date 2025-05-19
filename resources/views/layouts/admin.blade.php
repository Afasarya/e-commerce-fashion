<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Admin - @yield('title', 'Dashboard')</title>
        
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
    <body class="antialiased bg-gray-100">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="fixed inset-y-0 left-0 bg-gray-900 w-64 hidden md:flex md:flex-col">
                <div class="flex-1 flex flex-col min-h-0 bg-gray-900">
                    <div class="flex items-center h-16 flex-shrink-0 px-4 bg-gray-800">
                        <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-white">
                            FASHION ADMIN
                        </a>
                    </div>
                    <div class="flex-1 flex flex-col overflow-y-auto">
                        <nav class="flex-1 px-2 py-4 space-y-1">
                            <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <i class="ri-dashboard-line mr-3 text-xl {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}"></i>
                                Dashboard
                            </a>
                            
                            <a href="{{ route('admin.products.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.products.*') ? 'bg-yellow-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <i class="ri-t-shirt-2-line mr-3 text-xl {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}"></i>
                                Products
                            </a>
                            
                            <a href="{{ route('admin.categories.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.categories.*') ? 'bg-yellow-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <i class="ri-list-check mr-3 text-xl {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}"></i>
                                Categories
                            </a>
                            
                            <a href="{{ route('admin.orders.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders.*') ? 'bg-yellow-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <i class="ri-shopping-bag-line mr-3 text-xl {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}"></i>
                                Orders
                            </a>
                            
                           

                            <div class="pt-6">
                                <a href="{{ route('home') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <i class="ri-store-2-line mr-3 text-xl text-gray-400 group-hover:text-gray-300"></i>
                                    View Store
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                    @csrf
                                    <button type="submit" class="group flex w-full items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                                        <i class="ri-logout-box-line mr-3 text-xl text-gray-400 group-hover:text-gray-300"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden fixed top-0 left-0 z-20 w-full bg-gray-900 flex justify-between items-center h-16 px-4">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-white">
                    FASHION ADMIN
                </a>
                <button id="mobile-toggle" type="button" class="text-gray-300">
                    <i class="ri-menu-line text-2xl"></i>
                </button>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="md:hidden fixed inset-0 z-10 bg-gray-900 transform -translate-x-full transition-transform duration-300 ease-in-out pt-16">
                <nav class="px-4 py-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-3 text-base font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="ri-dashboard-line mr-3 text-xl {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.products.index') }}" class="group flex items-center px-2 py-3 text-base font-medium rounded-md {{ request()->routeIs('admin.products.*') ? 'bg-yellow-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="ri-t-shirt-2-line mr-3 text-xl {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}"></i>
                        Products
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" class="group flex items-center px-2 py-3 text-base font-medium rounded-md {{ request()->routeIs('admin.categories.*') ? 'bg-yellow-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="ri-list-check mr-3 text-xl {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}"></i>
                        Categories
                    </a>
                    
                    <a href="{{ route('admin.orders.index') }}" class="group flex items-center px-2 py-3 text-base font-medium rounded-md {{ request()->routeIs('admin.orders.*') ? 'bg-yellow-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="ri-shopping-bag-line mr-3 text-xl {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}"></i>
                        Orders
                    </a>
                    
                  

                    <div class="pt-6">
                        <a href="{{ route('home') }}" class="group flex items-center px-2 py-3 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="ri-store-2-line mr-3 text-xl text-gray-400 group-hover:text-gray-300"></i>
                            View Store
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="group flex w-full items-center px-2 py-3 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="ri-logout-box-line mr-3 text-xl text-gray-400 group-hover:text-gray-300"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
            
            <!-- Content -->
            <div class="md:pl-64 flex flex-col flex-1">
                <!-- Header -->
                <div class="sticky top-0 z-10 md:hidden"></div>
                
                <!-- Main content -->
                <main class="flex-1">
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
            </div>
        </div>
        
        <script>
            // Mobile menu toggle
            document.addEventListener('DOMContentLoaded', function() {
                const mobileToggle = document.getElementById('mobile-toggle');
                const mobileMenu = document.getElementById('mobile-menu');
                
                if (mobileToggle && mobileMenu) {
                    mobileToggle.addEventListener('click', function() {
                        mobileMenu.classList.toggle('-translate-x-full');
                        
                        if (mobileToggle.innerHTML.includes('ri-menu-line')) {
                            mobileToggle.innerHTML = '<i class="ri-close-line text-2xl"></i>';
                        } else {
                            mobileToggle.innerHTML = '<i class="ri-menu-line text-2xl"></i>';
                        }
                    });
                }
            });
        </script>
        
        @yield('scripts')
    </body>
</html> 