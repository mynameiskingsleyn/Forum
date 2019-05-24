<?php

namespace Forum\Traits;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{
    public function recordVisit()
    {
        // Redis::incr($this->visitCacheKey());
        $this->increment('visits');
        // return $this;
    }

    public function visits()
    {
        // $visits = Redis::get($this->visitCacheKey());
        // return $visits? $visits : 0;
        //return Redis::get($this->visitCacheKey()) ?? 0;
    }

    public function resetVisits()
    {
        Redis::del($this->visitCacheKey());
    }

    protected function visitCacheKey()
    {
        return "threads.{$this->id}.visits";
    }
}
