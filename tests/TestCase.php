<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    //$channel = $this->create('Forum\Channel');

    public function signIn($user = null)
    {
        $user = $user ?: create('Forum\User');
        $this->actingAs($user);
        return $this;
    }
}
