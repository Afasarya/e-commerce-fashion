<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display user's cart
     */
    public function index()
    {
        $cartItems = Auth::user()->cartItems()
            ->with(['product', 'variant', 'size'])
            ->get();
            
        $subtotal = $cartItems->sum(function ($item) {
            return $item->getSubtotal();
        });
        
        return view('cart.index', compact('cartItems', 'subtotal'));
    }
    
    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'product_size_id' => 'nullable|exists:product_sizes,id',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        if (!$product->active) {
            return redirect()->back()->with('error', 'This product is not available.');
        }
        
        // Check size is available
        if ($request->product_size_id) {
            $size = ProductSize::findOrFail($request->product_size_id);
            if (!$size->isInStock()) {
                return redirect()->back()->with('error', 'Selected size is out of stock.');
            }
        }
        
        // Check if item already exists in cart
        $existingItem = Auth::user()->cartItems()
            ->where('product_id', $request->product_id)
            ->where('product_variant_id', $request->product_variant_id)
            ->where('product_size_id', $request->product_size_id)
            ->first();
            
        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'product_variant_id' => $request->product_variant_id,
                'product_size_id' => $request->product_size_id,
                'quantity' => $request->quantity,
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }
    
    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $cartItem = CartItem::findOrFail($request->cart_item_id);
        
        // Check if cart item belongs to the user
        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->route('cart.index')->with('error', 'You do not have permission to update this cart item.');
        }
        
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }
    
    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);
        
        $cartItem = CartItem::findOrFail($request->cart_item_id);
        
        // Check if cart item belongs to the user
        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->route('cart.index')->with('error', 'You do not have permission to remove this cart item.');
        }
        
        $cartItem->delete();
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart successfully.');
    }
}
