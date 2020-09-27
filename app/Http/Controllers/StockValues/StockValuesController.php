<?php

namespace App\Http\Controllers\StockValues;

use Illuminate\Http\Request;
use App\Item;

class StockValuesController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:stock_values_access'])->only('index');
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

            if ($request->get('v', false)) {
                $v = $request->get('v');

                $modelQuery->where('value', (int)$v);
            }
        }

        $total   = $modelQuery->count();
        $records = $modelQuery->orderBy('value', 'DESC')->paginate(Item::PAGINATE_RECORDS);

        return view('stock_values.index', compact('total', 'records', 'request', 'isFiltered'));
    }
}
