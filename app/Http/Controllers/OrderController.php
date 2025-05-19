<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of user's orders
     */
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        
        return view('orders.index', compact('orders'));
    }
    
    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'You do not have permission to view this order.');
        }
        
        $order->load(['items.product', 'items.variant', 'items.size']);
        
        return view('orders.show', compact('order'));
    }
    
    /**
     * Process payment confirmation
     */
    public function paymentConfirmation(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $order = Order::findOrFail($request->order_id);
        
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'You do not have permission to confirm payment for this order.');
        }
        
        // Check if payment is already completed
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)->with('info', 'Payment for this order has already been completed.');
        }
        
        // Store payment proof image
        $imagePath = $request->file('payment_proof')->store('payment_proofs', 'public');
        
        // Update order status
        $order->update([
            'payment_status' => 'processing', // Admin will verify and set to 'paid'
            'payment_id' => 'MANUAL-' . time(),
        ]);
        
        return redirect()->route('orders.show', $order)->with('success', 'Payment confirmation submitted successfully. We will verify your payment shortly.');
    }
    
    /**
     * Handle Midtrans payment callback
     */
    public function paymentCallback(Request $request)
    {
        // This is a placeholder for Midtrans callback
        // In a real implementation, you would verify the callback signature, process the payment result,
        // and update the order status accordingly
        
        // For demonstration, we'll just log the callback and return a success response
        Log::info('Midtrans callback received', $request->all());
        
        return response()->json(['status' => 'success']);
    }
}
