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
use Illuminate\Support\Facades\Redis;
use Forum\Classes\Trending;
use Log;

// use Forum\Inspection\Spam;

class ThreadsController extends Controller
{
    /**
     * Add authentication
     *
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('must-be-confirmed')->only('store');
    }
    /**
     * Display a listing of the resource.
     *
     *@param Channel $channel
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trendings)
    {
        $threads = $this->getThreads($channel, $filters);
        if (request()->wantsJSon()) {
            //dd($threads);
            return $threads;
        }

        // $trending = collect(Redis::zrevrange('trending_threads', 0, 2))->map(function ($thread) {
        //     return json_decode($thread);
        // });

        $trending=$trendings->get('threads');//= array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));

        // return view('threads.index')->withThreads($threads)->withChannel($channel? :null);
        return view('threads.index', compact('threads', 'channel', 'trending'));
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
        //$this->middleware('must-be-confirmed');
        $userid = Auth::id();
        $rules = [
           'title' => 'required|max:225|min:5|spamfree',
           'body' =>'required|min:10|spamfree',
           'channel_id' => 'required|exists:channels,id'
       ];
        $custum_messages = [
          'required'=>'the :attribute field is required',
          'spamfree'=>'The :attribute field has spams please fix'
        ];


        //dd('shit balls');
        $this->validate($request, $rules, $custum_messages);
        //dd($request->all());
        // dd($request->title);
        // $spam->detect(request('title'));
        // $spam->detect(request('body'));
        // if (count($errors) > 0) {
        //     foreach ($errors as $error) {
        //         session()->put('flash', "$error");
        //     }
        // }
        $thread = Thread::create([
            'user_id' => $userid,
            'channel_id'=> $request->channel_id,
            'title'=> $request->title,
            'body'=> $request->body,
            //'slug'=> $request->title
        ]);
        if ($request->wantsJSon()) {
            return response($thread, 201);
        }

        return redirect($thread->path())->with('flash', 'Your thread has been published!!');
        //return redirect()->route('threads.show',$thread->id); */
    }

    /**
     * Display the specified resource.
     *
     * @param  \Forum\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel_slug, Thread $thread, Trending $trendings)
    {
        //record that user visited this page...

        //Record a timestamp.
        //$key = sprintf("user.%s.visits.%s", auth()->id(), $thread->id);
        //cache()->forever(auth()->user()->visitedThreadCacheKey($thread), Carbon::now());
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        //using database --> $auth()->user()->visits()->create(['thread_id'=>$thread->id,'user_id'=>auth()->id()])
        //find thread with slug..
        $ch = Channel::where('slug', '=', $channel_slug)->first();
        //dd($thread);
        if (!$ch) {
            return redirect('404');
        }
        //dd('we are geting here yall!!');
        //thread->load('replies'); this will load the replies as well.
        //$thread->withCount('replies');
        // Redis::zincrby('trending_threads', 1, json_encode([
        //   'title' => $thread->title,
        //   'path' => $thread->path()
        // ]));
        $item = [
          'title' => $thread->title,
          'path' => $thread->path()
        ];
        // get thread rank..
        $rank = $trendings->rank('threads', $item);
        //add thread count for perpose of showing most popular
        $trendings->add('threads', $item);
        // increase the number of visits for this thread
        $thread->recordVisit();

        //dd($thread);
        // $replies = $thread->replies()->latest();
        $replies = $thread->replies()->get();
        // ->paginate(2);
        //dd($replies->toArray());
        if (request()->wantsJSon()) {
            return $thread->append(['replies','channel']);
        }
        //return $thread;
        return view('threads.show', compact('thread', 'ch', 'replies','rank')); /// will use the next due to vue..
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
        $spam->detect(request('title'));
        $spam->detect(request('body'));
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
    public function reserved_functions_code()
    {
        $thrds = Thread::all();
        //Log::debug($thrds);
        foreach ($thrds as $thrd) {
            Log::info($thrd->title.':'.$thrd->slug);
            if (!$thrd->slug) {
                Log::info('thread-> '.$thrd->title.' has no slug');
                $thrd->setSlugAttribute($thrd->title);
                Log::info('---new value----------');
                Log::info('thread-> '.$thrd->title.' new slug is '.$thrd->slug);
                $thrd->save();
            }
        }
    }
}
