<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with(['product', 'variant', 'size'])->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty. Please add products before checkout.');
        }
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->getSubtotal();
        });
        
        return view('checkout.index', compact('cartItems', 'subtotal'));
    }
    
    /**
     * Process the checkout
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'shipping_phone' => 'required|string|max:15',
            'payment_method' => 'required|in:midtrans',
            'notes' => 'nullable|string',
        ]);
        
        $cartItems = Auth::user()->cartItems()->with(['product', 'variant', 'size'])->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty. Please add products before checkout.');
        }
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->getSubtotal();
        });
        
        // Calculate shipping, tax and total the same way as in MidtransService
        $shipping = 15000;
        $tax = round($subtotal * 0.1);
        $total = $subtotal + $shipping + $tax;
        
        try {
            DB::beginTransaction();
            
            // Create order with the correctly calculated total
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . Str::upper(Str::random(8)),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_phone' => $request->shipping_phone,
                'notes' => $request->notes,
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_size_id' => $item->product_size_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->getActualPrice(),
                ]);
            }
            
            // Clear cart
            Auth::user()->cartItems()->delete();
            
            // Prepare payment with Midtrans (Sandbox mode)
            // This is a placeholder, actual implementation would require Midtrans SDK
            // For now, we'll just simulate the payment by redirecting to the order page
            
            DB::commit();
            
            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully. Please complete your payment.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }
}
