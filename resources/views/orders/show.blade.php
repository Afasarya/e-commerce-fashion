@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Order Details</h1>
            <a href="{{ route('orders.index') }}" class="text-yellow-600 hover:text-yellow-900">
                <span>&larr; Back to orders</span>
            </a>
        </div>
        
        <!-- Order status banner -->
        <div class="mt-6 rounded-lg bg-gray-50 px-6 py-6 md:flex md:items-center md:justify-between">
            <div class="flex space-x-6 md:space-x-8">
                <div>
                    <h2 class="text-sm font-medium text-gray-500">Order number</h2>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $order->order_number }}</p>
                </div>
                <div>
                    <h2 class="text-sm font-medium text-gray-500">Date placed</h2>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <h2 class="text-sm font-medium text-gray-500">Total amount</h2>
                    <p class="mt-1 text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="mt-6 space-y-4 sm:flex sm:space-x-4 sm:space-y-0 md:mt-0">
                <div>
                    @if($order->status == 'pending')
                        <span class="inline-flex items-center rounded-md bg-yellow-100 px-2.5 py-0.5 text-sm font-medium text-yellow-800">
                            Status: Pending
                        </span>
                    @elseif($order->status == 'processing')
                        <span class="inline-flex items-center rounded-md bg-blue-100 px-2.5 py-0.5 text-sm font-medium text-blue-800">
                            Status: Processing
                        </span>
                    @elseif($order->status == 'shipped')
                        <span class="inline-flex items-center rounded-md bg-indigo-100 px-2.5 py-0.5 text-sm font-medium text-indigo-800">
                            Status: Shipped
                        </span>
                    @elseif($order->status == 'completed')
                        <span class="inline-flex items-center rounded-md bg-green-100 px-2.5 py-0.5 text-sm font-medium text-green-800">
                            Status: Completed
                        </span>
                    @elseif($order->status == 'cancelled')
                        <span class="inline-flex items-center rounded-md bg-red-100 px-2.5 py-0.5 text-sm font-medium text-red-800">
                            Status: Cancelled
                        </span>
                    @endif
                </div>
                <div>
                    @if($order->payment_status == 'pending')
                        <span class="inline-flex items-center rounded-md bg-yellow-100 px-2.5 py-0.5 text-sm font-medium text-yellow-800">
                            Payment: Pending
                        </span>
                    @elseif($order->payment_status == 'processing')
                        <span class="inline-flex items-center rounded-md bg-blue-100 px-2.5 py-0.5 text-sm font-medium text-blue-800">
                            Payment: Processing
                        </span>
                    @elseif($order->payment_status == 'paid')
                        <span class="inline-flex items-center rounded-md bg-green-100 px-2.5 py-0.5 text-sm font-medium text-green-800">
                            Payment: Paid
                        </span>
                    @elseif($order->payment_status == 'failed')
                        <span class="inline-flex items-center rounded-md bg-red-100 px-2.5 py-0.5 text-sm font-medium text-red-800">
                            Payment: Failed
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Payment section if payment is pending -->
        @if($order->payment_status == 'pending')
            <div class="mt-8 bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                <div class="px-4 py-6 sm:p-8">
                    <h2 class="text-lg font-semibold leading-6 text-gray-900">Payment</h2>
                    <p class="mt-2 text-sm text-gray-500">Complete your payment to finalize your order.</p>
                    
                    <div class="mt-6">
                        <button 
                            type="button" 
                            id="pay-button"
                            class="rounded-md bg-yellow-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-yellow-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500"
                        >
                            Pay Now
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">
                            <span class="font-medium">Alternative:</span> If you wish to pay via bank transfer or other methods manually, 
                            please contact our customer support.
                        </p>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Order summary -->
        <div class="mt-8 bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
            <div class="px-4 py-6 sm:px-6">
                <h2 class="text-lg font-medium text-gray-900">Order summary</h2>
            </div>
            
            <div class="border-t border-gray-200">
                <ul role="list" class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <li class="p-4 sm:p-6">
                            <div class="flex items-start">
                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-lg bg-gray-200">
                                    <img 
                                        src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/product-placeholder.jpg') }}"
                                        alt="{{ $item->product->name }}"
                                        class="h-full w-full object-cover object-center"
                                    >
                                </div>
                                
                                <div class="ml-6 flex-1">
                                    <div class="flex items-start justify-between">
                                        <h3 class="text-base font-medium text-gray-900">
                                            <a href="{{ route('products.show', $item->product->slug) }}">
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>
                                        <p class="ml-4 text-base font-medium text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    </div>
                                    
                                    <p class="mt-1 text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                    
                                    @if($item->variant)
                                        <p class="mt-1 text-sm text-gray-500">Color: {{ $item->variant->value }}</p>
                                    @endif
                                    
                                    @if($item->size)
                                        <p class="mt-1 text-sm text-gray-500">Size: {{ $item->size->size }}</p>
                                    @endif
                                    
                                    <div class="mt-2 text-sm text-gray-500">
                                        <p>Quantity: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                <div class="flex justify-between text-sm font-medium text-gray-900">
                    <p>Subtotal</p>
                    <p>Rp {{ number_format($order->items->sum(function ($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}</p>
                </div>
                <div class="mt-2 flex justify-between text-sm font-medium text-gray-900">
                    <p>Shipping</p>
                    <p>Rp {{ number_format(15000, 0, ',', '.') }}</p>
                </div>
                <div class="mt-2 flex justify-between text-sm font-medium text-gray-900">
                    <p>Tax</p>
                    <p>Rp {{ number_format($order->total_amount - $order->items->sum(function ($item) { return $item->price * $item->quantity; }) - 15000, 0, ',', '.') }}</p>
                </div>
                <div class="mt-4 flex justify-between border-t border-gray-200 pt-4 text-base font-medium text-gray-900">
                    <p>Total</p>
                    <p>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Shipping information -->
        <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-8 lg:grid-cols-2">
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                <div class="px-4 py-6 sm:p-8">
                    <h2 class="text-lg font-semibold leading-6 text-gray-900">Shipping Address</h2>
                    
                    <div class="mt-6 text-sm leading-6 text-gray-700">
                        <p class="font-medium text-gray-900">{{ $order->shipping_name }}</p>
                        <p class="mt-1">{{ $order->shipping_address }}</p>
                        <p class="mt-1">Phone: {{ $order->shipping_phone }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                <div class="px-4 py-6 sm:p-8">
                    <h2 class="text-lg font-semibold leading-6 text-gray-900">Payment Information</h2>
                    
                    <div class="mt-6 text-sm leading-6 text-gray-700">
                        <p><span class="font-medium text-gray-900">Payment Method:</span> {{ ucfirst($order->payment_method) }}</p>
                        <p class="mt-1"><span class="font-medium text-gray-900">Payment Status:</span> {{ ucfirst($order->payment_status) }}</p>
                        
                        @if($order->payment_id)
                            <p class="mt-1"><span class="font-medium text-gray-900">Payment ID:</span> {{ $order->payment_id }}</p>
                        @endif
                        
                        @if($order->notes)
                            <div class="mt-4 border-t border-gray-200 pt-4">
                                <h3 class="font-medium text-gray-900">Order Notes:</h3>
                                <p class="mt-1">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($order->payment_status == 'pending')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            // Show loading state
            payButton.disabled = true;
            payButton.innerHTML = 'Processing...';
            
            // Call your backend to get the Midtrans token
            fetch('{{ route('payment.token', $order) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.message);
                    console.error('Payment error:', data);
                    payButton.disabled = false;
                    payButton.innerHTML = 'Pay Now';
                    return;
                }
                
                if (data.token) {
                    // Open Midtrans Snap payment page
                    window.snap.pay(data.token, {
                        onSuccess: function(result) {
                            // Update order status first
                            fetch('{{ route('payment.update-status', $order) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    transaction_id: result.transaction_id,
                                    payment_type: result.payment_type,
                                    transaction_status: 'settlement' // For success case
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success && data.redirect) {
                                    window.location.href = data.redirect;
                                } else {
                                    window.location.href = '{{ route('payment.success') }}';
                                }
                            })
                            .catch(error => {
                                console.error('Error updating order status:', error);
                                window.location.href = '{{ route('payment.success') }}';
                            });
                        },
                        onPending: function(result) {
                            // For pending cases (like bank transfers)
                            // We still update the transaction ID but keep status as pending
                            fetch('{{ route('payment.update-status', $order) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    transaction_id: result.transaction_id,
                                    payment_type: result.payment_type,
                                    transaction_status: 'pending'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                window.location.href = '{{ route('orders.show', $order) }}';
                            })
                            .catch(error => {
                                console.error('Error updating order status:', error);
                                window.location.href = '{{ route('orders.show', $order) }}';
                            });
                        },
                        onError: function(result) {
                            console.error('Payment failed:', result);
                            // Update as failed
                            fetch('{{ route('payment.update-status', $order) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    transaction_id: result.transaction_id || 'error',
                                    payment_type: result.payment_type || 'unknown',
                                    transaction_status: 'error'
                                })
                            })
                            .then(() => {
                                alert('Payment failed: ' + (result.status_message || 'Please try again or contact customer support.'));
                                payButton.disabled = false;
                                payButton.innerHTML = 'Pay Now';
                            });
                        },
                        onClose: function() {
                            payButton.disabled = false;
                            payButton.innerHTML = 'Pay Now';
                            alert('Payment window closed. Your order is still pending payment.');
                        }
                    });
                } else {
                    alert('Could not initialize payment. Please try again or contact customer support.');
                    payButton.disabled = false;
                    payButton.innerHTML = 'Pay Now';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again or contact customer support.');
                payButton.disabled = false;
                payButton.innerHTML = 'Pay Now';
            });
        });
    });
</script>
@endif
@endsection