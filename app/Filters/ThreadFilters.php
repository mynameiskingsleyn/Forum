<?php

namespace Forum\Filters;

use Forum\Filters\Filters;
use Forum\User;

class ThreadFilters extends Filters
{
    protected $filters =  ['by','popular'];
    /**
    * Filter the thread according to username
    * @param $username
    * @return mixed
    **/
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }
    protected function popular()
    {
        $this->builder->getQuery()->orders=[];
        return $this->builder->orderBy('replies_count', 'desc');
    }
}
