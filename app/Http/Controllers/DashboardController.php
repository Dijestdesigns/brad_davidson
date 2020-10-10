<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\User;
use App\Log;
use App\UserSupplement;
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
        $user             = auth()->user();
        $userId           = $user->id;
        $itemCount        = Item::count();
        $userCount        = User::where('id', '!=', User::$superadminId)->count();
        $totalStockValues = Item::select(DB::raw('SUM(qty) as qty, SUM(`value`) as value'))->first();

        $totalStocks = $totalValues = 0;
        if (!empty($totalStockValues)) {
            $totalStocks = $totalStockValues->qty;
            $totalValues = number_format($totalStockValues->value, 2);
        }

        $logs = Log::orderBy('id', 'DESC')->limit(10)->get();

        if ($user->isSuperAdmin()) {
            $supplements = [];
        } else {
            $supplements = UserSupplement::where('user_id', $userId)->orderBy('date', 'DESC')->first();
        }

        return view('dashboard', compact('itemCount', 'userCount', 'totalStocks', 'totalValues', 'logs', 'supplements'));
    }
}
