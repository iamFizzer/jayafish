<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request) { Category::create($request->validate(['name'=>'required|max:100|unique:categories,name','type'=>'required|in:alat_pancing,pakan_ikan','color'=>'required|regex:/^#[0-9a-fA-F]{6}$/'])); return back()->with('success','Kategori berhasil ditambahkan.'); }
}
