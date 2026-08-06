<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'sku', 'price', 'unit', 'stock', 'minimum_stock', 'image'];
    protected $casts = ['price' => 'decimal:2'];

    public function category() { return $this->belongsTo(Category::class); }
    public function stockIns() { return $this->hasMany(StockIn::class); }
    public function transactionItems() { return $this->hasMany(TransactionItem::class); }
}
