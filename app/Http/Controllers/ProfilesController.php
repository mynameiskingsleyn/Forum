<?php

namespace Forum\Http\Controllers;

use Illuminate\Http\Request;
use Forum\User;
use Forum\Activity;

class ProfilesController extends Controller
{
    //
    /**
    *
    *
    */
    public function show(User $profile_user)
    {
        return view('profiles.show', ['profile_user' => $profile_user,
          'activities' => Activity::feed($profile_user)

      ]);
    }
}
