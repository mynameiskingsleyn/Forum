<?php

namespace Forum\Http\Controllers;

use Illuminate\Http\Request;
use Forum\Thread;

//use Forum\ThreadSubscription;

class ThreadSubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function store($channelId, Thread $thread)
    {
        $thread->subscribe();
    }

    public function destroy($channelId, Thread $thread)
    {
        $thread->unsubscribe();
    }
}
