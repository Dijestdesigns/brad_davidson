<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\Client;
use App\Item;

class ClientItem extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'qty', 'old_qty', 'item_id', 'client_id', 'created_by', 'updated_by'
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
            'qty'        => ['required', 'integer'],
            'old_qty'    => ['required', 'integer'],
            'item_id'    => ['required', 'integer', 'exists:' . Item::getTableName() . ',id'],
            'client_id'  => ['required', 'integer', 'exists:' . Client::getTableName() . ',id'],
            'created_by' => $createdBy,
            'updated_by' => $updatedBy
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function item()
    {
        return $this->hasItem('App\Item', 'id', 'item_id');
    }
}
