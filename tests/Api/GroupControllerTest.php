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
}
