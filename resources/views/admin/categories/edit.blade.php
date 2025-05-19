@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Category</h1>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center">
                <i class="ri-arrow-left-line mr-2"></i> Back to Categories
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Category Info -->
            <div class="md:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" name="name" id="name" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{ old('name', $category->name) }}" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                                <textarea name="description" id="description" rows="3" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center mb-6">
                                <input type="checkbox" name="active" id="active" value="1" class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded" {{ old('active', $category->active) ? 'checked' : '' }}>
                                <label for="active" class="ml-2 block text-sm text-gray-700">Active</label>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
                                    <i class="ri-save-line mr-1"></i> Update Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Category Info & Products -->
            <div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Category Info</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-500">ID</div>
                                <div class="font-medium">{{ $category->id }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-500">Slug</div>
                                <div class="font-medium">{{ $category->slug }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-500">Products</div>
                                <div class="font-medium">{{ $category->products->count() }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="text-sm text-gray-500">Status</div>
                                <div class="font-medium">
                                    @if($category->active)
                                        <span class="text-green-600">Active</span>
                                    @else
                                        <span class="text-red-600">Inactive</span>
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
                            <p class="text-sm text-red-800 mb-4">Deleting this category will remove it permanently. Categories with products cannot be deleted.</p>
                            
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md" onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                    <i class="ri-delete-bin-line mr-1"></i> Delete Category
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection