<?php

namespace App\Http\Controllers\Calendar;

use Illuminate\Http\Request;
use App\Calendar;

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
        $calendars = Calendar::select('id as calendarId', 'name as title', 'start_date as start', 'end_date as end', 'color as backgroundColor', 'color as borderColor', 'repeats')->get();

        $selectedDate = false;
        if ($request->get('i', false)) {
            $selectedDate = date('Y-m-d', $request->get('i'));
        }

        if (empty($selectedDate) || strtotime($selectedDate) <= 0) {
            $selectedDate = date('Y-m-d');
        }

        return view('calendar.index', compact('calendars', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $data  = $request->all();
        $model = new Calendar();

        $data['user_id']    = auth()->user()->id;
        $data['start_date'] = date('Y-m-d h:i:s', strtotime($data['start_date']));
        $data['end_date']   = date('Y-m-d h:i:s', strtotime($data['end_date']));

        $validator = $model::validators($data);

        $validator->validate();

        $create = $model::create($data);

        if ($create) {
            return redirect('calendar?i=' . strtotime($data['start_date']))->header('Cache-Control', 'no-store, no-cache, must-revalidate')->with('success', __("New calendar created!"));
        }

        return redirect('calendar')->with('error', __("There has been an error!"));
    }

    public function update(Request $request)
    {
        $data  = $request->all();
        $model = new Calendar();

        if (!empty($data['calendarId'])) {
            $id     = (int)$data['calendarId'];
            $record = $model::find($id);

            if ($record) {
                $data['user_id']    = auth()->user()->id;
                $data['start_date'] = date('Y-m-d h:i:s', strtotime($data['start_date']));
                $data['end_date']   = date('Y-m-d h:i:s', strtotime($data['end_date']));

                $validator = $model::validators($data);

                $validator->validate();

                $update = $record->update($data);

                if ($update) {
                    return redirect('calendar?i=' . strtotime($data['start_date']))->header('Cache-Control', 'no-store, no-cache, must-revalidate')->with('success', __("Calendar updated!"));
                }
            }
        }

        return redirect('calendar')->with('error', __("There has been an error!"));
    }
}
