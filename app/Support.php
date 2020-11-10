<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;

class Support extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'query', 'user_id'
    ];

    public static function validators(array $data, $returnBoolsOnly = false, $isUpdate = false)
    {
        $createdBy = ['required', 'integer', 'exists:' . User::getTableName() . ',id'];
        if ($isUpdate) {
            $createdBy = [];
        }

        $validator = Validator::make($data, [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email'],
            'query'   => ['required', 'string'],
            'user_id' => ['required', 'integer', 'exists:' . User::getTableName() . ',id']
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
