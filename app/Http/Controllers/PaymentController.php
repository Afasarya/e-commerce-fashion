<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;
    
    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function updateStatus(Request $request, Order $order)
{
    // Validate request
    $request->validate([
        'transaction_id' => 'required|string',
        'payment_type' => 'required|string',
        'transaction_status' => 'required|string',
    ]);
    
    // Check if the order belongs to the authenticated user
    if ($order->user_id !== Auth::id()) {
        return response()->json([
            'error' => true,
            'message' => 'Unauthorized'
        ], 403);
    }
    
    // Update order details based on transaction status
    if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
        $order->payment_status = 'paid';
        $order->status = 'processing';
    } else if ($request->transaction_status == 'pending') {
        $order->payment_status = 'pending';
    } else {
        $order->payment_status = 'failed';
    }
    
    // Save transaction ID from Midtrans
    $order->payment_id = $request->transaction_id;
    $order->save();
    
    Log::info('Order status updated via direct callback', [
        'order_id' => $order->id,
        'order_number' => $order->order_number,
        'status' => $order->payment_status,
        'transaction_id' => $request->transaction_id,
        'transaction_status' => $request->transaction_status
    ]);
    
    return response()->json([
        'success' => true,
        'order' => $order,
        'redirect' => route('payment.success', ['order_id' => $order->id])
    ]);
}

/**
 * Payment success redirect
 */
public function success(Request $request)
{
    $order = null;
    if ($request->has('order_id')) {
        $order = Order::findOrFail($request->order_id);
        
        // Verify the order belongs to the current user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')
                ->with('error', 'Unauthorized access to order details.');
        }
    }
    
    return view('payment.success', compact('order'));
}
    
    /**
     * Generate payment token for an order
     */
    public function generateToken(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        // Check if payment is pending
        if ($order->payment_status !== 'pending') {
            return response()->json([
                'error' => true,
                'message' => 'This order has already been paid or is being processed'
            ], 400);
        }
        
        try {
            $result = $this->midtransService->createTransaction($order);
            
            if (isset($result['error'])) {
                Log::error('Payment token generation failed', [
                    'order_id' => $order->id,
                    'message' => $result['message']
                ]);
                
                return response()->json([
                    'error' => true,
                    'message' => 'Failed to initialize payment: ' . $result['message']
                ], 500);
            }
            
            return response()->json([
                'error' => false,
                'token' => $result['token']
            ]);
        } catch (\Exception $e) {
            Log::error('Exception when generating payment token', [
                'order_id' => $order->id,
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Handle payment notification from Midtrans
     */
    public function notification(Request $request)
    {
        Log::info('Payment notification received', $request->all());
        
        try {
            $notification = $request->all();
            $result = $this->midtransService->handleNotification($notification);
            
            if (!$result['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $result['message']
                ], 404);
            }
            
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Error handling payment notification: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the notification'
            ], 500);
        }
    }
    
    /**
     * Payment success redirect
     */
    public function paymentSuccess(Request $request)
    {
        return redirect()->route('orders.index')->with('success', 'Payment completed successfully. Thank you for your order!');
    }
    
    /**
     * Payment failed redirect
     */
    public function failed(Request $request)
    {
        return redirect()->route('orders.index')->with('error', 'Payment failed. Please try again or contact customer support.');
    }
    
}