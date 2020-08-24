<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\Tag;

class Client extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'notes', 'created_by', 'updated_by'
    ];

    public static function validators(array $data, $returnBoolsOnly = false, $isUpdate = false)
    {
        $createdBy = ['required', 'integer', 'exists:' . User::getTableName() . ',id'];
        $updatedBy = [];
        if ($isUpdate) {
            $createdBy = [];
            $updatedBy = ['required', 'integer', 'exists:' . User::getTableName() . ',id'];
        }

        $validator = Validator::make($data, [
            'name'       => ['required', 'string', 'max:255'],
            'notes'      => ['nullable'],
            'created_by' => $createdBy,
            'updated_by' => $updatedBy,
            'tags.*'     => ['required', 'integer', 'exists:' . Tag::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function userCreatedBy()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public function clientTags()
    {
        return $this->hasMany('App\ClientTag', 'client_id', 'id');
    }

    public function photo()
    {
        return $this->hasOne('App\ClientPhoto', 'client_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany('App\ClientPhoto', 'client_id', 'id');
    }
}
