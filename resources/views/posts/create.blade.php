<x-layouts.app :title="__('Create New Post')">
<form action="{{ route('dashboard.posts.store') }}" method="POST">
        @csrf
        <flux:input
            label="Title"
            name="title"
            type="text"
            class="mb-3"
            placeholder="Enter post title"
            required />

        <flux:textarea
            label="Content"
            name="content"
            type="textarea"
            class="mb-3"
            placeholder="Enter post content"
            required />

        <flux:input
            label="Image"
            name="image"
            type="file"
            class="mb-3"
            accept="image/*"
            required />

        <flux:button type="submit" variant="primary">
            Save
        </flux:button>
    </form>
</x-layouts.app>
