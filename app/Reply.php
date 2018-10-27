<?php

namespace Forum;

//use Forum\Traits\Favoritable;
// use Illuminate\Database\Eloquent\Model;
use Forum\BaseModel;

class Reply extends BaseModel
{
    protected $guarded = [];

    protected $with = ['owner','favorites','activity'];
    protected $appends = ['FavoritesCount','isFavorited'];
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
}
