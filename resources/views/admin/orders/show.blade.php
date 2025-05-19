@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Order #{{ $order->order_number }}</h1>
                <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('F j, Y') }}</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center">
                <i class="ri-arrow-left-line mr-2"></i> Back to Orders
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="md:col-span-2">
                <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                    </div>
                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $item->variant ? $item->variant->value : 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $item->size ? $item->size->size : 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="5" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Subtotal:</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Shipping:</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp 15.000
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Total:</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Shipping Information</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Recipient Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_phone }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_address }}</dd>
                            </div>
                            @if($order->notes)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Order Status Panel -->
            <div>
                <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Order Status</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Order Status</label>
                                <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                                <select id="payment_status" name="payment_status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Admin Notes</label>
                                <textarea id="notes" name="admin_notes" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm">{{ $order->admin_notes }}</textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-md">
                                Update Order
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Customer Information -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        @if($order->user)
                            <dl>
                                <div class="mb-2">
                                    <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->user->email }}</dd>
                                </div>
                            </dl>
                        @else
                            <p class="text-sm text-gray-500">Guest Customer</p>
                        @endif
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Payment Information</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <div class="mb-2">
                                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($order->payment_method) }}</dd>
                            </div>
                            <div class="mb-2">
                                <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                                <dd class="mt-1">
                                    @if($order->payment_status == 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($order->payment_status == 'paid')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                    @elseif($order->payment_status == 'failed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Failed
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            @if($order->payment_id)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->payment_id }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection