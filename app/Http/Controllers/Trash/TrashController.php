<?php

namespace App\Http\Controllers\Trash;

use Illuminate\Http\Request;
use App\DeletedRecord;

class TrashController extends \App\Http\Controllers\BaseController
{
    public function index(Request $request)
    {
        $model      = new DeletedRecord();
        $modelQuery = $model::query();

        $total   = $modelQuery->count();
        $records = $modelQuery->orderBy('id', 'DESC')->paginate(DeletedRecord::PAGINATE_RECORDS);

        return view('trash.index', compact('total', 'records'));
    }
}
