<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Store;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalSales = Transaction::sum('total');
        return view('pages.transaction', compact('totalSales'));
    }

    public function api()
    {
        $transactions = Transaction::with('transaction_details.product');

        $datatables = datatables()->of($transactions)
                            ->addColumn('customer_code', function($transaction){
                                return $transaction->customer_id == null ? 'Umum' : $transaction->customer->customer_code;
                            })
                            ->addColumn('customer_name', function($transaction){
                                return $transaction->customer_id == null ? 'Umum' : $transaction->customer->name;
                            })
                            ->editColumn('total', function($transaction){
                                return "Rp. " . convert_rupiah($transaction->total);
                            })
                            ->editColumn('cash', function($transaction){
                                return "Rp. " . convert_rupiah($transaction->cash);
                            })
                            ->editColumn('change', function($transaction){
                                return "Rp. " . convert_rupiah($transaction->change);
                            })
                            ->addColumn('date', function($transaction){
                                return convert_date_time($transaction->created_at);
                            })
                            ->addIndexColumn();
        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|numeric|exists:customers,id',
            'invoice' => 'required|numeric|unique:transactions',
            'total' => 'required|numeric|gt:0',
            'cash' => 'required|numeric|gte:total',
            'change' => 'required|numeric|min:0',
        ]); 
        $transaction = Transaction::create($validated);
        $carts = Cart::all();
        foreach($carts as $cart) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $cart->product_id,
                'buy_price' => $cart->product->buy_price,
                'sell_price' => $cart->product->sell_price,
                'qty' => $cart->qty,
                'profit' => ($cart->product->sell_price - $cart->product->buy_price) * $cart->qty
            ]);
            $product = Product::find($cart->product_id);
            $product->update([
                'stock' => $product->stock - $cart->qty
            ]);
            $cart->delete();
        }
        $transaction->update([
            'profit' => $transaction->transaction_details->sum('profit')
        ]);        
        return $transaction->id;
    }

    public function print($id)
    {
        $transaction = Transaction::find($id);
        $store = Store::first();
        return view('pages.print', compact('transaction', 'store'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
