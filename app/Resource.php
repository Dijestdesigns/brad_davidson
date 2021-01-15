<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class Resource extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'url', 'mime_type', 'extensions', 'for_all'
    ];

    protected $casts = [
        'title'      => 'string',
        'url'        => 'string',
        'mime_type'  => 'string',
        'extensions' => 'string',
        'for_all'    => 'boolean'
    ];

    const FOR_ALL     = '0';
    const NOT_FOL_ALL = '1';

    public static $forAll = [
        self::FOR_ALL     => 'Yes',
        self::NOT_FOL_ALL => 'Nope'
    ];

    public static $fileSystems       = 'public';
    public static $storageFolderName = 'resources';
    // public static $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    public static function validators(array $data, $returnBoolsOnly = false)
    {
        $validator = Validator::make($data, [
            'title'      => ['required', 'string', 'max:255'],
            'url'        => ['required', 'string', 'max:255'],
            'mime_type'  => ['required', 'string', 'max:255'],
            'extensions' => ['required', 'string', 'max:255'],
            'for_all'    => ['in:' . implode(",", array_keys(self::$forAll))]
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

        $storageFolderName = (str_ireplace("\\", "/", self::$storageFolderName));
        return Storage::disk(self::$fileSystems)->url($storageFolderName . '/' . $value);
    }
}
