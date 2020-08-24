<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\Item;
use App\Tag;

class ItemTag extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id', 'tag_id'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'item_id'  => ['required', 'integer', 'exists:' . Item::getTableName() . ',id'],
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
}
