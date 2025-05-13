<x-layouts.app :title="('Products')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" class="text-center mb-4">Products</flux:heading>
        <flux:subheading size="lg" class="text-center mb-6">Manage your product data efficiently</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <!-- Search and Add New Product Button -->
    <div class="flex justify-between items-center mb-6">
        <div class="w-full max-w-xs">
            <form action="{{ route('dashboard.products.index') }}" method="get" class="flex items-center space-x-2">
                @csrf
                <flux:input icon="magnifying-glass" name="q" value="{{ $q }}" placeholder="Search Products" class="w-full" />
            </form>
        </div>
        <div>
            <flux:button icon="plus" class="bg-teal-600 hover:bg-teal-700 text-white">
                <flux:link href="{{ route('dashboard.products.create') }}" variant="subtle">Add New Product</flux:link>
            </flux:button>
        </div>
    </div>

    <!-- Success Message -->
    @if(session()->has('success'))
        <flux:badge color="lime" class="mb-6 w-full">{{ session()->get('success') }}</flux:badge>
    @endif

    <!-- Table for displaying products -->
    <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
        <table class="min-w-full leading-normal table-auto">
            <thead class="bg-gray-100">
                <tr class="text-left text-sm text-gray-500">
                    <th class="py-3 px-5">ID</th>
                    <th class="py-3 px-5">IMAGE</th>
                    <th class="py-3 px-5">NAME</th>
                    <th class="py-3 px-5">SLUG</th>
                    <th class="py-3 px-5">SKU</th>
                    <th class="py-3 px-5">PRICE</th>
                    <th class="py-3 px-5">STOCK</th>
                    <th class="py-3 px-5">STATUS</th>
                    <th class="py-3 px-5">CREATED AT</th>
                    <th class="py-3 px-5">ACTIONS</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @foreach($products as $key => $product)
                    <tr class="hover:bg-gray-50 transition duration-300">
                        <td class="py-3 px-6">{{ $key + 1 }}</td>
                        <td class="py-3 px-6">

                            @if($product->image_url)
                            <img src="{{ Storage::url($product->image_url) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded">
                            @else
                            <div class="h-12 w-12 bg-gray-200 flex items-center justify-center rounded">
                            <span class="text-gray-500 text-sm"></span>
                            </div>
                            @endif

                        </td>
                        <td class="py-3 px-6">{{ $product->name }}</td>
                        <td class="py-3 px-6">{{ $product->slug }}</td>
                        <td class="py-3 px-6">{{ $product->sku }}</td>
                        <td class="py-3 px-6">Rp {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td class="py-3 px-6">{{ $product->stock }}</td>
                        <td class="py-3 px-6">
                            @if($product->is_active)
                                <flux:badge color="lime">Active</flux:badge>
                            @else
                                <flux:badge color="red">Inactive</flux:badge>
                            @endif
                        </td>
                        <td class="py-3 px-6">{{ $product->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-6">
                            <flux:dropdown>
                                <flux:button icon:trailing="chevron-down" class="bg-gray-200 hover:bg-gray-300">Actions</flux:button>
                                <flux:menu>
                                    <flux:menu.item icon="pencil" href="{{ route('dashboard.products.edit', $product->id) }}">Edit</flux:menu.item>
                                    <flux:menu.item icon="trash" variant="danger" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this product?')) 
                                    document.getElementById('delete-form-{{ $product->id }}').submit();">Delete</flux:menu.item>
                                    <form id="delete-form-{{ $product->id }}" action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </flux:menu>
                            </flux:dropdown>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</x-layouts.app>
