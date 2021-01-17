<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Role;

class TrainingContent extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'day', 'title', 'description', 'url', 'mime_type', 'extensions', 'role_id'
    ];

    protected $casts = [
        'day'         => 'integer',
        'title'       => 'string',
        'description' => 'string',
        'url'         => 'string',
        'mime_type'   => 'string',
        'extensions'  => 'string',
        'role_id'     => 'integer'
    ];

    public static $fileSystems       = 'public';
    public static $storageFolderName = 'training_contents';

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'day'         => ['required', 'integer'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'url'         => ['required', 'string', 'max:255'],
            'mime_type'   => ['nullable', 'string', 'max:255'],
            'extensions'  => ['nullable', 'string', 'max:255'],
            'role_id'     => ['required', 'integer', 'exists:' . Role::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function getUrlAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (empty($this->mime_type)) {
            if (strpos($value, 'youtube') !== false || strpos($value, 'youtu') !== false) {
                preg_match("/[^\/]+$/", $value, $matches);

                if (!empty($matches[0])) {
                    return "https://www.youtube.com/embed/" . $matches[0];
                }
            }

            return $value;
        }

        $storageFolderName = (str_ireplace("\\", "/", self::$storageFolderName));
        return Storage::disk(self::$fileSystems)->url($storageFolderName . '/' . $this->role_id . '/' . $value);
    }
}
