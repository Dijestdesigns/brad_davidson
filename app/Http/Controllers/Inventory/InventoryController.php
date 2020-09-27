<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Item;
use App\Tag;
use App\ItemTag;
use App\ItemPhoto;
use App\User;
use App\ClientItem;
use App\Log;
use DB;
use Illuminate\Http\UploadedFile;

class InventoryController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:inventories_access'])->only('index');
        $this->middleware(['permission:inventories_create'])->only(['create','store']);
        $this->middleware(['permission:inventories_edit'])->only(['edit','update']);
        $this->middleware(['permission:inventories_change_quantities'])->only(['changeQuantity']);
        $this->middleware(['permission:inventories_edit'])->only(['inventories_move_to_folder']);
        $this->middleware(['permission:inventories_delete'])->only('destroy');
    }

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

                $modelQuery->where($model::getTableName() . '.qty', (int)$q);
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

            if ($request->get('f', false)) {
                $f = $request->get('f');

                $modelQuery->join(ClientItem::getTableName(), function($join) use($f, $model) {
                    $join->on($model::getTableName() . '.id', '=', ClientItem::getTableName() . '.item_id')
                             ->where(ClientItem::getTableName() . '.qty', '>', 0)
                             ->where(ClientItem::getTableName() . '.user_id', (int)$f);
                });

                $modelQuery->groupBy(ClientItem::getTableName() . '.item_id');
            }
        }

        $total   = $modelQuery->count();
        $records = $modelQuery->select($model::getTableName() . '.*')->orderBy('name', 'ASC')->paginate(9);

        // $quantity = $model::all()->pluck('qty', 'qty');
        $levels   = $model::all()->pluck('min_level', 'min_level');
        $tags     = Tag::all();
        $folders  = User::where('id', '!=', User::$superadminId)->get();

        return view('inventory.index', compact('total', 'records', 'request', 'isFiltered', 'tags', 'levels', 'folders'));
    }

    public function create()
    {
        $tags = Tag::all();

        return view('inventory.create', compact('tags'));
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
            $find = $model::find($create->id);
            self::createLog($find, __("Created inventory {$find->name}"), Log::CREATE, [], $find->toArray());

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

            return redirect('inventory')->with('success', __("Inventory created!"));
        }

        return redirect('inventory/create')->with('error', __("There has been an error!"));
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

            return view('inventory.edit', compact('record', 'tags'));
        }

        return redirect('inventory')->with('error', __("Not found!"));
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

            $oldData = $record->toArray();

            $update = $record->update($data);

            if ($update) {
                $find = $model::find($id);
                self::createLog($find, __("Updated inventory {$find->name}"), Log::UPDATE, $oldData, $find->toArray());

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

                return redirect('inventory')->with('success', __("Inventory updated!"));
            }
        }

        return redirect('inventory')->with('error', __("There has been an error!"));
    }

    public function destroy(int $id)
    {
        $record = Item::where('id', $id)->get();

        if (!empty($record[0])) {
            DB::beginTransaction();

            $find = ItemTag::where('item_id', $id)->get();
            if (!empty($find) && !$find->isEmpty()) {
                self::remove($find);
            }

            $isRemoved = self::remove($record);

            if ($isRemoved) {
                self::createLog($record[0], __("Deleted inventory " . $record[0]->name), Log::DELETE, $record[0]->toArray(), []);

                DB::commit();

                return redirect('inventory')->with('success', __("Inventory deleted!"));
            } else {
                DB::rollBack();

                return redirect('inventory')->with('error', __("There has been an error!"));
            }
        }

        return redirect('inventory')->with('error', __("Not found!"));
    }

    public function changeQuantity(Request $request, $id)
    {
        $model  = new Item();
        $record = $model::find($id);

        if ($record) {
            $oldQuantity = $record->qty;
            $newQuantity = $request->get('qty');

            $oldData = $record->toArray();

            $record->qty = $newQuantity;
            $update = $record->save();

            if ($update) {
                $find = $model::find($id);
                self::createLog($find, __("Changed quantity of inventory {$find->name} from {$oldData['qty']} to {$find->qty}"), Log::UPDATE, $oldData, $find->toArray());

                return redirect('inventory')->with('success', __("Inventory quantity changed from {$oldQuantity} to {$newQuantity}!"));
            }
        }

        return redirect('inventory')->with('error', __("Not found!"));
    }

    public function moveToFolder(Request $request, $id)
    {
        $data = $request->all();

        $model  = new Item();
        $record = $model->find($id);

        if ($record) {
            $userId       = (!empty($data['folder'])) ? (int)$data['folder'] : NULL;
            $postedAmount = (!empty($data['amount'])) ? (int)$data['amount'] : 0;
            $origionalQty = $record->qty;

            if (empty($postedAmount)) {
                return redirect('inventory')->with('error', __("Please enter amount properly!"));
            } elseif ($postedAmount > $origionalQty) {
                return redirect('inventory')->with('error', __("Entered more amount then actual quantity. Please insert amount properly!"));
            }

            $deductedQty = ($origionalQty - $postedAmount);

            $insert['qty']        = $postedAmount;
            $insert['old_qty']    = $origionalQty;
            $insert['item_id']    = $id;
            $insert['user_id']    = $userId;
            $insert['created_by'] = auth()->user()->id;

            $validator = ClientItem::validators($insert);

            $validator->validate();

            $create = ClientItem::create($insert);

            if ($create) {
                $oldData = $record->toArray();

                $record->qty = $deductedQty;
                $record->save();

                $newData = $record->toArray();

                $find = User::find($userId);
                self::createLog($find, __("Moved {$postedAmount} inventory quantities of {$record->name} to {$find->name}"), Log::UPDATE, $oldData, $newData);

                return redirect('inventory')->with('success', __("Inventory {$record->name}, {$postedAmount} quantity moved to {$find->name} folder. Now remains {$deductedQty} quantity!"));
            }
        }

        return redirect('inventory')->with('error', __("Not found!"));
    }
}
