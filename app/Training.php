<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\RequiredIf;
use App\ClientTraining;
use Carbon\Carbon;

class Training extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_daily', 'day_from', 'day_to', 'browse_file'
    ];

    const IS_DAILY = '1';
    const IS_NOT_DAILY = '0';

    public static $isDaily = [
        self::IS_DAILY     => 'Daily',
        self::IS_NOT_DAILY => 'Custom'
    ];

    const IS_BROWSE_FILE     = '1';
    const IS_NOT_BROWSE_FILE = '0';

    public static $isBrowseFile = [
        self::IS_NOT_BROWSE_FILE => 'Not',
        self::IS_BROWSE_FILE     => 'Yes'
    ];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'name'        => ['required', 'string', 'max:255'],
            'is_daily'    => ['required', 'in:' . implode(",", array_keys(self::$isDaily))],
            'day_from'    => [new RequiredIf($data['is_daily'] == self::IS_NOT_DAILY)],
            'day_to'      => [new RequiredIf($data['is_daily'] == self::IS_NOT_DAILY)],
            'browse_file' => ['required', 'in:' . implode(",", array_keys(self::$isBrowseFile))],
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function clientTraining($date)
    {
        $userId = auth()->user()->id;

        return $this->hasOne('App\ClientTraining', 'training_id', 'id')->where('user_id', $userId)->whereDate('date', $date);
    }

    public function isDone($date)
    {
        return $this->clientTraining($date)->where('is_attended', ClientTraining::IS_ATTENDED)->exists();
    }
}
