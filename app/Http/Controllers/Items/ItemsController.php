<?php

namespace App\Http\Controllers\Items;

use Illuminate\Http\Request;
use App\Item;
use App\Tag;
use App\ItemTag;
use App\ItemPhoto;
use App\Client;
use App\ClientItem;
use DB;
use Illuminate\Http\UploadedFile;

class ItemsController extends \App\Http\Controllers\BaseController
{
    public function index(Request $request)
    {
        $model          = new Item();
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

            if ($request->get('q', false)) {
                $q = $request->get('q');

                $modelQuery->where('qty', (int)$q);
            }
            
            if ($request->get('v', false)) {
                $v = $request->get('v');

                $modelQuery->where('value', (int)$v);
            }

            if ($request->get('ml', false)) {
                $ml = $request->get('ml');

                $modelQuery->where('min_level', (int)$ml);
            }

            if ($request->get('t', false)) {
                $t = $request->get('t');

                $modelQuery->join(ItemTag::getTableName(), function($join) use($t, $model) {
                    $join->on($model::getTableName() . '.id', '=', ItemTag::getTableName() . '.item_id')
                             ->where(ItemTag::getTableName() . '.tag_id', (int)$t);
                });
            }
        }

        $total   = $modelQuery->count();
        $records = $modelQuery->paginate(8);

        // $quantity = $model::all()->pluck('qty', 'qty');
        $levels   = $model::all()->pluck('min_level', 'min_level');
        $tags     = Tag::all();
        $folders  = Client::all();

        return view('items.index', compact('total', 'records', 'request', 'isFiltered', 'tags', 'levels', 'folders'));
    }

    public function create()
    {
        $tags = Tag::all();

        return view('items.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $data               = $request->all();
        $data['created_by'] = auth()->user()->id;
        $model              = new Item();
        $itemTagModel       = new ItemTag();

        $validator = $model::validators($data);

        $validator->validate();

        $create = $model::create($data);

        if ($create) {
            $tagData['item_id'] = $create->id;
            $tagData['tag_id']  = (!empty($data['tags'])) ? $data['tags'] : [];

            $this->photos($create->id, $request->photos, 'create');

            $validator = $itemTagModel::validators($tagData, true);
            if ($validator) {
                foreach ((array)$tagData as $index => $data) {
                    $tagDatas = [];

                    if (is_array($data)) {
                        foreach ($data as $tag) {
                            $tagDatas['item_id'] = $create->id;
                            $tagDatas['tag_id']  = $tag;

                            $tagCreate = $itemTagModel->create($tagDatas);
                        }
                    }
                }
            }

            return redirect('items')->with('success', __("Item created!"));
        }

        return redirect('items/create')->with('error', __("There has been an error!"));
    }

    public function photos($id, $datas, $flag = 'create')
    {
        $create = false;

        if (!empty($datas)) {
            if ($flag == 'update') {
                ItemPhoto::where('item_id', $id)->delete();
            }

            foreach ($datas as $data) {
                if ($data instanceof UploadedFile) {
                    $check['photo']   = $data;
                    $check['item_id'] = $id;

                    if (ItemPhoto::validators($check, true)) {
                        $imageName = time() . '_' . $id . '.' . $data->getClientOriginalExtension();
                        $moveFiles = $data->storeAs(ItemPhoto::$storageFolderName . "/{$id}", $imageName, ItemPhoto::$fileSystems);

                        if ($moveFiles) {
                            $check['photo'] = $imageName;

                            $create = ItemPhoto::create($check);
                        }
                    }
                }
            }
        }

        return $create;
    }

    public function edit(int $id)
    {
        $record = Item::find($id);

        if ($record) {
            $tags = Tag::all();

            return view('items.edit', compact('record', 'tags'));
        }

        return redirect('items')->with('error', __("Not found!"));
    }

    public function update(Request $request, int $id)
    {
        $model  = new Item();
        $record = $model::find($id);

        if ($record) {
            $data               = $request->all();
            // $data['created_by'] = auth()->user()->id;
            $itemTagModel       = new ItemTag();

            $validator = $model::validators($data, false, true);

            $validator->validate();

            $update = $record->update($data);

            if ($update) {
                $tagData['item_id'] = $id;
                $tagData['tag_id']  = (!empty($data['tags'])) ? $data['tags'] : [];

                $this->photos($id, $request->photos, 'update');

                $validator = $itemTagModel::validators($tagData, true);
                if ($validator) {
                    // First remove older tags.
                    $find = $itemTagModel::where('item_id', $id)->delete();
                    /*if (!empty($find) && !$find->isEmpty()) {
                        // self::remove($find);
                    }*/

                    foreach ((array)$tagData as $index => $data) {
                        $tagDatas = [];

                        if (is_array($data)) {
                            foreach ($data as $tag) {
                                $tagDatas['item_id'] = $id;
                                $tagDatas['tag_id']  = $tag;

                                $tagCreate = $itemTagModel->create($tagDatas);
                            }
                        }
                    }
                }

                return redirect('items')->with('success', __("Item updated!"));
            }
        }

        return redirect('items')->with('error', __("There has been an error!"));
    }

    public function destroy(int $id)
    {
        $record = Item::where('id', $id)->get();

        if ($record) {
            DB::beginTransaction();

            $find = ItemTag::where('item_id', $id)->get();
            if (!empty($find) && !$find->isEmpty()) {
                self::remove($find);
            }

            $isRemoved = self::remove($record);

            if ($isRemoved) {
                DB::commit();

                return redirect('items')->with('success', __("Item deleted!"));
            } else {
                DB::rollBack();

                return redirect('items')->with('error', __("There has been an error!"));
            }
        }

        return redirect('items')->with('error', __("Not found!"));
    }

    public function changeQuantity(Request $request, $id)
    {
        $model  = new Item();
        $record = $model::find($id);

        if ($record) {
            $oldQuantity = $record->qty;
            $newQuantity = $request->get('qty');

            $record->qty = $newQuantity;
            $update = $record->save();

            if ($update) {
                return redirect('items')->with('success', __("Item quantity changed from {$oldQuantity} to {$newQuantity}!"));
            }
        }

        return redirect('items')->with('error', __("Not found!"));
    }

    public function moveToFolder(Request $request, $id)
    {
        $data = $request->all();

        $model  = new Item();
        $record = $model->find($id);

        if ($record) {
            $clientId     = (!empty($data['folder'])) ? (int)$data['folder'] : NULL;
            $postedAmount = (!empty($data['amount'])) ? (int)$data['amount'] : 0;
            $origionalQty = $record->qty;

            if (empty($postedAmount)) {
                return redirect('items')->with('error', __("Please enter amount properly!"));
            } elseif ($postedAmount > $origionalQty) {
                return redirect('items')->with('error', __("Entered more amount then actual quantity. Please insert amount properly!"));
            }

            $deductedQty = ($origionalQty - $postedAmount);

            $insert['qty']        = $postedAmount;
            $insert['old_qty']    = $origionalQty;
            $insert['item_id']    = $id;
            $insert['client_id']  = $clientId;
            $insert['created_by'] = auth()->user()->id;

            $validator = ClientItem::validators($insert);

            $validator->validate();

            $create = ClientItem::create($insert);

            if ($create) {
                $record->qty = $deductedQty;
                $record->save();

                $client = Client::find($clientId);

                return redirect('items')->with('success', __("Item {$record->name}, {$postedAmount} quantity moved to {$client->name} folder. Now remains {$deductedQty} quantity!"));
            }
        }

        return redirect('items')->with('error', __("Not found!"));
    }
}
