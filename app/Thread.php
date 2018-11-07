<?php

namespace Forum;

//use Illuminate\Database\Eloquent\Model;
use Forum\BaseModel;
use Forum\Notifications\ThreadWasUpdated;
//use Forum\Events\ThreadHasNewReply;
use Forum\Events\ThreadRecievedNewReply;

class Thread extends BaseModel
{
    protected $guarded = [];
    protected $with = ['owner'];
    protected $appends = ['isSubscribedTo'];

    use Traits\RecordsActivity;
    protected static function boot()
    {
        parent::boot();
        // static::addGlobalScope('repliesCount', function ($builder) {
        //     $builder->withCount('replies');
        // });

        static::addGlobalScope('Owner', function ($builder) {
            $builder->with('owner');
        });
        // static::deleted(function ($thread) {
        //     $thread->replies->each(function ($reply) {
        //         //  $reply->activity()->each->delete();
        //         $reply->delete();
        //     });
        //     // $thread->replies->activity()->each->delete();
        //     //$thread->recordActivity('deleted');
        // });
    }

    public function path()
    {
        //return "/threads/{$this->channel->slug}/{$this->id}";
        //('/threads/'.$this->channel->slug.'/'.$this->id);
        return '/threads/'.$this->channel->slug .'/'.$this->id;
    }
    public function repPostPath()
    {
        return '/threads/'.$this->id.'/replies';
    }
    public function replies()
    {
        //  var_dump('replies calle again');
        return $this->hasMany('Forum\Reply');
    }
    // public function getRepliesCount()
    // {
    //     return $this->replies()->count();
    // }
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id')
                    ->withCount('threads');
    }
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        //$this->increment('replies_count');
        //prepare notification for all Subscribers...
        // $this->subscriptions->filter(function ($sub) use ($reply) {
        //     return $sub->user_id != $reply->user_id;
        // })
        //prepare notification..
        // $this->subscriptions
        //     ->where('user_id', '!=', $reply->user_id)
        // ->each->notify($reply);


        event(new ThreadRecievedNewReply($reply));
        
        //  $this->notifySubscribers($reply);


        // ->each(function ($sub) use ($reply) {
        //     $sub->user->notify(new ThreadWasUpdated($this, $reply));
        // });


        // foreach ($this->subscriptions as $subscription) {
        //     if ($subscription->user_id != $reply->user_id) {
        //         $subscription->user->notify(new ThreadWasUpdated($this, $reply));
        //     }
        // }

        return $reply;
    }
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ? : auth()->id())->delete();
        //  dd('unsubscribed');
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
          'user_id' => $userId ? :auth()->id()
        ]);
        return $this;
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
          ->where('user_id', auth()->id())
          ->exists();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions
          ->where('user_id', '!=', $reply->user_id)
          ->each->notify($reply);
    }
    public function hasUpdatesFor($user=null)
    {
        //Look in the cache for the proper key..

        // Compare that carbon instance with the $thread->updated_at
        $user = $user ? :auth()->user();
        $key = $user->visitedThreadCacheKey($this);
        //  $key = sprintf("user.%s.visits.%s", auth()->id(), $this->id);
        return $this->updated_at > cache($key);
    }
}
