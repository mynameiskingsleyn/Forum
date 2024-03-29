<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();
        DB::statement('PRAGMA foreign_keys=on');
    }
    //$channel = $this->create('Forum\Channel');
    /**
     * Set the URL of the previous request.
     *
     * @param  string  $url
     * @return $this
     */
    public function from(string $url)
    {
        $this->app['session']->setPreviousUrl($url);

        return $this;
    }

    public function signIn($user = null)
    {
        $user = $user ?: create('Forum\User');
        $this->actingAs($user);
        return $this;
    }
}
