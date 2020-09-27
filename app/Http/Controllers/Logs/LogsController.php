<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Http\Request;
use App\Log;
use App\ClientItem;
use Illuminate\Pagination\LengthAwarePaginator;

class LogsController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:logs_access'])->only('index');
    }

    public function index(Request $request)
    {
        $model          = new Log();
        $isFiltered     = false;
        // $total          = $model::count();
        $modelQuery     = $model::query();
        $requestClonned = clone $request;

        $cleanup = $requestClonned->except(['page']);
        $requestClonned->query = new \Symfony\Component\HttpFoundation\ParameterBag($cleanup);

        if (count($requestClonned->all()) > 0) {
            $isFiltered = (!empty(array_filter($requestClonned->all())));
        }

        if ($isFiltered) {
            if ($request->get('s', false)) {
                $s = $request->get('s');

                $modelQuery->where(function($query) use($s) {
                    $query->where('message', 'LIKE', "%$s%");
                });
            }

            if ($request->get('d', false)) {
                $d = date('Y-m-d', strtotime($request->get('d')));

                $modelQuery->where(function($query) use($d) {
                    $query->whereDate('created_at', '=', $d);
                });
            }

            if ($request->get('t', false)) {
                $t = ucfirst($request->get('t'));

                $modelQuery->where(function($query) use($t) {
                    $query->where('model', '=', "App\\$t");
                });
            }

            if ($request->get('hash', false)) {
                $hash = ucfirst($request->get('hash'));

                $modelQuery->where(function($query) use($hash) {
                    $query->where('id', '=', "$hash");
                });
            }

            if ($request->get('model', false)) {
                $m = $request->get('model');

                $modelQuery->join(ClientItem::getTableName(), ClientItem::getTableName() . '.client_id', '=', $model::getTableName() . '.model_id');
                $modelQuery->where(ClientItem::getTableName() . '.item_id', $m);
                $modelQuery->groupBy($model::getTableName() . '.id');

                $unionModel = $model::query();
                $unionModel->select($model::getTableName() . ".*")->where('model_id', '=', "{$m}")->groupBy($model::getTableName() . '.id');
                $modelQuery->union($unionModel);
            }
        }

        $modelQuery = $modelQuery->select($model::getTableName() . ".*");

        if (!$request->has('model')) {
            $modelQuery->orderBy($model::getTableName() . '.id', 'DESC');
        }

        $paginate    = Log::PAGINATE_RECORDS;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $total = $modelQuery->count();
        $logs  = $modelQuery->forPage($currentPage, $paginate)->get();

        if ($request->has('model') && !empty($logs) && !$logs->isEmpty()) {
            $logs = $logs->sortBy(function($log) {
                          return $log->id;
                      }, SORT_REGULAR, true);
        }

        $page        = $request->get('page', 1);
        $records     = new LengthAwarePaginator($logs, $total, $paginate, $currentPage, [
            'path'     => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        return view('logs.index', compact('total', 'records', 'request', 'isFiltered'));
    }
}
