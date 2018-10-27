<?php

namespace Forum\Http\Controllers;

use Illuminate\Http\Request;
use Forum\User;

class UserNotificationsController extends Controller
{
    /**
     * UserNotificationsController
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->unreadNotifications;
    }

    //
    public function destroy(User $profile_user, $notificationId)
    {
        //($notificationId);
        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }
}
