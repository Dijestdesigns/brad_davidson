<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Tag;
use App\Chat;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $guard_name = 'admin';
    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'contact', 'category', 'email', 'password', 'profile_photo', 'profile_photo_icon', 'shipping_address', 'gender', 'age', 'weight', 'weight_unit', 'is_superadmin', 'is_online', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static $roleAdmin   = 'Superadmin';

    public static $superadminId = 1;

    const PAGINATE_RECORDS = 20;

    public static $categories = [
        '0' => 'None',
        '1' => 'Phase 1',
        '2' => 'Phase 2',
        '3' => 'Phase 3',
        '4' => 'Monthly Breakthrough',
    ];

    public static $weightUnits = [
        'n' => 'Select',
        'k' => 'KG',
        'p' => 'Pound'
    ];

    public static $genders = [
        'n' => 'Select',
        'm' => 'Male',
        'f' => 'Female'
    ];

    public static $fileSystems             = 'public';
    public static $storageParentFolderName = 'client_photos';
    public static $storageFolderName       = 'profile';
    public static $storageFolderNameIcon   = 'profile\\icons';
    public static $allowedExtensions       = ['jpg', 'jpeg', 'png', 'gif'];

    const ONLINE  = '1';
    const OFFLINE = '0';
    public static $isOnline = [
        self::ONLINE  => 'Online',
        self::OFFLINE => 'Offline'
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function isSuperAdmin()
    {
        return ($this->is_superadmin == '1' && $this->hasRole(self::$roleAdmin)) ? true : false;
    }

    public static function validators(array $data, $returnBoolsOnly = false, $isUpdate = false, $user = null)
    {
        $createdBy = ['required', 'integer', 'exists:' . self::getTableName() . ',id'];
        $updatedBy = [];
        $email     = ['unique:' . self::getTableName() . ',email'];
        $password  = ['required', 'string', 'min:8', 'confirmed'];

        if ($isUpdate) {
            $createdBy = [];
            $updatedBy = ['required', 'integer', 'exists:' . self::getTableName() . ',id'];

            if (!empty($user) && $user instanceof self) {
                $email = [Rule::unique('users')->ignore($user, 'email')];
            }

            if (empty($data['password'])) {
                $password  = [];
            }
        }

        $weightUnits = ['in:n'];
        $weight      = ['nullable'];
        if (!empty($data['weight'])) {
            $weightUnits = ['required', 'in:k,p'];
        }
        if (!empty($data['weight_unit']) && $data['weight_unit'] != 'n') {
            $weightUnits = ['required', 'in:k,p'];
            $weight      = ['required'];
        }

        $validator = Validator::make($data, [
            'name'             => ['required', 'string', 'max:255'],
            'surname'          => ['nullable', 'string', 'max:255'],
            'profile_photo'    => ['nullable', 'mimes:' . implode(",", self::$allowedExtensions)],
            'profile_photo_icon' => ['nullable', 'string'],
            'shipping_address' => ['nullable'],
            'gender'           => ['in:' . implode(",", array_keys(self::$genders))],
            'age'              => ['nullable', 'integer'],
            'weight'           => array_merge(['integer'], $weight),
            'weight_unit'      => array_merge([], $weightUnits),
            'contact'          => ['nullable', 'string', 'max:255'],
            'category'         => ['in:' . implode(",", array_keys(self::$categories))],
            'email'            => array_merge(['required', 'string', 'email', 'max:255'], $email),
            'password'         => $password,
            'created_by'       => $createdBy,
            'updated_by'       => $updatedBy,
            'is_superadmin'    => ['in:0,1'],
            'is_online'        => ['in:' . implode(",", array_keys(self::$isOnline))],
            'tags.*'           => ['required', 'integer', 'exists:' . Tag::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
    }

    public function getProfilePhotoAttribute($value)
    {
        $defaultProfilePhoto = asset('img/friends/fr-05.jpg');

        if (empty($value)) {
            return $defaultProfilePhoto;
        }

        $storageParentFolderName = (str_ireplace("\\", "/", self::$storageParentFolderName));
        $storageFolderName       = (str_ireplace("\\", "/", self::$storageFolderName));
        $fileName                = $storageParentFolderName . '/' . $this->id . '/' . $storageFolderName . '/' . $value;

        if (Storage::disk(self::$fileSystems)->has($fileName)) {
            return $profilePhoto = Storage::disk(self::$fileSystems)->url($fileName);
        } else {
            return $defaultProfilePhoto;
        }
    }

    public function getProfilePhotoIconAttribute($value)
    {
        $defaultProfilePhotoIcon = $this->profile_photo;

        if (empty($value)) {
            return $defaultProfilePhotoIcon;
        }

        $storageParentFolderName = (str_ireplace("\\", "/", self::$storageParentFolderName));
        $storageFolderNameIcon   = (str_ireplace("\\", "/", self::$storageFolderNameIcon));
        $fileName                = $storageParentFolderName . '/' . $this->id . '/' . $storageFolderNameIcon . '/' . $value;

        if (Storage::disk(self::$fileSystems)->has($fileName)) {
            return $profilePhotoIcon = Storage::disk(self::$fileSystems)->url($fileName);
        } else {
            return $defaultProfilePhotoIcon;
        }
    }

    public function getUnread($default = 0)
    {
        $myUserId = auth()->user()->id;
        $userId   = $this->id;
        $count    = $default;

        if ($this instanceof self) {
            $chats = $this->chatsWithSender;

            if (!empty($chats) && !$chats->isEmpty()) {
                foreach ($chats as $chat) {
                    if (!$chat->isRead()) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function userCreatedBy()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public function clientTags()
    {
        return $this->hasMany('App\ClientTag', 'user_id', 'id');
    }

    public function photo()
    {
        return $this->hasOne('App\ClientPhoto', 'user_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany('App\ClientPhoto', 'user_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany('App\UserNote', 'user_id', 'id');
    }

    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class)->withTimestamps();
    }

    public function chats()
    {
        return $this->hasMany('App\Chat', 'user_id', 'id');
    }

    public function chatsWithSender()
    {
        $myUserId = auth()->user()->id;

        return $this->hasMany('App\Chat', 'send_by', 'id')->where('user_id', $myUserId);
    }
}
