<?php

namespace Forum\Http\Controllers;

use Illuminate\Http\Request;
use Forum\Thread;
use Forum\Reply;
use Forum\User;
use Gate;
use Session;
use Forum\Notifications\YouWhereMentioned;
use Forum\Http\Requests\CreatePostRequest;

//use Forum\Inspection\Spam;

class RepliesController extends Controller
{
    // allow only logged in users.
    public function __construct()
    {
        $this->middleware('auth', ['except'=>'index']);
    }
    // store

    public function store(Thread $thread, CreatePostRequest $form)
    {
        //try {

        //$this->authorize('create', new Reply);
        //different approach using gate....
        // if (Gate::denies('create', new Reply)) {
        //     return response(
        //         'You are posting too frequently, please take a break :)',
        //           422
        //       );
        // }
        //request()->validate(['body' => 'required|spamfree']);
        // $this->validate(
        // request(),
        //   [
        //     'body' =>'required|min:4|spamfree'
        //   ]
        //   );
        //return $form->persist($thread);
        $reply =$thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
        // Inspect the body of the reply for username mentions..

        return response($reply, 200);
        // And then for each mentioned user, notify them.
        //return $reply;

        //return $reply->load('owner');
        // } catch (\Exception $e) {
        //     return response(
        //       'sorry your reply could not be saved at this point',
        //         422
        //     );
        // }


        //Session::flash('success', 'Reply saved');
        // if (request()->expectsJson()) {
        //     return $reply->load('owner');
        // }
        //return back()->with('flash', 'Your reply has been saved');
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

        //request()->validate(['body' => 'required|spamfree']);
        $this->validate(
            request(),
              [
                'body' =>'required|min:10|spamfree'
              ]
              );
        //$spam->detect(request('body'));
        $reply->update(['body'=>request('body')]);
        if (request()->expectsJson()) {
            //dd('end of all');
            //return response(['status'=>'Reply Updated']);
        } else {
            //return redirect()->back()->with('flash', 'Your reply has been updated!!');
        }

        //return $reply->fresh()->load('owner');
    }



    //$reply->update(request(['body']));

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->latest()->paginate(4);
    }
}
