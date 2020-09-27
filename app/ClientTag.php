<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\Tag;

class ClientTag extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'tag_id'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'user_id'  => ['required', 'integer', 'exists:' . User::getTableName() . ',id'],
            'tag_id.*' => ['required', 'integer', 'exists:' . Tag::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function tags()
    {
        return $this->hasMany('App\Tag', 'id', 'tag_id');
    }

    public function tag()
    {
        return $this->hasOne('App\Tag', 'id', 'tag_id');
    }
}
