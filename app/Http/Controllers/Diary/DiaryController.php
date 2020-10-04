<?php

namespace App\Http\Controllers\Diary;

use Illuminate\Http\Request;
use App\Log;
use App\Diary;

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
        $userId = auth()->user()->id;

        $model  = new Diary();

        $modelQuery = $model::query();

        if ($request->get('s', false)) {
            $s = $request->get('s');

            $modelQuery->where(function($query) use($s, $model) {
                $query->where($model::getTableName() . '.name', 'LIKE', "%$s%")
                      ->orWhere($model::getTableName() . '.content','LIKE', "%$s%");
            });
        }

        $records = $modelQuery->where('user_id', $userId)->orderBy('created_at', 'DESC')->get();

        $selectedId = $firstId = (!empty($records) && !$records->isEmpty()) ? $records->first()['id'] : '';

        if (!empty($request->get('i'))) {
            $i = $request->get('i', $firstId);

            if (!empty($records) && !$records->isEmpty()) {
                if (in_array($i, $records->pluck('id')->toArray())) {
                    $selectedId = $i;
                }
            }
        }

        return view('diary.index', compact('records', 'request', 'firstId', 'selectedId'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $id = NULL;

        $data['user_id'] = auth()->user()->id;
        $data['content'] = !empty($data['contents'][$id]) ? $data['contents'][$id] : NULL;

        $model = new Diary();

        if (!empty($data['deletedId'])) {
            $id = (int)$data['deletedId'];

            $find = $model::where('id', $id)->get();

            if (!empty($find) && !$find->isEmpty()) {
                $record = clone $find;

                $isRemoved = self::remove($find);

                if ($isRemoved) {
                    self::createLog($record[0], __("Deleted diary " . $record[0]->name), Log::DELETE, $record[0]->toArray(), []);

                    return redirect('diary')->with('success', __("Diary deleted!"));
                }
            }
        } elseif (!empty($data['newName'])) {
            $data['name'] = $data['newName'];

            $validator = $model::validators($data);

            $validator->validate();

            $model->name    = $data['name'];
            $model->user_id = $data['user_id'];
            $model->save();

            if ($model->id) {
                $id = $model->id;

                return redirect('diary?i=' . $id)->with('success', __("New diary created!"));
            }
        } elseif (!empty($data['currentId'])) {
            $id     = (int)$data['currentId'];
            $record = $model::find($id);

            $data['name']    = !empty($data['names'][$id]) ? $data['names'][$id] : NULL;
            $data['content'] = !empty($data['contents'][$id]) ? $data['contents'][$id] : NULL;

            $validator = $model::validators($data);

            $validator->validate();

            $update = $record->update($data);

            if ($update) {
                return redirect('diary?i=' . $id)->with('success', __("Diary updated!"));
            }
        }

        return redirect('diary')->with('error', __("There has been an error!"));
    }
}
