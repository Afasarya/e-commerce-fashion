@extends('layouts.app')

@section('title', 'Shop')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Shop Our Collection</h1>
            <p class="mt-4 text-lg text-gray-500">Browse our latest products and find your perfect style</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar / Filters -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6 sticky top-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Filters</h3>
                    
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Search -->
                        <div class="mb-6">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative rounded-md shadow-sm">
                                <input 
                                    type="text" 
                                    name="search" 
                                    id="search" 
                                    class="block w-full rounded-md border-gray-300 pl-3 pr-10 focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm" 
                                    placeholder="Search products..."
                                    value="{{ request('search') }}"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Categories -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                            <div class="space-y-2">
                                @foreach($categories as $category)
                                <div class="flex items-center">
                                    <input 
                                        id="category-{{ $category->id }}" 
                                        name="category" 
                                        value="{{ $category->slug }}" 
                                        type="radio" 
                                        class="h-4 w-4 text-yellow-500 focus:ring-yellow-500 border-gray-300 rounded"
                                        {{ request('category') == $category->slug ? 'checked' : '' }}
                                    >
                                    <label for="category-{{ $category->id }}" class="ml-3 text-sm text-gray-700">
                                        {{ $category->name }}
                                    </label>
                                </div>
                                @endforeach
                                
                                @if(request('category'))
                                <div class="mt-2">
                                    <a href="{{ route('products.index') }}" class="text-sm text-yellow-500 hover:text-yellow-700">
                                        Clear category
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-6">
                            <label for="price_range" class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label for="min_price" class="sr-only">Minimum Price</label>
                                    <input 
                                        type="number" 
                                        name="min_price" 
                                        id="min_price" 
                                        class="block w-full rounded-md border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm" 
                                        placeholder="Min"
                                        min="0"
                                        value="{{ request('min_price') }}"
                                    >
                                </div>
                                <div>
                                    <label for="max_price" class="sr-only">Maximum Price</label>
                                    <input 
                                        type="number" 
                                        name="max_price" 
                                        id="max_price" 
                                        class="block w-full rounded-md border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm" 
                                        placeholder="Max"
                                        min="0"
                                        value="{{ request('max_price') }}"
                                    >
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sort -->
                        <div class="mb-6">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                            <select 
                                id="sort" 
                                name="sort" 
                                class="block w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-base focus:border-yellow-500 focus:outline-none focus:ring-yellow-500 sm:text-sm"
                            >
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full bg-yellow-500 rounded-md py-2 px-4 text-sm font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                            Apply Filters
                        </button>
                        
                        @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('products.index') }}" class="mt-2 block text-center text-sm text-gray-500 hover:text-gray-700">
                                Clear all filters
                            </a>
                        @endif
                    </form>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="lg:col-span-3">
                @if($products->isEmpty())
                    <div class="bg-white shadow rounded-lg p-6 text-center">
                        <i class="ri-shopping-bag-line text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No products found</h3>
                        <p class="text-gray-500">Try adjusting your search or filter criteria</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="group relative">
                                <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-200 transition-all duration-300 group-hover:opacity-90">
                                    <img 
                                        src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.jpg') }}" 
                                        alt="{{ $product->name }}" 
                                        class="h-full w-full object-cover object-center"
                                    >
                                    @if($product->isOnSale())
                                        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                            SALE
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <h3 class="text-sm text-gray-700">
                                        <a href="{{ route('products.show', $product->slug) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                                    <div class="mt-2 flex items-center">
                                        @if($product->isOnSale())
                                            <span class="text-sm text-gray-500 line-through mr-2">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a 
                                        href="{{ route('products.show', $product->slug) }}"
                                        class="relative inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                                    >
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection