<?php

namespace Forum\Http\Controllers\Api;

use Illuminate\Http\Request;
use Forum\Http\Controllers\Controller;
use Forum\User;

class UsersController extends Controller
{
    //
    public function index()
    {
        $search = request('name');
        return User::where('name', 'LIKE', "$search%")
        ->take(5)
        ->pluck('name');
    }
}
