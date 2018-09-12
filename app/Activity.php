<?php

namespace Forum;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //
    
    protected $guarded = [];

    public function subject()
    {
        return $this->morphTo();
    }
    public static function feed($profile_user, $take=10)
    {
        return  $profile_user->activity->take($take)->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });
        // return \DB::table('activities')
        //             ->where('user_id', $profile_user->id)
        //             ->latest()
        //             ->with('subjec')
        //             ->take(20)
        //             ->groupBy(function ($activity) {
        //                 return $activity->created_at->format('Y-m-d');
        //             });
    }
}
