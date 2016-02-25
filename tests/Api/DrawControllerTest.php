<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class DrawControllerTest extends AbstractApiBaseControllerTest
{
    use DatabaseMigrations, WithoutMiddleware;

    public function testPutMarkDrawSucceededSetsValueInDatabase()
    {
        $this->actingAs($this->user);

        $draw = factory(\App\Models\Draw::class)->create(['group_id' => $this->groups[0]->id]);
        $draw->users()->attach($this->user->id);

        $response = $this->call('PUT', route('api.draws.succeeded', ['draw' => $draw->id, 'user' => $this->user->id]));

        $this->assertEquals(200, $response->getStatusCode());

        $this->seeInDatabase('draws_users', ['draw_id' => $draw->id, 'user_id' => $this->user->id, 'succeeded' => 1]);
    }
}
