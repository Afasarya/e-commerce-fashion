@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol role="list" class="flex items-center space-x-2">
                <li>
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Home</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <a href="{{ route('products.index') }}" class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700">Shop</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $product->category->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <span class="ml-2 text-sm font-medium text-gray-900">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8">
            <!-- Product images -->
            <div class="lg:col-span-1 lg:self-start">
                <div class="overflow-hidden rounded-lg">
                    <img 
                        src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.jpg') }}" 
                        alt="{{ $product->name }}" 
                        class="h-full w-full object-cover object-center"
                        id="main-product-image"
                    >
                </div>
                
                @if($product->variants->isNotEmpty() && $product->variants->where('image', '!=', null)->count() > 0)
                    <div class="mt-4 grid grid-cols-4 gap-2">
                        <div class="col-span-1 aspect-h-1 aspect-w-1 overflow-hidden rounded-md cursor-pointer border-2 border-yellow-500">
                            <img 
                                src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.jpg') }}" 
                                alt="{{ $product->name }}" 
                                class="h-full w-full object-cover object-center variant-thumbnail"
                                data-image="{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.jpg') }}"
                            >
                        </div>
                        
                        @foreach($product->variants->where('image', '!=', null) as $variant)
                            <div class="col-span-1 aspect-h-1 aspect-w-1 overflow-hidden rounded-md cursor-pointer border-2 border-transparent hover:border-yellow-500">
                                <img 
                                    src="{{ asset('storage/' . $variant->image) }}" 
                                    alt="{{ $variant->value }}" 
                                    class="h-full w-full object-cover object-center variant-thumbnail"
                                    data-image="{{ asset('storage/' . $variant->image) }}"
                                >
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Product info -->
            <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>
                
                <div class="mt-3">
                    <h2 class="sr-only">Product information</h2>
                    <div class="flex items-center">
                        @if($product->isOnSale())
                            <p class="text-3xl tracking-tight text-gray-900">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</p>
                            <p class="ml-3 text-lg line-through text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <span class="ml-4 inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">SALE</span>
                        @else
                            <p class="text-3xl tracking-tight text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="mt-6">
                    <h3 class="sr-only">Description</h3>
                    <div class="space-y-6 text-base text-gray-700">
                        <p>{{ $product->description }}</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('cart.add') }}" class="mt-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    @if($product->sizes->isNotEmpty())
                        <input type="hidden" name="has_sizes" value="1">
                    @endif
                    
                    <!-- Variants -->
                    @if($product->variants->isNotEmpty())
                        <div class="mt-8">
                            <h3 class="text-sm font-medium text-gray-900">Color</h3>
                            <fieldset class="mt-2">
                                <legend class="sr-only">Choose a color</legend>
                                <div class="flex items-center space-x-3">
                                    @foreach($product->variants as $variant)
                                        <label
                                            class="-m-0.5 relative p-0.5 rounded-full flex items-center justify-center cursor-pointer focus:outline-none ring-gray-900 {{ $loop->first ? 'ring ring-offset-1' : '' }}"
                                            data-variant-id="{{ $variant->id }}"
                                        >
                                            <input 
                                                type="radio" 
                                                name="product_variant_id" 
                                                value="{{ $variant->id }}" 
                                                class="sr-only variant-radio" 
                                                {{ $loop->first ? 'checked' : '' }}
                                                aria-labelledby="variant-{{ $variant->id }}-label"
                                            >
                                            <span id="variant-{{ $variant->id }}-label" class="sr-only">{{ $variant->value }}</span>
                                            <span class="h-8 w-8 bg-gray-200 border border-black border-opacity-10 rounded-full"></span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>
                    @endif
                    
                    <!-- Sizes -->
                    @if($product->sizes->isNotEmpty())
                        <div class="mt-8">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-gray-900">Size</h3>
                                <a href="#" class="text-sm font-medium text-yellow-600 hover:text-yellow-500">Size guide</a>
                            </div>
                            
                            <fieldset class="mt-2">
                                <legend class="sr-only">Choose a size</legend>
                                <div class="grid grid-cols-4 gap-3 sm:grid-cols-6">
                                    @foreach($product->sizes as $size)
                                        <label
                                            class="group relative flex items-center justify-center rounded-md border py-3 px-4 text-sm font-medium uppercase {{ $size->isInStock() ? 'hover:bg-gray-50 focus:outline-none sm:flex-1 cursor-pointer bg-white text-gray-900 shadow-sm' : 'cursor-not-allowed bg-gray-50 text-gray-200' }}"
                                        >
                                            <input 
                                                type="radio" 
                                                name="product_size_id" 
                                                value="{{ $size->id }}"
                                                class="sr-only size-radio"
                                                {{ $size->isInStock() ? '' : 'disabled' }}
                                                data-size-name="{{ $size->size }}"
                                                aria-labelledby="size-{{ $size->id }}-label"
                                                required
                                            >
                                            <span id="size-{{ $size->id }}-label">{{ $size->size }}</span>
                                            @if(!$size->isInStock())
                                                <span class="pointer-events-none absolute -inset-px rounded-md border-2 border-gray-200">
                                                    <svg class="absolute inset-0 h-full w-full stroke-2 text-gray-200" viewBox="0 0 100 100" preserveAspectRatio="none" stroke="currentColor">
                                                        <line x1="0" y1="100" x2="100" y2="0" vector-effect="non-scaling-stroke" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                            <p class="mt-2 text-sm text-red-600 hidden" id="size-error">Please select a size</p>
                        </div>
                    @endif
                    
                    <!-- Quantity -->
                    <div class="mt-8">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-900">Quantity</h3>
                        </div>
                        <div class="mt-2 flex items-center border border-gray-300 rounded-md">
                            <button type="button" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none" id="decrease-quantity">
                                <i class="ri-subtract-line"></i>
                            </button>
                            <input 
                                type="number"
                                name="quantity"
                                id="quantity"
                                value="1"
                                min="1"
                                class="w-16 text-center border-none focus:outline-none focus:ring-0"
                            >
                            <button type="button" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none" id="increase-quantity">
                                <i class="ri-add-line"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex">
                        <button
                            type="submit"
                            class="flex max-w-xs flex-1 items-center justify-center rounded-md border border-transparent bg-yellow-500 px-8 py-3 text-base font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full"
                        >
                            Add to cart
                        </button>
                    </div>
                </form>
                
                <section aria-labelledby="details-heading" class="mt-12">
                    <h2 id="details-heading" class="sr-only">Additional details</h2>
                    
                    <div class="divide-y divide-gray-200 border-t">
                        <div x-data="{ open: false }" class="py-6">
                            <h3>
                                <button
                                    @click="open = !open"
                                    class="flex w-full items-center justify-between text-left text-gray-900"
                                    aria-expanded="false"
                                >
                                    <span class="text-base font-medium">Features</span>
                                    <span class="ml-6 flex items-center">
                                        <i x-show="!open" class="ri-add-line h-5 w-5"></i>
                                        <i x-show="open" class="ri-subtract-line h-5 w-5"></i>
                                    </span>
                                </button>
                            </h3>
                            <div x-show="open" class="mt-2 prose prose-sm max-w-none text-gray-500">
                                <ul role="list">
                                    <li>High-quality materials</li>
                                    <li>Durable and long-lasting</li>
                                    <li>Comfortable fit</li>
                                    <li>Stylish design</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div x-data="{ open: false }" class="py-6">
                            <h3>
                                <button
                                    @click="open = !open"
                                    class="flex w-full items-center justify-between text-left text-gray-900"
                                    aria-expanded="false"
                                >
                                    <span class="text-base font-medium">Care Instructions</span>
                                    <span class="ml-6 flex items-center">
                                        <i x-show="!open" class="ri-add-line h-5 w-5"></i>
                                        <i x-show="open" class="ri-subtract-line h-5 w-5"></i>
                                    </span>
                                </button>
                            </h3>
                            <div x-show="open" class="mt-2 prose prose-sm max-w-none text-gray-500">
                                <ul role="list">
                                    <li>Machine wash cold</li>
                                    <li>Do not bleach</li>
                                    <li>Tumble dry low</li>
                                    <li>Iron on low heat if necessary</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div x-data="{ open: false }" class="py-6">
                            <h3>
                                <button
                                    @click="open = !open"
                                    class="flex w-full items-center justify-between text-left text-gray-900"
                                    aria-expanded="false"
                                >
                                    <span class="text-base font-medium">Shipping & Returns</span>
                                    <span class="ml-6 flex items-center">
                                        <i x-show="!open" class="ri-add-line h-5 w-5"></i>
                                        <i x-show="open" class="ri-subtract-line h-5 w-5"></i>
                                    </span>
                                </button>
                            </h3>
                            <div x-show="open" class="mt-2 prose prose-sm max-w-none text-gray-500">
                                <p>Free shipping on all orders over $50. Delivery within 3-5 business days.</p>
                                <p>Returns accepted within 30 days of delivery. Item must be unworn and with original tags.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity controls
        const quantityInput = document.getElementById('quantity');
        const increaseBtn = document.getElementById('increase-quantity');
        const decreaseBtn = document.getElementById('decrease-quantity');
        
        increaseBtn.addEventListener('click', function() {
            quantityInput.value = parseInt(quantityInput.value) + 1;
        });
        
        decreaseBtn.addEventListener('click', function() {
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        });
        
        // Variant thumbnails
        const variantThumbnails = document.querySelectorAll('.variant-thumbnail');
        const mainImage = document.getElementById('main-product-image');
        
        variantThumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Update main image
                mainImage.src = this.getAttribute('data-image');
                
                // Update active thumbnail border
                variantThumbnails.forEach(t => {
                    t.parentElement.classList.remove('border-yellow-500');
                    t.parentElement.classList.add('border-transparent');
                });
                this.parentElement.classList.remove('border-transparent');
                this.parentElement.classList.add('border-yellow-500');
            });
        });
        
        // Variant selection
        const variantLabels = document.querySelectorAll('[data-variant-id]');
        
        variantLabels.forEach(label => {
            label.addEventListener('click', function() {
                // Only allow clicking if there's an input child
                const input = this.querySelector('input[type="radio"]');
                if (input) {
                    variantLabels.forEach(l => {
                        l.classList.remove('ring', 'ring-offset-1');
                    });
                    this.classList.add('ring', 'ring-offset-1');
                    input.checked = true;
                }
            });
        });
        
        // Size selection
        const sizeLabels = document.querySelectorAll('.size-radio');
        
        if (sizeLabels.length > 0) {
            sizeLabels.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove selected class from all labels
                    document.querySelectorAll('.size-radio').forEach(r => {
                        r.closest('label').classList.remove('ring-2', 'ring-yellow-500');
                    });
                    
                    // Add selected class to the checked radio's label
                    if (this.checked) {
                        this.closest('label').classList.add('ring-2', 'ring-yellow-500');
                        
                        // Hide error message if it was showing
                        document.getElementById('size-error').classList.add('hidden');
                    }
                });
            });
        }
        
        // Form validation
        const addToCartForm = document.querySelector('form[action="{{ route('cart.add') }}"]');
        
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function(e) {
                // If we have sizes and none are selected, show error and prevent submission
                const sizesExist = document.querySelectorAll('.size-radio').length > 0;
                const sizeSelected = document.querySelector('.size-radio:checked');
                
                if (sizesExist && !sizeSelected) {
                    e.preventDefault();
                    document.getElementById('size-error').classList.remove('hidden');
                    // Scroll to the size section
                    document.getElementById('size-error').scrollIntoView({behavior: 'smooth'});
                }
            });
        }
    });
</script>
@endsection