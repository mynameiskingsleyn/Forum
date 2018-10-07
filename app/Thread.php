<?php

namespace Forum;

//use Illuminate\Database\Eloquent\Model;
use Forum\BaseModel;

class Thread extends BaseModel
{
    protected $guarded = [];
    protected $with = ['owner'];

    use Traits\RecordsActivity;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('repliesCount', function ($builder) {
            $builder->withCount('replies');
        });

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
        return '/threads/'.$this->channel->slug.'/'.$this->id;
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
        return $this->replies()->create($reply);
    }
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
