<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')->when($request->q, fn ($q, $v) => $q->where('name', 'like', "%$v%"))->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        return view('products.index', compact('products', 'categories'));
    }
    public function store(Request $request)
    {
        $data = $request->validate(['category_id'=>'required|exists:categories,id','name'=>'required|max:150','sku'=>'required|max:50|unique:products,sku','price'=>'required|numeric|min:0','unit'=>'required|max:30','stock'=>'required|integer|min:0','minimum_stock'=>'required|integer|min:0','image'=>'nullable|url']);
        $product = Product::create($data); $this->log($request, 'Tambah produk', $product->name);
        return back()->with('success', 'Produk berhasil ditambahkan.');
    }
    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['category_id'=>'required|exists:categories,id','name'=>'required|max:150','sku'=>'required|max:50|unique:products,sku,'.$product->id,'price'=>'required|numeric|min:0','unit'=>'required|max:30','minimum_stock'=>'required|integer|min:0','image'=>'nullable|url']);
        $product->update($data); $this->log($request, 'Ubah produk', $product->name);
        return back()->with('success', 'Produk berhasil diperbarui.');
    }
    public function destroy(Request $request, Product $product)
    {
        abort_if($product->stockIns()->exists() || $product->transactionItems()->exists(), 422, 'Produk sudah memiliki riwayat dan tidak dapat dihapus.');
        $name=$product->name; $product->delete(); $this->log($request, 'Hapus produk', $name); return back()->with('success', 'Produk dihapus.');
    }
    private function log(Request $request, string $action, string $description) { ActivityLog::create(['user_id'=>$request->user()->id,'action'=>$action,'description'=>$description]); }
}
