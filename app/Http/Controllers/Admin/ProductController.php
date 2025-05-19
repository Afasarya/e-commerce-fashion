<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::where('active', true)->get();
        
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'featured' => 'nullable|boolean',
            'active' => 'nullable|boolean',
        ]);
        
        // Handle image upload
        $imagePath = $request->file('image')->store('products', 'public');
        
        // Create product
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::lower(Str::random(5)),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'image' => $imagePath,
            'featured' => $request->boolean('featured'),
            'active' => $request->boolean('active', true),
        ]);
        
        return redirect()->route('admin.products.edit', $product)->with('success', 'Product created successfully. Now you can add sizes and variants.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('active', true)->get();
        $product->load(['variants', 'sizes']);
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'featured' => 'nullable|boolean',
            'active' => 'nullable|boolean',
        ]);
        
        // Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = $product->image;
        }
        
        // Update product
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'image' => $imagePath,
            'featured' => $request->boolean('featured'),
            'active' => $request->boolean('active', true),
        ]);
        
        return redirect()->route('admin.products.edit', $product)->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Delete image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Delete associated sizes and variants
            $product->sizes()->delete();
            $product->variants()->delete();
            
            // Delete product
            $product->delete();
            
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
    
    /**
     * Store a new variant for the product.
     */
    public function storeVariant(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'variant_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $imagePath = null;
        if ($request->hasFile('variant_image')) {
            $imagePath = $request->file('variant_image')->store('variants', 'public');
        }
        
        ProductVariant::create([
            'product_id' => $product->id,
            'name' => $request->name,
            'value' => $request->value,
            'image' => $imagePath,
            'active' => true,
        ]);
        
        return redirect()->route('admin.products.edit', $product)->with('success', 'Variant added successfully.');
    }
    
    /**
     * Remove the specified variant from storage.
     */
    public function destroyVariant(Product $product, ProductVariant $variant)
    {
        // Check if variant belongs to the product
        if ($variant->product_id !== $product->id) {
            return redirect()->route('admin.products.edit', $product)->with('error', 'Variant does not belong to this product.');
        }
        
        // Delete variant image
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }
        
        // Delete variant
        $variant->delete();
        
        return redirect()->route('admin.products.edit', $product)->with('success', 'Variant deleted successfully.');
    }
    
    /**
     * Store a new size for the product.
     */
    public function storeSize(Request $request, Product $product)
    {
        $request->validate([
            'size' => 'required|string|max:10',
            'stock' => 'required|integer|min:0',
        ]);
        
        ProductSize::create([
            'product_id' => $product->id,
            'size' => $request->size,
            'stock' => $request->stock,
            'active' => true,
        ]);
        
        return redirect()->route('admin.products.edit', $product)->with('success', 'Size added successfully.');
    }
    
    /**
     * Remove the specified size from storage.
     */
    public function destroySize(Product $product, ProductSize $size)
    {
        // Check if size belongs to the product
        if ($size->product_id !== $product->id) {
            return redirect()->route('admin.products.edit', $product)->with('error', 'Size does not belong to this product.');
        }
        
        // Delete size
        $size->delete();
        
        return redirect()->route('admin.products.edit', $product)->with('success', 'Size deleted successfully.');
    }
}
