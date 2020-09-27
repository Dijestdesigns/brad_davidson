<?php

namespace App\Http\Controllers\Supplements;

use Illuminate\Http\Request;

class SupplementsController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:supplements_access'])->only('index');
        $this->middleware(['permission:supplements_create'])->only(['create','store']);
        $this->middleware(['permission:supplements_edit'])->only(['edit','update']);
        $this->middleware(['permission:supplements_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        return view('supplements.index');
    }
}
