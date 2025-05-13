<x-layouts.app :title="__('posts')">
    <h1> halaman admin posts</h1>

    @if(session()->has('success'))
    <

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Created</th>
                <th>Update</th>
            </tr>
        </thead>


    </table>
</x-layouts.app>