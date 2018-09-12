<?php

namespace Forum\Http\Controllers;

use Illuminate\Http\Request;
use Forum\Thread;
use Forum\Reply;
use Session;

class RepliesController extends Controller
{
    // allow only logged in users.
    public function __construct()
    {
        $this->middleware('auth');
    }
    // store

    public function store(Thread $thread)
    {
        $this->validate(
            request(),
          [
            'body' =>'required|min:4'
          ]
        );

        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        //Session::flash('success', 'Reply saved');
        return back()->with('flash', 'Your reply has been saved');
    }
    public function destroy(Reply $reply)
    {
        // if ($reply->user_id != auth()->id()) {
        //     return response([], 403);
        // }
        $this->authorize('update', $reply);
        //$reply->favorites->delete();
        $reply->delete();
        if (request()->expectsJson()) {
            return response(['status'=>'Reply Deleted']);
        }
        return back();
    }
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(['body' => request('body')]);

        //$reply->update(request(['body']));
    }
}
