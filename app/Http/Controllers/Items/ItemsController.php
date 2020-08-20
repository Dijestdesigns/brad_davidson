<?php

namespace App\Http\Controllers\Items;

use Illuminate\Http\Request;

class ItemsController extends \App\Http\Controllers\BaseController
{
    public function index()
    {
        return view('items.index');
    }

    public function create()
    {
        
    }
}
