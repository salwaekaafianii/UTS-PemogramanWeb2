<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    // menampilkan daftar produk dengan fitur pencarian
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                      ->orWhere('description', 'like', '%' . $request->q . '%')
                      ->orWhere('sku', 'like', '%' . $request->q . '%');
            })
            ->paginate(10);

        return view('dashboard.products.index', [
            'products' => $products,
            'q' => $request->q
        ]);
    }
    //menampilkan form tambah produk
    public function create()
    {
        return view('dashboard.products.create');
    }
    // menyimpan data produk baru
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:50|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()
                ->with('errorMessage', 'Validasi Error, Silahkan lengkapi data terlebih dahulu');
        }

        // simpan data ke database
        $product = new Product;
        $product->fill($request->only([
            'name', 'slug', 'description', 'sku', 'price',
            'stock', 'product_category_id', 'is_active'
        ]));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploads/products', $imageName, 'public');
            $product->image_url = $imagePath;
        }

        $product->save();

        return redirect()->back()->with('success', 'Data produk berhasil disimpan');
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
        return view('dashboard.products.edit', compact('product'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()
                ->with('errorMessage', 'Validasi Error, Silahkan lengkapi data terlebih dahulu');
        }

        $product->fill($request->only([
            'name', 'slug', 'description', 'sku', 'price',
            'stock', 'product_category_id', 'is_active'
        ]));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploads/products', $imageName, 'public');
            $product->image_url = $imagePath;
        }

        $product->save();

        return redirect()->back()->with('success', 'Data produk berhasil diperbarui');
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
        // kalau slug ini adalah kategori, kamu bisa filter berdasarkan kategori
        $products = Product::where('slug', $slug)->get(); // atau bisa pakai all() kalau slug bukan dari produk
        return view('products.show', compact('slug', 'products'));
    }

}