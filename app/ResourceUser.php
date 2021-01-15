<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\Resource;

class ResourceUser extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'resource_id'
    ];

    protected $casts = [
        'user_id'     => 'integer',
        'resource_id' => 'integer'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'user_id'     => ['required', 'integer', 'exists:' . User::getTableName() . ',id'],
            'resource_id' => ['required', 'integer', 'exists:' . Resource::getTableName() . ',id']
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
