<?php

namespace App\Http\Controllers\Calendar;

use Illuminate\Http\Request;

class CalendarController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:calendar_access'])->only('index');
        $this->middleware(['permission:calendar_create'])->only(['create','store']);
        $this->middleware(['permission:calendar_edit'])->only(['edit','update']);
        $this->middleware(['permission:calendar_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        return view('calendar.index');
    }
}
