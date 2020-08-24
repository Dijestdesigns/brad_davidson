<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\Client;
use Illuminate\Support\Facades\Storage;

class ClientPhoto extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo', 'client_id'
    ];

    public static $fileSystems       = 'public';
    public static $storageFolderName = 'client_photos';
    public static $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'photo'     => ['required', 'mimes:' . implode(",", self::$allowedExtensions), 'max:255'],
            'client_id' => ['required', 'integer', 'exists:' . Client::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function getPhotoAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        $storageFolderName = (str_ireplace("\\", "/", self::$storageFolderName));
        return Storage::disk(self::$fileSystems)->url($storageFolderName . '/' . $this->client_id . '/' . $value);
    }
}
