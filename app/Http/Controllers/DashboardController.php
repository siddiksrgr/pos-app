<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // grafik sales and profit
        $days = Carbon::now()->daysInMonth;
        $laberBarY = [];
        foreach(range(1, $days) as $day){
            $laberBarY[] = $day;
        }
        $laberBarX = ['Sales', 'Profit'];
        $data_bar = [];
        foreach($laberBarX as $key => $value){
            $data_bar[$key]['label'] = $laberBarX[$key];
            $data_bar[$key]['backgroundColor'] = $key == 0 ? 'rgba(60, 141, 188, 0.9)' : 'rgba(210, 214, 222, 1)';
            $data_day = [];
            foreach(range(1, $days) as $day){
                if($key == 0){
                    $data_day[] = Transaction::select(DB::raw("SUM(total) as total"))->whereDay('created_at', $day)->first()->total;
                } else {
                    $data_day[] = Transaction::select(DB::raw("SUM(profit) as profit"))->whereDay('created_at', $day)->first()->profit;
                }
            }
            $data_bar[$key]['data'] = $data_day;
        }

        // sales today
        $salesToday = Transaction::whereDay('created_at', Carbon::now()->day)->get();

        // the most sell product
        $product = DB::table('products')
            ->leftJoin('transaction_details','products.id','=','transaction_details.product_id')
            ->selectRaw('products.*, COALESCE(sum(transaction_details.qty),0) total')
            ->groupBy('products.id')
            ->orderBy('total','desc')
            ->take(5)
            ->get();
        $label_donut = $product->pluck('name');
        $data_donut = $product->pluck('total');

        return view('pages.dashboard', compact('data_bar', 'laberBarY', 'salesToday', 'label_donut', 'data_donut'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
