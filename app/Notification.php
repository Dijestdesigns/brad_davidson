<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;

class Notification extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'message', 'href', 'user_id', 'send_by', 'is_read'
    ];

    const READ   = '1';
    const UNREAD = '0';
    public static $isRead = [
        self::READ   => 'Read',
        self::UNREAD => 'Unread'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'title'   => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:255'],
            'href'    => ['nullable', 'string', 'max:255'],
            'user_id' => ['required', 'integer', 'exists:' . User::getTableName() . ',id'],
            'send_by' => ['required', 'integer', 'exists:' . User::getTableName() . ',id'],
            'is_read' => ['in:' . implode(",", array_keys(self::$isRead))]
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function getNotifications()
    {
        $userId = auth()->user()->id;

        $notifications = self::where('user_id', $userId)->where('is_read', self::UNREAD)->with('sendByUser')->orderBy('updated_at', 'DESC')->get();

        $total = $notifications->count();

        return json_encode([
            'datas' => $notifications
        ]);
    }

    public function sendByUser()
    {
        return $this->hasOne('App\User', 'id', 'send_by');
    }
}
