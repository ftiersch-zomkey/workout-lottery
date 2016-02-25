<?php

abstract class AbstractApiBaseControllerTest extends TestCase {
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
}