<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class BasicTest extends TestCase
{
    use DatabaseMigrations;

    public function testStartPageReturnsOKCode()
    {
        $this->visit(route('page.home'))
             ->seeStatusCode(200);
    }

    public function testAppPageReturnsOKCode()
    {
        $this->visit(route('app.home'))
             ->seeStatusCode(200);
    }
}
