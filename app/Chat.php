<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\ChatRoomUser;
use App\ChatStatus;
use Illuminate\Support\Facades\Storage;

class Chat extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'file', 'is_individual', 'chat_room_user_id', 'user_id', 'send_by'
    ];

    protected $casts = [
        'message'            => 'string',
        'file'               => 'file',
        'is_individual'      => 'enum',
        'chat_room_user_id'  => 'integer',
        'user_id'            => 'integer',
        'send_by'            => 'integer'
    ];

    const IS_INDIVIDUAL     = '1';
    const IS_NOT_INDIVIDUAL = '0';

    public static $isIndividual = [
        self::IS_INDIVIDUAL     => '1',
        self::IS_NOT_INDIVIDUAL => '0'
    ];

    public static $fileSystems       = 'public';
    public static $storageFolderName = 'chat\\files';
    public static $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'message'           => ['nullable', 'string', 'max:255'],
            'file'              => ['nullable', 'mimes:' . implode(",", self::$allowedExtensions), 'max:255'],
            'is_individual'     => ['nullable', 'in:' . implode(",", self::$isIndividual)],
            'chat_room_user_id' => ['nullable', 'integer', 'exists:' . ChatRoomUser::getTableName() . ',id'],
            'user_id'           => ['nullable', 'integer', 'exists:' . User::getTableName() . ',id'],
            'send_by'           => ['required', 'integer', 'exists:' . User::getTableName() . ',id'],
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

    public function sentUser()
    {
        return $this->hasOne('App\User', 'id', 'send_by');
    }

    public function chatRoomUser()
    {
        return $this->hasOne('App\ChatRoomUser', 'id', 'chat_room_user_id');
    }

    public function chatRoomUsersExceptMe(int $chatRoomId)
    {
        return ChatRoomUser::where('user_id', '!=', auth()->user()->id)->where('chat_room_id', $chatRoomId)->get();
    }

    public function chatStatus()
    {
        return $this->hasOne('App\ChatStatus', 'chat_id', 'id');
    }

    public function isRead()
    {
        return $this->chatStatus()->where('is_read', ChatStatus::IS_READ)->where('user_id', auth()->user()->id)->exists();
    }

    public function getFileAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        $storageFolderName = (str_ireplace("\\", "/", self::$storageFolderName));
        return Storage::disk(self::$fileSystems)->url($storageFolderName . '/' . $value);
    }
}
