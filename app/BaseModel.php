<?php

namespace App;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    const PAGINATE_RECORDS = 20;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
