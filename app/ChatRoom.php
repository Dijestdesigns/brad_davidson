<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\ChatRoomUser;
use App\Chat;

class ChatRoom extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'descriptions', 'created_by'
    ];

    protected $casts = [
        'name'         => 'string',
        'descriptions' => 'string',
        'created_by'   => 'integer'
    ];

    public static function validators(array $data, $returnBoolsOnly = false, $isUpdate = false)
    {
        $createdBy = ['required'];

        if ($isUpdate) {
            $createdBy = ['nullable'];
        }

        $validator = Validator::make($data, [
            'name'         => ['required', 'string', 'max:255'],
            'descriptions' => ['nullable', 'string'],
            'created_by'   => array_merge(['integer', 'exists:' . User::getTableName() . ',id'], $createdBy)
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function users()
    {
        // return $this->hasOne('App\User', 'id', 'created_by');
        return $this->belongsToMany(User::class, ChatRoomUser::class)->withTimestamps();
    }

    public function roomUsers()
    {
        // return $this->belongsToMany('App\ChatRoom', 'App\ChatRoomUser');
        return $this->hasMany('App\ChatRoomUser', 'chat_room_id', 'id');
    }

    public function roomUser(int $userId)
    {
        return $this->hasOne('App\ChatRoomUser', 'chat_room_id', 'id')->where('user_id', $userId);
    }

    public function getTotalUsers()
    {
        return $this->roomUsers()->count();
    }

    public function getTimeAgo($default = NULL)
    {
        $timeAgo = $default;

        if (!empty($this)) {
            $latestActivity = ChatRoomUser::where('chat_room_id', $this->id)
                                          ->join(Chat::getTableName(), ChatRoomUser::getTableName() . '.id', '=', Chat::getTableName() . '.chat_room_user_id')
                                          ->orderBy(Chat::getTableName() . '.created_at', 'DESC')
                                          ->first();

            if (!empty($latestActivity)) {
                $timeAgo = $latestActivity->created_at->diffForHumans();
            }
        }

        return $timeAgo;
    }

    public function getUnread($default = 0)
    {
        $user   = auth()->user();
        $userId = $user->id;
        $count  = $default;

        $roomUsers = $this->roomUsers;

        if (!empty($roomUsers) && !$roomUsers->isEmpty()) {
            foreach ($roomUsers as $roomUser) {
                if ($roomUser->user_id == $userId) {
                    continue;
                }

                $chats = $roomUser->chats;

                if (!empty($chats) && !$chats->isEmpty()) {
                    foreach ($chats as $chat) {
                        if (!$chat->isRead()) {
                            $count++;
                        }
                    }
                }
            }
        }

        return $count;
    }

    public function hasUser($userId)
    {
        if (empty($this->users)) {
            return false;
        }

        foreach ($this->users as $user) {
            if($user->id == $userId) {
                return true;
            }
        }

        return false;
    }
}
