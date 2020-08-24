<?php

namespace App\Http\Controllers\Folders;

use Illuminate\Http\Request;
use App\Client;
use App\Tag;
use App\ClientTag;
use App\ClientPhoto;
use App\ClientItem;
use DB;
use Illuminate\Http\UploadedFile;

class FoldersController extends \App\Http\Controllers\BaseController
{
    public function index(Request $request)
    {
        $model          = new Client();
        $isFiltered     = false;
        // $total          = $model::count();
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
                    $query->where($model::getTableName() . '.name', 'LIKE', "%$s%")
                          ->orWhere($model::getTableName() . '.name','LIKE', "%$s%");
                });
            }

            if ($request->get('t', false)) {
                $t = $request->get('t');

                $modelQuery->join(ClientTag::getTableName(), function($join) use($t, $model) {
                    $join->on($model::getTableName() . '.id', '=', CLientTag::getTableName() . '.client_id')
                             ->where(ClientTag::getTableName() . '.tag_id', (int)$t);
                });
            }
        }

        $modelQuery->leftJoin(ClientItem::getTableName(), $model::getTableName() . '.id', '=', ClientItem::getTableName() . '.client_id');
        $modelQuery->groupBy(ClientItem::getTableName() . '.client_id');
        $modelQuery->select(DB::raw($model::getTableName() . ".*, SUM(" . ClientItem::getTableName() . '.qty) as qty'));

        $total   = $modelQuery->count();
        $records = $modelQuery->paginate($model::PAGINATE_RECORDS);

        $tags    = Tag::all();

        return view('folders.index', compact('total', 'records', 'request', 'isFiltered', 'tags'));
    }

    public function create()
    {
        $tags = Tag::all();

        return view('folders.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $data               = $request->all();
        $data['created_by'] = auth()->user()->id;
        $model              = new Client();
        $clientTagModel     = new ClientTag();

        $validator = $model::validators($data);

        $validator->validate();

        $create = $model::create($data);

        if ($create) {
            $tagData['client_id'] = $create->id;
            $tagData['tag_id']    = (!empty($data['tags'])) ? $data['tags'] : [];

            $this->photos($create->id, $request->photos, 'create');

            $validator = $clientTagModel::validators($tagData, true);
            if ($validator) {
                foreach ((array)$tagData as $index => $data) {
                    $tagDatas = [];

                    if (is_array($data)) {
                        foreach ($data as $tag) {
                            $tagDatas['client_id'] = $create->id;
                            $tagDatas['tag_id']    = $tag;

                            $tagCreate = $clientTagModel->create($tagDatas);
                        }
                    }
                }
            }

            return redirect('folders')->with('success', __("Folder created!"));
        }

        return redirect('folders/create')->with('error', __("There has been an error!"));
    }

    public function photos($id, $datas, $flag = 'create')
    {
        $create = false;

        if (!empty($datas)) {
            if ($flag == 'update') {
                ClientPhoto::where('client_id', $id)->delete();
            }

            foreach ($datas as $data) {
                if ($data instanceof UploadedFile) {
                    $check['photo']     = $data;
                    $check['client_id'] = $id;

                    if (ClientPhoto::validators($check, true)) {
                        $imageName = time() . '_' . $id . '.' . $data->getClientOriginalExtension();
                        $moveFiles = $data->storeAs(ClientPhoto::$storageFolderName . "/{$id}", $imageName, ClientPhoto::$fileSystems);

                        if ($moveFiles) {
                            $check['photo'] = $imageName;

                            $create = ClientPhoto::create($check);
                        }
                    }
                }
            }
        }

        return $create;
    }

    public function edit(int $id)
    {
        $record = Client::find($id);

        if ($record) {
            $tags = Tag::all();

            return view('folders.edit', compact('record', 'tags'));
        }

        return redirect('folders')->with('error', __("Not found!"));
    }

    public function update(Request $request, int $id)
    {
        $model  = new Client();
        $record = $model::find($id);

        if ($record) {
            $data               = $request->all();
            $data['updated_by'] = auth()->user()->id;
            $clientTagModel     = new ClientTag();

            $validator = $model::validators($data, false, true);

            $validator->validate();

            $update = $record->update($data);

            if ($update) {
                $tagData['client_id'] = $id;
                $tagData['tag_id']    = (!empty($data['tags'])) ? $data['tags'] : [];

                $this->photos($id, $request->photos, 'update');

                $validator = $clientTagModel::validators($tagData, true);
                if ($validator) {
                    // First remove older tags.
                    $find = $clientTagModel::where('client_id', $id)->delete();
                    /*if (!empty($find) && !$find->isEmpty()) {
                        // self::remove($find);
                    }*/

                    foreach ((array)$tagData as $index => $data) {
                        $tagDatas = [];

                        if (is_array($data)) {
                            foreach ($data as $tag) {
                                $tagDatas['client_id'] = $id;
                                $tagDatas['tag_id']    = $tag;

                                $tagCreate = $clientTagModel->create($tagDatas);
                            }
                        }
                    }
                }

                return redirect('folders')->with('success', __("Folder updated!"));
            }
        }

        return redirect('folders')->with('error', __("There has been an error!"));
    }

    public function destroy(int $id)
    {
        $record = Client::where('id', $id)->get();

        if ($record) {
            DB::beginTransaction();

            $find = ClientTag::where('client_id', $id)->get();
            if (!empty($find) && !$find->isEmpty()) {
                self::remove($find);
            }

            $isRemoved = self::remove($record);

            if ($isRemoved) {
                DB::commit();

                return redirect('folders')->with('success', __("Folder deleted!"));
            } else {
                DB::rollBack();

                return redirect('folders')->with('error', __("There has been an error!"));
            }
        }

        return redirect('folders')->with('error', __("Not found!"));
    }
}
