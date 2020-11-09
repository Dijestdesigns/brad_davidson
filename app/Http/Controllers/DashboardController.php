<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\User;
use App\Log;
use App\UserSupplement;
use App\ClientCoaching;
use Carbon\Carbon;
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
        $now              = Carbon::now();
        $currentUserRole  = !empty($user->getRoleNames()[0]) ? $user->getRoleNames()[0] : false;

        $totalStocks = $totalValues = 0;
        if (!empty($totalStockValues)) {
            $totalStocks = !empty($totalStockValues->qty) ? $totalStockValues->qty : 0;
            $totalValues = number_format($totalStockValues->value, 2);
        }

        $logs = Log::orderBy('id', 'DESC')->limit(10)->get();

        if ($user->isSuperAdmin()) {
            $supplements = [];
        } else {
            $supplements = UserSupplement::where('user_id', $userId)->orderBy('date', 'DESC')->first();
        }

        $coachings = [];
        if (auth()->user()->can('coaching_show_to_clients')) {
            $coachings = ClientCoaching::whereDate('date', $now)->where('user_id', $userId)->get();
        }

        return view('dashboard', compact('itemCount', 'userCount', 'totalStocks', 'totalValues', 'logs', 'supplements', 'coachings', 'currentUserRole'));
    }
}
