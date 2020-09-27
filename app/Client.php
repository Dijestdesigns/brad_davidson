<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use App\User;
use App\Tag;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $guard_name = 'client';
    protected $guard = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'notes', 'created_by', 'updated_by'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const PAGINATE_RECORDS = 20;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public static function validators(array $data, $returnBoolsOnly = false, $isUpdate = false)
    {
        $createdBy = ['required', 'integer', 'exists:' . User::getTableName() . ',id'];
        $updatedBy = [];
        if ($isUpdate) {
            $createdBy = [];
            $updatedBy = ['required', 'integer', 'exists:' . User::getTableName() . ',id'];
        }

        $validator = Validator::make($data, [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:' . self::getTableName() . ',email'],
            'notes'      => ['nullable'],
            'password'   => ['string', 'min:8'],
            'created_by' => $createdBy,
            'updated_by' => $updatedBy,
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
        return $this->hasMany('App\ClientTag', 'client_id', 'id');
    }

    public function photo()
    {
        return $this->hasOne('App\ClientPhoto', 'client_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany('App\ClientPhoto', 'client_id', 'id');
    }
}
