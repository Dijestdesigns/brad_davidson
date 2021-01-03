<?php

namespace App\Http\Controllers\Coaching;

use Illuminate\Http\Request;
use App\Coaching;
use App\ClientCoaching;
use App\ClientCoachingInfo;
use App\User;
use App\ModelHasRoles;
use App\Log;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use DB;

class CoachingController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:coaching_access'])->only('index');
        $this->middleware(['permission:coaching_create'])->only(['create','store']);
        $this->middleware(['permission:coaching_edit'])->only(['edit','update']);
        $this->middleware(['permission:coaching_delete'])->only('destroy');

        $this->middleware(['permission:coaching_info_create'])->only(['clientInfoCreate']);
        $this->middleware(['permission:coaching_info_edit'])->only(['clientInfoUpdate']);

        $this->middleware(['permission:coaching_show_to_clients'])->only(['clientIndex']);
    }

    public function index(Request $request)
    {
        $model          = new Coaching();
        $isFiltered     = false;
        $modelQuery     = $model::query();
        $requestClonned = clone $request;

        $cleanup = $requestClonned->except(['page', 'clientsPage']);
        $requestClonned->query = new \Symfony\Component\HttpFoundation\ParameterBag($cleanup);

        if (count($requestClonned->all()) > 0) {
            $isFiltered = (!empty(array_filter($requestClonned->all())));
        }

        if ($isFiltered || $request->get('t') == '0') {
            if ($request->get('s', false)) {
                $s = $request->get('s');

                $modelQuery->where(function($query) use($s, $model) {
                    $query->where($model::getTableName() . '.name', 'LIKE', "%$s%");
                });
            }

            if ($request->get('t', false) || $request->get('t') == '0') {
                $t = $request->get('t');

                $modelQuery->where(function($query) use($t, $model) {
                    $query->where($model::getTableName() . '.is_daily', '=', $t);
                });
            }
        }

        $total   = $modelQuery->count();
        $records = $modelQuery->orderBy('name', 'ASC')->paginate($model::PAGINATE_RECORDS);

        // Client coaching informations.
        $roles   = [3, 4, 5];
        $clients = User::select(User::getTableName() . '.*')
                       ->join(ModelHasRoles::getTableName(), User::getTableName() . '.id', '=', ModelHasRoles::getTableName() . '.model_id')
                       ->whereIn(ModelHasRoles::getTableName() . '.role_id', $roles)
                       ->groupBy(User::getTableName() . '.id')
                       ->paginate($model::PAGINATE_RECORDS, ['*'], 'clientsPage');

        $coachings = Coaching::all();

        return view('coaching.index', compact('request', 'isFiltered', 'total', 'records', 'clients', 'coachings'));
    }

    public function create()
    {
        return view('coaching.create');
    }

    public function store(Request $request)
    {
        $data  = $request->all();
        $model = new Coaching();

        $validator = $model::validators($data);

        $validator->validate();

        $create = $model::create($data);

        if ($create) {
            $find = $model::find($create->id);
            self::createLog($find, __("Created coaching {$find->name}"), Log::CREATE, [], $find->toArray());

            return redirect('coaching')->with('success', __("Coaching created!"));
        }

        return redirect('coaching')->with('error', __("There has been an error!"));
    }

    public function edit(int $id)
    {
        $record = Coaching::find($id);

        if ($record) {
            return view('coaching.edit', compact('record'));
        }

        return redirect('coaching')->with('error', __("Not found!"));
    }

    public function update(Request $request, int $id)
    {
        $model  = new Coaching();
        $record = $model::find($id);

        if ($record) {
            $data = $request->all();

            $validator = $model::validators($data, false, true, $record);

            $validator->validate();

            $oldData = $record->toArray();

            $update = $record->update($data);

            if ($update) {
                $find = $model::find($id);
                self::createLog($find, __("Updated coaching {$find->name}"), Log::UPDATE, $oldData, $find->toArray());

                return redirect('coaching')->with('success', __("Coaching updated!"));
            }
        }

        return redirect('coaching')->with('error', __("There has been an error!"));
    }

    public function destroy(int $id)
    {
        $record = Coaching::where('id', $id)->get();

        if (!empty($record[0])) {
            DB::beginTransaction();

            $isRemoved = self::remove($record);

            if ($isRemoved) {
                self::createLog($record[0], __("Deleted coaching " . $record[0]->name), Log::DELETE, $record[0]->toArray(), []);

                DB::commit();

                return redirect('coaching')->with('success', __("Coaching deleted!"));
            } else {
                DB::rollBack();

                return redirect('coaching')->with('error', __("There has been an error!"));
            }
        }

        return redirect('coaching')->with('error', __("Not found!"));
    }

    public function clientStore(Request $request)
    {
        $data   = $request->all();
        $model  = new ClientCoaching();
        $userId = auth()->user()->id;

        // Old logic.
        if (false && !empty($data['wholeDayCoachings']) && !empty($data['client_coaching_info_id']) && is_numeric($data['client_coaching_info_id']) && !empty($data['current_day']) && is_numeric($data['current_day'])) {
            $errorCoachingName = [];
            $isUpdate          = false;
            $coachingInfoId    = (int)$data['client_coaching_info_id'];
            $currentDay        = (int)$data['current_day'];
            $isError           = false;

            // Get coaching info.
            $coachingInfo = ClientCoachingInfo::find($coachingInfoId);

            if (empty($coachingInfo)) {
                return redirect('/')->with('error', __("Coaching info doesn't found!"));
            }

            if ($coachingInfo->total_days == $currentDay) {
                $coachingInfo->update(['is_done' => ClientCoachingInfo::IS_DONE]);
            }

            $update = [];

            $updateFunction = function($id, $isUpdateOldRecords = false, $find) use(&$update, $data, $model) {
                if (empty($find)) {
                    return false;
                }

                $update[$id]['day']                     = $find->day;
                $update[$id]['date']                    = date('Y-m-d', strtotime($find->date));
                $update[$id]['is_attended']             = empty($data['coaching'][$id]) ? $model::IS_NOT_ATTENDED : $model::IS_ATTENDED;
                $update[$id]['coaching_id']             = $find->coaching_id;
                $update[$id]['client_coaching_info_id'] = $find->client_coaching_info_id;
                $update[$id]['user_id']                 = $find->user_id;
                $update[$id]['browse_file']             = NULL;

                if (!empty($data['browse_file'][$id]) && $data['browse_file'][$id] instanceof UploadedFile) {
                    $update[$id]['browse_file'] = $data['browse_file'][$id];
                }

                if (empty($update[$id]['browse_file']) && $find->coaching->browse_file == Coaching::IS_BROWSE_FILE) {
                    $errorCoachingName[] = $find->coaching->name;
                }
            };

            foreach ($data['wholeDayCoachings'] as $id => $coaching) {
                $find = $model::find($id);

                $updateFunction($id, false, $find);

                if (!$model::validators($update[$id], true)) {
                    $isError = (!empty(session('error'))) ? session('error') : __("There has been an error!");
                } else {
                    if (!empty($update[$id]['browse_file'])) {
                        $imageName = time() . '_' . $id . '_' . $find->user_id . '.' . $data['browse_file'][$id]->getClientOriginalExtension();
                        $moveFiles = $data['browse_file'][$id]->storeAs($model::$storageFolderName . "/{$id}", $imageName, $model::$fileSystems);

                        if ($moveFiles) {
                            $update[$id]['browse_file'] = $imageName;
                        }
                    }
                }
            }

            if (!$isError) {
                foreach ($data['wholeDayCoachings'] as $id => $coaching) {
                    $find = $model::find($id);

                    if (empty($data['coaching'][$id])) {
                        $isUpdate = $find->update(['is_attended' => $model::IS_NOT_ATTENDED, 'browse_file' => NULL]);
                    } elseif (!empty($update[$id])) {
                        $isUpdate = $find->update($update[$id]);
                    }
                }

                if ($isUpdate) {
                    $msg = NULL;

                    if (!empty($errorCoachingName)) {
                        $msg = " But image not uploaded for " . implode(',', $errorCoachingName) ." coaching.";
                    }

                    return redirect('/')->with('success', __("Coaching updated!" . $msg));
                }
            } else {
                return redirect('/')->with('error', $isError);
            }
        }

        if (!empty($data['wholeDayCoachings']) && !empty($data['current_day']) && is_numeric($data['current_day'])) {
            $errorCoachingName = [];
            $isUpdate          = false;
            $currentDay        = (int)$data['current_day'];
            $isError           = false;

            $update = [];

            $updateFunction = function($id, $isUpdateOldRecords = false, $find) use(&$update, $data, $model, $userId) {
                if (empty($find)) {
                    return false;
                }

                if (empty($data['coaching'][$id])) {
                    return false;
                }

                $update[$id]['day']                     = !empty($data['day'][$id]) ? $data['day'][$id] : NULL;
                $update[$id]['date']                    = !empty($data['date']) && strtotime($data['date']) > 0 ? $data['date'] : NULL;
                $update[$id]['is_attended']             = empty($data['coaching'][$id]) ? $model::IS_NOT_ATTENDED : $model::IS_ATTENDED;
                $update[$id]['coaching_id']             = $id;
                $update[$id]['client_coaching_info_id'] = NULL;
                $update[$id]['user_id']                 = $userId;
                $update[$id]['browse_file']             = NULL;

                if (!empty($data['browse_file'][$id]) && $data['browse_file'][$id] instanceof UploadedFile) {
                    $update[$id]['browse_file'] = $data['browse_file'][$id];
                }

                if (empty($update[$id]['browse_file']) && $find->browse_file == Coaching::IS_BROWSE_FILE) {
                    $errorCoachingName[] = $find->name;
                }
            };

            foreach ($data['wholeDayCoachings'] as $id => $coaching) {
                $find = Coaching::find($id);

                $updateFunction($id, false, $find);

                if (empty($update[$id])) {
                    continue;
                }

                if (!$model::validators($update[$id], true)) {
                    $isError = (!empty(session('error'))) ? session('error') : __("There has been an error!");
                } else {
                    if (!empty($update[$id]['browse_file'])) {
                        $imageName = time() . '_' . $id . '_' . $find->user_id . '.' . $data['browse_file'][$id]->getClientOriginalExtension();
                        $moveFiles = $data['browse_file'][$id]->storeAs($model::$storageFolderName . "/{$id}", $imageName, $model::$fileSystems);

                        if ($moveFiles) {
                            $update[$id]['browse_file'] = $imageName;
                        }
                    }
                }
            }

            if (!$isError) {
                foreach ($data['wholeDayCoachings'] as $id => $coaching) {
                    if (empty($update[$id])) {
                        continue;
                    }

                    $isUpdate = $model->insert($update[$id]);
                }

                if ($isUpdate) {
                    $msg = NULL;

                    if (!empty($errorCoachingName)) {
                        $msg = " But image not uploaded for " . implode(',', $errorCoachingName) ." coaching.";
                    }

                    return redirect('coaching/client/index')->with('success', __("Coaching updated!" . $msg));
                }
            } else {
                return redirect('coaching/client/index')->with('error', $isError);
            }
        }

        return redirect('coaching/client/index')->with('error', __("Not found!"));
    }

    public function clientInfoCreate(int $userId, Request $request)
    {
        $data   = $request->all();
        $model  = new ClientCoachingInfo();

        $startedAt   = (!empty($data['started_at']) && strtotime($data['started_at']) > 0) ? $data['started_at'] : NULL;
        $finishedAt  = (!empty($data['finished_at']) && strtotime($data['finished_at']) > 0) ? $data['finished_at'] : NULL;
        $coachingIds = !empty($data['coaching_ids']) ? implode(",", $data['coaching_ids']) : NULL;
        $totalDays   = 0;
        $now         = Carbon::now();

        if (!empty($startedAt) && !empty($finishedAt)) {
            $totalDays = Carbon::parse($startedAt)->diffInDays(Carbon::parse($finishedAt)) + 1;
        }

        $createData = [
            'total_days'    => $totalDays,
            'started_at'    => $startedAt,
            'finished_at'   => $finishedAt,
            'is_done'       => $model::IS_NOT_DONE,
            'coaching_ids'  => $coachingIds,
            'user_id'       => $userId
        ];

        $validator = $model::validators($createData);

        $validator->validate();

        // Check date exists or not.
        $exists = $model::whereBetween('started_at', [$startedAt, $finishedAt])->where('user_id', $userId)->first();
        if (!empty($exists)) {
            return redirect('coaching')->with('error', __("This start/complete date already exists!"));
        }

        $exists = $model::whereBetween('finished_at', [$startedAt, $finishedAt])->where('user_id', $userId)->first();
        if (!empty($exists)) {
            return redirect('coaching')->with('error', __("This start/complete date already exists!"));
        }

        $create = $model::create($createData);

        if ($create) {
            $insert = [];

            $index = 0;
            for ($day = 1; $day <= $totalDays; $day++) {
                foreach ((array)$data['coaching_ids'] as $coachingId) {
                    $insert[$index] = [
                        'day'         => $day,
                        'date'        => Carbon::parse($startedAt)->addDays($day - 1)->format('Y-m-d'),
                        'is_attended' => ClientCoaching::IS_NOT_ATTENDED,
                        'browse_file' => NULL,
                        'coaching_id' => $coachingId,
                        'client_coaching_info_id' => $create->id,
                        'user_id'     => $userId,
                        'created_at'  => $now
                    ];

                    $validator = ClientCoaching::validators($insert[$index], true, true);
                    if (!$validator) {
                        unset($insert[$index]);
                    }

                    $index++;
                }
            }

            if (!empty($insert)) {
                ClientCoaching::insert($insert);
            }

            $find = User::find($userId);
            self::createLog($find, __("Created coaching for client {$find->fullname}"), Log::CREATE, [], $find->toArray());

            return redirect('coaching')->with('success', __("Client coaching created!"));
        }

        return redirect('coaching')->with('error', __("There has been an error!"));
    }

    public function clientHistory(int $userId, Request $request)
    {
        $model          = new ClientCoaching();
        $isFiltered     = false;
        $modelQuery     = $model::query();
        $requestClonned = clone $request;
        $now            = Carbon::now();
        $weekStartDate  = new Carbon('2020-11-02');
        $weekStartDate1 = new Carbon('2020-11-02');
        $weekStartDate2 = new Carbon('2020-11-09');
        $weekStartDate3 = new Carbon('2020-11-09');
        $weekStartDate4 = new Carbon('2020-11-16');
        $weekStartDate5 = new Carbon('2020-11-16');
        $weekStartDate6 = new Carbon('2020-11-23');
        $weekStartDate7 = new Carbon('2020-11-23');
        $weekStartDate8 = new Carbon('2020-11-30');
        $weekStartDate9 = new Carbon('2020-11-30');
        $weekStartDate10 = new Carbon('2020-12-07');
        $weekStartDate11 = new Carbon('2020-12-07');
        $weekStartDate12 = new Carbon('2020-12-14');
        $weekStartDate13 = new Carbon('2020-12-14');
        $weekStartDate14 = new Carbon('2020-12-21');
        $weekStartDate15 = new Carbon('2020-12-21');
        $weekStartDate16 = new Carbon('2020-12-28');
        $weekStartDate17 = new Carbon('2020-12-28');
        $weekStartDate18 = new Carbon('2021-01-04');
        $weekStartDate19 = new Carbon('2021-01-04');
        $coachings      = Coaching::all();

        $cleanup = $requestClonned->except(['page']);
        $requestClonned->query = new \Symfony\Component\HttpFoundation\ParameterBag($cleanup);

        if (count($requestClonned->all()) > 0) {
            $isFiltered = (!empty(array_filter($requestClonned->all())));
        }

        if ($isFiltered) {
            if ($request->get('s', false) && $request->get('f', false)) {
                $s = $request->get('s');
                $f = $request->get('f');

                $modelQuery->where(function($query) use($s, $f) {
                    $query->whereBetween('started_at', [$s, $f])
                          ->orWhereBetween('finished_at', [$s, $f]);
                });
            } elseif ($request->get('s', false)) {
                $s = $request->get('s');

                $modelQuery->whereDate('started_at', '>=', $s);
            } elseif ($request->get('f', false)) {
                $f = $request->get('f');

                $modelQuery->whereDate('finished_at', '<=', $f);
            }
        }

        $modelQuery->where('user_id', $userId);

        $total   = $modelQuery->count();
        $records = $modelQuery->paginate($model::PAGINATE_RECORDS);

        return view('coaching.history', compact('request', 'isFiltered', 'total', 'records', 'now', 'userId', 'coachings', 'weekStartDate', 'userId', 'weekStartDate1', 'weekStartDate2', 'weekStartDate3', 'weekStartDate4', 'weekStartDate5', 'weekStartDate6', 'weekStartDate7', 'weekStartDate8', 'weekStartDate9', 'weekStartDate10', 'weekStartDate11', 'weekStartDate12', 'weekStartDate13', 'weekStartDate14', 'weekStartDate15', 'weekStartDate16', 'weekStartDate17', 'weekStartDate18', 'weekStartDate19'));
    }

    public function clientIndex()
    {
        $user           = auth()->user();
        $now            = Carbon::now();
        $weekStartDate  = new Carbon('2020-11-02');
        $weekStartDate1 = new Carbon('2020-11-09');
        $weekStartDate2 = new Carbon('2020-11-16');
        $weekStartDate3 = new Carbon('2020-11-23');
        $weekStartDate4 = new Carbon('2020-11-30');
        $weekStartDate5 = new Carbon('2020-12-07');
        $weekStartDate6 = new Carbon('2020-12-14');
        $weekStartDate7 = new Carbon('2020-12-21');
        $weekStartDate8 = new Carbon('2020-12-28');
        $weekStartDate9 = new Carbon('2021-01-04');
        $currentWeekDay = $now->dayOfWeek;
        $userId         = $user->id;
        $coachings      = Coaching::all();

        return view('coaching.clientIndex', compact('coachings', 'now', 'weekStartDate', 'currentWeekDay', 'weekStartDate1', 'weekStartDate2', 'weekStartDate3', 'weekStartDate4', 'weekStartDate5', 'weekStartDate6', 'weekStartDate7', 'weekStartDate8', 'weekStartDate9'));
    }
}
