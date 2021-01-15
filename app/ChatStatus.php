<?php

namespace App;

use Illuminate\Support\Facades\Validator;

class ChatStatus extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_read', 'user_id', 'chat_id'
    ];

    protected $casts = [
        'is_read' => 'enum',
        'user_id' => 'integer',
        'chat_id' => 'integer'
    ];

    const IS_READ     = '1';
    const IS_NOT_READ = '0';

    public static $isRead = [
        self::IS_READ     => '1',
        self::IS_NOT_READ => '0'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'is_read' => ['nullable', 'in:' . implode(",", self::$isRead)],
            'user_id' => ['required', 'integer', 'exists:' . User::getTableName() . ',id'],
            'chat_id' => ['required', 'integer', 'exists:' . User::getTableName() . ',id'],
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

    public function readByUser()
    {
        return $this->hasOne('App\User', 'id', 'chat_id');
    }

    public function chat()
    {
        return $this->hasOne('App\Chat', 'id', 'chat_id');
    }
}
