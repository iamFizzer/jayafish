<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = Carbon::parse($request->input('from', now()->startOfMonth()->toDateString()));
        $to = Carbon::parse($request->input('to', now()->toDateString()));
        $transactions = Transaction::whereBetween('transaction_date', [$from, $to]);
        $summary = [
            'revenue' => (clone $transactions)->sum('total'),
            'transactions' => (clone $transactions)->count(),
            'items' => TransactionItem::whereHas('transaction', fn ($q) => $q->whereBetween('transaction_date', [$from, $to]))->sum('quantity'),
        ];
        $trend = (clone $transactions)->select('transaction_date', DB::raw('SUM(total) total'))->groupBy('transaction_date')->orderBy('transaction_date')->get();
        $best = TransactionItem::query()->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')->join('products', 'products.id', '=', 'transaction_items.product_id')->join('categories', 'categories.id', '=', 'products.category_id')->whereBetween('transactions.transaction_date', [$from, $to])->select('products.name', 'categories.type', DB::raw('SUM(transaction_items.quantity) quantity'))->groupBy('products.id', 'products.name', 'categories.type')->orderByDesc('quantity')->get()->groupBy('type');
        $categories = TransactionItem::query()->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')->join('products', 'products.id', '=', 'transaction_items.product_id')->join('categories', 'categories.id', '=', 'products.category_id')->whereBetween('transactions.transaction_date', [$from, $to])->select('categories.name', 'categories.color', DB::raw('SUM(transaction_items.subtotal) total'))->groupBy('categories.id', 'categories.name', 'categories.color')->get();
        $lowStocks = Product::with('category')->whereColumn('stock', '<=', 'minimum_stock')->orderBy('stock')->get();
        $recent = Transaction::with('user')->latest('transaction_date')->limit(6)->get();
        return view('dashboard', compact('summary', 'trend', 'best', 'categories', 'lowStocks', 'recent', 'from', 'to'));
    }
}
