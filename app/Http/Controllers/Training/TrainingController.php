<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;

class TrainingController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:training_access'])->only('index');
        $this->middleware(['permission:training_create'])->only(['create','store']);
        $this->middleware(['permission:training_edit'])->only(['edit','update']);
        $this->middleware(['permission:training_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        return view('training.index');
    }
}
