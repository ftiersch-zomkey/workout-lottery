<?php

use Illuminate\Database\Seeder;

class ExcerciseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exercises = ['Push-Up', 'Pull-Up', 'Squat', 'Sit-Up', 'Crunch'];

        foreach ($exercises as $exercise) {
            if (\App\Models\Excercise::whereName($exercise)->count() == 0) {
                \App\Models\Excercise::create([
                    'name' => $exercise
                ]);
            }
        }
    }
}