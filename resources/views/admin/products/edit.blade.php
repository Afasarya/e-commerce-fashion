@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Product</h1>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center">
                <i class="ri-arrow-left-line mr-2"></i> Back to Products
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Main Product Info -->
            <div class="md:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Product Information</h2>
                        
                        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <input type="text" name="name" id="name" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                    <select name="category_id" id="category_id" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" id="description" rows="4" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (Rp)</label>
                                    <input type="number" name="price" id="price" step="1" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('price', $product->price) }}" required>
                                    @error('price')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">Sale Price (Rp, optional)</label>
                                    <input type="number" name="sale_price" id="sale_price" step="1" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('sale_price', $product->sale_price) }}">
                                    @error('sale_price')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                                <div class="flex items-center space-x-6">
                                    @if($product->image)
                                        <div class="flex-shrink-0 h-20 w-20">
                                            <img class="h-20 w-20 rounded-md object-cover" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                        </div>
                                    @endif
                                    <input type="file" name="image" id="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Leave blank to keep current image</p>
                                @error('image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center mb-6">
                                <input type="checkbox" name="featured" id="featured" value="1" class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                <label for="featured" class="ml-2 block text-sm text-gray-700">Featured Product</label>
                            </div>

                            <div class="flex items-center mb-6">
                                <input type="checkbox" name="active" id="active" value="1" class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded" {{ old('active', $product->active) ? 'checked' : '' }}>
                                <label for="active" class="ml-2 block text-sm text-gray-700">Active</label>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
                                    <i class="ri-save-line mr-1"></i> Update Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Product Info</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-500">ID</div>
                                <div class="font-medium">{{ $product->id }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-500">Slug</div>
                                <div class="font-medium">{{ $product->slug }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-500">Created</div>
                                <div class="font-medium">{{ $product->created_at->format('d M Y') }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-500">Last Updated</div>
                                <div class="font-medium">{{ $product->updated_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manage Product Sizes -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Manage Sizes</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-md font-medium text-gray-700 mb-2">Add New Size</h3>
                        <form action="{{ route('admin.products.sizes.store', $product) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="flex space-x-4">
                                <div class="w-1/3">
                                    <label for="size" class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                                    <input type="text" name="size" id="size" placeholder="S, M, L, XL..." class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                                <div class="w-1/3">
                                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                                    <input type="number" min="0" name="stock" id="stock" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                                <div class="w-1/3 flex items-end">
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md w-full">
                                        <i class="ri-add-line mr-1"></i> Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div>
                        <h3 class="text-md font-medium text-gray-700 mb-2">Available Sizes</h3>
                        <div class="bg-gray-50 p-4 rounded">
                            @if($product->sizes->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($product->sizes as $size)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $size->size }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $size->stock }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($size->active)
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Active
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                Inactive
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <form action="{{ route('admin.products.sizes.destroy', [$product, $size]) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this size?')">
                                                                <i class="ri-delete-bin-line"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500">No sizes added yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-lg font-medium text-red-600 mb-4">Danger Zone</h2>
                
                <div class="bg-red-50 p-4 rounded border border-red-100">
                    <p class="text-sm text-red-800 mb-4">Deleting this product will remove it permanently along with all its variants and sizes. This action cannot be undone.</p>
                    
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md" onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                            <i class="ri-delete-bin-line mr-1"></i> Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection