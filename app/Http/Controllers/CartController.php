<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::now()->format('d F Y');
        $customers = Customer::all();
        return view('pages.cart', compact('customers', 'today'));
    }

    public function api()
    {
        $carts = Cart::all();

        $datatables = datatables()->of($carts)
                            ->addColumn('code', function($cart){
                                return $cart->product->product_code;
                            })
                            ->addColumn('name', function($cart){
                                return $cart->product->name;
                            })
                            ->addColumn('price', function($cart){
                                return convert_rupiah($cart->product->sell_price);
                            })
                            ->addColumn('total', function($cart){
                                return convert_rupiah($cart->qty * $cart->product->sell_price);
                            })->addIndexColumn();
        return $datatables->make(true);
    }

    public function invoice()
    {
        $today = Carbon::now()->format('Ymd');
        $transaction = Transaction::latest()->first();
        if($transaction) {
            $invoice = str_split($transaction->invoice, 8);
            if($invoice[0] == $today){
                $invoice[1]+=1;
                return implode($invoice);
            } else {
                return $today.+1;
            }
        } else {
            return $today.+1;
        }
    }

    public function total()
    {
        $carts = Cart::all();
        if($carts) {
            $total = $carts->reduce(function($carry, $item){
                return $carry + ($item->product->sell_price * $item->qty);
            });
        } else {
            $total = 0;
        }
        return $total;
    }

    public function getCustomers()
    {
        $customers = Customer::all();
        return $customers;
    }

    public function getProduct($code)
    {
        $product = Product::where('product_code', $code)->first();
        return $product;
    }

    public function cancel()
    {
        Cart::truncate();
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
        $request->validate([
            'product_code' => 'required|string|exists:products,product_code',
        ]);

        $product = Product::where('product_code', $request->product_code)->first();
        $cart = Cart::where('product_id', $product->id)->first();

        if($cart) {
            $max = $product->stock - $cart->qty;
            $request->validate([
                'qty' => 'required|numeric|min:1|max:'.$max,
            ],
            [
                'qty.max' => 'Qty product with code '. $product->product_code .' available: ' . $max
            ]);
            $cart->update([ 'qty' => $cart->qty + $request->qty ]); 
        } else {
            $request->validate([
                'qty' => 'required|numeric|min:1|max:'.$product->stock,
            ],
            [
                'qty.max' => 'Qty product with code '. $product->product_code .' available: ' . $product->stock
            ]);
            Cart::create([
                'product_id' => $product->id,
                'qty' => $request->qty
            ]);
        }
        return redirect('carts');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $validated =  $request->validate([
            'qty' => 'required|numeric|min:1|max:'.$cart->product->stock,
        ],
        [
            'qty.max' => 'Qty product with code '. $cart->product->product_code .' available: ' . $cart->product->stock
        ]);
        $cart->update($validated);
        return redirect('carts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
    }
}
