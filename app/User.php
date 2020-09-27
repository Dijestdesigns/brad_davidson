<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Tag;

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
        'name', 'surname', 'contact', 'category', 'email', 'password', 'is_superadmin', 'notes', 'created_by', 'updated_by'
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

        $validator = Validator::make($data, [
            'name'       => ['required', 'string', 'max:255'],
            'surname'    => ['nullable', 'string', 'max:255'],
            'contact'    => ['nullable', 'string', 'max:255'],
            'category'   => ['in:' . implode(",", array_keys(self::$categories))],
            'email'      => array_merge(['required', 'string', 'email', 'max:255'], $email),
            'notes'      => ['nullable'],
            'password'   => $password,
            'created_by' => $createdBy,
            'updated_by' => $updatedBy,
            'is_superadmin' => ['in:0,1'],
            'tags.*'     => ['required', 'integer', 'exists:' . Tag::getTableName() . ',id']
        ]);

        if ($returnBoolsOnly === true) {
            if ($validator->fails()) {
                \Session::flash('error', $validator->errors()->first());
            }

            return !$validator->fails();
        }

        return $validator;
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
}
