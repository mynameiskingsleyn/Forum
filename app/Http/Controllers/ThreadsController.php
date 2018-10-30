<?php

namespace Forum\Http\Controllers;

use Forum\Thread;
use Forum\User;
use Forum\Channel;
use Forum\Filters\ThreadFilters;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Session;

class ThreadsController extends Controller
{
    /**
     * Add authentication
     *
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     *@param Channel $channel
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);
        if (request()->wantsJSon()) {
            //dd($threads);
            return $threads;
        }
        //Session::flash('flash', 'this is threads home page');
        //dd($threads->toSql());
        return view('threads.index')->withThreads($threads)->withChannel($channel? :null);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return a view
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->id = Auth::id();

        $this->validate($request, [
           'title' => 'required|max:225|min:5',
           'body' =>'required|min:10',
           'channel_id' => 'required|exists:channels,id'
       ]);
        //dd($request->all());
        // dd($request->title);
        $thread = Thread::create([
            'user_id' => $this->id,
            'channel_id'=> $request->channel_id,
            'title'=> $request->title,
            'body'=> $request->body
        ]);
        //dd("No problem ".$thread->user_id);
        // Session::flash('success', 'Thread created succesfully');
        return redirect($thread->path())->with('flash', 'Your thread has been published!!');
        //return redirect()->route('threads.show',$thread->id); */
    }

    /**
     * Display the specified resource.
     *
     * @param  \Forum\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel_slug, Thread $thread)
    {
        //record that user visited this page...

        //Record a timestamp.
        //$key = sprintf("user.%s.visits.%s", auth()->id(), $thread->id);
        //cache()->forever(auth()->user()->visitedThreadCacheKey($thread), Carbon::now());
        auth()->user()->read($thread);
        //using database --> $auth()->user()->visits()->create(['thread_id'=>$thread->id,'user_id'=>auth()->id()])
        //find thread with slug..
        $ch = Channel::where('slug', '=', $channel_slug)->first();
        //dd($thread);
        if (!$ch) {
            return redirect('404');
        }
        //thread->load('replies'); this will load the replies as well.
        //$thread->withCount('replies');

        //dd($thread);
        // $replies = $thread->replies()->latest();
        $replies = $thread->replies()->get();
        // ->paginate(2);
        //dd($replies->toArray());
        if (request()->wantsJSon()) {
            return $thread->append(['replies','channel']);
        }
        //return $thread;
        return view('threads.show', compact('thread', 'ch', 'replies')); /// will use the next due to vue..
        //return view('threads.show', compact('thread', 'ch'));
        // dd("great job yall!!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Forum\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Forum\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Forum\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
        //$thread->replies()->favorites()->delete();
        $this->authorize('update', $thread);

        // if ($thread->user_id != auth()->id()) {
        //     if (request()->wantsJson()) {
        //         return response(['status'=>'Permision Denied'], 403);
        //     }
        //     abort(403, "You do not have permision to do this");
        // }


        $thread->replies->each->delete();
        $thread->delete();
        if (request()->wantsJson()) {
            return response([], 204);
        }
        return redirect('/threads');
    }
    protected function getThreads($channel, $filters)
    {
        //if slug is present
        if ($channel->exists) {
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
            //dd($threads);
        }
        $threads = $threads->with('channel')->filter($filters)->paginate(5);
        //  dd($threads->toSql());
        return $threads;
    }
}
