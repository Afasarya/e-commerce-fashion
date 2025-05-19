@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="bg-white">
    <div class="max-w-3xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="mt-3 text-3xl font-bold text-gray-900">Payment Successful!</h1>
            <p class="mt-2 text-lg text-gray-500">Thank you for your order. Your payment has been successfully processed.</p>
            
            @if(isset($order))
            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-6 text-left">
                <h2 class="text-lg font-medium text-gray-900">Order #{{ $order->order_number }}</h2>
                <p class="mt-1 text-sm text-gray-500">Placed on {{ $order->created_at->format('F j, Y') }}</p>
                
                <div class="mt-4">
                    <div class="flex justify-between text-sm font-medium">
                        <p class="text-gray-600">Total Amount:</p>
                        <p class="text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex justify-between text-sm font-medium mt-1">
                        <p class="text-gray-600">Status:</p>
                        <p class="text-green-600">Paid</p>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="mt-8">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    View My Orders
                </a>
                
                <a href="{{ route('products.index') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection