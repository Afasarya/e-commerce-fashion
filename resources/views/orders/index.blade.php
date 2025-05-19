@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">My Orders</h1>
        
        @if($orders->isEmpty())
            <div class="mt-12 text-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 rounded-lg">
                <i class="ri-shopping-bag-line text-6xl text-gray-400"></i>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No orders yet</h3>
                <p class="mt-1 text-sm text-gray-500">When you place an order, it will appear here.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Start Shopping
                    </a>
                </div>
            </div>
        @else
            <div class="mt-12 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Order Number</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Payment</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">View</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($orders as $order)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $order->order_number }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${{ number_format($order->total_amount, 2) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @if($order->status == 'pending')
                                        <span class="inline-flex items-center rounded-md bg-yellow-100 px-2.5 py-0.5 text-sm font-medium text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($order->status == 'processing')
                                        <span class="inline-flex items-center rounded-md bg-blue-100 px-2.5 py-0.5 text-sm font-medium text-blue-800">
                                            Processing
                                        </span>
                                    @elseif($order->status == 'shipped')
                                        <span class="inline-flex items-center rounded-md bg-indigo-100 px-2.5 py-0.5 text-sm font-medium text-indigo-800">
                                            Shipped
                                        </span>
                                    @elseif($order->status == 'completed')
                                        <span class="inline-flex items-center rounded-md bg-green-100 px-2.5 py-0.5 text-sm font-medium text-green-800">
                                            Completed
                                        </span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="inline-flex items-center rounded-md bg-red-100 px-2.5 py-0.5 text-sm font-medium text-red-800">
                                            Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @if($order->payment_status == 'pending')
                                        <span class="inline-flex items-center rounded-md bg-yellow-100 px-2.5 py-0.5 text-sm font-medium text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($order->payment_status == 'processing')
                                        <span class="inline-flex items-center rounded-md bg-blue-100 px-2.5 py-0.5 text-sm font-medium text-blue-800">
                                            Processing
                                        </span>
                                    @elseif($order->payment_status == 'paid')
                                        <span class="inline-flex items-center rounded-md bg-green-100 px-2.5 py-0.5 text-sm font-medium text-green-800">
                                            Paid
                                        </span>
                                    @elseif($order->payment_status == 'failed')
                                        <span class="inline-flex items-center rounded-md bg-red-100 px-2.5 py-0.5 text-sm font-medium text-red-800">
                                            Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('orders.show', $order) }}" class="text-yellow-600 hover:text-yellow-900">
                                        View<span class="sr-only">, {{ $order->order_number }}</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 