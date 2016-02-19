<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'email' => 'mail@ftiersch.de',
            'password' => \Illuminate\Support\Facades\Hash::make('test')
        ]);
    }
}
