<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product_code', 'name', 'buy_price', 'sell_price', 'stock', 'category_id'];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function transaction_detail()
    {
        return $this->belongsTo('App\Models\TransactionDetail', 'product_id');
    }
}
