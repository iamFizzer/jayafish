<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $fillable = ['product_id', 'user_id', 'quantity', 'purchase_price', 'received_at', 'notes'];
    protected $casts = ['received_at' => 'date', 'purchase_price' => 'decimal:2'];

    public function product() { return $this->belongsTo(Product::class); }
    public function user() { return $this->belongsTo(User::class); }
}
