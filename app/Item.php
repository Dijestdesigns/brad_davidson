<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\Tag;

class Item extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'qty', 'min_level', 'price', 'value', 'notes', 'created_by'
    ];

    public static function validators(array $data, $returnBoolsOnly = false, $isUpdate = false)
    {
        $createdBy = ['required', 'integer', 'exists:' . User::getTableName() . ',id'];
        if ($isUpdate) {
            $createdBy = [];
        }

        $validator = Validator::make($data, [
            'name'       => ['required', 'string', 'max:255'],
            'qty'        => ['required', 'integer'],
            'min_level'  => ['required', 'integer'],
            'price'      => ['required', 'between:0,99.99'],
            'value'      => ['required', 'between:0,99.99'],
            'notes'      => ['nullable'],
            'created_by' => $createdBy,
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

    public function tags()
    {
        return $this->hasMany('App\ItemTag', 'item_id', 'id');
    }

    public function photo()
    {
        return $this->hasOne('App\ItemPhoto', 'item_id', 'id');
    }


    public function photos()
    {
        return $this->hasMany('App\ItemPhoto', 'item_id', 'id');
    }
}
