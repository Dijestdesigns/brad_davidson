<?php

namespace App\Http\Controllers\Diary;

use Illuminate\Http\Request;

class DiaryController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:diary_access'])->only('index');
        $this->middleware(['permission:diary_create'])->only(['create','store']);
        $this->middleware(['permission:diary_edit'])->only(['edit','update']);
        $this->middleware(['permission:diary_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        return view('diary.index');
    }
}
