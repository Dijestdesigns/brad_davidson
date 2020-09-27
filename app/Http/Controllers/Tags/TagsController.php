<?php

namespace App\Http\Controllers\Tags;

use Illuminate\Http\Request;
use App\Tag;
use App\Log;

class TagsController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:tags_access'])->only('index');
        $this->middleware(['permission:tags_create'])->only(['create','store']);
        $this->middleware(['permission:tags_edit'])->only(['edit','update']);
        $this->middleware(['permission:tags_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        $model          = new Tag();
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

                $modelQuery->where(function($query) use($s) {
                    $query->where('name', 'LIKE', "%$s%")
                          ->orWhere('name','LIKE', "%$s%");
                });
            }
        }

        $total   = $modelQuery->count();
        $records = $modelQuery->orderBy('name', 'ASC')->paginate(Tag::PAGINATE_RECORDS);

        return view('tags.index', compact('total', 'records', 'request', 'isFiltered'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(Request $request)
    {
        $data               = $request->all();
        $data['created_by'] = auth()->user()->id;
        $model              = new Tag();

        $validator = $model::validators($data);

        $validator->validate();

        $create = $model::create($data);

        if ($create) {
            $find = $model::find($create->id);
            self::createLog($find, __("Created tag {$find->name}"), Log::CREATE, [], $find->toArray());

            return redirect('tags')->with('success', __("Tag created!"));
        }

        return redirect('tags/create')->with('error', __("There has been an error!"));
    }

    public function edit(int $id)
    {
        $record = Tag::find($id);

        if ($record) {
            return view('tags.edit', compact('record'));
        }

        return redirect('tags')->with('error', __("Not found!"));
    }

    public function update(Request $request, int $id)
    {
        $record = Tag::find($id);

        if ($record) {
            $data               = $request->all();
            // $data['created_by'] = auth()->user()->id;

            $validator = Tag::validators($data, false, true);

            $validator->validate();

            $oldData = $record->toArray();

            if ($record->update($data)) {
                $find = Tag::find($id);
                self::createLog($find, __("Updated tag {$find->name}"), Log::UPDATE, $oldData, $find->toArray());

                return redirect('tags')->with('success', __("Tag updated!"));
            }
        }

        return redirect('tags')->with('error', __("Not found!"));
    }

    public function destroy(int $id)
    {
        $record = Tag::where('id', $id)->get();

        if (!empty($record[0])) {
            $isRemoved = self::remove($record);

            if ($isRemoved) {
                self::createLog($record[0], __("Deleted tag " . $record[0]->name), Log::DELETE, $record[0]->toArray(), []);

                return redirect('tags')->with('success', __("Tag deleted!"));
            } else {
                return redirect('tags')->with('error', __("There has been an error!"));
            }
        }

        return redirect('tags')->with('error', __("Not found!"));
    }
}
