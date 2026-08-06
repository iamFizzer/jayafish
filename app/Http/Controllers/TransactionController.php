<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions=Transaction::with(['items.product.category','user'])->when($request->from,fn($q,$v)=>$q->whereDate('transaction_date','>=',$v))->when($request->to,fn($q,$v)=>$q->whereDate('transaction_date','<=',$v))->latest('transaction_date')->paginate(15)->withQueryString();
        return view('transactions.index',['transactions'=>$transactions,'products'=>Product::with('category')->where('stock','>',0)->orderBy('name')->get()]);
    }
    public function store(Request $request)
    {
        $data=$request->validate(['customer_name'=>'nullable|max:150','transaction_date'=>'required|date','notes'=>'nullable|max:500','product_id'=>'required|array|min:1','product_id.*'=>'required|exists:products,id','quantity'=>'required|array','quantity.*'=>'required|integer|min:1']);
        DB::transaction(function () use ($data,$request) {
            $transaction=Transaction::create(['invoice_number'=>'TRX-'.now()->format('Ymd-His').'-'.random_int(10,99),'user_id'=>$request->user()->id,'customer_name'=>$data['customer_name']??null,'transaction_date'=>$data['transaction_date'],'total'=>0,'notes'=>$data['notes']??null]); $total=0;
            foreach($data['product_id'] as $i=>$id){ $product=Product::lockForUpdate()->findOrFail($id); $qty=(int)$data['quantity'][$i]; if($product->stock<$qty) throw ValidationException::withMessages(['quantity'=>"Stok {$product->name} hanya {$product->stock}."]); $subtotal=$product->price*$qty; $transaction->items()->create(['product_id'=>$id,'quantity'=>$qty,'price'=>$product->price,'subtotal'=>$subtotal]); $product->decrement('stock',$qty); $total+=$subtotal; }
            $transaction->update(['total'=>$total]); ActivityLog::create(['user_id'=>$request->user()->id,'action'=>'Transaksi baru','description'=>$transaction->invoice_number]);
        });
        return back()->with('success','Transaksi berhasil disimpan dan stok telah diperbarui.');
    }
    public function destroy(Request $request, Transaction $transaction)
    {
        DB::transaction(function() use($transaction,$request){ foreach($transaction->items as $item){$item->product()->increment('stock',$item->quantity);} ActivityLog::create(['user_id'=>$request->user()->id,'action'=>'Hapus transaksi','description'=>$transaction->invoice_number.' (stok dikembalikan)']); $transaction->delete(); });
        return back()->with('success','Transaksi dihapus dan stok dikembalikan.');
    }
}
