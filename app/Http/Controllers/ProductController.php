<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('pages.product', compact('categories'));
    }

    public function api()
    {
        $products = Product::with('category')->get();

        $datatables = datatables()->of($products)
                                ->addColumn('category', function($product){
                                    return $product->category->name;
                                })
                                ->addColumn('formatted_buy_price', function($product){
                                    return "Rp. " . convert_rupiah($product->buy_price);
                                })
                                ->addColumn('formatted_sell_price', function($product){
                                    return "Rp. " . convert_rupiah($product->sell_price);
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
            'product_code' => 'required|string|max:255|unique:products',
            'name' => 'required|string|max:255|unique:products',
            'buy_price' => 'required|numeric|min:1',
            'sell_price' => 'required|numeric|gt:buy_price', //gt is greater than
            'stock' => 'required|numeric|max:255|min:1',
            'category_id' => 'required|numeric',
        ],
        [
            'sell_price.gt' => 'The sell price must be greater than buy price.'
        ]);
        Product::create($validated);
        return redirect('products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_code' => 'required|string|max:255|unique:products,product_code,'.$product->id,
            'name' => 'required|string|max:255|unique:products,name,'.$product->id,
            'buy_price' => 'required|numeric|min:1',
            'sell_price' => 'required|numeric|gt:buy_price', //gt is greater than 
            'stock' => 'required|numeric|max:255|min:1',
            'category_id' => 'required|numeric',
        ],
        [
            'sell_price.gt' => 'The sell price must be greater than buy price.'
        ]);
        $product->update($validated);
        return redirect('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}
