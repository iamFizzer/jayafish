<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['invoice_number', 'user_id', 'customer_name', 'transaction_date', 'total', 'notes'];
    protected $casts = ['transaction_date' => 'date', 'total' => 'decimal:2'];

    public function items() { return $this->hasMany(TransactionItem::class); }
    public function user() { return $this->belongsTo(User::class); }
}
