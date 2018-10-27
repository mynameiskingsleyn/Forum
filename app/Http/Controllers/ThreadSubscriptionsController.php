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

        return redirect()->back()->with('thread');
    }

    public function destroy($channelId, Thread $thread)
    {
        $thread->unsubscribe();
        //  dd('controller called');
        //return redirect()->back()->with('thread');
    }
}
