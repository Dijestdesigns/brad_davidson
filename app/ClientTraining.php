<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\Training;
use Illuminate\Support\Facades\Storage;

class ClientTraining extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'day', 'is_attended', 'date', 'browse_file', 'training_id', 'client_training_info_id', 'user_id'
    ];

    public static $fileSystems       = 'public';
    public static $storageFolderName = 'client_trainings';
    public static $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    const IS_ATTENDED = '1';
    const IS_NOT_ATTENDED = '0';

    public static $isAttended = [
        self::IS_ATTENDED     => 'Yes',
        self::IS_NOT_ATTENDED => 'No'
    ];

    public static function validators(array $data, $returnBoolsOnly = false, $excludeBrowseFile = false)
    {
        $browseFile = ['nullable'];

        if (!$excludeBrowseFile && !empty($data['day']) && !empty($data['training_id'])) {
            $training = Training::find((int)$data['training_id']);

            if (!empty($training)) {
                if ((int)$data['day'] == (int)$training->day_from) {
                    $browseFile = ['required'];

                    if (!empty($data['training_id']) && !empty($data['date']) && strtotime($data['date']) > 0) {
                        $userId = auth()->user()->id;

                        $check = self::where('training_id', (int)$data['training_id'])->where('user_id', $userId)->whereDate('date', $data['date'])->first();
                        if (!empty($check)) {
                            if (!empty($check->browse_file)) {
                                $browseFile = ['nullable'];
                            }
                        }
                    }
                } elseif ((int)$data['day'] == (int)$training->day_to) {
                    $browseFile = ['required'];
                }
            }
        }

        $browseFileExcluded = ['mimes:' . implode(",", self::$allowedExtensions)];

        if ($excludeBrowseFile === true) {
            $browseFileExcluded = [];
        }

        $validator = Validator::make($data, [
            'day'         => ['required', 'integer'],
            'date'        => ['required', 'date_format:Y-m-d'],
            'is_attended' => ['in:' . implode(",", array_keys(self::$isAttended))],
            'browse_file' => array_merge($browseFile, ['max:255'], $browseFileExcluded),
            'training_id' => ['required', 'integer', 'exists:' . Training::getTableName() . ',id'],
            'client_training_info_id' => ['nullable', 'integer', 'exists:' . ClientTrainingInfo::getTableName() . ',id'],
            'user_id'     => ['required', 'integer', 'exists:' . User::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function getBrowseFileAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        $storageFolderName = (str_ireplace("\\", "/", self::$storageFolderName));
        return Storage::disk(self::$fileSystems)->url($storageFolderName . '/' . $this->training_id . '/' . $value);
    }

    public function training()
    {
        return $this->hasOne('App\Training', 'id', 'training_id');
    }

    public function trainingInfo()
    {
        return $this->hasOne('App\ClientTrainingInfo', 'id', 'client_training_info_id');
    }
}
