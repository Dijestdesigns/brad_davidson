<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;

class Tag extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'created_by'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'name'        => ['required', 'string', 'max:255'],
            'created_by'  => ['required', 'integer', 'exists:' . User::getTableName() . ',id']
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
