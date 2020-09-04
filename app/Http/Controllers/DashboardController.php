<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Client;
use App\Log;
use DB;

class DashboardController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $itemCount        = Item::count();
        $clientCount      = Client::count();
        $totalStockValues = Item::select(DB::raw('SUM(qty) as qty, SUM(`value`) as value'))->first();

        $totalStocks = $totalValues = 0;
        if (!empty($totalStockValues)) {
            $totalStocks = $totalStockValues->qty;
            $totalValues = number_format($totalStockValues->value, 2);
        }

        $logs = Log::orderBy('id', 'DESC')->limit(10)->get();

        return view('dashboard', compact('itemCount', 'clientCount', 'totalStocks', 'totalValues', 'logs'));
    }
}
