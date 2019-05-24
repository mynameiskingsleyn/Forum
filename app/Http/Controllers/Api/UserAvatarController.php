<?php

namespace Forum\Http\Controllers\Api;

use Illuminate\Http\Request;
use Forum\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function store()
    {
        //$file_name = 'avatar'.Auth()->id().'.jpg';
        request()->validate(
          ['avatar'=>'required|image']
        );
        // auth()->user()->update(
        //   ['avatar_path' => request()->file('avatar')->storeAs('avatar', $file_name, 'public')]
        // );
        $avatar_path = request()->file('avatar')->store('avatars', 'public');
        auth()->user()->update(
          ['avatar_path' =>$avatar_path ]
        );
        return response([], 204);
        return back();
    }
}
