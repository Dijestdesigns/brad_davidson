<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\User;
use App\ChatRoom;
use App\ChatRoomUser;
use App\Chat;
use App\ChatStatus;
use App\Log;
use App\Notification;
use DB;
use App\Events\ChatRoomCreated;
use App\Events\NewMessage;
use App\Events\NewMessageIndividual;
use App\Events\Notifications;
use Illuminate\Http\UploadedFile;

class ChatController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        // $this->middleware(['permission:chat_access'])->only('index');
        $this->middleware(['permission:chat_group_create'])->only(['create','store']);
        $this->middleware(['permission:chat_group_edit'])->only(['edit','update']);
        $this->middleware(['permission:chat_group_delete'])->only('destroy');
        $this->middleware(['permission:chat_group_delete_user'])->only('destroyUser');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('chat_access') && !auth()->user()->can('chat_group_access')) {
            abort(403, __('User does not have the right permissions.'));
        }

        $user   = auth()->user();
        $userId = $user->id;

        if (!$user->isSuperAdmin()) {
            $users = User::where('id', User::$superadminId)->get();
        } else {
            $users = User::where('id', '!=', $userId)->get();
        }

        if ($user::getTableName() == 'users' && $user->isSuperAdmin() && $user->hasRole($user::$roleAdmin)) {
            $chatRooms = new ChatRoom();
        } else {
            $chatRooms = ChatRoom::select(ChatRoom::getTableName() . '.*')->join(ChatRoomUser::getTableName(), ChatRoom::getTableName() . '.id', '=', ChatRoomUser::getTableName() . '.chat_room_id')->where(ChatRoomUser::getTableName() . '.user_id', $userId);
        }

        $chatRoomName  = "";
        $chatRoomUsers = [];
        $chatUsers     = [];
        if ($request->get('i', false)) {
            $i = $request->get('i');

            $chatRoomName  = ChatRoom::find((int)$i);
            $chatRoomUsers = ChatRoomUser::where('chat_room_id', (int)$i);

            if (!$user->isSuperAdmin()) {
                $chatRoomUsers->where('user_id', $userId);
            }

            $chatRoomUsers = $chatRoomUsers->get();

            $chatUsers = User::select(User::getTableName() . '.*')
                              ->where(User::getTableName() . '.id', '!=', auth()->user()->id)
                              ->where(User::getTableName() . '.id', '!=', User::$superadminId)
                              ->leftJoin(ChatRoomUser::getTableName(), function($join) use($i) {
                                  $join->on(User::getTableName() . '.id', '=', ChatRoomUser::getTableName() . '.user_id')
                                       ->where(ChatRoomUser::getTableName() . '.chat_room_id', $i);
                              })
                              ->whereNull(ChatRoomUser::getTableName() . '.user_id')
                              ->get();
        }

        if ($request->get('s', false)) {
            $s = $request->get('s');

            $chatRooms = $chatRooms->where(function($where) use($s) {
                $where->where('name', 'LIKE', "%$s%")
                      ->orWhere('descriptions', 'LIKE', "%$s%");
            });
        }

        $chatRooms = $chatRooms->get();

        return view('chat.index', compact('request', 'users', 'chatRooms', 'chatRoomUsers', 'chatRoomName', 'chatUsers'));
    }

    public function individual(int $userId)
    {
        if (!auth()->user()->can('chat_access')) {
            abort(403, __('User does not have the right permissions.'));
        }

        $myUserId      = auth()->user()->id;
        $user          = User::find($userId);

        $chatMessages  = Chat::where(Chat::getTableName() . '.is_individual', Chat::IS_INDIVIDUAL)
                             ->where(function($query) use($myUserId, $userId) {
                                $query->orWhere(function($query) use($myUserId, $userId) {
                                            $query->where(Chat::getTableName() . '.send_by', $myUserId)
                                                  ->where(Chat::getTableName() . '.user_id', $userId);
                                      })
                                      ->orWhere(function($query) use($myUserId, $userId) {
                                            $query->where(Chat::getTableName() . '.send_by', $userId)
                                                  ->where(Chat::getTableName() . '.user_id', $myUserId);
                                      });
                             })
                             ->with('user')
                             ->orderBy(Chat::getTableName() . '.created_at', 'ASC')
                             ->get();

        if (!empty($chatMessages) && !$chatMessages->isEmpty()) {
            foreach ($chatMessages as $chatMessage) {
                $this->markAsRead($chatMessage->id);
            }
        }

        return view('chat.individual', compact('user', 'chatMessages', 'myUserId'));
    }

    public function group(int $groupId, Request $request)
    {
        $chatRoomName  = ChatRoom::find((int)$groupId);

        if (!auth()->user()->can('chat_group_access')) {
            abort(403, __('User does not have the right permissions.'));
        }

        if (empty($chatRoomName)) {
            abort(403, __('Lobby doesn\'t exists!'));
        }

        $chatRoomUsers = ChatRoomUser::where('chat_room_id', (int)$groupId)->where('user_id', '!=', auth()->user()->id)->get();
        $chatMessages  = Chat::select(Chat::getTableName() . '.*', ChatRoomUser::getTableName() . '.user_id')
                             ->join(ChatRoomUser::getTableName(), Chat::getTableName() . '.chat_room_user_id', '=', ChatRoomUser::getTableName() . '.id')
                             ->where(ChatRoomUser::getTableName() . '.chat_room_id', (int)$groupId)
                             ->where(Chat::getTableName() . '.is_individual', Chat::IS_NOT_INDIVIDUAL)
                             ->orderBy(Chat::getTableName() . '.created_at', 'ASC')
                             ->with('user')
                             ->get();

        $chatUsers     = User::select(User::getTableName() . '.*')
                             ->where(User::getTableName() . '.id', '!=', auth()->user()->id)
                             ->where(User::getTableName() . '.id', '!=', User::$superadminId)
                             ->leftJoin(ChatRoomUser::getTableName(), function($join) use($groupId) {
                                 $join->on(User::getTableName() . '.id', '=', ChatRoomUser::getTableName() . '.user_id')
                                      ->where(ChatRoomUser::getTableName() . '.chat_room_id', $groupId);
                             })
                             ->whereNull(ChatRoomUser::getTableName() . '.user_id')
                             ->get();


        if (!empty($chatMessages) && !$chatMessages->isEmpty()) {
            foreach ($chatMessages as $chatMessage) {
                $this->markAsRead($chatMessage->id);
            }
        }

        return view('chat.group', compact('groupId', 'chatRoomName', 'chatRoomUsers', 'chatMessages', 'chatUsers', 'request'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (!empty($data['is_create'])) {
            if ($data['is_create'] == 'lobby') {
                if (!auth()->user()->can('chat_group_create')) {
                    abort(403, __('User does not have the right permissions.'));
                }

                $data['created_by'] = auth()->user()->id;
                $model              = new ChatRoom();

                $validator = $model::validators($data, true);

                if ($validator) {
                    $create = $model::create($data);

                    if ($create) {
                        $find = $model::find($create->id);
                        self::createLog($find, __("Created chat lobby {$find->name}"), Log::CREATE, [], $find->toArray());

                        // $users = collect(request('users'));
                        // $users->push(auth()->user()->id);
                        // $create->users()->attach($users);
                        // broadcast(new ChatRoomCreated($create))->toOthers();

                        return redirect('chat')->with('success', __("Chat lobby created!"));
                    }
                } elseif (!empty(session('error'))) {
                    return redirect('chat')->with('error', session('error'));
                }
            } elseif ($data['is_create'] == 'user') {
                if (!auth()->user()->can('chat_group_add_user')) {
                    abort(403, __('User does not have the right permissions.'));
                }

                $model    = new ChatRoomUser();
                $tempDate = $data;

                $data = [];

                $data['chat_room_id'] = $tempDate['chat_room_id'];
                $data['user_id']      = $tempDate['user_id'];

                $validator = $model::validators($data, true);

                if ($validator) {
                    $create = $model::create($data);

                    if ($create) {
                        $find = $model::find($create->id);
                        self::createLog($find, __("Added lobby {$create->chatRoom->name} user {$find->name}"), Log::CREATE, [], $find->toArray());

                        if (!$model->checkCreatorExists($data['chat_room_id'])) {
                            $users = auth()->user()->id;
                            $create->chatRoom->users()->attach($users);
                        }

                        broadcast(new ChatRoomCreated($create->chatRoom))->toOthers();

                        return redirect('chat/'.$data['chat_room_id'].'/group')->with('success', __("Chat user created!"));
                    }
                }
            }
        }

        return redirect('chat')->with('error', __("There has been an error!"));
    }

    public function update(Request $request, int $id)
    {
        $model  = new ChatRoom();
        $record = $model::find($id);

        if ($record) {
            $data = $request->all();

            $validator = $model::validators($data, true, true);

            $tempDate  = $data;

            $data      = [];

            $data['name']         = $tempDate['name'];
            $data['descriptions'] = $tempDate['descriptions'];

            if ($validator) {
                $oldData = $record->toArray();

                $update = $record->update($data);

                if ($update) {
                    $find = $model::find($id);
                    self::createLog($find, __("Updated chat lobby {$find->name}"), Log::UPDATE, $oldData, $find->toArray());

                    return redirect('chat')->with('success', __("Chat lobby updated!"));
                }
            } elseif (!empty(session('error'))) {
                return redirect('chat')->with('error', session('error'));
            }
        }

        return redirect('chat')->with('error', __("There has been an error!"));
    }

    public function destroy(int $id)
    {
        $record = ChatRoom::where('id', $id)->get();

        if (!empty($record[0])) {
            DB::beginTransaction();

            $find = ChatRoomUser::where('chat_room_id', $id)->get();
            if (!empty($find) && !$find->isEmpty()) {
                foreach ($find as $chatRoomUser) {
                    if (!empty($chatRoomUser->chats)) {
                        self::remove($chatRoomUser->chats);
                    }
                }

                self::remove($find);
            }

            $isRemoved = self::remove($record);

            if ($isRemoved) {
                self::createLog($record[0], __("Deleted lobby " . $record[0]->name), Log::DELETE, $record[0]->toArray(), []);

                DB::commit();

                return redirect('chat')->with('success', __("Chat lobby deleted!"));
            } else {
                DB::rollBack();

                return redirect('chat')->with('error', __("There has been an error!"));
            }
        }

        return redirect('chat')->with('error', __("Not found!"));
    }

    public function destroyUser(int $chatRoomId, Request $request)
    {
        $record = ChatRoomUser::where('id', $chatRoomId)->get();
        $s      = $request->get('s', '');
        $i      = $request->get('i', '');

        if (!empty($record[0]) && !empty($record[0]->chatRoom)) {
            DB::beginTransaction();

            $isRemoved = self::remove($record);

            if ($isRemoved) {
                $findUser = User::find($record[0]->user_id);

                if (!empty($findUser)) {
                    self::createLog($record[0], __("Deleted lobby user {$findUser->fullname} from " . $record[0]->chatRoom->name . ' lobby.'), Log::DELETE, $record[0]->toArray(), []);
                } else {
                    self::createLog($record[0], __("Deleted lobby user from " . $record[0]->chatRoom->name . ' lobby.'), Log::DELETE, $record[0]->toArray(), []);
                }

                DB::commit();

                return redirect('chat?i=' . $i . '&s=' . $s)->with('success', __("Chat lobby user deleted!"));
            } else {
                DB::rollBack();

                return redirect('chat?i=' . $i . '&s=' . $s)->with('error', __("There has been an error!"));
            }
        }

        return redirect('chat?i=' . $i . '&s=' . $s)->with('error', __("Not found!"));
    }

    public function groupPost(Request $request)
    {
        $model      = new Chat();
        $data       = $request->all();
        $userId     = auth()->user()->id;
        $chatRoomId = !empty($data['chat_room_id']) ? (int)$data['chat_room_id'] : NULL;

        $chatRoomUserId = NULL;
        if (!empty($chatRoomId)) {
            $chatRoomUserId = ChatRoomUser::where('chat_room_id', $chatRoomId)->where('user_id', $userId)->first();
            $chatRoomUserId = !empty($chatRoomUserId) ? $chatRoomUserId->id : NULL;
        }

        if (!empty($chatRoomUserId)) {
            $create = [
                'message'           => !empty($data['message']) ? $data['message'] : NULL,
                'file'              => (!empty($data['file']) && $data['file'] instanceof UploadedFile) ? $data['file'] : NULL,
                'is_individual'     => Chat::IS_NOT_INDIVIDUAL,
                'chat_room_user_id' => $chatRoomUserId,
                'send_by'           => $userId
            ];

            $validator = $model::validators($create, true);

            if ($validator) {
                $chat = $model::create($create);

                if ($chat) {
                    if (!empty($data['file']) && $data['file'] instanceof UploadedFile) {
                        $file      = $data['file'];
                        $fileName  = time() . '_' . $chat->id . '.' . $file->getClientOriginalExtension();
                        $moveFiles = $file->storeAs($model::$storageFolderName, $fileName, $model::$fileSystems);

                        if ($moveFiles) {
                            $model::where('id', $chat->id)->update(['file' => $fileName]);
                        }
                    }

                    $chatRoomUsers = $chat->chatRoomUsersExceptMe($chatRoomId);

                    if (!empty($chatRoomUsers) && !$chatRoomUsers->isEmpty()) {
                        foreach ($chatRoomUsers as $chatRoomUser) {
                            ChatStatus::create([
                                'user_id' => $chatRoomUser->user_id,
                                'chat_id' => $chat->id
                            ]);
                        }
                    }

                    broadcast(new NewMessage($chat))->toOthers();

                    $chat = $model::select(Chat::getTableName() . '.id as chat_id', Chat::getTableName() . '.message', Chat::getTableName() . '.file', Chat::getTableName() . '.created_at', ChatRoomUser::getTableName() . '.*')
                                ->where(Chat::getTableName() . '.id', $chat->id)
                                ->join(ChatRoomUser::getTableName(), Chat::getTableName() . '.chat_room_user_id', '=', ChatRoomUser::getTableName() . '.id')
                                ->with('user')->first();

                    return $chat;
                }
            }
        }

        return false;
    }

    public function individualPost(Request $request)
    {
        $model      = new Chat();
        $data       = $request->all();
        $userId     = auth()->user()->id;
        $withUserId = !empty($data['user_id']) ? $data['user_id'] : NULL;

        $create = [
            'message'       => !empty($data['message']) ? $data['message'] : NULL,
            'file'          => (!empty($data['file']) && $data['file'] instanceof UploadedFile) ? $data['file'] : NULL,
            'is_individual' => Chat::IS_INDIVIDUAL,
            'user_id'       => $withUserId,
            'send_by'       => $userId
        ];

        $validator = $model::validators($create, true);

        if ($validator) {
            $chat = $model::create($create);

            if ($chat) {
                if (!empty($data['file']) && $data['file'] instanceof UploadedFile) {
                    $file      = $data['file'];
                    $fileName  = time() . '_' . $chat->id . '.' . $file->getClientOriginalExtension();
                    $moveFiles = $file->storeAs($model::$storageFolderName, $fileName, $model::$fileSystems);

                    if ($moveFiles) {
                        $model::where('id', $chat->id)->update(['file' => $fileName]);
                    }
                }

                ChatStatus::create([
                    'user_id' => $withUserId,
                    'chat_id' => $chat->id
                ]);

                broadcast(new NewMessageIndividual($chat))->toOthers();

                // Notification of dashboard.
                if (isset($chat->user->is_online) && !$chat->user->is_online) {
                    $create = Notification::create([
                        'title'   => auth()->user()->name . ' ' . auth()->user()->surname,
                        'message' => !empty($data['message']) ? $data['message'] : NULL,
                        'href'    => route('chat.individual', $userId),
                        'send_by' => $userId,
                        'user_id' => $withUserId
                    ]);

                    // $notification = Notification::where('user_id', $withUserId)->where('is_read', Notification::UNREAD)->with('sendByUser')->get();
                    broadcast(new Notifications($create))->toOthers();
                }

                $chat = $model::select(Chat::getTableName() . '.*', Chat::getTableName() . '.id as chat_id')->where(Chat::getTableName() . '.id', $chat->id)->with('sentUser')->first();

                if (!empty($chat)) {
                    $user = !empty($chat->sentUser) ? $chat->sentUser : [];

                    $chat->user = $user;
                }

                return $chat;
            }
        }

        return false;
    }

    public function markAsRead(int $chatId)
    {
        $userId = auth()->user()->id;

        if (!empty($chatId)) {
            return ChatStatus::where('user_id', $userId)->where('chat_id', $chatId)->update(['is_read' => ChatStatus::IS_READ]);
        }

        return false;
    }

    public function setOnline(int $userId)
    {
        $user = User::find($userId);

        $user->is_online = User::ONLINE;

        return $user->save();
    }

    public function setOffline(int $userId)
    {
        $user = User::find($userId);

        $user->is_online = User::OFFLINE;

        return $user->save();
    }
}
