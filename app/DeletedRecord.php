<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;

class DeletedRecord extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data', 'model_name', 'deleted_by'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'data'        => ['required', 'string'],
            'model_name'  => ['required', 'string', 'max:255'],
            'deleted_by'  => ['required', 'integer', 'exists:' . User::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function userDeletedBy()
    {
        return $this->hasOne('App\User', 'id', 'deleted_by');
    }
}
