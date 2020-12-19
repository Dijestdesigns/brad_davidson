<?php

namespace App\Http\Controllers\Clients;

use Illuminate\Http\Request;
use App\User;
use App\Tag;
use App\ClientTag;
use App\ClientPhoto;
use App\ClientItem;
use App\Log;
use App\Role;
use App\UserNote;
use App\Note;
use DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClientsController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:clients_access'])->only('index');
        $this->middleware(['permission:clients_create'])->only(['create','store']);
        $this->middleware(['permission:clients_show'])->only('show');
        $this->middleware(['permission:clients_edit'])->only(['edit','update']);
        $this->middleware(['permission:clients_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        $model          = new User();
        $isFiltered     = false;
        // $total          = $model::count();
        $modelQuery     = $model::query();
        $requestClonned = clone $request;

        $cleanup = $requestClonned->except(['page']);
        $requestClonned->query = new \Symfony\Component\HttpFoundation\ParameterBag($cleanup);

        $userId = auth()->user()->id;

        if (count($requestClonned->all()) > 0) {
            $isFiltered = (!empty(array_filter($requestClonned->all())));
        }

        if ($isFiltered || $request->get('c') === "0") {
            if ($request->get('s', false)) {
                $s = $request->get('s');

                $modelQuery->where(function($query) use($s, $model) {
                    $query->where($model::getTableName() . '.name', 'LIKE', "%$s%")
                          ->orWhere($model::getTableName() . '.surname','LIKE', "%$s%")
                          ->orWhereRaw('CONCAT(' . $model::getTableName() . '.name, " ", ' . $model::getTableName(). '.surname) LIKE "%' . $s . '%"');
                });
            }

            if ($request->get('t', false)) {
                $t = $request->get('t');

                $modelQuery->join(ClientTag::getTableName(), function($join) use($t, $model) {
                    $join->on($model::getTableName() . '.id', '=', CLientTag::getTableName() . '.user_id')
                             ->where(ClientTag::getTableName() . '.tag_id', (int)$t);
                });
            }

            if ($request->get('c', false) || $request->get('c') === 0) {
                $c = $request->get('c');

                $modelQuery->where('category', $c);
            }
        }

        $modelQuery->leftJoin(ClientItem::getTableName(), $model::getTableName() . '.id', '=', ClientItem::getTableName() . '.user_id');
        $modelQuery->where($model::getTableName() . '.id', '!=', $model::$superadminId);
        $modelQuery->where($model::getTableName() . '.id', '!=', $userId);
        $modelQuery->groupBy($model::getTableName() . '.id');
        $modelQuery->select(DB::raw($model::getTableName() . ".*, SUM(" . ClientItem::getTableName() . '.qty) as qty'));

        $total   = $modelQuery->get()->count();
        $records = $modelQuery->orderBy('name', 'ASC')->paginate($model::PAGINATE_RECORDS);

        $tags       = Tag::all();
        $categories = User::$categories;

        return view('clients.index', compact('total', 'records', 'request', 'isFiltered', 'tags', 'categories'));
    }

    public function create()
    {
        $tags       = Tag::all();
        $categories = User::$categories;
        $roles      = Role::orderBy('id', 'ASC')->get();

        return view('clients.create', compact('tags', 'categories', 'roles'));
    }

    public function addNotes(Request $request, int $id)
    {
        $isCreate = false;
        $model    = new UserNote();
        $data     = $request->all();

        if (!empty($data['note_dates'])) {
            $create = [];

            foreach ($data['note_dates'] as $index => $noteDate) {
                if (empty($noteDate) || strtotime($noteDate) <= 0) {
                    continue;
                }

                $createData = [
                    'note_date' => $noteDate,
                    'notes'     => !empty($data['notes'][$index]) ? $data['notes'][$index] : NULL,
                    'user_id'   => $id
                ];

                $validator = $model::validators($createData, true);

                if ($validator) {
                    $create[$index] = $createData;
                }
            }

            if (!empty($create)) {
                $isCreate = $model::insert($create);
            }
        }

        return $isCreate;
    }

    public function updateNotes(Request $request, int $id)
    {
        $isUpdate = false;
        $model    = new UserNote();
        $data     = $request->all();

        if (!empty($data['note_dates'])) {
            $create = [];

            $model::where('user_id', $id)->delete();

            foreach ($data['note_dates'] as $index => $noteDate) {
                if (empty($noteDate) || strtotime($noteDate) <= 0) {
                    continue;
                }

                $createData = [
                    'note_date' => $noteDate,
                    'notes'     => !empty($data['notes'][$index]) ? $data['notes'][$index] : NULL,
                    'user_id'   => $id
                ];

                $validator = $model::validators($createData, true);

                if ($validator) {
                    $create[$index] = $createData;
                }
            }

            if (!empty($create)) {
                $isUpdate = $model::insert($create);
            }
        }

        return $isUpdate;
    }

    public function store(Request $request)
    {
        $data               = $request->all();
        $data['created_by'] = auth()->user()->id;
        $model              = new User();
        $clientTagModel     = new ClientTag();

        $validator = $model::validators($data);

        $validator->validate();

        $data['password'] = (!empty($data['password'])) ? Hash::make($data['password']) : '';

        if (isset($data['profile_photo_icon'])) {
            $profilePhotoIcon = $data['profile_photo_icon'];
            unset($data['profile_photo_icon']);
        }

        $create = $model::create($data);

        if ($create) {
            if (!empty($request->profile_photo)) {
                $this->profilePhoto($create->id, $request->profile_photo, $profilePhotoIcon);
            }

            $find = $model::find($create->id);
            self::createLog($find, __("Created client {$find->name}"), Log::CREATE, [], $find->toArray());

            $this->addNotes($request, $create->id);

            // Assign role
            if (!empty($data['role_id'])) {
                $role = Role::find($data['role_id']);
                if ($role) {
                    $create->assignRole($role);
                }
            }

            $tagData['user_id'] = $create->id;
            $tagData['tag_id']  = (!empty($data['tags'])) ? $data['tags'] : [];

            $this->photos($create->id, $request->photos, 'create');

            $validator = $clientTagModel::validators($tagData, true);
            if ($validator) {
                foreach ((array)$tagData as $index => $data) {
                    $tagDatas = [];

                    if (is_array($data)) {
                        foreach ($data as $tag) {
                            $tagDatas['user_id'] = $create->id;
                            $tagDatas['tag_id']  = $tag;

                            $tagCreate = $clientTagModel->create($tagDatas);
                        }
                    }
                }
            }

            return redirect('clients')->with('success', __("Folder created!"));
        }

        return redirect('clients/create')->with('error', __("There has been an error!"));
    }

    public function photos($id, $datas, $flag = 'create')
    {
        $create = false;

        if (!empty($datas)) {
            if ($flag == 'update') {
                ClientPhoto::where('user_id', $id)->delete();
            }

            foreach ($datas as $data) {
                if ($data instanceof UploadedFile) {
                    $check['photo']   = $data;
                    $check['user_id'] = $id;

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

    public function profilePhoto(int $id, UploadedFile $profilePhoto, $profilePhotoIcon = NULL)
    {
        $create = false;

        if (!empty($profilePhoto)) {
            $iconName = NULL;

            if ($profilePhoto instanceof UploadedFile) {
                $imageName = time() . '_' . $id . '.' . $profilePhoto->getClientOriginalExtension();
                $moveFiles = $profilePhoto->storeAs(User::$storageParentFolderName . "/{$id}/" . User::$storageFolderName, $imageName, User::$fileSystems);

                if ($moveFiles) {
                    // Store Icon.
                    if (!empty($profilePhotoIcon)) {
                        $extensions = !empty(explode('/', mime_content_type($profilePhotoIcon))[1]) ? explode('/', mime_content_type($profilePhotoIcon))[1] : false;

                        if (!empty($extensions) && in_array($extensions, User::$allowedExtensions)) {
                            $icon              = substr($profilePhotoIcon, strpos($profilePhotoIcon, ',') + 1);
                            $profilePhotoIcon  = str_replace(' ', '+', $profilePhotoIcon);
                            $profilePhotoIcon  = base64_decode($icon);

                            if ($profilePhotoIcon) {
                                $iconName      = time() . '_' . $id . '.' . $extensions;
                                $moveFilesIcon = Storage::disk(User::$fileSystems)->put(User::$storageParentFolderName . "/{$id}/" . User::$storageFolderNameIcon . "/" . $iconName, $profilePhotoIcon);
                            }
                        }
                    } else {
                        // Compress image code.
                    }

                    $model = User::find($id);

                    if ($model) {
                        $model->profile_photo      = $imageName;
                        $model->profile_photo_icon = $iconName;

                        $create = $model->save();
                    }
                }
            }
        }

        return $create;
    }

    public function edit(int $id)
    {
        $record = User::find($id);

        if ($record) {
            $tags       = Tag::all();
            $categories = User::$categories;
            $roles      = Role::orderBy('id', 'ASC')->get();

            return view('clients.edit', compact('record', 'tags', 'categories', 'roles'));
        }

        return redirect('clients')->with('error', __("Not found!"));
    }

    public function update(Request $request, int $id)
    {
        $model  = new User();
        $record = $model::find($id);

        if ($record) {
            if ($record->id == $model::$superadminId) {
                return redirect('clients')->with('error', __("You can't change Superadmin!"));
            }

            $data               = $request->all();
            $data['updated_by'] = auth()->user()->id;
            $clientTagModel     = new ClientTag();

            if (empty($data['password'])) {
                unset($data['password']);
            }

            $validator = $model::validators($data, false, true, $record);

            $validator->validate();

            $oldData = $record->toArray();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            if (isset($data['profile_photo_icon'])) {
                $profilePhotoIcon = $data['profile_photo_icon'];
                unset($data['profile_photo_icon']);
            }

            $update = $record->update($data);

            if ($update) {
                if (!empty($request->profile_photo)) {
                    $this->profilePhoto($id, $request->profile_photo, $profilePhotoIcon);
                }

                $find = $model::find($id);
                self::createLog($find, __("Updated client {$find->name}"), Log::UPDATE, $oldData, $find->toArray());

                $this->updateNotes($request, $id);

                $tagData['user_id'] = $id;
                $tagData['tag_id']  = (!empty($data['tags'])) ? $data['tags'] : [];

                $this->photos($id, $request->photos, 'update');

                // Update role
                if (!$record->isSuperAdmin()) {
                    if (isset($data['role_id']) && $data['role_id'] != "") {
                        $role = Role::find($data['role_id']);

                        // Check if the posted role_id same with client's current role
                        // if not revoke the old role and assign a new one
                        if ($role && !$record->hasRole($role)) {

                            // Check if the user has any role
                            if (!$record->hasAnyRole(Role::all())) {
                                $record->assignRole($role);
                            } else {
                                $currentRole = $record->getRoleNames()[0];
                                $record->removeRole($currentRole);
                                $record->assignRole($role);
                            } 
                        }
                    }
                }

                $validator = $clientTagModel::validators($tagData, true);
                if ($validator) {
                    // First remove older tags.
                    $find = $clientTagModel::where('user_id', $id)->delete();
                    /*if (!empty($find) && !$find->isEmpty()) {
                        // self::remove($find);
                    }*/

                    foreach ((array)$tagData as $index => $data) {
                        $tagDatas = [];

                        if (is_array($data)) {
                            foreach ($data as $tag) {
                                $tagDatas['user_id'] = $id;
                                $tagDatas['tag_id']  = $tag;

                                $tagCreate = $clientTagModel->create($tagDatas);
                            }
                        }
                    }
                }

                return redirect('clients')->with('success', __("Folder updated!"));
            }
        }

        return redirect('clients')->with('error', __("There has been an error!"));
    }

    public function destroy(int $id)
    {
        $record = User::where('id', $id)->get();

        if (!empty($record[0])) {
            DB::beginTransaction();

            $find = ClientTag::where('user_id', $id)->get();
            if (!empty($find) && !$find->isEmpty()) {
                self::remove($find);
            }

            $isRemoved = self::remove($record);

            if ($isRemoved) {
                self::createLog($record[0], __("Deleted client " . $record[0]->name), Log::DELETE, $record[0]->toArray(), []);

                DB::commit();

                return redirect('clients')->with('success', __("Folder deleted!"));
            } else {
                DB::rollBack();

                return redirect('clients')->with('error', __("There has been an error!"));
            }
        }

        return redirect('clients')->with('error', __("Not found!"));
    }

    public function show($id)
    {
        $record = User::find($id);

        if ($record) {
            $permissions = app('App\Http\Controllers\Roles\RoleController')->getPermissionsByGroup();

            return view('clients.show', ['client' => $record, 'groups' => $permissions]);
        }

        return redirect('clients')->with('error', __("Client not found!"));
    }

    public function myProfile()
    {
        $model  = new User();
        $userId = auth()->user()->id;

        $record = auth()->user();

        $roleNames  = "";
        $totalNotes = 0;
        $tagNames   = [];
        $category   = "";
        if ($record) {
            $roleNames = $record->getRoleNames();

            if (!empty($roleNames)) {
                $roleNames = implode(", ", $roleNames->toArray());
            }

            $totalNotes = Note::where('user_id', $userId)->count();

            $tags = ClientTag::where('user_id', $userId)->get();

            if (!empty($tags) && !$tags->isEmpty()) {
                $tags->map(function($tag) use(&$tagNames) {
                    $tagNames[] = $tag->tag->name;
                });
            }

            $category = (!empty($record->category) && !empty($model::$categories[$record->category])) ? $model::$categories[$record->category] : __('None');
        }

        if (!empty($tagNames)) {
            $tagNames = implode(", ", $tagNames);
        } else {
            $tagNames = "-";
        }

        return view('clients.profile', compact('record', 'roleNames', 'totalNotes', 'tagNames', 'category'));
    }

    public function updateProfile(Request $request)
    {
        $user   = auth()->user();
        $id     = $user->id;
        $model  = new User();

        if ($user) {
            $data               = $request->all();
            $data['updated_by'] = $id;
            $data['email']      = $user->email;

            if (empty($data['password'])) {
                unset($data['password']);
            }

            if (!empty($data['profile_photo_icon'])) {
                $profilePhotoIcon = $data['profile_photo_icon'];
                unset($data['profile_photo_icon']);
            }

            $validator = $model::validators($data, false, true, $user);

            $validator->validate();

            $oldData = $user->toArray();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $update = $user->update($data);

            if ($update) {
                if (!empty($request->profile_photo)) {
                    $this->profilePhoto($id, $request->profile_photo, $profilePhotoIcon);
                }

                $find = $model::find($id);
                self::createLog($find, __("Updated client {$find->name}"), Log::UPDATE, $oldData, $find->toArray());

                return redirect('clients/me#')->with('success', __("Profile updated!"));
            }
        }

        return redirect('clients/me#')->with('error', __("There has been an error!"));
    }
}
