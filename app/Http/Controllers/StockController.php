<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index() { return view('stocks.index', ['stocks'=>StockIn::with(['product','user'])->latest('received_at')->paginate(15), 'products'=>Product::orderBy('name')->get()]); }
    public function store(Request $request)
    {
        $data=$request->validate(['product_id'=>'required|exists:products,id','quantity'=>'required|integer|min:1','purchase_price'=>'nullable|numeric|min:0','received_at'=>'required|date','notes'=>'nullable|max:500']);
        DB::transaction(function () use ($data,$request) { $data['user_id']=$request->user()->id; $stock=StockIn::create($data); $stock->product()->increment('stock',$data['quantity']); ActivityLog::create(['user_id'=>$request->user()->id,'action'=>'Stok masuk','description'=>$stock->product->name.' +'.$data['quantity']]); });
        return back()->with('success','Stok masuk berhasil dicatat.');
    }
}
