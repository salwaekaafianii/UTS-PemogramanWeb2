<x-layouts.app :title="('Products')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl">Update Product</flux:heading>
        <flux:subheading size="lg" class="mb-6">Manage data Products</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if(session()->has('successMessage'))
        <flux:badge color="lime" class="mb-3 w-full">{{ session()->get('successMessage') }}</flux:badge>
    @elseif(session()->has('errorMessage'))
        <flux:badge color="red" class="mb-3 w-full">{{ session()->get('errorMessage') }}</flux:badge>
    @endif

    <form action="{{ route('dashboard.products.update', $product->id) }}" method="post" enctype="multipart/form-data">
        @method('patch')
        @csrf
        
        <flux:input label="Product Name" name="name" value="{{ $product->name }}" class="mb-3" />

        <flux:input label="Slug" name="slug" value="{{ $product->slug }}" class="mb-3" />

        <flux:input label="SKU" name="sku" class="mb-3" value="{{ old('sku', $product->sku) }}" /> 

        <flux:textarea label="Description" name="description" class="mb-3">{{ $product->description }}</flux:textarea>

        <flux:input label="Price" name="price" type="number" value="{{ $product->price }}" class="mb-3" />

        <flux:input label="Stock" name="stock" type="number" value="{{ $product->stock }}" class="mb-3" />

        <flux:select label="Category" name="product_category_id" class="mb-3">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $product->product_category_id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </flux:select>

        @if($product->image_url)
            <div class="mb-3">
                <img src="{{ Storage::url($product->image_url) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded">
            </div>
        @endif

        <flux:input type="file" label="Image" name="image" class="mb-3" />

        <flux:separator />

        <div class="mt-4">
            <flux:button type="submit" variant="primary">Update</flux:button>
            <flux:link href="{{ route('dashboard.products.index') }}" variant="ghost" class="ml-3">Back</flux:link>
        </div>
    </form>
</x-layouts.app>