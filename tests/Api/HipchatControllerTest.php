<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class HipchatControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    public function testGetCapabilitiesReturnsCapabilitiesJson()
    {
        $response = $this->call('GET', route('api.hipchat.capabilities'));

        $this->assertEquals(200, $response->getStatusCode());

        $this->seeJsonStructure(['name', 'description', 'key', 'links' => ['homepage', 'self'], 'vendor' => ['name', 'url'], 'capabilities' => ['hipchatApiConsumer', 'installable', 'webhook']]);
    }
}
