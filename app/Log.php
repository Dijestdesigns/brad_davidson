<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;

class Log extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model', 'model_id', 'message', 'old_data', 'new_data', 'operation_type', 'url', 'ip_address', 'user_agent', 'created_by'
    ];

    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

    public static $operationTypes = [
        self::CREATE => self::CREATE,
        self::UPDATE => self::UPDATE,
        self::DELETE => self::DELETE
    ];

    public function userCreatedBy()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'model'          => ['required', 'string', 'max:255'],
            'model_id'       => ['required', 'integer'],
            'message'        => ['required', 'string', 'max:255'],
            'old_data'       => ['nullable', 'json'],
            'new_data'       => ['nullable', 'json'],
            'operation_type' => ['required', 'in:' . implode(",", self::$operationTypes)],
            'url'            => ['required', 'string'],
            'ip_address'     => ['required', 'string'],
            'user_agent'     => ['required', 'string'],
            'created_by'     => ['required', 'integer', 'exists:' . User::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function getDifferences()
    {
        $oldData = collect(json_decode($this->old_data, true));
        $newData = collect(json_decode($this->new_data, true));

        $differences = $oldData->diffAssoc($newData);

        return $differences;
    }
}
