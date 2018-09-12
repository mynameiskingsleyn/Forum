<?php

namespace Forum;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug',
    ];
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function threads()
    {
        return $this->hasMany('Forum\Thread', 'channel_id');
    }
    public function path()
    {
        return '/threads/'.$this->slug;
    }
}
