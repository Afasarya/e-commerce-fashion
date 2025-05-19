@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 pt-8 pb-16 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Checkout</h1>
        
        @if($cartItems->isEmpty())
            <div class="mt-12 text-center py-12 px-4 sm:px-6 lg:px-8 bg-white rounded-lg shadow">
                <i class="ri-shopping-cart-line text-6xl text-gray-400"></i>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-1 text-sm text-gray-500">You need to add products to your cart before checkout.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Continue Shopping
                    </a>
                </div>
            </div>
        @else
            <form action="{{ route('checkout.process') }}" method="POST" class="mt-12">
                @csrf
                
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-2">
                    <!-- Shipping Information -->
                    <div>
                        <div class="bg-white px-4 py-6 sm:p-6 shadow rounded-lg">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Shipping Information</h2>
                                <p class="mt-1 text-sm text-gray-500">Please provide your shipping details.</p>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-6">
                                    <label for="shipping_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <div class="mt-1">
                                        <input 
                                            type="text" 
                                            id="shipping_name" 
                                            name="shipping_name" 
                                            value="{{ old('shipping_name', auth()->user()->name) }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm @error('shipping_name') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:outline-none focus:ring-red-500 @enderror"
                                        >
                                        @error('shipping_name')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="sm:col-span-6">
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <div class="mt-1">
                                        <textarea 
                                            id="shipping_address" 
                                            name="shipping_address" 
                                            rows="3" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm @error('shipping_address') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:outline-none focus:ring-red-500 @enderror"
                                        >{{ old('shipping_address', auth()->user()->address) }}</textarea>
                                        @error('shipping_address')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="sm:col-span-6">
                                    <label for="shipping_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <div class="mt-1">
                                        <input 
                                            type="text" 
                                            id="shipping_phone" 
                                            name="shipping_phone" 
                                            value="{{ old('shipping_phone', auth()->user()->phone) }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm @error('shipping_phone') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:outline-none focus:ring-red-500 @enderror"
                                        >
                                        @error('shipping_phone')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="sm:col-span-6">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Order Notes (Optional)</label>
                                    <div class="mt-1">
                                        <textarea 
                                            id="notes" 
                                            name="notes" 
                                            rows="3" 
                                            placeholder="Special instructions for delivery or any other notes"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm"
                                        >{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 bg-white px-4 py-6 sm:p-6 shadow rounded-lg">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Payment Method</h2>
                                <p class="mt-1 text-sm text-gray-500">Select your preferred payment method.</p>
                            </div>
                            
                            <div class="mt-6">
                                <div class="flex items-center">
                                    <input 
                                        id="payment_method_midtrans" 
                                        name="payment_method" 
                                        type="radio" 
                                        value="midtrans" 
                                        checked 
                                        class="h-4 w-4 border-gray-300 text-yellow-600 focus:ring-yellow-500"
                                    >
                                    <label for="payment_method_midtrans" class="ml-3 block text-sm font-medium text-gray-700">
                                        Pay with Midtrans (Credit/Debit Card, Bank Transfer, E-Wallet)
                                    </label>
                                </div>
                                
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <img src="{{ asset('images/payment/visa.svg') }}" alt="Visa" class="h-8">
                                    <img src="{{ asset('images/payment/mastercard.svg') }}" alt="Mastercard" class="h-8">
                                    <img src="{{ asset('images/payment/bca.svg') }}" alt="BCA" class="h-8">
                                    <img src="{{ asset('images/payment/mandiri.svg') }}" alt="Mandiri" class="h-8">
                                    <img src="{{ asset('images/payment/gopay.svg') }}" alt="GoPay" class="h-8">
                                    <img src="{{ asset('images/payment/ovo.svg') }}" alt="OVO" class="h-8">
                                </div>
                                
                                @error('payment_method')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div>
                        <div class="bg-white px-4 py-6 sm:p-6 shadow rounded-lg">
                            <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>
                            
                            <div class="mt-6 flow-root">
                                <ul role="list" class="-my-6 divide-y divide-gray-200">
                                    @foreach($cartItems as $item)
                                        <li class="flex py-6">
                                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                                <img 
                                                    src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/product-placeholder.jpg') }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="h-full w-full object-cover object-center"
                                                >
                                            </div>
                                            
                                            <div class="ml-4 flex flex-1 flex-col">
                                                <div>
                                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                                        <h3>{{ $item->product->name }}</h3>
                                                        <p class="ml-4">Rp {{ number_format($item->product->getActualPrice(), 0, ',', '.') }}</p>
                                                    </div>
                                                    <p class="mt-1 text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                                    
                                                    @if($item->variant)
                                                        <p class="mt-1 text-sm text-gray-500">Color: {{ $item->variant->value }}</p>
                                                    @endif
                                                    
                                                    @if($item->size)
                                                        <p class="mt-1 text-sm text-gray-500">Size: {{ $item->size->size }}</p>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex flex-1 items-end justify-between text-sm">
                                                    <p class="text-gray-500">Qty {{ $item->quantity }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            <div class="mt-6 border-t border-gray-200 pt-6">
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <p>Subtotal</p>
                                    <p>Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex justify-between text-base font-medium text-gray-900 mt-2">
                                    <p>Shipping</p>
                                    <p>Rp {{ number_format(15000, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex justify-between text-base font-medium text-gray-900 mt-2">
                                    <p>Tax (10%)</p>
                                    <p>Rp {{ number_format($subtotal * 0.1, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex justify-between text-base font-medium text-gray-900 mt-4 border-t border-gray-200 pt-4">
                                    <p>Total</p>
                                    <p>Rp {{ number_format($subtotal + 15000 + ($subtotal * 0.1), 0, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button 
                                    type="submit" 
                                    class="w-full rounded-md border border-transparent bg-yellow-500 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-50"
                                >
                                    Place Order
                                </button>
                            </div>
                            
                            <div class="mt-6 text-center text-sm text-gray-500">
                                <p>
                                    or 
                                    <a href="{{ route('cart.index') }}" class="font-medium text-yellow-600 hover:text-yellow-500">
                                        Return to Cart
                                        <span aria-hidden="true"> &rarr;</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endsection