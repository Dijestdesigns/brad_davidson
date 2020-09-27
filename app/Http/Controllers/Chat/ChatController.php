<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;

class ChatController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        $this->middleware(['permission:chat_access'])->only('index');
        $this->middleware(['permission:chat_create'])->only(['create','store']);
        $this->middleware(['permission:chat_edit'])->only(['edit','update']);
        $this->middleware(['permission:chat_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        return view('chat.index');
    }
}
