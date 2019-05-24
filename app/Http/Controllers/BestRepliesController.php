<?php

namespace Forum\Http\Controllers;

use Illuminate\Http\Request;
use Forum\Reply;
use Auth;
use Log;

class BestRepliesController extends Controller
{
    //
    public function store(Reply $reply)
    {
        $user = Auth::user();
        
        //abort_if($user->id != $reply->thread->user_id, 401);
        $this->authorize('update', $reply->thread);
        $reply->thread->markBestReply($reply);
    }
}
