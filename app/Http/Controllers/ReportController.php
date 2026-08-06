<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query=Transaction::with(['items.product.category','user'])->when($request->from,fn($q,$v)=>$q->whereDate('transaction_date','>=',$v))->when($request->to,fn($q,$v)=>$q->whereDate('transaction_date','<=',$v));
        return view('reports.index',['transactions'=>(clone $query)->latest('transaction_date')->paginate(20)->withQueryString(),'total'=>(clone $query)->sum('total')]);
    }
    public function export(Request $request)
    {
        $rows=Transaction::with('user')->when($request->from,fn($q,$v)=>$q->whereDate('transaction_date','>=',$v))->when($request->to,fn($q,$v)=>$q->whereDate('transaction_date','<=',$v))->get();
        return response()->streamDownload(function()use($rows){$f=fopen('php://output','w'); fwrite($f,"\xEF\xBB\xBF"); fputcsv($f,['No Invoice','Tanggal','Pelanggan','Kasir','Total']); foreach($rows as $r)fputcsv($f,[$r->invoice_number,$r->transaction_date->format('d/m/Y'),$r->customer_name,$r->user->name,$r->total],';'); fclose($f);},'laporan-penjualan-'.now()->format('Ymd').'.csv',['Content-Type'=>'text/csv']);
    }
}
