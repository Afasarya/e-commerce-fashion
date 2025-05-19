@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <header class="py-6 md:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 items-center gap-12">
            <div class="text-center md:text-left">
                <h1 class="text-5xl md:text-6xl font-black leading-tight">
                    <span class="relative inline-block px-2 py-1 bg-white transform -rotate-1">LET'S</span><br />
                    EXPLORE<br />
                    <span class="relative inline-block px-2 py-1 bg-yellow-400 transform -rotate-1">UNIQUE</span><br />
                    CLOTHES.
                </h1>
                <p class="mt-4 text-gray-700 text-lg">Live for influential and innovative fashion!</p>
                <div class="mt-8 md:mt-10">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Shop Now
                    </a>
                </div>
            </div>
            <div class="hidden md:block relative">
                <img src="{{ asset('images/header.png') }}" alt="Fashion model" class="w-full object-cover">
            </div>
        </div>
    </header>

    <!-- Brands Banner -->
    <section class="bg-yellow-400 py-8 overflow-hidden">
        {{-- Note: For production use, please ensure you have permission to use these logos --}}
        <div class="flex whitespace-nowrap animate-marquee">
            {{-- First set of logos --}}
            <img src="{{ asset('images/banner-1.png') }}" alt="Amazon logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-2.png') }}" alt="Shopify logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-3.png') }}" alt="eBay logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-4.png') }}" alt="Alibaba logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-5.png') }}" alt="Etsy logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-6.png') }}" alt="Walmart logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-7.png') }}" alt="Target logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-8.png') }}" alt="Best Buy logo" class="h-8 mx-10">
            
            {{-- Duplicate logos for continuous scrolling effect --}}
            <img src="{{ asset('images/banner-1.png') }}" alt="Amazon logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-2.png') }}" alt="Shopify logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-3.png') }}" alt="eBay logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-4.png') }}" alt="Alibaba logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-5.png') }}" alt="Etsy logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-6.png') }}" alt="Walmart logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-7.png') }}" alt="Target logo" class="h-8 mx-10">
            <img src="{{ asset('images/banner-8.png') }}" alt="Best Buy logo" class="h-8 mx-10">
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" id="catalogue">
        <h2 class="text-3xl font-bold text-center mb-12 relative">
            NEW ARRIVALS
            <span class="absolute bottom-0 right-0 h-10 w-24 -z-10 bg-no-repeat bg-contain bg-right-bottom" style="background-image: url('{{ asset('images/header-bg.png') }}')"></span>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Category Card 1 -->
            <div class="group">
                <div class="rounded-xl overflow-hidden mb-4">
                    <img src="{{ asset('images/arrival-1.jpg') }}" alt="Hoodies & Sweatshirts" class="w-full h-72 object-cover transition duration-300 group-hover:scale-110">
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-xl font-medium text-gray-900">Hoodies & Sweatshirts</h4>
                        <a href="{{ route('products.index') }}?category=hoodies" class="text-gray-500 hover:text-yellow-500">Explore Now</a>
                    </div>
                    <span class="text-gray-400 text-xl">
                        <i class="ri-arrow-right-line"></i>
                    </span>
                </div>
            </div>
            
            <!-- Category Card 2 -->
            <div class="group">
                <div class="rounded-xl overflow-hidden mb-4">
                    <img src="{{ asset('images/arrival-2.jpg') }}" alt="Coats & Parkas" class="w-full h-72 object-cover transition duration-300 group-hover:scale-110">
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-xl font-medium text-gray-900">Coats & Parkas</h4>
                        <a href="{{ route('products.index') }}?category=coats" class="text-gray-500 hover:text-yellow-500">Explore Now</a>
                    </div>
                    <span class="text-gray-400 text-xl">
                        <i class="ri-arrow-right-line"></i>
                    </span>
                </div>
            </div>
            
            <!-- Category Card 3 -->
            <div class="group">
                <div class="rounded-xl overflow-hidden mb-4">
                    <img src="{{ asset('images/arrival-3.jpg') }}" alt="Tees & T-Shirts" class="w-full h-72 object-cover transition duration-300 group-hover:scale-110">
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-xl font-medium text-gray-900">Tees & T-Shirts</h4>
                        <a href="{{ route('products.index') }}?category=tees" class="text-gray-500 hover:text-yellow-500">Explore Now</a>
                    </div>
                    <span class="text-gray-400 text-xl">
                        <i class="ri-arrow-right-line"></i>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Sale Section -->
    <section class="bg-yellow-400 py-16" id="fashion">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-8 items-center">
            <div class="order-2 md:order-1">
                <img src="{{ asset('images/sale.png') }}" alt="Sale" class="max-w-full mx-auto transform md:scale-110 md:-translate-y-6">
            </div>
            <div class="order-1 md:order-2">
                <h2 class="text-5xl font-black leading-tight">
                    <span class="relative inline-block px-2 py-1 bg-white transform -rotate-1">PAYDAY</span><br />
                    SALE NOW
                </h2>
                <p class="mt-6 text-lg">
                    Spend minimal $100 get 30% off voucher code for your next purchase
                </p>
                <h4 class="mt-6 text-xl font-semibold">1 July - 10 July 2024</h4>
                <p class="mt-2 text-sm">*Terms and conditions apply</p>
                <div class="mt-8">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-900 hover:bg-white hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                        Shop Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Young's Favourite Section -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" id="favourite">
        <h2 class="text-3xl font-bold text-center mb-12 relative">
            YOUNG'S FAVOURITE
            <span class="absolute bottom-0 right-0 h-10 w-24 -z-10 bg-no-repeat bg-contain bg-right-bottom" style="background-image: url('{{ asset('images/header-bg.png') }}')"></span>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Favourite Card 1 -->
            <div class="group">
                <div class="rounded-xl overflow-hidden mb-4">
                    <img src="{{ asset('images/favourite-1.jpg') }}" alt="Trending on Instagram" class="w-full h-96 object-cover transition duration-300 group-hover:scale-110">
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-xl font-medium text-gray-900">Trending on Instagram</h4>
                        <a href="{{ route('products.index') }}?tag=trending" class="text-gray-500 hover:text-yellow-500">Explore Now</a>
                    </div>
                    <span class="text-gray-400 text-xl">
                        <i class="ri-arrow-right-line"></i>
                    </span>
                </div>
            </div>
            
            <!-- Favourite Card 2 -->
            <div class="group">
                <div class="rounded-xl overflow-hidden mb-4">
                    <img src="{{ asset('images/favourite-2.jpg') }}" alt="All under $40" class="w-full h-96 object-cover transition duration-300 group-hover:scale-110">
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-xl font-medium text-gray-900">All under $40</h4>
                        <a href="{{ route('products.index') }}?max_price=40" class="text-gray-500 hover:text-yellow-500">Explore Now</a>
                    </div>
                    <span class="text-gray-400 text-xl">
                        <i class="ri-arrow-right-line"></i>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Download App Section -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto grid md:grid-cols-2 gap-8 items-center" id="lifestyle">
        <div class="order-2 md:order-1 text-center md:text-left">
            <h2 class="text-3xl font-bold mb-6">DOWNLOAD APP & GET THE VOUCHER!</h2>
            <p class="text-gray-700 mb-8">
                Get 30% off for first transaction using our new mobile app for now.
            </p>
            <div class="flex flex-wrap justify-center md:justify-start gap-4">
                <a href="#" class="inline-block">
                    <img src="{{ asset('images/apple.png') }}" alt="App Store" class="h-12">
                </a>
                <a href="#" class="inline-block">
                    <img src="{{ asset('images/google.png') }}" alt="Google Play" class="h-12">
                </a>
            </div>
        </div>
        <div class="order-1 md:order-2">
            <img src="{{ asset('images/download.png') }}" alt="Mobile App" class="max-w-full mx-auto">
        </div>
    </section>

    <!-- Promo Section -->
    <section class="bg-yellow-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4 max-w-3xl mx-auto">
                JOIN SHOPPING COMMUNITY TO GET MONTHLY PROMO
            </h2>
            <p class="text-gray-800 mb-8">
                Type your email down below and be young wild generation
            </p>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-md mx-auto flex">
                @csrf
                <input type="email" name="email" placeholder="Add your email here" class="flex-1 px-4 py-2 rounded-l-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-medium rounded-r-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    SEND
                </button>
            </form>
        </div>
    </section>
@endsection

@section('styles')
<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    .animate-marquee {
        animation: marquee 30s linear infinite;
    }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/scrollreveal"></script>
<script>
    const scrollRevealOption = {
        origin: "bottom",
        distance: "50px",
        duration: 1000,
    };

    document.addEventListener('DOMContentLoaded', function() {
        ScrollReveal().reveal("header img", {
            ...scrollRevealOption,
            origin: "right",
        });
        ScrollReveal().reveal("header h1", {
            ...scrollRevealOption,
            delay: 500,
        });
        ScrollReveal().reveal("header p", {
            ...scrollRevealOption,
            delay: 1000,
        });
        ScrollReveal().reveal("header .mt-8", {
            ...scrollRevealOption,
            delay: 1500,
        });

        // Arrival cards
        document.querySelectorAll('#catalogue .group').forEach((item, index) => {
            ScrollReveal().reveal(item, {
                ...scrollRevealOption,
                delay: 500 * index,
            });
        });

        // Sale section
        ScrollReveal().reveal("#fashion img", {
            ...scrollRevealOption,
            origin: "left",
        });
        ScrollReveal().reveal("#fashion h2", {
            ...scrollRevealOption,
            delay: 500,
        });
        ScrollReveal().reveal("#fashion p", {
            ...scrollRevealOption,
            delay: 1000,
        });
        ScrollReveal().reveal("#fashion .mt-8", {
            ...scrollRevealOption,
            delay: 1500,
        });

        // Favourite cards
        document.querySelectorAll('#favourite .group').forEach((item, index) => {
            ScrollReveal().reveal(item, {
                ...scrollRevealOption,
                delay: 500 * index,
            });
        });
    });
</script>
@endsection 