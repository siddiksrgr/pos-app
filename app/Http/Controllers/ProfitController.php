<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class ProfitController extends Controller
{
    public function index()
    {
        $totalProfit = Transaction::sum('profit');
        return view('pages.profit', compact('totalProfit'));
    }

    public function api()
    {
        $profits = Transaction::with('transaction_details.product')->get();

        $datatables = datatables()->of($profits)
                            ->addColumn('invoice', function($profit){
                                return $profit->invoice;
                            })
                            ->addColumn('date', function($profit){
                                return convert_date_time($profit->created_at);
                            })
                            ->addColumn('profit', function($profit){
                                return "Rp. " . convert_rupiah($profit->transaction_details->sum('profit'));
                            })
                            ->addIndexColumn();
        return $datatables->make(true);
    }
}
