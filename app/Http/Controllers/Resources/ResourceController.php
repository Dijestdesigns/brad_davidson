<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Resource;
use App\ResourceUser;
use App\User;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

class ResourceController extends \App\Http\Controllers\BaseController
{

    public function __construct()
    {
        $this->middleware(['permission:resource_create'])->only(['create','store']);
        $this->middleware(['permission:resource_show'])->only('show');
        $this->middleware(['permission:resource_edit'])->only(['edit','update']);
        $this->middleware(['permission:resource_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model              = new Resource();
        $modelResourceUsers = new ResourceUser();

        if (auth()->user()->isSuperAdmin()) {
            $isFiltered     = false;
            $modelQuery     = $model::query();
            $requestClonned = clone $request;

            $cleanup = $requestClonned->except(['page']);
            $requestClonned->query = new \Symfony\Component\HttpFoundation\ParameterBag($cleanup);

            if (count($requestClonned->all()) > 0) {
                $isFiltered = (!empty(array_filter($requestClonned->all())));
            }

            if ($isFiltered) {
                if ($request->get('s', false)) {
                    $s = $request->get('s');

                    $modelQuery->where(function($query) use($s, $model) {
                        $query->where($model::getTableName() . '.title', 'LIKE', "%$s%");
                    });
                }

                if ($request->get('e', false)) {
                    $e = $request->get('e');

                    $modelQuery->where(function($query) use($e, $model) {
                        $query->where($model::getTableName() . '.extensions', 'LIKE', "%$e%");
                    });
                }
            }


            $total      = $modelQuery->get()->count();

            $resources  = $modelQuery->orderBy('id','ASC')->paginate(Resource::PAGINATE_RECORDS);

            $extensions = Resource::all()->pluck('extensions');

            return view('resources.list', compact('total', 'resources', 'extensions', 'request', 'isFiltered'));
        } elseif (auth()->user()->can('resource_access')) {
            $recordsForAll = $model::where(['for_all' => $model::FOR_ALL])->orderBy($model::getTableName() . '.id', 'DESC');

            $recordsForSpacific = $model::select($model::getTableName() . '.*')
                                        ->join($modelResourceUsers::getTableName(), $model::getTableName() . '.id', '=', $modelResourceUsers::getTableName() . '.resource_id')
                                        ->where(['for_all' => $model::NOT_FOL_ALL, 'user_id' => auth()->user()->id])
                                        ->orderBy($model::getTableName() . '.id', 'DESC');

            $records = $recordsForSpacific->union($recordsForAll)->get();

            return view('resources.show', compact('records'));
        }

        abort(401);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = User::where('id', '!=', User::$superadminId)->get();

        return view('resources.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data  = $request->all();
        $model = new Resource();
        $modelResourceUsers = new ResourceUser();
        $now                = Carbon::now();

        if (!empty($data['url']) && $data['url'] instanceof UploadedFile) {
            $url       = $data['url'];
            $pathInfos = pathinfo($url->getClientOriginalName());

            if (!empty($pathInfos['extension'])) {
                $fileName  = (empty($pathInfos['filename']) ? time() : $pathInfos['filename']) . ' - ' . time() . '.' . $pathInfos['extension'];
                $storeFile = $url->storeAs($model::$storageFolderName, $fileName, $model::$fileSystems);

                if ($storeFile) {
                    $data['url']        = $fileName;
                    $data['extensions'] = $pathInfos['extension'];
                    $data['mime_type']  = $url->getClientMimeType();
                }
            }
        }

        $validator = $model::validators($data);

        $validator->validate();

        $data['for_all'] = (isset($data['users'][0]) && count($data['users']) <= 1 && $data['users'][0] == '0') ? '0' : '1';

        $create = $model::updateOrCreate(["title" => $data['title'], "url" => $data['url'], "mime_type" => $data['mime_type'], "extensions" => $data['extensions'], "for_all" => $data['for_all']], $data);

        if ($create) {
            $id = $create->id;

            if (!empty($data['users'])) {
                $resourceUser = [];
                foreach ($data['users'] as $index => $userId) {
                    if (empty($userId)) {
                        continue;
                    }

                    $resourceUser[$index]['user_id']     = $userId;
                    $resourceUser[$index]['resource_id'] = $id;
                    $resourceUser[$index]['created_at']  = $now;
                    $resourceUser[$index]['updated_at']  = $now;

                    if (!$modelResourceUsers::validators($resourceUser[$index], true)) {
                        unset($resourceUser[$index]);
                    }
                }

                if (!empty($resourceUser)) {
                    $modelResourceUsers::insert($resourceUser);
                }
            }

            return redirect('resources')->with('success', __("Resource added!"));
        }

        return redirect('resources')->with('error', __("Not added! Something went wrong. Please try again with proper selection of file."));
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
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Resource::where('id', $id)->get();

        if (!empty($record[0])) {
            DB::beginTransaction();

            $isRemoved = self::remove($record);
        }
    }

    public function download(int $id)
    {
        $record = Resource::find($id);

        if (!empty($record)) {
            $headers = array(
                'Content-Type: application/pdf',
            );

            return response()->download(public_path() . '/storage/resources/' . basename($record->url), basename($record->url), $headers);
        }

        abort(404);
    }
}
