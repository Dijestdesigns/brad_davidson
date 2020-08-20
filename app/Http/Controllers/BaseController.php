<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    protected $httpRequest = null;

    public function __construct()
    {
        $this->httpRequest = Request();
    }
}
