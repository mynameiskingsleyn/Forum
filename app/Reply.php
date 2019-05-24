<?php

namespace Forum;

//use Forum\Traits\Favoritable;
// use Illuminate\Database\Eloquent\Model;
use Forum\BaseModel;
use Carbon\Carbon;

class Reply extends BaseModel
{
    protected $guarded = [];

    protected $with = ['owner','favorites','activity'];
    protected $appends = ['FavoritesCount','isFavorited','isBest','canMarkBest'];//,'canMarkBest','isBest'];
    //
    use Traits\Favoritable;
    use Traits\RecordsActivity;

    protected static function boot()
    {
        parent::boot();
        // static::addGlobalScope('favoritesCount', function ($builder) {
        //     $builder->withCount('favorites');
        // });
        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
            if ($reply->isBest()) {
                //$reply->thread->update(['best_reply_id'=>null]);
            }
        });
        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
    // public function activity()
    // {
    //   return $this->morphMany(Activity::class,'subject');
    // }
    public function path()
    {
        return $this->thread->path()."#reply-{$this->id}";
    }
    // public function favoritesCountAttribute()
    // {
    //     return $this->favorites->count();
    // }
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);
        $names = $matches[1];
        return $names;
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }

    public function isBest()
    {
        //return false;
        return $this->id == $this->thread->best_reply_id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function canMarkBest()
    {
        return $this->thread->owner->id == auth()->id();
    }

    public function getCanMarkBestAttribute()
    {
        return $this->canMarkBest();
    }
}
