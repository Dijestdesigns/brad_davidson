<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
