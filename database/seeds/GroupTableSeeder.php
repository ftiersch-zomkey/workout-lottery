<?php

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultGroup = \App\Models\GroupType::create([
            'name' => 'default'
        ]);

        $hipchatGroup = \App\Models\GroupType::create([
            'name' => 'hipchat'
        ]);

        \App\Models\Group::create([
            'creator_user_id' => 1,
            'group_type_id' => $defaultGroup->id,
            'name' => 'Default Gruppe'
        ]);

        \App\Models\Group::create([
            'creator_user_id' => 1,
            'group_type_id' => $hipchatGroup->id,
            'name' => 'Hipchat Gruppe'
        ]);
    }
}
