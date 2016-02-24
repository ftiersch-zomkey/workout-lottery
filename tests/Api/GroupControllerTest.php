<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class GroupControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    protected $user = null;
    protected $groupTypes = [];
    protected $groups = [];

    public function setUp() {
        parent::setUp();

        $this->user = factory(\App\Models\User::class)->create();

        $this->groupTypes[] = factory(\App\Models\GroupType::class)->create(['name' => 'default']);
        $this->groupTypes[] = factory(\App\Models\GroupType::class)->create(['name' => 'hipchat']);

        for ($i = 1; $i <= 3; $i++) {
            $this->groups[] = factory(\App\Models\Group::class)->create([
                'creator_user_id' => $this->user->id,
                'group_type_id' => $this->groupTypes[0]->id,
                'name' => 'Group ' . $i
            ]);
        }

    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->groups);
        unset($this->groupTypes);
    }

    public function testGetGroupListReturnsAllGroups()
    {
        $this->seeInDatabase('groups', ['name' => 'Group 1']);

        $response = $this->call('GET', route('api.groups.list'));

        $this->assertEquals(200, $response->getStatusCode());

        $this   ->seeJsonStructure(['*' => ['id', 'group_type_id', 'group_type' => ['name'], 'name']])
                ->seeJson(['name' => 'Group 1'])
                ->seeJson(['name' => 'Group 2'])
                ->seeJson(['name' => 'Group 3']);
    }

    public function testGetOwnGroupListReturnsOnlyLoggedInUsersGroups()
    {
        // set up another user and some groups for him
        $testUser = factory(\App\Models\User::class)->create();
        for ($i = 1; $i <= 2; $i++) {
            factory(\App\Models\Group::class)->create([
                'creator_user_id' => $testUser->id,
                'group_type_id' => $this->groupTypes[0]->id,
                'name' => 'NotShown ' . $i
            ]);
        }

        $this->actingAs($this->user);

        $response = $this->call('GET', route('api.groups.list.own'));

        $this->assertEquals(200, $response->getStatusCode());

        $this   ->seeJsonStructure(['*' => ['id', 'group_type_id', 'group_type' => ['name'], 'name']])
                ->seeJson(['name' => 'Group 1'])
                ->seeJson(['name' => 'Group 2'])
                ->seeJson(['name' => 'Group 3'])
                ->dontSeeJson(['name' => 'NotShown 1'])
                ->dontSeeJson(['name' => 'NotShown 2']);
    }

    public function testPostAddGroupAddsANewGroupToTheDatabaseAndReturnsIt()
    {
        $this->actingAs($this->user);

        $response = $this->call('POST', route('api.groups.add'), [
            'name' => 'Added Group 1'
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this   ->seeInDatabase('groups', ['name' => 'Added Group 1', 'creator_user_id' => $this->user->id])
                ->seeJsonStructure(['id', 'name', 'creator_user_id', 'group_type' => ['id', 'name']])
                ->seeJson(['name' => 'Added Group 1']);
    }

    public function testPostAddGroupReturnsValidationErrorForWrongInput()
    {
        $this->actingAs($this->user);

        $response = $this->call('POST', route('api.groups.add'), [
            'name' => ''
        ], [], [], [
            'HTTP_Accept' => 'application/json'
        ]);

        $this->assertEquals(422, $response->getStatusCode());
        $this->seeJsonStructure(['name']);
    }

    public function testPutUpdateGroupSavesValidValuesToTheDatabase()
    {
        $this->actingAs($this->user);

        $response = $this->call('PUT', route('api.groups.edit', ['group' => $this->groups[0]->id]), [
            'name' => 'Updated Testgroup',
            'interval_minutes' => 45,
            'interval_time_start' => '03:00:00',
            'interval_time_end' => '17:00:00',
            'number_of_winners' => 1337,
            'finish_exercise_time' => 30
        ], [], [], [
            'HTTP_Accept' => 'application/json'
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this   ->seeJsonStructure(['id', 'name', 'group_type' => ['name'], 'interval_time_start', 'interval_time_end', 'interval_minutes', 'finish_exercise_time', 'number_of_winners'])
                ->seeJson(['name' => 'Updated Testgroup', 'interval_time_start' => '03:00:00', 'interval_time_end' => '17:00:00', 'interval_minutes' => 45, 'finish_exercise_time' => 30, 'number_of_winners' => 1337])
                ->seeInDatabase('groups', ['name' => 'Updated Testgroup', 'interval_minutes' => 45, 'interval_time_start' => '03:00:00', 'interval_time_end' => '17:00:00', 'number_of_winners' => 1337, 'finish_exercise_time' => 30]);
    }

    public function testPutUpdateGroupGetsValidatedForWrongData()
    {
        $this->actingAs($this->user);

        $response = $this->call('PUT', route('api.groups.edit', ['group' => $this->groups[0]->id]), [
            'name' => '',
            'interval_minutes' => 'test',
            'interval_time_start' => '00-00-00',
            'interval_time_end' => '18-00-00',
            'number_of_winners' => 'test',
            'finish_exercise_time' => 'test'
        ], [], [], [
            'HTTP_Accept' => 'application/json'
        ]);

        $this->assertEquals(422, $response->getStatusCode());

        $this->seeJsonStructure(['name', 'interval_minutes', 'interval_time_start', 'interval_time_end', 'number_of_winners', 'finish_exercise_time']);
    }

    public function testDeleteGroupDeletesGroupFromDatabase()
    {
        $this->actingAs($this->user);

        $response = $this->call('DELETE', route('api.groups.delete', ['group' => $this->groups[0]->id]));

        $this->assertEquals(200, $response->getStatusCode());

        $this->seeJsonStructure(['id', 'name', 'interval_minutes', 'interval_time_start', 'interval_time_end', 'number_of_winners', 'finish_exercise_time']);
        $this->dontSeeInDatabase('groups', ['id' => $this->groups[0]->id, 'name' => $this->groups[0]->name]);
        $this->seeInDatabase('groups', ['id' => $this->groups[1]->id, 'name' => $this->groups[1]->name]);
    }

    public function testListGroupDrawsListsAllDrawsForSelectedGroup()
    {
        $this->actingAs($this->user);

        $draw1 = factory(\App\Models\Draw::class)->create(['group_id' => $this->groups[0]->id]);
        $draw2 = factory(\App\Models\Draw::class)->create(['group_id' => $this->groups[0]->id]);
        $draw3 = factory(\App\Models\Draw::class)->create(['group_id' => $this->groups[0]->id]);

        $response = $this->call('GET', route('api.groups.draws.list', ['group' => $this->groups[0]->id]));

        $this->assertEquals(200, $response->getStatusCode());

        $this->seeJsonStructure(['0' => ['id', 'users', 'exercises'], '1' => ['id', 'users', 'exercises'], '2' => ['id', 'users', 'exercises']]);
    }

    public function testListGroupDrawsOnlyListsDrawsForSelectedGroups()
    {
        $this->actingAs($this->user);

        $draw1 = factory(\App\Models\Draw::class)->create(['group_id' => $this->groups[0]->id]);
        $draw2 = factory(\App\Models\Draw::class)->create(['group_id' => $this->groups[0]->id]);
        $draw3 = factory(\App\Models\Draw::class)->create(['group_id' => $this->groups[1]->id]);

        $response = $this->call('GET', route('api.groups.draws.list', ['group' => $this->groups[0]->id]));

        $this->assertEquals(200, $response->getStatusCode());

        $this->seeJsonStructure(['0' => ['id', 'users', 'exercises'], '1' => ['id', 'users', 'exercises']]);

        $result = json_decode($response->getContent());

        $this->assertNotEquals(false, $result);
        $this->assertEquals(2, count($result));
    }
}
