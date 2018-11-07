<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Forum\Inspection\Spam;

class SpamTest extends TestCase
{
    //use DatabaseMigrations;

    /** @test **/
    public function it_checks_for_invalid_keywords()
    {
        //invalid keyword
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException('Exception');

        $spam->detect('yahoo customer support');
    }
    /** @test */
    public function it_checks_for_key_being_held_down()
    {
        $this->withoutExceptionHandling();
        //key held down
        $spam = new Spam();

        $this->expectException('Exception');
        $spam->detect('Hello world aaaaaaaa');
    }
}
