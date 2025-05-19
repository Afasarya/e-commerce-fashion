@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Shopping Cart</h1>
        
        @if($cartItems->isEmpty())
            <div class="mt-12 text-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 rounded-lg">
                <i class="ri-shopping-cart-line text-6xl text-gray-400"></i>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-1 text-sm text-gray-500">Looks like you haven't added any products to your cart yet.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Continue Shopping
                    </a>
                </div>
            </div>
        @else
            <form action="{{ route('cart.update') }}" method="POST" class="mt-12">
                @csrf
                @method('PATCH')
                
                <div class="border-t border-b border-gray-200 divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                        <div class="py-6 sm:py-10 flex flex-col sm:flex-row">
                            <div class="flex-shrink-0">
                                <img 
                                    src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/product-placeholder.jpg') }}" 
                                    alt="{{ $item->product->name }}" 
                                    class="w-24 h-24 rounded-md object-center object-cover sm:w-32 sm:h-32"
                                >
                            </div>
                            
                            <div class="flex-1 ml-0 sm:ml-6 mt-4 sm:mt-0">
                                <div class="flex justify-between">
                                    <h3 class="text-base font-medium text-gray-900">
                                        <a href="{{ route('products.show', $item->product->slug) }}">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>
                                    <p class="ml-4 text-base font-medium text-gray-900">Rp {{ number_format($item->product->getActualPrice() * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                                
                                <div class="mt-1 text-sm text-gray-500">
                                    <p>{{ $item->product->category->name }}</p>
                                    
                                    @if($item->variant)
                                        <p class="mt-1">Color: {{ $item->variant->value }}</p>
                                    @endif
                                    
                                    @if($item->size)
                                        <p class="mt-1">Size: {{ $item->size->size }}</p>
                                    @endif
                                </div>
                                
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center border border-gray-300 rounded-md">
                                        <button type="button" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none decrease-quantity" data-item-id="{{ $item->id }}">
                                            <i class="ri-subtract-line"></i>
                                        </button>
                                        <input 
                                            type="number"
                                            name="quantities[{{ $item->id }}]"
                                            value="{{ $item->quantity }}"
                                            min="1"
                                            class="w-12 text-center border-none focus:outline-none focus:ring-0 quantity-input"
                                            data-item-id="{{ $item->id }}"
                                        >
                                        <button type="button" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none increase-quantity" data-item-id="{{ $item->id }}">
                                            <i class="ri-add-line"></i>
                                        </button>
                                    </div>
                                    
                                    <div>
                                        <button 
                                            type="button" 
                                            class="text-sm font-medium text-red-600 hover:text-red-500 remove-item"
                                            data-item-id="{{ $item->id }}"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-10">
                    <div class="rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:p-8">
                        <h2 class="sr-only">Order summary</h2>
                        
                        <div class="flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <table class="min-w-full">
                                        <tbody>
                                            <tr class="border-t border-gray-200">
                                                <th scope="row" class="px-4 py-2 text-left text-sm font-normal text-gray-500">Subtotal</th>
                                                <td class="px-4 py-2 text-right text-sm text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="border-t border-gray-200">
                                                <th scope="row" class="px-4 py-2 text-left text-sm font-normal text-gray-500">Shipping (estimated)</th>
                                                <td class="px-4 py-2 text-right text-sm text-gray-900">Rp {{ number_format(15000, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="border-t border-gray-200">
                                                <th scope="row" class="px-4 py-2 text-left text-sm font-normal text-gray-500">Tax (estimated)</th>
                                                <td class="px-4 py-2 text-right text-sm text-gray-900">Rp {{ number_format($subtotal * 0.1, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="border-t border-gray-200">
                                                <th scope="row" class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Order Total</th>
                                                <td class="px-4 py-2 text-right text-sm font-semibold text-gray-900">Rp {{ number_format($subtotal + 15000 + ($subtotal * 0.1), 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                        <button 
                            type="submit" 
                            class="sm:col-span-1 rounded-md border border-transparent bg-gray-50 px-4 py-3 text-base font-medium text-gray-900 shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-50"
                        >
                            Update Cart
                        </button>
                        
                        <a 
                            href="{{ route('checkout.index') }}" 
                            class="sm:col-span-1 rounded-md border border-transparent bg-yellow-500 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-50 text-center"
                        >
                            Proceed to Checkout
                        </a>
                    </div>
                    
                    <div class="mt-6 text-center text-sm text-gray-500">
                        <p>
                            or 
                            <a href="{{ route('products.index') }}" class="font-medium text-yellow-600 hover:text-yellow-500">
                                Continue Shopping
                                <span aria-hidden="true"> &rarr;</span>
                            </a>
                        </p>
                    </div>
                </div>
            </form>
            
            <!-- Remove item form -->
            <form id="remove-form" action="{{ route('cart.remove') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
                <input type="hidden" name="cart_item_id" id="remove-item-id">
            </form>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@if(!$cartItems->isEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity controls
        const decreaseBtns = document.querySelectorAll('.decrease-quantity');
        const increaseBtns = document.querySelectorAll('.increase-quantity');
        const quantityInputs = document.querySelectorAll('.quantity-input');
        
        decreaseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                }
            });
        });
        
        increaseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
                input.value = parseInt(input.value) + 1;
            });
        });
        
        // Remove item
        const removeBtns = document.querySelectorAll('.remove-item');
        const removeForm = document.getElementById('remove-form');
        const removeItemIdInput = document.getElementById('remove-item-id');
        
        removeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                removeItemIdInput.value = itemId;
                removeForm.submit();
            });
        });
    });
</script>
@endif
@endsection