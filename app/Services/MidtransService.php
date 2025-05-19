<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $serverKey;
    protected $clientKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;
    
    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->clientKey = config('services.midtrans.client_key');
        $this->isProduction = config('services.midtrans.is_production');
        $this->isSanitized = config('services.midtrans.is_sanitized');
        $this->is3ds = config('services.midtrans.is_3ds');
    }
    
    /**
     * Create Snap payment page for an order
     *
     * @param Order $order
     * @return array
     */
    public function createTransaction(Order $order)
    {
        // Item Details - calculate these first
        $items = [];
        $itemsTotal = 0;
        
        // Add order items
        foreach ($order->items as $item) {
            $price = (int) $item->price;
            $quantity = $item->quantity;
            $subtotal = $price * $quantity;
            $itemsTotal += $subtotal;
            
            $items[] = [
                'id' => $item->product_id,
                'price' => $price,
                'quantity' => $quantity,
                'name' => substr($item->product->name, 0, 50), // Limit name length
            ];
        }
        
        // Fixed shipping cost
        $shipping = 15000;
        $items[] = [
            'id' => 'shipping',
            'price' => $shipping,
            'quantity' => 1,
            'name' => 'Shipping Cost',
        ];
        $itemsTotal += $shipping;
        
        // Calculate tax exactly (10% of items subtotal)
        $taxAmount = (int) round(($order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        }) * 0.1));
        
        $items[] = [
            'id' => 'tax',
            'price' => $taxAmount,
            'quantity' => 1,
            'name' => 'Tax',
        ];
        $itemsTotal += $taxAmount;
        
        // Now use the exact itemsTotal as the gross_amount
        $transaction_details = [
            'order_id' => $order->order_number,
            'gross_amount' => $itemsTotal,
        ];
        
        // Customer Details
        $customer_details = [
            'first_name' => $order->shipping_name,
            'email' => $order->user->email ?? 'customer@example.com',
            'phone' => $order->shipping_phone,
            'billing_address' => [
                'first_name' => $order->shipping_name,
                'phone' => $order->shipping_phone,
                'address' => $order->shipping_address,
            ],
            'shipping_address' => [
                'first_name' => $order->shipping_name,
                'phone' => $order->shipping_phone,
                'address' => $order->shipping_address,
            ],
        ];
        
        // Build the payload with essential fields only
        $payload = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $items,
        ];
        
        try {
            // Log the actual payload for debugging
            Log::info('Midtrans Payload', [
                'gross_amount' => $itemsTotal,
                'items_total' => array_sum(array_map(function($item) {
                    return $item['price'] * $item['quantity'];
                }, $items)),
                'items' => $items
            ]);
            
            $snapToken = $this->getSnapToken($payload);
            return [
                'token' => $snapToken,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            Log::error('Payload: ' . json_encode($payload));
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Get Snap Token from Midtrans API
     *
     * @param array $payload
     * @return string
     */
    protected function getSnapToken($payload)
    {
        $baseUrl = $this->isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->serverKey . ':'),
        ];
        
        // Log the request payload for debugging
        Log::info('Midtrans Request Payload', [
            'url' => $baseUrl,
            'payload' => $payload,
            'gross_amount' => $payload['transaction_details']['gross_amount'],
            'items_sum' => array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $payload['item_details']))
        ]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        Log::info('Midtrans Response', ['statusCode' => $statusCode, 'response' => $response]);
        
        if ($error) {
            Log::error('Midtrans CURL Error: ' . $error);
            throw new \Exception('Payment gateway connection error: ' . $error);
        }
        
        // FIX: Accept both 200 and 201 as success status codes
        if ($statusCode != 200 && $statusCode != 201) {
            Log::error('Midtrans API Error Response: ' . $response);
            $errorMessage = 'Payment gateway error (HTTP ' . $statusCode . ')';
            
            // Try to get more specific error from response
            $responseData = json_decode($response, true);
            if (isset($responseData['error_messages'])) {
                $errorMessage .= ': ' . implode(', ', $responseData['error_messages']);
            }
            
            throw new \Exception($errorMessage);
        }
        
        $responseData = json_decode($response, true);
        
        if (!isset($responseData['token'])) {
            Log::error('Midtrans No Token in Response', $responseData);
            throw new \Exception('Payment gateway error: No token received');
        }
        
        return $responseData['token'];
    }
    
    /**
     * Handle Midtrans payment notification
     *
     * @param array $notification
     * @return array
     */
    public function handleNotification($notification)
    {
        $orderId = $notification['order_id'];
        $transactionStatus = $notification['transaction_status'];
        $paymentType = $notification['payment_type'];
        $transactionId = $notification['transaction_id'];
        
        Log::info('Midtrans notification received', [
            'order_id' => $orderId,
            'status' => $transactionStatus,
            'payment_type' => $paymentType,
        ]);
        
        $order = Order::where('order_number', $orderId)->first();
        
        if (!$order) {
            Log::error('Order not found for notification', ['order_id' => $orderId]);
            return [
                'success' => false,
                'message' => 'Order not found',
            ];
        }
        
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $order->payment_status = 'paid';
                $order->payment_id = $transactionId;
                $order->status = 'processing';
                break;
            case 'pending':
                $order->payment_status = 'pending';
                $order->payment_id = $transactionId;
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                $order->payment_status = 'failed';
                $order->payment_id = $transactionId;
                break;
        }
        
        $order->save();
        
        return [
            'success' => true,
            'order' => $order,
        ];
    }
}