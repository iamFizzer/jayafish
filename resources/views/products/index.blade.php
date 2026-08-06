@extends('layouts.app')
@section('title','Produk & Kategori')
@section('subtitle','Kelola katalog, harga, dan batas minimum stok')

@section('content')
<div class="toolbar">
    <form method="GET" action="{{ route('products.index') }}" class="flex w-full flex-wrap gap-2 lg:w-auto">
        <input class="input min-w-56 flex-1" name="q" value="{{ request('q') }}" placeholder="Cari nama atau SKU produk...">
        <select class="input min-w-56 flex-1" name="category_id">
            <option value="">Semua kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button class="btn-secondary" type="submit">Cari & Filter</button>
        @if(request()->filled('q') || request()->filled('category_id'))
            <a class="btn-secondary" href="{{ route('products.index') }}">Reset</a>
        @endif
    </form>
    <div class="flex gap-2">
        <button class="btn-secondary" data-modal="categoryModal">+ Kategori</button>
        <button class="btn-primary" data-modal="productModal">+ Tambah produk</button>
    </div>
</div>

@if(request()->filled('q') || request()->filled('category_id'))
    <div class="mb-4 text-sm text-slate-500">
        Ditemukan <b class="text-slate-800">{{ $products->total() }}</b> produk
        @if(request()->filled('category_id'))
            pada kategori <b class="text-slate-800">{{ $categories->firstWhere('id', (int) request('category_id'))?->name }}</b>
        @endif
        @if(request()->filled('q'))
            dengan kata kunci <b class="text-slate-800">“{{ request('q') }}”</b>
        @endif
    </div>
@endif

<div class="table-card">
    <div class="overflow-x-auto">
        <table>
            <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Batas minimum</th><th></th></tr></thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td><div class="flex items-center gap-3"><div class="product-thumb">{{ strtoupper(substr($product->name, 0, 1)) }}</div><div><b>{{ $product->name }}</b><div class="text-xs text-slate-400">{{ $product->sku }} · {{ $product->unit }}</div></div></div></td>
                    <td><span class="badge" style="background:{{ $product->category->color }}18;color:{{ $product->category->color }}">{{ $product->category->name }}</span></td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td><b class="{{ $product->stock <= $product->minimum_stock ? 'text-rose-600' : 'text-emerald-700' }}">{{ $product->stock }} {{ $product->unit }}</b></td>
                    <td>{{ $product->minimum_stock }} {{ $product->unit }}</td>
                    <td><button class="link" data-modal="edit-{{ $product->id }}">Edit</button></td>
                </tr>
                <x-modal id="edit-{{ $product->id }}" title="Edit produk">
                    <form method="POST" action="{{ route('products.update', $product) }}" class="form-grid">@csrf @method('PUT')<x-product-fields :categories="$categories" :product="$product"/><div class="col-span-full flex justify-end"><button class="btn-primary">Simpan perubahan</button></div></form>
                </x-modal>
            @empty
                <tr><td colspan="6" class="empty">Tidak ada produk yang sesuai dengan pencarian atau kategori.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $products->links() }}</div>
</div>

<x-modal id="productModal" title="Tambah produk baru">
    <form method="POST" action="{{ route('products.store') }}" class="form-grid">@csrf <x-product-fields :categories="$categories"/><div class="col-span-full flex justify-end"><button class="btn-primary">Simpan produk</button></div></form>
</x-modal>
<x-modal id="categoryModal" title="Tambah kategori">
    <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">@csrf<label class="field-label">Nama kategori<input class="input mt-1" name="name" required></label><label class="field-label">Kelompok<select class="input mt-1" name="type"><option value="alat_pancing">Alat Pancing</option><option value="pakan_ikan">Pakan Ikan</option></select></label><label class="field-label">Warna label<input class="input mt-1 h-12" type="color" name="color" value="#0f766e"></label><button class="btn-primary w-full">Simpan kategori</button></form>
</x-modal>
@endsection
