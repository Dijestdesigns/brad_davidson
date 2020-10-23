<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\ChatRoom;

class ChatRoomUser extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_room_id', 'user_id'
    ];

    protected $casts = [
        'chat_room_id'  => 'integer',
        'user_id'       => 'integer'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'chat_room_id' => ['required', 'integer', 'exists:' . ChatRoom::getTableName() . ',id'],
            'user_id'      => ['required', 'integer', 'exists:' . User::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function chatRoom()
    {
        return $this->hasOne('App\ChatRoom', 'id', 'chat_room_id');
    }

    public function chats()
    {
        return $this->hasMany('App\Chat', 'chat_room_user_id', 'id');
    }

    public function checkCreatorExists(int $chatRoomId)
    {
        return $this->where('user_id', auth()->user()->id)->where('chat_room_id', $chatRoomId)->exists();
    }
}
