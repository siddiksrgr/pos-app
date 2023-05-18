<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'product_id', 'buy_price', 'sell_price', 'qty', 'profit'];

    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
