<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\Training;

class ClientTrainingInfo extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total_days', 'started_at', 'finished_at', 'is_done', 'training_ids', 'user_id'
    ];

    const IS_DONE     = '1';
    const IS_NOT_DONE = '0';

    public static $isDone = [
        self::IS_DONE     => 'Yes',
        self::IS_NOT_DONE => 'No'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'total_days'   => ['required', 'integer'],
            'started_at'   => ['required', 'date_format:Y-m-d'],
            'finished_at'  => ['required', 'date_format:Y-m-d'],
            'is_done'      => ['in:' . implode(",", array_keys(self::$isDone))],
            'training_ids' => ['required', 'string'],
            'user_id'      => ['required', 'integer', 'exists:' . User::getTableName() . ',id']
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

    public function clientTrainings()
    {
        return $this->hasMany('App\ClientTraining', 'client_training_info_id', 'id');
    }
}
