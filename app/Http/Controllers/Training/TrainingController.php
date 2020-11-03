<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Training;
use App\ClientTraining;
use App\ClientTrainingInfo;
use App\User;
use App\ModelHasRoles;
use App\Log;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use DB;

class TrainingController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:training_access'])->only('index');
        $this->middleware(['permission:training_create'])->only(['create','store']);
        $this->middleware(['permission:training_edit'])->only(['edit','update']);
        $this->middleware(['permission:training_delete'])->only('destroy');

        $this->middleware(['permission:training_info_create'])->only(['clientInfoCreate']);
        $this->middleware(['permission:training_info_edit'])->only(['clientInfoUpdate']);

        $this->middleware(['permission:training_show_to_clients'])->only(['clientIndex']);
    }

    public function index(Request $request)
    {
        $model          = new Training();
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

        // Client training informations.
        $roles   = [3, 4];
        $clients = User::select(User::getTableName() . '.*')
                       ->join(ModelHasRoles::getTableName(), User::getTableName() . '.id', '=', ModelHasRoles::getTableName() . '.model_id')
                       ->whereIn(ModelHasRoles::getTableName() . '.role_id', $roles)
                       ->groupBy(User::getTableName() . '.id')
                       ->paginate($model::PAGINATE_RECORDS, ['*'], 'clientsPage');

        $trainings = Training::all();

        return view('training.index', compact('request', 'isFiltered', 'total', 'records', 'clients', 'trainings'));
    }

    public function create()
    {
        return view('training.create');
    }

    public function store(Request $request)
    {
        $data  = $request->all();
        $model = new Training();

        $validator = $model::validators($data);

        $validator->validate();

        $create = $model::create($data);

        if ($create) {
            $find = $model::find($create->id);
            self::createLog($find, __("Created training {$find->name}"), Log::CREATE, [], $find->toArray());

            return redirect('training')->with('success', __("Training created!"));
        }

        return redirect('training')->with('error', __("There has been an error!"));
    }

    public function edit(int $id)
    {
        $record = Training::find($id);

        if ($record) {
            return view('training.edit', compact('record'));
        }

        return redirect('training')->with('error', __("Not found!"));
    }

    public function update(Request $request, int $id)
    {
        $model  = new Training();
        $record = $model::find($id);

        if ($record) {
            $data = $request->all();

            $validator = $model::validators($data, false, true, $record);

            $validator->validate();

            $oldData = $record->toArray();

            $update = $record->update($data);

            if ($update) {
                $find = $model::find($id);
                self::createLog($find, __("Updated training {$find->name}"), Log::UPDATE, $oldData, $find->toArray());

                return redirect('training')->with('success', __("Training updated!"));
            }
        }

        return redirect('training')->with('error', __("There has been an error!"));
    }

    public function destroy(int $id)
    {
        $record = Training::where('id', $id)->get();

        if (!empty($record[0])) {
            DB::beginTransaction();

            $isRemoved = self::remove($record);

            if ($isRemoved) {
                self::createLog($record[0], __("Deleted training " . $record[0]->name), Log::DELETE, $record[0]->toArray(), []);

                DB::commit();

                return redirect('training')->with('success', __("Training deleted!"));
            } else {
                DB::rollBack();

                return redirect('training')->with('error', __("There has been an error!"));
            }
        }

        return redirect('training')->with('error', __("Not found!"));
    }

    public function clientStore(Request $request)
    {
        $data   = $request->all();
        $model  = new ClientTraining();
        $userId = auth()->user()->id;

        // Old logic.
        if (false && !empty($data['wholeDayTrainings']) && !empty($data['client_training_info_id']) && is_numeric($data['client_training_info_id']) && !empty($data['current_day']) && is_numeric($data['current_day'])) {
            $errorTrainingName = [];
            $isUpdate          = false;
            $trainingInfoId    = (int)$data['client_training_info_id'];
            $currentDay        = (int)$data['current_day'];
            $isError           = false;

            // Get training info.
            $trainingInfo = ClientTrainingInfo::find($trainingInfoId);

            if (empty($trainingInfo)) {
                return redirect('/')->with('error', __("Training info doesn't found!"));
            }

            if ($trainingInfo->total_days == $currentDay) {
                $trainingInfo->update(['is_done' => ClientTrainingInfo::IS_DONE]);
            }

            $update = [];

            $updateFunction = function($id, $isUpdateOldRecords = false, $find) use(&$update, $data, $model) {
                if (empty($find)) {
                    return false;
                }

                $update[$id]['day']                     = $find->day;
                $update[$id]['date']                    = date('Y-m-d', strtotime($find->date));
                $update[$id]['is_attended']             = empty($data['training'][$id]) ? $model::IS_NOT_ATTENDED : $model::IS_ATTENDED;
                $update[$id]['training_id']             = $find->training_id;
                $update[$id]['client_training_info_id'] = $find->client_training_info_id;
                $update[$id]['user_id']                 = $find->user_id;
                $update[$id]['browse_file']             = NULL;

                if (!empty($data['browse_file'][$id]) && $data['browse_file'][$id] instanceof UploadedFile) {
                    $update[$id]['browse_file'] = $data['browse_file'][$id];
                }

                if (empty($update[$id]['browse_file']) && $find->training->browse_file == Training::IS_BROWSE_FILE) {
                    $errorTrainingName[] = $find->training->name;
                }
            };

            foreach ($data['wholeDayTrainings'] as $id => $training) {
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
                foreach ($data['wholeDayTrainings'] as $id => $training) {
                    $find = $model::find($id);

                    if (empty($data['training'][$id])) {
                        $isUpdate = $find->update(['is_attended' => $model::IS_NOT_ATTENDED, 'browse_file' => NULL]);
                    } elseif (!empty($update[$id])) {
                        $isUpdate = $find->update($update[$id]);
                    }
                }

                if ($isUpdate) {
                    $msg = NULL;

                    if (!empty($errorTrainingName)) {
                        $msg = " But image not uploaded for " . implode(',', $errorTrainingName) ." training.";
                    }

                    return redirect('/')->with('success', __("Training updated!" . $msg));
                }
            } else {
                return redirect('/')->with('error', $isError);
            }
        }

        if (!empty($data['wholeDayTrainings']) && !empty($data['current_day']) && is_numeric($data['current_day'])) {
            $errorTrainingName = [];
            $isUpdate          = false;
            $currentDay        = (int)$data['current_day'];
            $isError           = false;

            $update = [];

            $updateFunction = function($id, $isUpdateOldRecords = false, $find) use(&$update, $data, $model, $userId) {
                if (empty($find)) {
                    return false;
                }

                if (empty($data['training'][$id])) {
                    return false;
                }

                $update[$id]['day']                     = !empty($data['day'][$id]) ? $data['day'][$id] : NULL;
                $update[$id]['date']                    = !empty($data['date']) && strtotime($data['date']) > 0 ? $data['date'] : NULL;
                $update[$id]['is_attended']             = empty($data['training'][$id]) ? $model::IS_NOT_ATTENDED : $model::IS_ATTENDED;
                $update[$id]['training_id']             = $id;
                $update[$id]['client_training_info_id'] = NULL;
                $update[$id]['user_id']                 = $userId;
                $update[$id]['browse_file']             = NULL;

                if (!empty($data['browse_file'][$id]) && $data['browse_file'][$id] instanceof UploadedFile) {
                    $update[$id]['browse_file'] = $data['browse_file'][$id];
                }

                if (empty($update[$id]['browse_file']) && $find->browse_file == Training::IS_BROWSE_FILE) {
                    $errorTrainingName[] = $find->name;
                }
            };

            foreach ($data['wholeDayTrainings'] as $id => $training) {
                $find = Training::find($id);

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
                foreach ($data['wholeDayTrainings'] as $id => $training) {
                    if (empty($update[$id])) {
                        continue;
                    }

                    $isUpdate = $model->insert($update[$id]);
                }

                if ($isUpdate) {
                    $msg = NULL;

                    if (!empty($errorTrainingName)) {
                        $msg = " But image not uploaded for " . implode(',', $errorTrainingName) ." training.";
                    }

                    return redirect('training/client/index')->with('success', __("Training updated!" . $msg));
                }
            } else {
                return redirect('training/client/index')->with('error', $isError);
            }
        }

        return redirect('training/client/index')->with('error', __("Not found!"));
    }

    public function clientInfoCreate(int $userId, Request $request)
    {
        $data   = $request->all();
        $model  = new ClientTrainingInfo();

        $startedAt   = (!empty($data['started_at']) && strtotime($data['started_at']) > 0) ? $data['started_at'] : NULL;
        $finishedAt  = (!empty($data['finished_at']) && strtotime($data['finished_at']) > 0) ? $data['finished_at'] : NULL;
        $trainingIds = !empty($data['training_ids']) ? implode(",", $data['training_ids']) : NULL;
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
            'training_ids'  => $trainingIds,
            'user_id'       => $userId
        ];

        $validator = $model::validators($createData);

        $validator->validate();

        // Check date exists or not.
        $exists = $model::whereBetween('started_at', [$startedAt, $finishedAt])->where('user_id', $userId)->first();
        if (!empty($exists)) {
            return redirect('training')->with('error', __("This start/complete date already exists!"));
        }

        $exists = $model::whereBetween('finished_at', [$startedAt, $finishedAt])->where('user_id', $userId)->first();
        if (!empty($exists)) {
            return redirect('training')->with('error', __("This start/complete date already exists!"));
        }

        $create = $model::create($createData);

        if ($create) {
            $insert = [];

            $index = 0;
            for ($day = 1; $day <= $totalDays; $day++) {
                foreach ((array)$data['training_ids'] as $trainingId) {
                    $insert[$index] = [
                        'day'         => $day,
                        'date'        => Carbon::parse($startedAt)->addDays($day - 1)->format('Y-m-d'),
                        'is_attended' => ClientTraining::IS_NOT_ATTENDED,
                        'browse_file' => NULL,
                        'training_id' => $trainingId,
                        'client_training_info_id' => $create->id,
                        'user_id'     => $userId,
                        'created_at'  => $now
                    ];

                    $validator = ClientTraining::validators($insert[$index], true, true);
                    if (!$validator) {
                        unset($insert[$index]);
                    }

                    $index++;
                }
            }

            if (!empty($insert)) {
                ClientTraining::insert($insert);
            }

            $find = User::find($userId);
            self::createLog($find, __("Created training for client {$find->fullname}"), Log::CREATE, [], $find->toArray());

            return redirect('training')->with('success', __("Client training created!"));
        }

        return redirect('training')->with('error', __("There has been an error!"));
    }

    public function clientHistory(int $userId, Request $request)
    {
        $model          = new ClientTraining();
        $isFiltered     = false;
        $modelQuery     = $model::query();
        $requestClonned = clone $request;
        $now            = Carbon::now();
        $weekStartDate  = new Carbon('2020-11-02');
        $weekStartDate1 = new Carbon('2020-11-02');
        $trainings      = Training::all();

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

        return view('training.history', compact('request', 'isFiltered', 'total', 'records', 'now', 'userId', 'trainings', 'weekStartDate', 'userId', 'weekStartDate1'));
    }

    public function clientIndex()
    {
        $user           = auth()->user();
        $now            = Carbon::now();
        $weekStartDate  = new Carbon('2020-11-02');
        $currentWeekDay = $weekStartDate->dayOfWeek + 1;
        $userId         = $user->id;
        $trainings      = Training::all();

        return view('training.clientIndex', compact('trainings', 'now', 'weekStartDate', 'currentWeekDay'));
    }
}
