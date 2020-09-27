<?php

namespace App\Http\Controllers\StockLevels;

use Illuminate\Http\Request;
use App\Item;

class StockLevelsController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:stock_levels_access'])->only('index');
    }

    public function index(Request $request)
    {
        $model          = new Item();
        $modelQuery     = $model::query();
        $isFiltered     = false;
        $requestClonned = clone $request;

        $cleanup = $requestClonned->except(['page']);
        $requestClonned->query = new \Symfony\Component\HttpFoundation\ParameterBag($cleanup);

        if (count($requestClonned->all()) > 0) {
            $isFiltered = (!empty(array_filter($requestClonned->all())));
        }

        if ($isFiltered) {
            if ($request->get('s', false)) {
                $s = $request->get('s');

                $modelQuery->where(function($query) use($s, $model) {
                    $query->where($model::getTableName() . '.name', 'LIKE', "%$s%")
                          ->orWhere($model::getTableName() . '.name','LIKE', "%$s%");
                });
            }

            if ($request->get('q', false)) {
                $q = $request->get('q');

                $modelQuery->where('qty', (int)$q);
            }
        }

        $total   = $modelQuery->count();
        $records = $modelQuery->orderBy('qty', 'DESC')->paginate(Item::PAGINATE_RECORDS);

        return view('stock_levels.index', compact('total', 'records', 'request', 'isFiltered'));
    }
}
