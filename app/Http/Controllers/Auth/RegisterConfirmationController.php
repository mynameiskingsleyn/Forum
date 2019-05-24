<?php

namespace Forum\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Forum\Http\Controllers\Controller;
use Forum\User;

class RegisterConfirmationController extends Controller
{
    //
    public function index()
    {
        //dd('here now');
        $confirmed = false;
        try {
            User::where('confirmation_token', request('token'))
              ->firstOrFail()
              ->confirm();
        } catch (\Exception $e) {
            return redirect('/threads')
              ->with('flash', 'Your account could not be confirmed, you must have a wrong or expired token.');
        }
        return redirect('/threads')
        ->with('flash', 'Your account is now confirmed You may post to the form');
    }
}
