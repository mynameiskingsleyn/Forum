<?php

namespace Forum\Classes;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get($name)
    {
        //$full_name = 'trending_'.$name;
        return $trending = array_map('json_decode', Redis::zrevrange($this->cacheKey($name), 0, 4));
    }

    public function add($name, $array)
    {
        //$full_name = 'trending_'.$name;
        Redis::zincrby($this->cacheKey($name), 1, json_encode(
        $array
      ));
    }

    public function rank($name, $array)
    {
        $rank = Redis::zrevrank($this->cacheKey($name),json_encode($array));
        //dd($rank);
        if($rank !== null){
            $rank = $rank +1;
            return "ranked number $rank of all $name";
        }
        return "has not been ranked yet ";

    }
    protected function cacheKey($name)
    {
        //'cat';
        //$new_name="nothing";
        $new_name = app()->environment('testing')? 'testing_trending_'.$name : 'trending_'.$name;
        //dd($new_name);
        return $new_name;
    }

    public function reset($name)
    {
        $real_name = $this->cacheKey($name);
        Redis::del($real_name);
    }
}
