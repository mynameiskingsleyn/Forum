<?php

namespace Tests\HomeMadeFakes;

use Forum\Classes\Trending;
use PHPUnit\Framework\Assert;

class FakeTrending extends Trending
{
    // $trending_items =[
    //   'threads'=>'trending_threads'
    // ];
    // public function __construct()
    // {
    //     parent::__construct();
    // }
    public $testing_trending_threads =[];
    public function assertisEmpty($name)
    {
        //dd($name);
        $real_name = $this->cacheKey($name);
        //dd($this->$real_name);

        Assert::assertEmpty($this->$real_name);
    }

    public function assertCount($count, $name)
    {
        $real_name = $this->cacheKey($name);
        Assert::assertCount($count, $this->$real_name);
    }

    public function add($name, $array)
    {
        $real_name = $this->cacheKey($name);
        $this->$real_name[]=json_encode($array);
    }

    public function get($name)
    {
        $real_name = $this->cacheKey($name);
        return $this->$real_name;
    }
}
