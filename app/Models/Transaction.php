<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'invoice', 'total', 'cash', 'change', 'profit'];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function transaction_details()
    {
        return $this->hasMany('App\Models\TransactionDetail', 'transaction_id');
    }
}
