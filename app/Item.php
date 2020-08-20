<?php

namespace App;

class Item extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'qty', 'min_level', 'price', 'value', 'notes'
    ];
}
