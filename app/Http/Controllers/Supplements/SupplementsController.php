<?php

namespace App\Http\Controllers\Supplements;

use Illuminate\Http\Request;
use App\Log;
use App\User;
use App\UserSupplement;
use Carbon\Carbon;
use DB;

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
        $userId = auth()->user()->id;

        $model = new UserSupplement();

        $users = [];

        $isFiltered     = false;
        $requestClonned = clone $request;

        $cleanup = $requestClonned->except(['page']);
        $requestClonned->query = new \Symfony\Component\HttpFoundation\ParameterBag($cleanup);

        if (count($requestClonned->all()) > 0) {
            $isFiltered = (!empty(array_filter($requestClonned->all())));
        }

        $modelQuery = $model::query();

        if (auth()->user()->isSuperAdmin()) {
            $users = User::all();
        } else {
            $modelQuery->where('user_id', $userId);
        }

        if ($isFiltered) {
            if ($request->get('u', false)) {
                $u = $request->get('u');

                $modelQuery->where(function($query) use($u, $model) {
                    $query->where('user_id', $u);
                });
            }

            if ($request->get('d', false) && strtotime($request->get('d')) > 0) {
                $d = date('Y-m-d', strtotime($request->get('d')));

                $modelQuery->where(function($query) use($d) {
                    $query->whereDate('date', $d);
                });
            }
        }

        $modelQuery->leftJoin(User::getTableName(), $model::getTableName() . '.user_id', '=', User::getTableName() . '.id');
        $modelQuery->select($model::getTableName() . ".id", $model::getTableName() . '.user_id', DB::RAW('DATE(' . $model::getTableName() . '.date) AS supplement_date'));

        $total   = $modelQuery->count();
        $records = $modelQuery->groupBy(DB::RAW($model::getTableName() . '.user_id, DATE(' . $model::getTableName() . '.date)'))->orderBy('date', 'DESC')->paginate($model::PAGINATE_RECORDS);

        return view('supplements.index', compact('records', 'total', 'isFiltered', 'users', 'request'));
    }

    public function create()
    {
        $users = User::all();

        return view('supplements.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data   = $request->all();
        $model  = new UserSupplement();
        $now    = Carbon::now();
        $date   = (!empty($data['date']) && strtotime($data['date']) > 0) ? $data['date'] : NULL;
        $userId = (!empty($data['user_id'])) ? (int)$data['user_id'] : 0;

        $create = [];

        $find = $model::where('user_id', $userId)->whereDate('date', $date)->exists();
        if ($find) {
            return redirect('supplements/create')->with('error', __("This supplements already exists for this user! Please click <a href='" . route('supplements.edit', [$userId, strtotime($date)]) . "' target='__blank'>Here</a> to edit it!"));
        }

        if ($model::TOTAL_ROWS > 0) {
            for ($rowId = 1; $rowId <= $model::TOTAL_ROWS; $rowId++) {
                $index = $rowId - 1;

                $create[$index]['row_id']     = $rowId;
                $create[$index]['date']       = $date;
                $create[$index]['user_id']    = $userId;
                // $create[$index]['created_at'] = $now;

                if (!empty($data['supplement'][$index])) {
                    $create[$index]['supplement'] = $data['supplement'][$index];
                } else {
                    $create[$index]['supplement'] = NULL;
                }

                if (!empty($data['upon_waking'][$index])) {
                    $create[$index]['upon_waking'] = $data['upon_waking'][$index];
                } else {
                    $create[$index]['upon_waking'] = NULL;
                }

                if (!empty($data['at_breakfast'][$index])) {
                    $create[$index]['at_breakfast'] = $data['at_breakfast'][$index];
                } else {
                    $create[$index]['at_breakfast'] = NULL;
                }

                if (!empty($data['at_lunch'][$index])) {
                    $create[$index]['at_lunch'] = $data['at_lunch'][$index];
                } else {
                    $create[$index]['at_lunch'] = NULL;
                }

                if (!empty($data['at_dinner'][$index])) {
                    $create[$index]['at_dinner'] = $data['at_dinner'][$index];
                } else {
                    $create[$index]['at_dinner'] = NULL;
                }

                if (!empty($data['before_bed'][$index])) {
                    $create[$index]['before_bed'] = $data['before_bed'][$index];
                } else {
                    $create[$index]['before_bed'] = NULL;
                }
            }
        }

        $validator = $model::validators($create);

        $validator->validate();

        /*$find = $model::where('user_id', $userId)->where('date', $date)->get();

        if (!empty($find) && !$find->isEmpty()) {
            $isRemoved = self::remove($find);

            if ($isRemoved) {
                self::createLog($find[0], __("Deleted supplements for " . $find[0]->date . " date and for " . $find[0]->user . " user."), Log::DELETE, $find[0]->toArray(), []);
            }
        }*/

        foreach ($create as $createData) {
            $matchingData = [
                'row_id'  => $createData['row_id'],
                'date'    => $createData['date'],
                'user_id' => $createData['user_id']
            ];

            $model->updateOrCreate($matchingData, $createData);
        }

        $find = $model::where('user_id', $createData['user_id'])->whereDate('date', $createData['date'])->get();
        if (!empty($find) && !$find->isEmpty()) {
            self::createLog($find[0], __("Created supplements for {$find[0]->user->name} and supplement date is : {$find[0]->date}"), Log::CREATE, [], $find->toArray());
        }

        return redirect('supplements')->with('success', __("Supplements created!"));
    }

    public function edit(int $userId, $date)
    {
        $model  = new UserSupplement();
        $record = $model::where('user_id', $userId)->whereDate('date', date('Y-m-d', $date))->get();

        if ($record) {
            $users = User::all();

            $disabledDates = $model::select(DB::RAW('DATE(`date`) as supplement_date'))->where('user_id', $userId)->whereDate('date', '!=', date('Y-m-d', $date))->get();
            if (!empty($disabledDates) && !$disabledDates->isEmpty()) {
                $disabledDates = $disabledDates->pluck('supplement_date')->toArray();

                foreach ($disabledDates as &$disabledDate) {
                    $disabledDate = "'" . $disabledDate . "'";
                }

                $disabledDates = implode(",", array_unique($disabledDates));
            } else {
                $disabledDates = "";
            }

            return view('supplements.edit', compact('model', 'record', 'users', 'userId', 'date', 'disabledDates'));
        }

        return redirect('supplements')->with('error', __("Not found!"));
    }

    public function update(int $userId, $date, Request $request)
    {
        $data          = $request->all();
        $model         = new UserSupplement();
        $now           = Carbon::now();
        $origionalDate = !empty($date) && $date > 0 ? date('Y-m-d', $date) : false;
        $date          = (((!empty($data['date']) && strtotime($data['date']) > 0)) ? $data['date'] : ((!empty($date) && $date > 0) ? date('Y-m-d', $date) : NULL));
        $userId        = $userId;

        if (!$origionalDate) {
            return redirect('supplements')->with('error', __("There has been an error!"));
        }

        $create = [];

        $oldData = $model::where('user_id', $userId)->whereDate('date', $origionalDate)->get();

        if (!empty($oldData) && !$oldData->isEmpty()) {
            foreach ($oldData as $index => $old) {
                $rowId = $index + 1;

                $create[$index]['id']         = $old->id;
                $create[$index]['row_id']     = $rowId;
                $create[$index]['date']       = $date;
                $create[$index]['user_id']    = $userId;
                // $create[$index]['created_at'] = $now;

                if (!empty($data['supplement'][$index])) {
                    $create[$index]['supplement'] = $data['supplement'][$index];
                } else {
                    $create[$index]['supplement'] = NULL;
                }

                if (!empty($data['upon_waking'][$index])) {
                    $create[$index]['upon_waking'] = $data['upon_waking'][$index];
                } else {
                    $create[$index]['upon_waking'] = NULL;
                }

                if (!empty($data['at_breakfast'][$index])) {
                    $create[$index]['at_breakfast'] = $data['at_breakfast'][$index];
                } else {
                    $create[$index]['at_breakfast'] = NULL;
                }

                if (!empty($data['at_lunch'][$index])) {
                    $create[$index]['at_lunch'] = $data['at_lunch'][$index];
                } else {
                    $create[$index]['at_lunch'] = NULL;
                }

                if (!empty($data['at_dinner'][$index])) {
                    $create[$index]['at_dinner'] = $data['at_dinner'][$index];
                } else {
                    $create[$index]['at_dinner'] = NULL;
                }

                if (!empty($data['before_bed'][$index])) {
                    $create[$index]['before_bed'] = $data['before_bed'][$index];
                } else {
                    $create[$index]['before_bed'] = NULL;
                }
            }
        }

        $validator = $model::validators($create);

        $validator->validate();

        foreach ($create as $createData) {
            $matchingData = [
                'id'      => $createData['id'],
                'row_id'  => $createData['row_id'],
                'user_id' => $createData['user_id']
            ];

            $model->updateOrCreate($matchingData, $createData);
        }

        $find = $model::where('user_id', $userId)->whereDate('date', $date)->get();
        if (!empty($find) && !$find->isEmpty()) {
            self::createLog($find[0], __("Updated supplements for {$find[0]->user->name} and supplement date is : {$find[0]->date}"), Log::UPDATE, $oldData, $find->toArray());
        }

        return redirect('supplements')->with('success', __("Supplements updated!"));
    }

    public function destroy(int $userId, $date)
    {
        $date   = (!empty($date) && $date > 0) ? date('Y-m-d', $date) : NULL;

        $record = UserSupplement::where('user_id', $userId)->whereDate('date', $date)->get();

        if (!empty($record[0])) {
            DB::beginTransaction();

            $isRemoved = self::remove($record);

            if ($isRemoved) {
                self::createLog($record[0], __("Deleted supplements for {$record[0]->user->name} and supplement date is : {$record[0]->date}"), Log::DELETE, $record->toArray(), []);

                DB::commit();

                return redirect('supplements')->with('success', __("Supplements deleted!"));
            } else {
                DB::rollBack();

                return redirect('supplements')->with('error', __("There has been an error!"));
            }
        }

        return redirect('supplements')->with('error', __("Not found!"));
    }
}
