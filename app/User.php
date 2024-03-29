<?php

namespace Forum;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar_path'
    ];
    //  protected $with = ['threads'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email'
    ];
    protected $casts = [
      'confirmed'=>'boolean'
    ];
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('threadsCount', function ($builder) {
            $builder->withCount('threads');
        });
    }

    public function threads()
    {
        return $this->hasMany(Thread::class, 'user_id')
                    ->latest();
    }
    public function replies()
    {
        return $this->hasMany(Reply::class, 'user_id')
                  ->latest();
    }

    public function getThreadsCount()
    {
        return $this->threads()->count();
    }
    /**
    * Get the Route Key name for Laravel
    * @return string
    */
    public function getRouteKeyName()
    {
        return 'name';
    }
    public function activity()
    {
        return $this->hasMany(Activity::class, 'user_id')
                    ->latest()
                    ->with('subject');
    }
    public function read($thread)
    {
        cache()->forever($this->visitedThreadCacheKey($thread), Carbon::now());
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("user.%s.visits.%s", $this->id, $thread->id);
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function getAvatarPathAttribute($avatar)
    {
        //dd($avatar);
        //return asset($this->avatar_path?: 'images/avatars/default.png');
        return asset($avatar ?: 'images/avatars/default.png');
        // if (!$this->avatar_path) {
        //     return 'avatars/default.jpg';
        // }
        // return $this->avatar_path;
    }
    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;
        $this->save();
        return $this->confirmed;
    }
}
