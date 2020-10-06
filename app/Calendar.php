<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;

class Calendar extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'start_date', 'end_date', 'repeats', 'color', 'user_id'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'name'       => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'end_date'   => ['nullable', 'date_format:Y-m-d H:i:s'],
            'repeats'    => ['nullable', 'integer'],
            'color'      => ['nullable', 'string', 'max:255'],
            'user_id'    => ['required', 'integer', 'exists:' . User::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }
}
