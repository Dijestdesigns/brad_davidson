<?php

namespace App\Http\Controllers\TrainingContents;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Role;
use App\BaseModel;
use App\TrainingContent;
use App\ModelHasRoles;
use App\User;
use App\Log;
use Illuminate\Http\UploadedFile;
use DB;

class TrainingContentController extends \App\Http\Controllers\BaseController
{

    public function __construct()
    {
        $this->middleware(['permission:training_content_access'])->only('index');
        $this->middleware(['permission:training_content_create'])->only('create','store');
        $this->middleware(['permission:training_content_update'])->only(['edit','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelRoles    = new Role();
        $modelHasRoles = new ModelHasRoles();

        if (auth()->user()->isSuperAdmin()) {
            $isFiltered     = false;
            $modelQuery     = $modelRoles::query();
            $requestClonned = clone $request;

            $cleanup = $requestClonned->except(['page']);
            $requestClonned->query = new \Symfony\Component\HttpFoundation\ParameterBag($cleanup);

            if (count($requestClonned->all()) > 0) {
                $isFiltered = (!empty(array_filter($requestClonned->all())));
            }

            if ($isFiltered) {
                if ($request->get('s', false)) {
                    $s = $request->get('s');

                    $modelQuery->where(function($query) use($s, $modelRoles) {
                        $query->where($modelRoles::getTableName() . '.name', 'LIKE', "%$s%");
                    });
                }
            }

            $modelQuery->join($modelHasRoles::getTableName(), $modelRoles::getTableName() . '.id', '=', $modelHasRoles::getTableName() . '.role_id')->where($modelHasRoles::getTableName() . '.model_id', '!=', User::$superadminId)->groupBy($modelRoles::getTableName() . '.id');

            $total = $modelQuery->get()->count();

            $roles = $modelQuery->orderBy('id','ASC')->paginate(BaseModel::PAGINATE_RECORDS);

            return view('training_contents.list', compact('total', 'roles', 'request', 'isFiltered'));
        } elseif (auth()->user()->can('training_content_access')) {
            $role   = auth()->user()->roles->first();
            $roleId = $role->id;

            $records = TrainingContent::where('role_id', (int)$roleId)->orderBy('day', 'DESC')->get();

            return view('training_contents.show', compact('records'));
        }

        abort(401);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data  = $request->all();
        $model = new TrainingContent();

        if (!empty($data['file']) && $data['file'] instanceof UploadedFile) {
            $file      = $data['file'];
            $pathInfos = pathinfo($file->getClientOriginalName());

            if (!empty($pathInfos['extension'])) {
                $fileName  = (empty($pathInfos['filename']) ? time() : $pathInfos['filename']) . ' - ' . time() . '.' . $pathInfos['extension'];
                $storeFile = $file->storeAs($model::$storageFolderName . '/' . $data['role_id'], $fileName, $model::$fileSystems);

                if ($storeFile) {
                    $data['url']        = $fileName;
                    $data['extensions'] = $pathInfos['extension'];
                    $data['mime_type']  = $file->getClientMimeType();
                }
            }
        }

        $validator = $model::validators($data);

        $validator->validate();

        $create = $model::create($data);

        if ($create) {
            $id = $create->id;

            $find = $model::find($id);
            self::createLog($find, __("Created training content {$find->title}"), Log::CREATE, [], $find->toArray());

            return redirect()->route('trainingContents.edit', $data['role_id'])->with('success', __("Resource added!"));
        }

        if (!empty($data['role_id'])) {
            return redirect()->route('trainingContents.edit', $data['role_id'])->with('error', __("Not added!"));
        } else {
            return redirect()->route('trainingContents.index')->with('error', __("Not added!"));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $records = TrainingContent::where('role_id', $id)->orderBy('day', 'DESC')->get();

        $roleId = $id;

        return view('training_contents.edit', compact('records', 'roleId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $roleId = $request->get('role_id', false);
        $record = TrainingContent::where('id', $id)->get();

        if (!empty($record[0])) {
            DB::beginTransaction();

            $isRemoved = self::remove($record);

            if ($isRemoved) {
                self::createLog($record[0], __("Deleted training content " . $record[0]->title), Log::DELETE, $record[0]->toArray(), []);

                DB::commit();

                return redirect()->route('trainingContents.edit', $roleId)->with('success', __("Resource deleted!"));
            } else {
                DB::rollBack();

                return redirect()->route('trainingContents.edit', $roleId)->with('success', __("There has been an error!"));
            }
        }

        return redirect()->route('trainingContents.edit', $roleId)->with('error', __("Not found!"));
    }
}
