<x-layouts.app :title="('Products')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl">Add New Product</flux:heading>
        <flux:subheading size="lg" class="mb-6">Manage data Products</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if(session()->has('successMessage'))
        <flux:badge color="lime" class="mb-3 w-full">{{ session()->get('successMessage') }}</flux:badge>
    @elseif(session()->has('errorMessage'))
        <flux:badge color="red" class="mb-3 w-full">{{ session()->get('errorMessage') }}</flux:badge>
    @endif

    <form action="{{ route('dashboard.products.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <flux:input label="Product Name" name="name" class="mb-3" value="{{ old('name') }}" />

        <flux:input label="Slug" name="slug" class="mb-3" value="{{ old('slug') }}" />

        <flux:input label="SKU" name="sku" class="mb-3" value="{{ old('sku') }}" />

        <flux:textarea label="Description" name="description" class="mb-3">{{ old('description') }}</flux:textarea>

        <flux:input label="Price" name="price" type="number" class="mb-3" value="{{ old('price') }}" />

        <flux:input label="Stock" name="stock" type="number" class="mb-3" value="{{ old('stock') }}" />

        <flux:select label="Category" name="product_category_id" class="mb-3">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('product_category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </flux:select>

        <flux:input type="file" label="Image" name="image" class="mb-3" />

        <flux:separator />

        <div class="mt-4">
            <flux:button type="submit" variant="primary">Save</flux:button>
            <flux:link href="{{ route('dashboard.products.index') }}" variant="ghost" class="ml-3">Back</flux:link>
        </div>
    </form>
</x-layouts.app>



