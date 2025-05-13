<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Categories;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    // menampilkan daftar produk dengan fitur pencarian
    public function index(Request $request)
    {
            $q = $request->q;

        $products = Product::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                      ->orWhere('description', 'like', '%' . $request->q . '%')
                      ->orWhere('sku', 'like', '%' . $request->q . '%');
            })
            ->paginate(10);
        

        return view('dashboard.products.index', compact('products', 'q'));
    
    }
    //menampilkan form tambah produk
    public function create()
    {
        $categories = Categories::all();
        return view('dashboard.products.create', compact('categories'));
    }
 // menyimpan produk baru ke dalam database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'product_category_id' => 'required|exists:product_categories,id', 
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $product = new Product();
        $product->name = $validated['name'];
        $product->slug = $validated['slug'];
        $product->sku = $validated['sku'];
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->product_category_id = $validated['product_category_id'];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
            $product->image_url = $imagePath;
        }

        $product->save(); //simpan produk ke database

        return redirect()->route('dashboard.products.index')->with('success', 'Produk berhasil disimpan');

    }

    // menampilkan detail produk
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('dashboard.products.show', compact('product'));
    }

    
    // menampilkan form edit produk
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Categories::all();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }
    // memperbarui data produk
    public function update(Request $request, string $id)
{
    $product = Product::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|unique:products,slug,' . $product->id . '|max:255',
        'sku' => 'nullable|string|max:100',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'product_category_id' => 'required|exists:product_categories,id',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
        if ($product->image_url) {
            Storage::delete('public/' . $product->image_url);
        }

        $imagePath = $request->file('image')->store('images/products', 'public');
        $validated['image_url'] = $imagePath;
    }

    $product->update($validated);

    return redirect()->route('dashboard.products.index')->with('success', 'Produk berhasil diperbarui');
}

    // menghapus produk
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Data produk berhasil dihapus');
    }

    public function publicView($slug)
    {
        $products = Product::where('slug', $slug)->get();
        return view('products.show', compact('slug', 'products'));
    }

}