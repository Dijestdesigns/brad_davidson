<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;

class UserNote extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'note_date', 'notes', 'user_id'
    ];

    protected $casts = [
        'note_date' => 'timestamp',
        'notes'     => 'string',
        'user_id'   => 'integer'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'note_date' => ['required', 'string', 'date_format:Y-m-d'],
            'notes'     => ['nullable', 'string'],
            'user_id'   => ['required', 'integer', 'exists:' . User::getTableName() . ',id']
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
}
